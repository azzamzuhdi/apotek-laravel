@extends('layouts.frontend')

@section('content')
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Pembayaran Pending</h3>
    </div>
    <div class="box-body">
        <div class="text-center">
            <i class="fa fa-clock-o fa-4x text-warning"></i>
            <h3 class="mt-3">Pembayaran Anda Sedang Diproses</h3>
            <p class="text-muted">Mohon tunggu hingga pembayaran Anda selesai diproses.</p>
            <p class="text-muted">Anda akan menerima notifikasi ketika pembayaran telah berhasil.</p>
            
            <div class="mt-4">
                <a href="{{ route('kasir.index') }}" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> Kembali ke Kasir
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 