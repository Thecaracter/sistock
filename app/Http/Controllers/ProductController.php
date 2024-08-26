<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('pages.product', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $image = $request->file('image');
            $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('fotoproduct'), $imageName);

            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            return redirect()->route('product.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil ditambahkan.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
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
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $product->name = $request->name;
            $product->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('fotoproduct'), $imageName);

                // Delete old image if exists
                $oldImagePath = public_path('fotoproduct/' . $product->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }

                $product->image = $imageName;
            }

            $product->save();

            return redirect()->route('product.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil diperbarui.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('product.index')->with('notification', [
                'title' => 'Gagal!',
                'text' => 'Produk gagal diperbarui.',
                'type' => 'error',
            ]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete image if exists
            $imagePath = public_path('fotoproduct/' . $product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $product->delete();

            return redirect()->route('product.index')->with('notification', [
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil dihapus.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('product.index')->with('notification', [
                'title' => 'Gagal!',
                'text' => 'Produk gagal dihapus.',
                'type' => 'error',
            ]);
        }
    }
}
