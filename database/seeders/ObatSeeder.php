<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $obat = [
            [
                'nama_obat' => 'Paracetamol 500mg',
                'kategori' => 'Obat Bebas',
                'stok' => 100,
                'harga' => 5000,
                'stok_keluar' => 0
            ],
            [
                'nama_obat' => 'Amoxicillin 500mg',
                'kategori' => 'Obat Keras',
                'stok' => 50,
                'harga' => 15000,
                'stok_keluar' => 0
            ],
            [
                'nama_obat' => 'Omeprazole 20mg',
                'kategori' => 'Obat Keras',
                'stok' => 75,
                'harga' => 25000,
                'stok_keluar' => 0
            ],
            [
                'nama_obat' => 'Cetirizine 10mg',
                'kategori' => 'Obat Bebas',
                'stok' => 60,
                'harga' => 12000,
                'stok_keluar' => 0
            ],
            [
                'nama_obat' => 'Metformin 500mg',
                'kategori' => 'Obat Keras',
                'stok' => 40,
                'harga' => 30000,
                'stok_keluar' => 0
            ]
        ];

        foreach ($obat as $item) {
            Obat::create($item);
        }
    }
} 