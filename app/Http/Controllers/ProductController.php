<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('productEntries')->get();
        return view('pages.product', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'part_code' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'merk' => 'nullable|string',
        ]);

        try {
            $image = $request->file('image');
            $imageName = $this->generateUniqueFileName($image);
            $image->move(public_path('fotoproduct'), $imageName);

            Product::create([
                'name' => $request->name,
                'part_code' => $request->part_code,
                'image' => $imageName,
                'merk' => $request->merk, // Ini tetap sama
            ]);

            return redirect()->route('product.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil ditambahkan.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('product.index')->with('notification', [
                'title' => 'Gagal!',
                'text' => 'Produk gagal ditambahkan.',
                'type' => 'error',
            ]);
        }
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'part_code' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'merk' => 'nullable|string',
        ]);

        try {
            $product->name = $request->name;
            $product->part_code = $request->part_code;
            $product->merk = $request->merk;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $imageName = now()->format('Ymd_His') . '_' . Str::random(10) . '.' . $extension;

                // Delete old image if exists
                if ($product->image) {
                    $oldImagePath = public_path('fotoproduct/' . $product->image);
                    if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Move new image
                $image->move(public_path('fotoproduct'), $imageName);
                $product->image = $imageName;
            }

            $product->save();

            return redirect()->route('product.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil diperbarui.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('product.index')->with('notification', [
                'title' => 'Gagal!',
                'text' => 'Produk gagal diperbarui. Error: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }


    public function destroy(Product $product)
    {
        try {
            // Get the image path
            $imagePath = public_path('fotoproduct/' . $product->image);

            // Delete the product from the database
            if ($product->delete()) {
                // If product is successfully deleted from the database, delete the image file
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                return redirect()->route('product.index')->with('notification', [
                    'title' => 'Berhasil!',
                    'text' => 'Produk berhasil dihapus.',
                    'type' => 'success',
                ]);
            } else {
                throw new \Exception('Gagal menghapus produk dari database.');
            }
        } catch (\Exception $e) {
            return redirect()->route('product.index')->with('notification', [
                'title' => 'Gagal!',
                'text' => 'Produk gagal dihapus. Error: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }
    private function generateUniqueFileName($image)
    {
        $now = Carbon::now();
        $date = $now->format('Ymd_His');
        $random = Str::random(5);
        return $date . '_' . $random . '.' . $image->getClientOriginalExtension();
    }
    public function importExcel(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Impor data dari file Excel
        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Produk berhasil diimpor!');
    }
    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}