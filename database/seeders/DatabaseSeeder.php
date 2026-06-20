<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Lecturer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Users ----
        User::create([
            'name' => 'Admin SI-Pedia', 'username' => 'Admin',
            'email' => 'adminSIPedia@gmail.com', 'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Ucup Pratama', 'username' => 'ucup',
            'email' => 'ucupganteng@gmail.com', 'password' => Hash::make('password'),
            'role' => 'user', 'study_program' => 'Sistem Informasi', 'force' => '2024',
        ]);

        // ---- Categories ----
        $cats = collect(['Berita', 'Event', 'Akademik', 'Lomba'])
            ->mapWithKeys(fn ($name) => [$name => Category::create(['name' => $name])->id]);

        // ---- Articles ----
        $articles = [
            ['Penerimaan Mahasiswa Baru (PMB) Universitas Indraprasta PGRI (Unindra) 2026/2027 resmi dibuka!', 'Berita', '2026-04-13', 240],
            ['Rektor dan segenap Civitas Academica Universitas Indraprasta PGRI mengucapkan Selamat Hari Lahir Pancasila.', 'Event', '2026-06-01', 190],
            ['Program Course Artificial Intelligence (AI)', 'Akademik', '2026-01-24', 155],
            ['Pengumuman Sosialisasi Satria Data & Lomba Inovasi Digital Mahasiswa', 'Lomba', '2026-05-22', 130],
        ];
        foreach ($articles as [$title, $cat, $date, $views]) {
            Article::create([
                'title' => $title, 'category_id' => $cats[$cat], 'writer' => 'Admin',
                'status' => 'active', 'views' => $views, 'created_at' => $date,
                'content' => 'Konten artikel SI-Pedia.',
            ]);
        }

        // ---- Lecturers ----
        $lect = [
            ['197653653', 'BudiSantoso', 'Jakarta'],
            ['195436828', 'Sitirahma', 'Bogor'],
            ['199007867', 'AndiPratama', 'Depok'],
            ['194537869', 'RezaFauzi', 'Bekasi'],
            ['197537861', 'IrwanHidayat', 'Cibinong'],
        ];
        foreach ($lect as [$nidn, $username, $address]) {
            Lecturer::create(['nidn' => $nidn, 'username' => $username, 'address' => $address, 'status' => 'active']);
        }

        // ---- Reviews ----
        for ($i = 0; $i < 8; $i++) {
            Review::create([
                'title' => 'BiangLala', 'type' => 'Social media',
                'description' => 'What makes this website impressive is not only the completeness...',
                'views' => 1200, 'status' => 'pending', 'reviewed_at' => '2025-07-28',
            ]);
        }
    }
}
