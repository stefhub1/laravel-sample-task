## [Laravel Sample Task](https://sad-states-switch-23-227-186-130.loca.lt/)

**Live** -> [here](https://sad-states-switch-23-227-186-130.loca.lt/)

### Execute
- `git clone`
- `cd sample-task`
- `composer install`
- `npm install`
- `cp .env.example .env`
- `php artisan key:generate`
- Edit .env - DB_DATABASE, DB_USERNAME, DB_PASSWORD, ACCESS_EMAIL, ASSET_URL
- `php artisan migrate`
- `php artisan db:seed` (optional)
- `php artisan serve`
- `npm run dev` or `npm run build`
