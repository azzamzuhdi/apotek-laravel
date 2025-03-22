@extends('layouts.frontend')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Stok Masuk</h3>
        </div>
        <div class="card-body">
            <!-- Tabel Laporan Stok Masuk -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Jumlah Masuk</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Tanggal Proses</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->obat->nama_obat ?? '-' }}</td>
                            <td>{{ $data->jumlah_masuk }}</td>
                            <td>{{ $data->expired }}</td>
                            <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
