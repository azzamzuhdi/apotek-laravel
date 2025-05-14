@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pembayaran Gagal</div>

                <div class="card-body text-center">
                    <i class="fas fa-times-circle text-danger" style="font-size: 48px;"></i>
                    <h3 class="mt-3">Pembayaran Anda Gagal</h3>
                    <p>Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi.</p>
                    <a href="/payment" class="btn btn-primary">Coba Lagi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 