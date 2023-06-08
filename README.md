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

### Install Package

```
composer require yogameleniawan/realtime-job-batching
```

### Service Repository
- Create repository class on `your_project\app\Repositories`
For example: `VerificationRepository.php`.

So now you have repository class `your_project\app\Repositories\VerificationRepository.php`
- Add `implements RealtimeJobBatchInterface`  in your Repository Class
- Don't forget to import interface `use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;`
- The interface has 2 methods. So, you should implement these methods :
  
  `public function get_all(): Collection`
  In this function you can get all data from your database and return it into Collection. Why you should return into Collection? First of all, the data will be looping with foreach method and then add one by one into job and then the process will executed one by one.
  
  `public function save($data): void`
  In this function you can make your own business logic to update/delete/something else that you want. So, this function doesn't have any return data that's why this function must be void type.
- This is the example of Repository Class

![image](https://github.com/yogameleniawan/job-batching-with-realtime-progress/assets/64576201/a73774ed-6854-4bec-895d-074f0fbf82e8)


