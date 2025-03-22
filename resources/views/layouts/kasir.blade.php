@extends('layouts.frontend')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kasir</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_obat }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form id="kasir-form">
            @csrf
            <div class="form-group">
                <label for="total_belanja">Total Belanja</label>
                <input type="text" class="form-control" id="total_belanja" value="Rp {{ number_format($transaksi->sum('subtotal'), 2, ',', '.') }}" readonly>
            </div>
            <div class="form-group">
                <label for="uang_pembayaran">Uang Pembayaran</label>
                <input type="number" class="form-control" id="uang_pembayaran" name="uang_pembayaran" min="{{ $transaksi->sum('subtotal') }}" required>
            </div>
            <div class="form-group">
                <label for="kembalian">Kembalian</label>
                <input type="text" class="form-control" id="kembalian" readonly>
            </div>
            <button type="button" class="btn btn-success" id="proses-transaksi">Selesaikan Transaksi</button>
        </form>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uangPembayaran = document.getElementById('uang_pembayaran');
        const totalBelanja = {{ $transaksi->sum('subtotal') }};
        const kembalian = document.getElementById('kembalian');

        uangPembayaran.addEventListener('input', function () {
            const pembayaran = parseFloat(this.value) || 0;
            const kembali = pembayaran - totalBelanja;
            kembalian.value = kembali >= 0 ? `Rp ${new Intl.NumberFormat('id-ID').format(kembali)}` : 'Rp 0';
        });

        document.getElementById('proses-transaksi').addEventListener('click', function () {
            if (parseFloat(uangPembayaran.value) < totalBelanja) {
                alert('Uang pembayaran kurang!');
                return;
            }

            // Proses transaksi (kirim ke server)
            fetch('{{ route('kasir.proses') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    total_belanja: totalBelanja,
                    uang_pembayaran: parseFloat(uangPembayaran.value),
                    kembalian: parseFloat(uangPembayaran.value) - totalBelanja
                })
            })
            .then(response => {
                if (!response.ok) throw response;
                return response.json();
            })
            .then(data => {
                alert(data.success);
                location.reload();
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan, silakan coba lagi.');
            });
        });
    });
</script>
