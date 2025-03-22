@extends('layouts.frontend')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Penjualan</h3>
    </div>
    <div class="card-body">
        <!-- Form Filter Tanggal -->
        <form action="{{ route('laporan.penjualan') }}" method="GET" class="form-inline mb-3">
            <label for="start_date" class="mr-2">Dari Tanggal:</label>
            <input type="date" name="start_date" id="start_date" class="form-control mr-3" value="{{ request('start_date') }}">
            <label for="end_date" class="mr-2">Sampai Tanggal:</label>
            <input type="date" name="end_date" id="end_date" class="form-control mr-3" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Tombol Ekspor ke PDF -->
        <a href="{{ route('laporan.penjualan.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-danger mb-3">
            <i class="fa fa-file-pdf-o"></i> Ekspor ke PDF
        </a>

        <table class="table table-bordered">
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
    </div>
</div>
@endsection
