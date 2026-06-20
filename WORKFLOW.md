# PWL Ensiklopedia — Alur Kerja Spec-Driven

**Stack:** Laravel 13 (PHP 8.3+) · Blade · Tailwind v4 (Vite) · DB + Auth · Web responsif

## Loop per screen
1. **Lihat SS Figma 3-4x** → analisis layout, token, komponen, teks.
2. **Tulis `specs/<screen>.md`** dari template (`specs/_TEMPLATE.md`).
3. **Tulis kode** Blade + Tailwind sesuai spec.
4. **Render & bandingkan**: `_compare/render_and_compare.py mirror.html <lebar> refs/<ss>.png <screen>`
   → cek gambar side-by-side (kiri = Figma, kanan = hasil).
5. **Revisi** sampai cocok (target 3-4 iterasi), centang checklist verifikasi di spec.
6. Lanjut screen berikutnya.

## Catatan lingkungan
- Sandbox **tidak punya PHP** → tidak bisa `artisan serve`. Verifikasi visual via
  **mirror HTML/Tailwind** yang identik dengan output Blade, di-render Chromium.
- Kode Laravel tetap lengkap & best-practice untuk dijalankan di mesin Anda:
  `composer install && npm install && npm run dev && php artisan migrate --seed && php artisan serve`

## Struktur
- `app/`, `routes/`, `database/` — kode Laravel
- `resources/views/` — Blade (layouts, components, pages, auth)
- `resources/css/app.css` — Tailwind v4 + design tokens
- `specs/` — spec per screen + `refs/` (screenshot Figma)
- `_compare/`, `_preview/` — perkakas verifikasi (tidak masuk produksi)
