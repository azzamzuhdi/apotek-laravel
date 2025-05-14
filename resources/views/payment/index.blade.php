@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pembayaran</div>

                <div class="card-body">
                    <form id="payment-form">
                        <div class="form-group mb-3">
                            <label for="amount">Jumlah Pembayaran (Rp)</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="10000" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const amount = document.getElementById('amount').value;
    
    // Tampilkan loading
    const button = this.querySelector('button');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = 'Memproses...';
    
    // Panggil API untuk membuat transaksi
    fetch('/api/payment/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ amount: amount })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Buka popup Midtrans
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    alert('Pembayaran berhasil!');
                    window.location.href = '/payment/success';
                },
                onPending: function(result) {
                    alert('Pembayaran pending!');
                    window.location.href = '/payment/pending';
                },
                onError: function(result) {
                    alert('Pembayaran gagal!');
                    window.location.href = '/payment/failed';
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            });
        } else {
            alert('Terjadi kesalahan: ' + data.message);
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan sistem');
        button.disabled = false;
        button.innerHTML = originalText;
    });
});
</script>
@endpush
@endsection 