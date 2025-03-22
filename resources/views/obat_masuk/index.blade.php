@extends('layouts.frontend')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Obat Masuk</h3>
        </div>
        <div class="card-body">
            <!-- Form Tambah Obat Masuk -->
            <form action="{{ route('obat-masuk.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="obat_id">Nama Obat</label>
                    <select name="obat_id" id="obat_id" class="form-control" required>
                        <option value="">-- Pilih Obat --</option>
                        @foreach ($obat as $o)
                            <option value="{{ $o->id }}">{{ $o->nama_obat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah_masuk">Jumlah Masuk</label>
                    <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="expired">Tanggal Kadaluarsa</label>
                    <input type="date" name="expired" id="expired" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Tambah Obat Masuk</button>
            </form>

            <hr>

            <!-- Data Obat Masuk -->
            <h4>Data Obat Masuk</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Jumlah Masuk</th>
                        <th>Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($obatMasuk as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->obat->nama_obat }}</td>
                            <td>{{ $data->jumlah_masuk }}</td>
                            <td>{{ $data->expired }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <form action="{{ route('obat-masuk.proses') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Proses ke Tabel Data Obat</button>
            </form>

        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection
