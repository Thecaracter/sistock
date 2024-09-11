<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCM Form</title>
    <style>
        @page {
            size: A4;
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
            padding-top: 15mm;
            padding-bottom: 30mm;
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
            max-width: 150px;
            height: auto;
        }

        .form-title {
            font-weight: bold;
            text-align: right;
            font-size: 24px;
        }

        .form-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 5px;
            padding: 10px 0;
            border-bottom: 2px solid #000;
            font-size: 14px;
        }

        .form-info div {
            padding: 3px;
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
            padding: 8px;
            text-align: left;
            font-size: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 14px;
        }

        .nama-barang {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            max-width: 150px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            padding: 30px 0;
            margin-top: 50px;
            font-size: 14px;
            page-break-inside: avoid;
        }

        .signature {
            width: 45%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 150px;
            page-break-inside: avoid;
        }

        .signature-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .signature p {
            margin: 5px 0;
        }

        .signature .name {
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        @media print {
            .page-break {
                page-break-before: always;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                page-break-inside: auto;
            }

            tr,
            td,
            th {
                page-break-inside: avoid;
            }

            .footer {
                display: block;
                page-break-inside: avoid;
                margin-top: 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                page-break-before: auto;
            }

            .signature {
                width: 45%;
                text-align: center;
                display: inline-block;
                page-break-inside: avoid;
                break-inside: avoid;
                vertical-align: top;
            }

            .signature-content {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .name {
                margin-top: 50px;
                page-break-inside: avoid;
                page-break-before: avoid;
                break-before: avoid;
            }

            body {
                margin-top: 20px;
                margin-left: 20px;
                margin-right: 20px;
                margin-bottom: 4cm;
            }
        }
    </style>
</head>

<body>
    <div id="printArea" class="container">
        <div class="header">
            <img src="{{ asset('foto/logo.png') }}" alt="Company Logo" class="logo">
            <div class="form-title">FORMULIR<br>PERMINTAAN BARANG</div>
        </div>
        <div class="form-info">
            <div>Nama Kapal</div>
            <div>{{ $productEntry->nama_kapal }}</div>
            <div>No Permintaan</div>
            <div id="noPermintaan">{{ $productEntry->no_permintaan }}</div>
            <div>Tanggal Permintaan</div>
            <div>{{ \Carbon\Carbon::parse($productEntry->tgl_permintaan)->format('d/m/Y') }}</div>
            <div>Lampiran</div>
            <div>{{ $productEntry->attachment ?? '' }}</div>
            <div>Jenis Barang</div>
            <div>{{ $productEntry->jenis_barang }}</div>
        </div>
        <table id="dataTable">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center; vertical-align: middle;">No</th>
                    <th style="width: 20%; text-align: center; vertical-align: middle;">Kode Barang</th>
                    <th style="width: 30%; text-align: center; vertical-align: middle;">Nama Barang</th>
                    <th style="width: 25%; text-align: center; vertical-align: middle;">Part Number</th>
                    <th style="width: 20%; text-align: center; vertical-align: middle;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productEntry->productEntryDetail as $index => $detail)
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">{{ $index + 1 }}</td>
                        <td style="text-align: center; vertical-align: middle;">{{ $detail->product->code_barang }}</td>
                        <td class="nama-barang">{{ $detail->product->name }}</td>
                        <td style="text-align: center; vertical-align: middle;">{{ $detail->product->part_code ?? '' }}
                        </td>
                        <td style="text-align: center; vertical-align: middle;">{{ $detail->quantity }}
                            {{ $detail->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="footer" class="footer">
            <div class="signature">
                <div class="signature-content">
                    <div>
                        <p>Departemen Fleet & Tugboat</p>
                        <p>Diajukan Oleh,</p>
                    </div>
                    <p class="name">{{ $submitted_by }}</p>
                </div>
            </div>
            <div class="signature">
                <div class="signature-content">
                    <div>
                        <p>Diketahui Atasan Langsung,</p>
                    </div>
                    <p class="name">{{ $approved_by }}</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script>
        function adjustFooterPosition() {
            const container = document.querySelector('.container');
            const table = document.getElementById('dataTable');
            const footer = document.getElementById('footer');

            const containerHeight = container.offsetHeight;
            const tableBottom = table.offsetTop + table.offsetHeight;
            const footerHeight = footer.offsetHeight;

            if (tableBottom + footerHeight > containerHeight) {
                const spacer = document.createElement('div');
                spacer.style.height = (containerHeight - tableBottom) + 'px';
                table.parentNode.insertBefore(spacer, footer);
            }
        }

        async function generatePDF() {
            adjustFooterPosition();

            const {
                jsPDF
            } = window.jspdf;
            const element = document.getElementById('printArea');
            const pdf = new jsPDF('p', 'mm', 'a4');

            await html2canvas(element, {
                scale: 4,
                useCORS: true,
                logging: true
            }).then((canvas) => {
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 190; // Mengecilkan lebar gambar (sebelumnya 210)
                const pageHeight = 295; // Tinggi halaman A4
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                const marginBottom = 50; // Menyediakan margin bawah

                // Tambahkan gambar di halaman pertama
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight -
                    marginBottom); // 10 untuk padding kiri
                heightLeft -= pageHeight;

                // Buat halaman baru jika konten lebih dari satu halaman
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight - marginBottom);
                    heightLeft -= pageHeight;
                }
            });

            const noPermintaan = document.getElementById('noPermintaan').innerText.trim();
            const filename = `Form-Permintaan-Barang-${noPermintaan}.pdf`;
            pdf.save(filename);

            setTimeout(() => {
                window.history.back();
            }, 100);
        }

        window.onload = generatePDF;
    </script>
</body>

</html>
