@extends('layouts.frontend')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Kasir</h3>
    </div>
    <div class="box-body">
        <!-- Form Tambah Item -->
        <form id="form-tambah" class="form-inline mb-3">
            @csrf
            <div class="form-group mx-sm-3">
                <select name="nama_obat" id="nama_obat" class="form-control" required>
                    <option value="">Pilih Obat</option>
                    @foreach($obat as $o)
                        <option value="{{ $o->nama_obat }}" data-stok="{{ $o->stok }}" data-harga="{{ $o->harga_jual }}">
                            {{ $o->nama_obat }} (Stok: {{ $o->stok }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mx-sm-3">
                <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>

        <!-- Tabel Keranjang -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
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
                    <td>
                        <button class="btn btn-danger btn-sm btn-hapus" data-id="{{ $item->id }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Form Pembayaran -->
        <form id="kasir-form">
            @csrf
            <div class="form-group">
                <label for="total_belanja">Total Belanja</label>
                <input type="text" class="form-control" id="total_belanja" value="Rp {{ number_format($transaksi->sum('subtotal'), 2, ',', '.') }}" readonly>
            </div>
            
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="payment_method" id="cash" value="cash" checked>
                        Tunai
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="payment_method" id="midtrans" value="midtrans">
                        Pembayaran Online (Midtrans)
                    </label>
                </div>
            </div>

            <div id="cash-payment" class="payment-section">
                <div class="form-group">
                    <label for="uang_pembayaran">Uang Pembayaran</label>
                    <input type="number" class="form-control" id="uang_pembayaran" name="uang_pembayaran" min="{{ $transaksi->sum('subtotal') }}" required>
                </div>
                <div class="form-group">
                    <label for="kembalian">Kembalian</label>
                    <input type="text" class="form-control" id="kembalian" readonly>
                </div>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-success" id="proses-transaksi">Selesaikan Transaksi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Form Tambah Item
    const formTambah = document.getElementById('form-tambah');
    formTambah.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route('kasir.tambah') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nama_obat: formData.get('nama_obat'),
                jumlah: formData.get('jumlah')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Terjadi kesalahan, silakan coba lagi.');
        });
    });

    // Tombol Hapus Item
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            
            fetch(`{{ url('kasir/hapus') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan, silakan coba lagi.');
            });
        });
    });

    // Proses Transaksi
    const uangPembayaran = document.getElementById('uang_pembayaran');
    const totalBelanja = {{ $transaksi->sum('subtotal') }};
    const kembalian = document.getElementById('kembalian');
    const cashPayment = document.getElementById('cash-payment');
    const paymentMethods = document.getElementsByName('payment_method');

    // Toggle payment method
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'cash') {
                cashPayment.style.display = 'block';
                uangPembayaran.required = true;
            } else {
                cashPayment.style.display = 'none';
                uangPembayaran.required = false;
            }
        });
    });

    uangPembayaran.addEventListener('input', function () {
        const pembayaran = parseFloat(this.value) || 0;
        const kembali = pembayaran - totalBelanja;
        kembalian.value = kembali >= 0 ? `Rp ${new Intl.NumberFormat('id-ID').format(kembali)}` : 'Rp 0';
    });

    document.getElementById('proses-transaksi').addEventListener('click', function () {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (selectedPayment === 'cash') {
            if (parseFloat(uangPembayaran.value) < totalBelanja) {
                alert('Uang pembayaran kurang!');
                return;
            }

            // Proses transaksi tunai
            fetch('{{ route('kasir.proses') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    total_belanja: totalBelanja,
                    uang_pembayaran: parseFloat(uangPembayaran.value),
                    kembalian: parseFloat(uangPembayaran.value) - totalBelanja,
                    payment_method: 'cash'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/payment/success';
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan, silakan coba lagi.');
            });
        } else {
            // Proses pembayaran Midtrans
            fetch('{{ route('kasir.proses') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    total_belanja: totalBelanja,
                    payment_method: 'midtrans'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '/payment/success';
                        },
                        onPending: function(result) {
                            window.location.href = '/payment/pending';
                        },
                        onError: function(result) {
                            window.location.href = '/payment/failed';
                        },
                        onClose: function() {
                            alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                        }
                    });
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan, silakan coba lagi.');
            });
        }
    });
});
</script>
@endpush
