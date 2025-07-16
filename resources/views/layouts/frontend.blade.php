<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Apotek RADINKA</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="resources/css/styles.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- ADD THE CLASS layout-boxed TO GET A BOXED LAYOUT -->

<body class="hold-transition skin-blue layout-boxed sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>RDK</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Apotek</b>RADINKA</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <li class="dropdown user user-menu">
                            <a href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();">
                                <i class="fa fa-user"></i> Log out
                            </a>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->

       <aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../../dist/img/avatar.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ ucfirst(auth()->user()->level) }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree">
            @if (auth()->user()->level == 'admin' || auth()->user()->level == 'user')
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-plus-circle"></i> <span>Stok</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu active">
                        <li><a href="{{ route('obat-masuk.index') }}"><i class="fa fa-circle-o"></i> Obat Masuk</a></li>
                        <li><a href="{{ route('laporan.obat-keluar') }}"><i class="fa fa-circle-o"></i> Obat Keluar</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="{{ route('obat.index') }}">
                        <i class="fa fa-medkit"></i> <span>Data Obat</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->level == 'kasir')
                <li class="treeview">
                    <a href="{{ route('kasir.index') }}">
                        <i class="fa fa-shopping-cart"></i> <span>Kasir</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->level == 'admin')
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-book"></i> <span>Laporan</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu active">
                        <li><a href="/laporan-stok-masuk"><i class="fa fa-circle-o"></i> Laporan Stok</a></li>
                        <li><a href="{{ route('laporan.penjualan') }}"><i class="fa fa-circle-o"></i> Laporan Penjualan</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </section>
</aside>


        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            {{-- <section class="content-header">
                <h1>
                    APOTEK RADINKA
                </h1>

            </section> --}}

            <!-- Main content -->
            <section class="content">
                @yield('content')

                <!-- Default box -->

                <!-- /.box -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- ./wrapper -->

        <!-- jQuery 3 -->
        <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="../../dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../../dist/js/demo.js"></script>
        <script>
            $(document).ready(function() {
                $('.treeview a').on('click', function(e) {
                    // Pastikan hanya menu tanpa dropdown yang dinavigasikan
                    if (!$(this).siblings('ul').length) {
                        e.preventDefault(); // Mencegah navigasi default sementara
                        window.location.href = $(this).attr('href'); // Navigasi manual
                    }
                });
            });
        </script>
</body>

</html>
