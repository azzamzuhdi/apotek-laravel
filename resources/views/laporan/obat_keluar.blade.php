@extends('layouts.frontend')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Obat Keluar</h3>
    </div>
     <!-- Form Filter Tanggal -->
     <form action="{{ route('laporan.obat-keluar') }}" method="GET" class="form-inline mb-3">
        <label for="start_date" class="mr-2">Dari Tanggal:</label>
        <input type="date" name="start_date" id="start_date" class="form-control mr-3" value="{{ request('start_date') }}">
        <label for="end_date" class="mr-2">Sampai Tanggal:</label>
        <input type="date" name="end_date" id="end_date" class="form-control mr-3" value="{{ request('end_date') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    <div class="card-body">
        <!-- Tabel Laporan Obat Keluar dengan Tampilan Rapi -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Nama Obat</th>
                        <th>Jumlah Keluar</th>
                        <th>Tanggal Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->nama_obat }}</td>
                            <td>{{ $data->jumlah_keluar }}</td>
                            <td>{{ $data->tanggal_keluar }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
