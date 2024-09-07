<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCM Form Keluar</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            background: white;
            box-sizing: border-box;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 2px solid #000;
        }

        .logo {
            font-weight: bold;
            font-size: 32px;
            color: #0066cc;
        }

        .form-title {
            font-weight: bold;
            text-align: right;
            font-size: 24px;
        }

        .form-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px;
            padding: 15px 0;
            border-bottom: 2px solid #000;
            font-size: 16px;
        }

        .form-info div {
            padding: 5px;
        }

        .form-info div:nth-child(even) {
            background-color: #e6ffe6;
        }

        .form-title-container {
            text-align: right;
        }

        .form-title {
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .current-date {
            font-size: 14px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 12px;
            text-align: left;
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 16px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            padding: 30px 0;
            margin-top: 30px;
            font-size: 14px;
        }

        .signature {
            width: 30%;
            text-align: center;
        }

        .signature p {
            margin: 5px 0;
        }

        .signature-line {
            height: 80px;
            border-bottom: 1px solid black;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div id="printArea" class="container">
        <div class="header">
            <img src="{{ asset('foto/logo.png') }}" alt="Company Logo" class="logo">
            <div class="form-title-container">
                <div class="form-title">FORMULIR<br>PENGELUARAN BARANG</div>
                <div class="current-date">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
            </div>
        </div>
        <div class="form-info">
            <div>Nama Kapal</div>
            <div>{{ $productExit->nama_kapal }}</div>
            <div>No Exit</div>
            <div id="noExit">{{ $productExit->no_exit }}</div>
            <div>Tanggal Exit</div>
            <div>{{ \Carbon\Carbon::parse($productExit->tgl_exit)->format('d/m/Y') }}</div>
            <div>Jenis Barang</div>
            <div>{{ $productExit->jenis_barang }}</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Kode Barang</th>
                    <th style="width: 25%;">Nama Barang</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productExit->productExitDetails as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->productEntryDetail->product->code_barang }}</td>
                        <td>{{ $detail->productEntryDetail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price, 2) }}</td>
                        <td>{{ number_format($detail->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="display: flex; justify-content: space-between; ">
            <p style="font-size: 12px;">Barang telah diperiksa dan diserahkan</p>
            <p style="font-size: 12px;">Barang telah diperiksa dan diserahkan</p>
        </div>
        <div class="footer">
            <div class="signature">
                <p>Departemen Head</p>
                <p>Procurement and Asset</p>
                <div class="signature-line"></div>
                <p>{{ $submitted_by }}</p>
                <p style="margin-top: 50px; text-align: left;">*wajib di isi</p>
            </div>
            <div class="signature">
                <p>Yang Menerima</p>
                <br>
                <div class="signature-line"></div>
                <p>{{ $recipient_by }}</p>
            </div>
            <div class="signature">
                <p>Departemen Head</p>
                <p>Fleet and Tugboat</p>
                <div class="signature-line"></div>
                <p>{{ $approved_by }}</p>
                <p style="margin-top: 50px; text-align: left;">*wajib di isi</p>
            </div>
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

            const canvas = await html2canvas(element, {
                scale: 1,
                useCORS: true,
                logging: true
            });

            const imgData = canvas.toDataURL('image/png');

            const a4HeightPx = 1123;

            const pdf = new jsPDF({
                orientation: 'p',
                unit: 'px',
                format: [canvas.width, a4HeightPx]
            });

            const pageCount = Math.ceil(canvas.height / a4HeightPx);

            for (let i = 0; i < pageCount; i++) {
                if (i > 0) {
                    pdf.addPage([canvas.width, a4HeightPx]);
                }

                pdf.addImage(
                    imgData,
                    'PNG',
                    0,
                    -i * a4HeightPx,
                    canvas.width,
                    canvas.height
                );
            }

            const noExit = document.getElementById('noExit').innerText.trim();
            const filename = `Form-Pengeluaran-Barang-${noExit}.pdf`;

            pdf.save(filename);

            setTimeout(() => {
                window.history.back();
            }, 100);
        }

        // window.onload = generatePDF;
    </script>

</body>

</html>
