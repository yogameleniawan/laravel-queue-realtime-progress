# Laravel Job Batching with Realtime Progress

## Requirement
- [Laravel 8 or Higher](https://www.laravel.com/)
- [Laravel Job Queue](https://laravel.com/docs/10.x/queues#jobs-and-database-transactions)
- [Pusher](https://pusher.com/)

## Configuration

### Migration Table
- Make migration job table
```
php artisan queue:table
```

- Make migration job batches table
```
php artisan queue:batches-table
```

- Migrate table
```
php artisan migrate
```

### Service Repository
- Create repository class on `your_project\app\Repositories`
For example: `VerificationRepository.php`.

So now you have repository class `your_project\app\Repositories\VerificationRepository.php`
