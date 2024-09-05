<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use App\Models\ProductEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductEntriesExport;
use App\Imports\ProductEntriesImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductEntriesController extends Controller
{
    public function index()
    {
        $productEntries = ProductEntry::orderBy('tgl_permintaan', 'desc')->get();
        return view('pages.productEntries', compact('productEntries'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_kapal' => 'required|string|max:255',
                'no_permintaan' => 'required|string|max:255',
                'tgl_permintaan' => 'required|date',
                'jenis_barang' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $attempts = 0;
            $maxAttempts = 5;
            $waitTime = 100; // milliseconds

            while ($attempts < $maxAttempts) {
                try {
                    $productEntry = DB::transaction(function () use ($validator) {
                        return ProductEntry::create($validator->validated());
                    });

                    return redirect()->route('product_entries.index')->with('notification', [
                        'title' => 'Success!',
                        'text' => 'Product Entry has been added successfully.',
                        'type' => 'success'
                    ]);
                } catch (\Exception $e) {
                    $attempts++;
                    if ($attempts >= $maxAttempts) {
                        throw $e;
                    }
                    usleep($waitTime * 1000);
                    $waitTime *= 2; // Exponential backoff
                }
            }
        } catch (ValidationException $e) {
            return redirect()->route('product_entries.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('notification', [
                    'title' => 'Validation Error!',
                    'text' => 'Please check the form for errors.',
                    'type' => 'error'
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to create product entry after multiple attempts: ' . $e->getMessage());
            return redirect()->route('product_entries.index')->with('notification', [
                'title' => 'Error!',
                'text' => 'Failed to add Product Entry. Please try again later.',
                'type' => 'error'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kapal' => 'required|string|max:255',
            'no_permintaan' => 'required|string|max:255',
            'tgl_permintaan' => 'required|date',
            'jenis_barang' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('product_entries.index')
                ->withErrors($validator)
                ->withInput()
                ->with('notification', [
                    'title' => 'Validation Error!',
                    'text' => 'Please check the form for errors.',
                    'type' => 'error'
                ]);
        }

        $attempts = 0;
        $maxAttempts = 5;
        $waitTime = 100; // milliseconds

        while ($attempts < $maxAttempts) {
            try {
                $result = DB::transaction(function () use ($id, $validator) {
                    // Menggunakan lockForUpdate untuk mengunci baris selama transaksi
                    $productEntry = ProductEntry::lockForUpdate()->findOrFail($id);

                    // Update data tanpa mengecek versi
                    $productEntry->update($validator->validated());

                    return $productEntry;
                });

                return redirect()->route('product_entries.index')->with('notification', [
                    'title' => 'Success!',
                    'text' => 'Product Entry has been updated successfully.',
                    'type' => 'success'
                ]);
            } catch (\Exception $e) {
                $attempts++;
                if ($attempts >= $maxAttempts) {
                    Log::error('Failed to update product entry after multiple attempts: ' . $e->getMessage());
                    return redirect()->route('product_entries.index')->with('notification', [
                        'title' => 'Error!',
                        'text' => 'Failed to update Product Entry. Please try again later.',
                        'type' => 'error'
                    ]);
                }
                usleep($waitTime * 1000);
                $waitTime *= 2; // Exponential backoff
            }
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $productEntry = ProductEntry::findOrFail($id);

            // Hapus detail terkait terlebih dahulu
            $productEntry->productEntryDetail()->delete();

            // Kemudian hapus product entry
            $productEntry->delete();

            DB::commit();

            return redirect()->route('product_entries.index')->with('notification', [
                'title' => 'Success!',
                'text' => 'Product Entry and its details have been deleted successfully.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete product entry: ' . $e->getMessage());
            return redirect()->route('product_entries.index')->with('notification', [
                'title' => 'Error!',
                'text' => 'Failed to delete Product Entry.',
                'type' => 'error'
            ]);
        }
    }
    // Ekspor data ke Excel
    public function export()
    {
        return Excel::download(new ProductEntriesExport, 'product_entries.xlsx');
    }

    // Impor data dari Excel
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new ProductEntriesImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'Data berhasil diimpor.');
    }
    public function show($id, Request $request)
    {
        $productEntry = ProductEntry::with('productEntryDetail.product')->findOrFail($id);

        // Get the submitted_by and approved_by from the request
        $submitted_by = $request->input('submitted_by');
        $approved_by = $request->input('approved_by');

        // Pass these values to the view
        return view('print.printDataMasuk', compact('productEntry', 'submitted_by', 'approved_by'));
    }


}