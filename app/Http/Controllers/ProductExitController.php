<?php

namespace App\Http\Controllers;

use App\Models\ProductExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductExitController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $productExits = ProductExit::with('productExitDetails')->orderBy('tgl_exit', 'desc')->get();
            return response()->json($productExits);
        }

        // Load data product exits untuk tampilan halaman pertama kali (bukan AJAX)
        $productExits = ProductExit::with('productExitDetails')->orderBy('tgl_exit', 'desc')->get();

        return view('pages.productExits', compact('productExits'));
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_kapal' => 'required|string|max:255',
                'no_exit' => 'required|string|max:255|unique:product_exits,no_exit',
                'tgl_exit' => 'required|date',
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
                    $productExit = DB::transaction(function () use ($validator) {
                        return ProductExit::create($validator->validated());
                    });

                    return redirect()->route('product_exits.index')->with([
                        'notification' => [
                            'type' => 'success',
                            'title' => 'Success',
                            'message' => 'Product Exit has been added successfully.',
                        ],
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
            return redirect()->route('product_exits.index')->with([
                'notification' => [
                    'type' => 'error',
                    'title' => 'Validation Error!',
                    'message' => 'Please check the input fields.',
                ],
                'errors' => $e->validator->errors(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create product exit after multiple attempts: ' . $e->getMessage());
            return redirect()->route('product_exits.index')->with([
                'notification' => [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Failed to add Product Exit. Please try again later.',
                ],
            ]);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Validasi input tanpa 'version'
            $validator = Validator::make($request->all(), [
                'nama_kapal' => 'required|string|max:255',
                'no_exit' => 'required|string|max:255|unique:product_exits,no_exit,' . $id,
                'tgl_exit' => 'required|date',
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
                    $result = DB::transaction(function () use ($id, $validator) {
                        // Menggunakan lockForUpdate untuk mengunci baris selama transaksi
                        $productExit = ProductExit::lockForUpdate()->findOrFail($id);

                        // Update data tanpa pengecekan version
                        $productExit->update($validator->validated());

                        return $productExit;
                    });

                    // Log the successful update
                    Log::info('Product Exit updated successfully', ['id' => $id]);

                    return redirect()->route('product_exits.index')->with([
                        'notification' => [
                            'type' => 'success',
                            'title' => 'Success',
                            'message' => 'Product Exit has been updated successfully.',
                        ],
                    ]);
                } catch (\Exception $e) {
                    $attempts++;
                    if ($attempts >= $maxAttempts) {
                        // Log the exception after maximum attempts
                        Log::error('Failed to update Product Exit after multiple attempts: ' . $e->getMessage(), ['id' => $id]);
                        return redirect()->route('product_exits.index')->with([
                            'notification' => [
                                'type' => 'error',
                                'title' => 'Error',
                                'message' => 'Failed to update Product Exit. Please try again later.',
                            ],
                        ]);
                    }
                    // Wait and try again using exponential backoff
                    usleep($waitTime * 1000);
                    $waitTime *= 2; // Exponential backoff
                }
            }
        } catch (ValidationException $e) {
            // Log validation errors
            Log::warning('Validation failed for Product Exit update: ' . $e->getMessage(), ['errors' => $e->validator->errors()]);

            return redirect()->route('product_exits.index')->with([
                'notification' => [
                    'type' => 'error',
                    'title' => 'Validation Error!',
                    'message' => 'Please check the input fields.',
                ],
                'errors' => $e->validator->errors(),
            ]);
        } catch (\Exception $e) {
            // Log general errors
            Log::error('Failed to update Product Exit: ' . $e->getMessage(), ['id' => $id]);

            return redirect()->route('product_exits.index')->with([
                'notification' => [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Failed to update Product Exit. Please try again later.',
                ],
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $productExit = ProductExit::findOrFail($id);

            // Hapus detail terkait terlebih dahulu
            $productExit->productExitDetails()->delete();

            // Kemudian hapus product exit
            $productExit->delete();

            DB::commit();

            return redirect()->route('product_exits.index')->with([
                'notification' => [
                    'type' => 'success',
                    'title' => 'Success',
                    'message' => 'Product Exit and its details have been deleted successfully.',
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete product exit: ' . $e->getMessage());
            return redirect()->route('product_exits.index')->with([
                'notification' => [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Failed to delete Product Exit.',
                ],
            ]);
        }
    }
}
