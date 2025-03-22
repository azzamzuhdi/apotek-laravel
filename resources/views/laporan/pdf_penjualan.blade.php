<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Total Belanja</th>
                <th>Uang Pembayaran</th>
                <th>Kembalian</th>
                <th>Detail Obat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i:s') }}</td>
                    <td>Rp {{ number_format($data->total_belanja, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($data->uang_pembayaran, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($data->kembalian, 2, ',', '.') }}</td>
                    <td>
                        @if (!empty($data->detail_obat))
                            <ul>
                                @foreach ($data->detail_obat as $obat)
                                    <li>{{ $obat['nama_obat'] }} ({{ $obat['jumlah'] }} pcs)</li>
                                @endforeach
                            </ul>
                        @else
                            <em>Tidak ada detail obat</em>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
