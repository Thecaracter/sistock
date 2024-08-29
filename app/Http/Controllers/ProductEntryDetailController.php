<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductEntry;
use Illuminate\Http\Request;
use App\Models\ProductEntryDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductEntryDetailExport;
use App\Imports\ProductEntryDetailImport;

class ProductEntryDetailController extends Controller
{
    public function index($productEntryId)
    {
        $products = Product::all(); // Ambil semua produk
        return view('pages.productEntryDetails', compact('productEntryId', 'products'));
    }

    public function loadData($productEntryId)
    {
        // Ambil data product entry
        $productEntry = ProductEntry::findOrFail($productEntryId);

        // Ambil semua detail berdasarkan product_entry_id dan termasuk relasi produk
        $details = ProductEntryDetail::with('product') // Load relasi product
            ->where('product_entry_id', $productEntryId)
            ->get();

        // Mengembalikan data dalam bentuk JSON
        return response()->json([
            'productEntry' => $productEntry,
            'details' => $details,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'product_entry_id' => 'required|exists:product_entries,id',
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Hitung total untuk detail entry ini
        $total = $request->quantity * $request->price;

        // Buat detail entry
        $detail = ProductEntryDetail::create([
            'product_entry_id' => $request->product_entry_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => $total,
        ]);

        // Update total di product entry
        $productEntry = ProductEntry::find($request->product_entry_id);
        $productEntry->total += $total;
        $productEntry->save();

        // Kembalikan respons jika sukses
        return response()->json([
            'message' => 'Product entry detail created successfully',
            'detail' => $detail
        ]);
    }
    public function destroy($id)
    {
        // Temukan detail entry berdasarkan ID
        $detail = ProductEntryDetail::findOrFail($id);

        // Temukan product entry yang terkait
        $productEntry = ProductEntry::find($detail->product_entry_id);

        // Kurangi total dari product entry berdasarkan total dari detail ini
        $productEntry->total -= $detail->total;
        $productEntry->save();

        // Hapus detail entry
        $detail->delete();

        // Kembalikan respons jika sukses
        return response()->json([
            'message' => 'Product entry detail deleted successfully',
        ]);
    }
    // Untuk export
    public function export($productEntryId)
    {
        return Excel::download(new ProductEntryDetailExport($productEntryId), 'product_entry_details.xlsx');
    }

    // Untuk import
    public function import(Request $request, $productEntryId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ProductEntryDetailImport($productEntryId), $request->file('file'));

        return redirect()->back()->with('success', 'Data berhasil diimpor');
    }

}
