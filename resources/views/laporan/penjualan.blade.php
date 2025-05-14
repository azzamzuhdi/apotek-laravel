@extends('layouts.frontend')

@section('content')
<style>
    .container-fluid.custom-wide {
        max-width: 98vw;
        width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    @media (min-width: 1200px) {
        .container-fluid.custom-wide {
            max-width: 1600px;
        }
    }
    .sidebar-menu > li.active > a {
        padding-left: 15px !important;
    }
    .sidebar-menu > li > a {
        padding-left: 15px !important;
    }
    .sidebar-menu > li > a > span {
        margin-left: 5px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Laporan Penjualan
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Laporan Penjualan</li>
        </ol>
    </section>

    <section class="content">
        <div class="container-fluid custom-wide">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Filter Laporan</h3>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('laporan.penjualan') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Akhir</label>
                                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Metode Pembayaran</label>
                                            <select name="payment_method" class="form-control">
                                                <option value="">Semua</option>
                                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                                <option value="midtrans" {{ request('payment_method') == 'midtrans' ? 'selected' : '' }}>Online</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="transaction_status" class="form-control">
                                                <option value="">Semua</option>
                                                <option value="settlement" {{ request('transaction_status') == 'settlement' ? 'selected' : '' }}>Lunas</option>
                                                <option value="pending" {{ request('transaction_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="failed" {{ request('transaction_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-xs-6">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>Rp {{ number_format($totalPenjualan, 2, ',', '.') }}</h3>
                                    <p>Total Penjualan</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-money"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>{{ $jumlahTransaksi }}</h3>
                                    <p>Jumlah Transaksi</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-6">
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>Rp {{ number_format($jumlahTransaksi > 0 ? $totalPenjualan / $jumlahTransaksi : 0, 2, ',', '.') }}</h3>
                                    <p>Rata-rata Transaksi</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-calculator"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar Transaksi</h3>
                            <div class="box-tools pull-right">
                                <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-file-pdf-o"></i> Export PDF
                                </a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Order ID</th>
                                            <th>Total</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transaksi as $index => $item)
                                        <tr>
                                            <td>{{ $transaksi->firstItem() + $index }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $item->order_id ?? '-' }}</td>
                                            <td>Rp {{ number_format($item->total_belanja, 2, ',', '.') }}</td>
                                            <td>
                                                @if($item->payment_method == 'cash')
                                                    <span class="label label-success">Tunai</span>
                                                @else
                                                    <span class="label label-info">Online</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->payment_method == 'cash')
                                                    <span class="label label-success">Lunas</span>
                                                @else
                                                    @if($item->transaction_status == 'settlement' || $item->transaction_status == 'capture')
                                                        <span class="label label-success">Lunas</span>
                                                    @elseif($item->transaction_status == 'pending')
                                                        <span class="label label-warning">Pending</span>
                                                    @elseif($item->transaction_status == 'deny' || $item->transaction_status == 'cancel' || $item->transaction_status == 'expire')
                                                        <span class="label label-danger">Gagal</span>
                                                    @else
                                                        <span class="label label-default">{{ ucfirst($item->transaction_status) }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#detailModal{{ $item->id }}">
                                                    <i class="fa fa-eye"></i> Detail
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Detail Transaksi</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Obat</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Harga</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach(is_array($item->detail_obat) ? $item->detail_obat : json_decode($item->detail_obat, true) as $detail)
                                                                <tr>
                                                                    <td>{{ $detail['nama_obat'] }}</td>
                                                                    <td>{{ $detail['jumlah'] }}</td>
                                                                    <td>Rp {{ number_format($detail['harga'], 2, ',', '.') }}</td>
                                                                    <td>Rp {{ number_format($detail['subtotal'], 2, ',', '.') }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center">
                                {{ $transaksi->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
        'language'    : {
            'search': 'Cari:',
            'lengthMenu': 'Tampilkan _MENU_ data per halaman',
            'zeroRecords': 'Data tidak ditemukan',
            'info': 'Menampilkan halaman _PAGE_ dari _PAGES_',
            'infoEmpty': 'Tidak ada data yang tersedia',
            'infoFiltered': '(difilter dari _MAX_ total data)',
            'paginate': {
                'first': 'Pertama',
                'last': 'Terakhir',
                'next': 'Selanjutnya',
                'previous': 'Sebelumnya'
            }
        }
    });
});
</script>
@endpush
