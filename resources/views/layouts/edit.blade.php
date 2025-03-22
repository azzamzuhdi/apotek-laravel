@extends('layouts.frontend') <!-- Ganti dengan layout yang sesuai -->

@section('content')
    <h1>Edit Obat</h1>

    <form action="{{ route('obat.update', $obat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_obat">Nama Obat</label>
            <input type="text" name="nama_obat" id="nama_obat" class="form-control" value="{{ $obat->nama_obat }}" required>
        </div>
        <div class="form-group">
            <label for="harga_beli">Harga Beli</label>
            <input type="number" name="harga_beli" id="harga_beli" class="form-control" value="{{ $obat->harga_beli }}" required>
        </div>
        <div class="form-group">
            <label for="harga_jual">Harga Jual</label>
            <input type="number" name="harga_jual" id="harga_jual" class="form-control" value="{{ $obat->harga_jual }}" required>
        </div>
        <div class="form-group">
            <label for="expired">Expired</label>
            <input type="date" name="expired" id="expired" class="form-control" value="{{ $obat->expired }}" required>
        </div>
        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" name="stok" id="stok" class="form-control" value="{{ $obat->stok }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
