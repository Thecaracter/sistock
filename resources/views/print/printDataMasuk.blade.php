<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCM Form</title>
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
            font-size: 16px;
        }

        .signature {
            width: 45%;
            text-align: center;
        }

        .signature p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div id="printArea" class="container">
        <div class="header">
            <div class="logo">PCM</div>
            <div class="form-title">FORMULIR<br>PERMINTAAN BARANG</div>
        </div>
        <div class="form-info">
            <div>Nama Kapal</div>
            <div>{{ $productEntry->nama_kapal }}</div>
            <div>No Permintaan</div>
            <div>{{ $productEntry->no_permintaan }}</div>
            <div>Tanggal Permintaan</div>
            <div>{{ \Carbon\Carbon::parse($productEntry->tgl_permintaan)->format('d/m/Y') }}</div>
            <div>Lampiran</div>
            <div>{{ $productEntry->attachment ?? '' }}</div>
            <div>Jenis Barang</div>
            <div>{{ $productEntry->jenis_barang }}</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Kode Barang</th>
                    <th style="width: 30%;">Nama Barang</th>
                    <th style="width: 25%;">Part Number</th>
                    <th style="width: 20%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productEntry->productEntryDetail as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->product->code_barang }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->product->part_code ?? '' }}</td>
                        <td>{{ $detail->quantity }} {{ $detail->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <div class="signature">
                <p>Departemen Fleet & Tugboat</p>
                <p>Diajukan Oleh,</p>
                <br><br><br><br>
                <p>{{ $submitted_by }}</p>
            </div>
            <div class="signature">
                <p>Diketahui Atasan Langsung,</p>
                <br><br><br><br><br>
                <p>{{ $approved_by }}</p>
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

            // Capture the entire content
            const canvas = await html2canvas(element, {
                scale: 1,
                useCORS: true,
                logging: true
            });

            const imgData = canvas.toDataURL('image/png');

            // A4 height in pixels (assuming 96 DPI)
            const a4HeightPx = 1123;

            // Create PDF with the width of the content and A4 height
            const pdf = new jsPDF({
                orientation: 'p',
                unit: 'px',
                format: [canvas.width, a4HeightPx]
            });

            // Calculate the number of pages needed
            const pageCount = Math.ceil(canvas.height / a4HeightPx);

            // Add content to pages
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

            pdf.save('Form-Permintaan-Barang.pdf');

            setTimeout(() => {
                window.history.back();
            }, 100);
        }

        window.onload = generatePDF;
    </script>
</body>

</html>
