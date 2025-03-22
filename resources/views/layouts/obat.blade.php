@extends('layouts.frontend')

@section('content')

    <div class="card">
        <!-- Pesan Flash -->
        <div id="flash-message">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <!-- Card Header -->
        <div class="card-header">
            <h3 class="card-title">Data Obat</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 1%">No.</th>
                            <th style="width: 20%">Nama Obat</th>
                            <th style="width: 15%">Harga Beli</th> <!-- Lebar kolom harga beli lebih sempit -->
                            <th style="width: 15%">Harga Jual</th> <!-- Lebar kolom harga jual lebih sempit -->
                            {{-- <th>Kadaluarsa</th> --}}
                            <th style="width: 8%" class="text-center">Stok</th>
                            <th style="width: 20%" class="text-center">Jumlah Keluar</th>
                            <!-- Lebar kolom input jumlah keluar -->
                            <th style="width: 20%" class="text-center">Aksi</th> <!-- Lebar kolom aksi -->
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($obat) > 0)
                            @foreach ($obat as $index => $o)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $o->nama_obat }}</td>
                                    <td>Rp {{ number_format($o->harga_beli, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($o->harga_jual, 2, ',', '.') }}</td>
                                    {{-- <td>{{ $o->expired }}</td> --}}
                                    <td class="text-center">{{ $o->stok }}</td>

                                    <!-- Kolom Aksi: Edit, Hapus, dan Obat Keluar -->
                                    <td class="text-center">
                                        <!-- Form Input Jumlah Keluar -->
                                        <form id="form-obat-keluar-{{ $o->id }}" style="display: inline;">
                                            @csrf
                                            <input type="number" name="jumlah_keluar"
                                                id="jumlah_keluar_{{ $o->id }}" placeholder="Jml Keluar"
                                                min="1" required style="width: 80px; margin-right: 5px;">
                                            <button type="button" class="btn btn-primary btn-sm btn-keluar"
                                                data-id="{{ $o->id }}">
                                                <i class="fa fa-arrow-down"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <!-- Kolom untuk tombol Edit dan Hapus -->
                                    <td class="text-center">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('obat.edit', $o->id) }}" class="btn btn-warning btn-sm"
                                            style="margin-left: 5px;">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('obat.destroy', $o->id) }}" method="POST"
                                            style="display:inline; margin-left: 5px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus obat ini?');">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data obat.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Tambah Obat -->
        <a href="{{ route('obat.create') }}" class="btn btn-success float-right">
            <i class="fa fa-plus"></i> Tambah Obat
        </a>

        <!-- Tombol Laporan Obat Keluar -->
        <a href="{{ route('laporan.obat-keluar') }}" class="btn btn-info float-left">
            <i class="fa fa-file"></i> Laporan Obat Keluar
        </a>
    </div>

@endsection

<!-- Script AJAX -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.btn-keluar').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                let jumlahKeluar = document.getElementById('jumlah_keluar_' + id).value;

                if (!jumlahKeluar || jumlahKeluar <= 0) {
                    alert('Harap isi jumlah keluar yang valid.');
                    return;
                }

                console.log('Mengirim request AJAX untuk ID:', id, 'Jumlah:',
                jumlahKeluar); // Debugging

                fetch('/obat/keluar/' + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            jumlah_keluar: jumlahKeluar
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            console.error('Response Error:', response);
                            throw response;
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response Sukses:', data);
                        alert(data.success);
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan, silakan coba lagi.');
                    });
            });
        });
    });
</script>
