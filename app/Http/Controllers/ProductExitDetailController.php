<?php

namespace App\Http\Controllers;

use App\Models\ProductExit;
use Illuminate\Http\Request;
use App\Models\ProductExitDetail;
use App\Models\ProductEntryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExitDetailsExport;
use App\Imports\ProductExitDetailsImport;

class ProductExitDetailController extends Controller
{
    /**
     * Menampilkan daftar detail exit produk berdasarkan ID product exit.
     */
    public function index($productExitId)
    {
        // Temukan ProductExit berdasarkan ID
        $productExit = ProductExit::findOrFail($productExitId);

        // Ambil semua ProductEntryDetail untuk diisi dalam dropdown
        $productEntryDetails = ProductEntryDetail::with('product')
            ->where('stock', '>', 0)
            ->get();

        // Ambil detail exit produk terkait
        $productExitDetails = ProductExitDetail::where('product_exit_id', $productExitId)->get();


        // Tampilkan view dengan data yang diperlukan
        return view('pages.productExitDetail', compact('productExit', 'productEntryDetails', 'productExitDetails'));

    }



    /**
     * Menyimpan detail exit produk yang baru.
     */
    public function store(Request $request, $productExitId)
    {
        // Tambahkan log untuk memeriksa nilai input yang diterima
        Log::info('Data yang diterima:', $request->all());

        // Validasi data yang masuk
        $request->validate([
            'product_entry_detail_id' => 'required|exists:product_entries_detail,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Temukan ProductEntryDetail berdasarkan ID
        $productEntryDetail = ProductEntryDetail::findOrFail($request->product_entry_detail_id);

        // Cek ketersediaan stok
        if ($productEntryDetail->stock < $request->quantity) {
            return response()->json([
                'message' => 'Stok tidak cukup untuk produk ini.',
            ], 400);
        }

        // Hitung total untuk detail exit ini
        $total = $request->quantity * $request->price;

        // Buat detail exit baru
        $detail = ProductExitDetail::create([
            'product_exit_id' => $productExitId,
            'product_entry_detail_id' => $request->product_entry_detail_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => $total,
            'exit_date' => now(),
        ]);

        // Decrement stok pada ProductEntryDetail yang terkait
        $productEntryDetail->stock -= $request->quantity;
        $productEntryDetail->save();

        // Perbarui total pada ProductExit
        $productExit = ProductExit::findOrFail($productExitId);
        $productExit->total += $total;
        $productExit->save();

        // Kembalikan respons jika sukses
        return response()->json([
            'message' => 'Detail exit produk berhasil ditambahkan.',
            'detail' => $detail
        ]);
    }


    public function destroy($id)
    {
        // Temukan detail exit produk berdasarkan ID
        $detail = ProductExitDetail::findOrFail($id);

        // Temukan ProductEntryDetail terkait
        $productEntryDetail = ProductEntryDetail::findOrFail($detail->product_entry_detail_id);

        // Kembalikan stok pada ProductEntryDetail
        $productEntryDetail->stock += $detail->quantity;
        $productEntryDetail->save();

        // Perbarui total pada ProductExit terkait
        $productExit = ProductExit::findOrFail($detail->product_exit_id);
        $productExit->total -= $detail->total;
        $productExit->save();

        // Hapus detail exit produk
        $detail->delete();

        // Kembalikan respons jika sukses
        return response()->json([
            'message' => 'Detail exit produk berhasil dihapus.'
        ]);
    }
    public function export($productExitId)
    {
        return Excel::download(new ProductExitDetailsExport($productExitId), 'product_exit_details.xlsx');
    }

    public function import(Request $request, $productExitId)
    {
        Log::info('Starting import process for ProductExit ID: ' . $productExitId);

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        Log::info('File validation passed');

        DB::beginTransaction();

        try {
            Log::info('Beginning Excel import');
            Excel::import(new ProductExitDetailsImport($productExitId), $request->file('excel_file'));

            DB::commit();
            Log::info('Import successful, database transaction committed');

            return redirect()->back()->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

}
