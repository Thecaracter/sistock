<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Produk</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        #printArea {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        h1 {
            color: #2c3e50;
            margin: 0;
            flex-grow: 1;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        .product-header {
            background-color: #ecf0f1;
            font-weight: bold;
            color: #2c3e50;
        }

        .entry-details {
            font-size: 0.95em;
        }

        .entry-details:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-row {
            font-weight: bold;
            background-color: #e8e8e8;
        }

        .grand-total {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.1em;
            text-align: right;
            color: #2c3e50;
            padding: 10px;
            background-color: #ecf0f1;
            border-radius: 5px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            #printArea {
                box-shadow: none;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div id="printArea">
        <div class="header">
            <img src="{{ asset('foto/logo.png') }}" alt="Company Logo" class="logo">
            <h1>Daftar Stock Barang</h1>
            <div class="current-date">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
        </div>

        @php
            use Carbon\Carbon;
            $grandTotal = 0;
            $grandTotalStockValue = 0;
        @endphp

        @foreach ($products as $product)
            <table>
                <tr class="product-header">
                    <td colspan="6">
                        <strong>Produk:</strong> {{ $product->name }}
                        (Kode: {{ $product->part_code }}, Merk: {{ $product->merk }})
                    </td>
                </tr>
                <tr>
                    <th style="text-align: center;">No. Permintaan</th>
                    <th style="text-align: center;">Tanggal Permintaan</th>
                    <th style="text-align: center;">Nama Kapal</th>
                    <th style="text-align: center;">Stock</th>
                    <th style="text-align: center;">Harga</th>
                    <th style="text-align: center;">Total</th>
                </tr>
                @php
                    $totalQuantity = 0;
                    $totalAmount = 0;
                @endphp
                @foreach ($product->productEntriesDetail as $detail)
                    @php
                        $entryTotal = $detail->stock * $detail->price;
                    @endphp
                    <tr class="entry-details">
                        <td>{{ $detail->productEntry->no_permintaan }}</td>
                        <td>{{ Carbon::parse($detail->productEntry->tgl_permintaan)->format('d/m/Y') }}</td>
                        <td>{{ $detail->productEntry->nama_kapal }}</td>
                        <td>{{ $detail->stock }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($entryTotal, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $totalQuantity += $detail->stock;
                        $totalAmount += $entryTotal;
                    @endphp
                @endforeach
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>{{ $totalQuantity }}</td>
                    <td></td>
                    <td style="text-align: right;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                </tr>
            </table>
            @php
                $grandTotal += $totalAmount;
                $grandTotalStockValue += $product->stock * $product->price;
            @endphp
        @endforeach

        <div class="grand-total">
            Total Keseluruhan: Rp {{ number_format($grandTotal, 0, ',', '.') }}
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script>
        async function generatePDF() {
            const {
                jsPDF
            } = window.jspdf;
            const element = document.getElementById('printArea');

            const a4Width = 210;
            const a4Height = 297;

            const pdf = new jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'a4'
            });

            async function addContentToPDF(element, pdf) {
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    logging: true
                });

                const imgData = canvas.toDataURL('image/png');
                const imgWidth = a4Width;
                const pageHeight = a4Height;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
            }

            await addContentToPDF(element, pdf);

            const filename = 'product.pdf';
            pdf.save(filename);

            setTimeout(() => {
                window.history.back();
            }, 10);
        }

        window.onload = generatePDF;
    </script>
</body>

</html>
