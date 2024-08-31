<?php

namespace App\Imports;

use App\Models\ProductExit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ProductExitImport implements ToModel, WithHeadingRow
{
    /**
     * Fungsi untuk membuat model dari setiap baris.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Menggunakan Carbon untuk parsing tanggal
        $tgl_exit = isset($row['tanggal_exit']) ? Carbon::parse($row['tanggal_exit']) : null;

        return new ProductExit([
            'nama_kapal' => $row['nama_kapal'],
            'no_exit' => $row['no_exit'],
            'tgl_exit' => $tgl_exit,
            'jenis_barang' => $row['jenis_barang'],
            'total' => is_numeric($row['total']) ? (int) round((float) $row['total']) : 0,
        ]);
    }
}
