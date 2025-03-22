@extends('layouts.frontend')

@section('content')

<div class="card">
    <div id="flash-message">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    
    <div class="card-header">
        <h3 class="card-title">Halaman Kasir</h3>
    </div>
    
    <div class="card-body">
        <form action="{{ route('kasir.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama_obat">Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control @error('nama_obat') is-invalid @enderror" placeholder="Nama Obat" required>
                @error('nama_obat')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" placeholder="Harga Jual" required>
                @error('harga_jual')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="qty">Jumlah</label>
                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" placeholder="Jumlah" required>
                @error('qty')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Simpan Transaksi</button>
        </form>
    </div>
</div>

<script>
    // Script untuk menghilangkan pesan flash secara otomatis setelah 5 detik
    setTimeout(function() {
        let flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            flashMessage.style.transition = "opacity 0.5s ease";
            flashMessage.style.opacity = 0;
            setTimeout(function() {
                flashMessage.remove();
            }, 500); // Menghapus elemen setelah hilang
        }
    }, 3000); // 5000 ms = 5 detik
</script>

@endsection
