## How to use
1. Clone repo
   ```
   git clone git@github.com:dtsiuhb/pencairan.git uhb-pencairan
   ```
2. Copy environment
   ```
   cp .env.example .env
   ```
3. Install package
   ```
   composer install
   npm install
   ```
4. Build assets
   ```
   npm run build
   ```
5. Run via laragon vhost
6. Jika menggunakan database local PC, buat database dahulu lalu jalankan migration dan seed
   ```
   php artisan migrate:fresh --seed
   ```

## Notes
Jika ada perubahan lakukan di branch masing-masing.
- Cara pindah branch
  ```
  git checkout [nama branch]
  ```
- Cara buat branch baru
  ```
  git checkout -b [nama branch]
  ```

## Troubleshooting
- Selalu redirect ke SSO
  1. Copy public_key dan private_key dari SSO > Services ke .env
  2. Jika sebelumnya sudah bisa login, kembalikan environment ke tahap sebelumnya dan logout dari aplikasi