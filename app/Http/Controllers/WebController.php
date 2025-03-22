<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class WebController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
   
    }
    public function index()
    {
        return view('layouts.landing');
    }
    public function obat()
    {
        return view('layouts.obat');
    }
    public function laporanStok()
    {
        return view('layouts.laporanStok');
    }
    public function laporanKasir()
    {
        return view('layouts.laporanKasir');
    }
    public function kasir()
    {
        return view('layouts.kasir');
    }

    public function obatMasuk() 
    {
        return view('layouts.obatMasuk');
    }
    // public function login()
    // {
    //     return view('auth.login');
    // }
}
