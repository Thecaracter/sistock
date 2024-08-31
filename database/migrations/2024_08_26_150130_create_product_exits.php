<?php

namespace App\Imports;

use App\Models\ProductExit;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExitImport implements ToModel, WithHeadings
{
    /**
     * Fungsi untuk membuat model dari setiap baris.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan tanggal dalam format numerik dan valid
        $date = $row[2];
        if (is_string($date) && !strtotime($date)) {
            $date = Date::excelToDateTimeObject($date);
        } else {
            $date = Date::excelToDateTimeObject($date);
        }

        // Konversi kolom total menjadi integer jika diperlukan
        $total = is_numeric($row[4]) ? (int) round((float) $row[4]) : 0;

        return new ProductExit([
            'nama_kapal' => $row[0], // Menggunakan kolom pertama
            'no_exit' => $row[1],    // Menggunakan kolom kedua
            'tgl_exit' => $date, // Pastikan ini adalah objek DateTime
            'jenis_barang' => $row[3], // Menggunakan kolom keempat
            'total' => $total, // Pastikan ini adalah integer
        ]);
    }

    /**
     * Fungsi untuk mendapatkan heading dari file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'nama kapal',
            'no exit',
            'tanggal exit',
            'jenis barang',
            'total',
        ];
    }
}
