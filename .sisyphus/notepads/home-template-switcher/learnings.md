# Learnings
## Route Name Discrepancies
- The `home-v2.blade.php` template was using non-existent routes like `program.akad`, `program.show`, and `program.category`.
- Real routes found in `web.php`:
  - `program.index` (with `akad` or `category` as query params)
  - `program.detail` (instead of `program.show`)
- Always verify route names using `php artisan route:list` when implementing new templates.
