@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pembayaran Berhasil</div>

                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                    <h3 class="mt-3">Pembayaran Anda Berhasil!</h3>
                    <p>Terima kasih telah melakukan pembayaran.</p>
                    <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 