# 🚀 Laravel Job Batching with Realtime Progress

<div align="center">

![Laravel Job Batching Banner](https://github.com/yogameleniawan/laravel-queue-realtime-progress/assets/64576201/42cf05bb-559d-4f62-950d-c1c66ebb4a8a)

[![Latest Version](https://img.shields.io/packagist/v/yogameleniawan/realtime-job-batching.svg?style=flat-square)](https://packagist.org/packages/yogameleniawan/realtime-job-batching)
[![Total Downloads](https://img.shields.io/packagist/dt/yogameleniawan/realtime-job-batching.svg?style=flat-square)](https://packagist.org/packages/yogameleniawan/realtime-job-batching)
[![License](https://img.shields.io/packagist/l/yogameleniawan/realtime-job-batching.svg?style=flat-square)](https://packagist.org/packages/yogameleniawan/realtime-job-batching)

*Execute Laravel job batches with real-time progress tracking using Pusher WebSocket*

</div>

## 🌐 Language / Bahasa

Choose your preferred language for documentation:

| Language | Documentation |
| 🇺🇸 **English** | [View English Documentation](#english-documentation) |
| 🇮🇩 **Bahasa Indonesia** | [Lihat Dokumentasi Bahasa Indonesia](#dokumentasi-bahasa-indonesia) |

### Quick Navigation
- [🚀 Getting Started](#️-installation) | [🚀 Memulai](#️-instalasi)
- [📖 API Reference](#-api-response) | [📖 Referensi API](#-response-api)
- [💡 Examples](#-javascript-setup) | [💡 Contoh](#-setup-javascript)

---

## 📖 English Documentation

### 🎯 Overview

Laravel Job Batching with Realtime Progress is a powerful package that allows you to execute batch jobs with real-time progress tracking. Monitor your job execution progress live using WebSocket technology powered by Pusher.

### 🎬 Live Demo

![Job Batching Preview](https://github.com/yogameleniawan/job-batching-with-realtime-progress/assets/64576201/039aacca-dcab-4fc2-a5e9-b99e1e0202ab)

### 📑 Table of Contents

- [✨ Features](#-features)
- [📋 Requirements](#-requirements)
- [⚙️ Installation](#️-installation)
- [🔧 Configuration](#-configuration)
- [🛠️ Implementation](#️-implementation)
- [📊 JavaScript Setup](#-javascript-setup)
- [📈 API Response](#-api-response)
- [🤝 Contributing](#-contributing)
- [📝 Changelog](#-changelog)
- [👥 Credits](#-credits)
- [📄 License](#-license)

### ✨ Features

- 🔄 **Real-time Progress Tracking** - Monitor job execution progress in real-time
- 📡 **WebSocket Integration** - Uses Pusher for instant updates
- 🎯 **Batch Processing** - Handle multiple jobs efficiently
- 🔌 **Easy Integration** - Simple setup with Laravel projects
- 📊 **Progress Metrics** - Get detailed progress information
- 🎨 **Customizable** - Flexible implementation for various use cases

### 📋 Requirements

- ![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-blue) [PHP 7.4 or Higher](https://www.php.net/)
- ![Laravel](https://img.shields.io/badge/Laravel-%3E%3D8.0-red) [Laravel 8 or Higher](https://www.laravel.com/)
- 🔗 [Laravel Job Queue](https://laravel.com/docs/10.x/queues#jobs-and-database-transactions)
- 📡 [Pusher Account](https://pusher.com/)

### ⚙️ Installation

#### Step 1: Database Setup

Create necessary migration tables:

```bash
# Create job table
php artisan queue:table

# Create job batches table  
php artisan queue:batches-table

# Run migrations
php artisan migrate
```

#### Step 2: Install Package

```bash
composer require yogameleniawan/realtime-job-batching
```

#### Step 3: Pusher Setup

Install Pusher PHP SDK:

```bash
composer require pusher/pusher-php-server
```

### 🔧 Configuration

#### Pusher Configuration

1. **Create Pusher App**: Visit [Pusher Dashboard](https://dashboard.pusher.com/channels) to create a new Channels app

2. **Environment Variables**: Add these variables to your `.env` file:

```env
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
PUSHER_HOST=
PUSHER_PORT=443  
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

### 🛠️ Implementation

#### Create Service Repository

1. **Create Repository Directory**: Create folder `app/Repositories` in your project

2. **Create Repository Class**: For example, `VerificationRepository.php`

3. **Implement Interface**: Your repository must implement `RealtimeJobBatchInterface`

```php
<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;

class VerificationRepository implements RealtimeJobBatchInterface
{
    /**
     * Get all data to be processed
     * @return Collection
     */
    public function get_all(): Collection
    {
        // Return collection of data to be processed
        return collect([
            // Your data here
        ]);
    }

    /**
     * Process individual data item
     * @param mixed $data
     * @return void
     */
    public function save($data): void
    {
        // Your business logic here
        // Update/delete/process the data
    }
}
```

#### Create Controller Method

Create a controller method to handle your job process:

```php
<?php

namespace App\Http\Controllers;

use YogaMeleniawan\JobBatchingWithRealtimeProgress\RealtimeJobBatch;
use App\Repositories\VerificationRepository;

class BatchController extends Controller
{
    public function executeVerification()
    {
        $result = RealtimeJobBatch::setRepository(new VerificationRepository())
                                 ->execute(name: 'User Verification Process');
        
        return response()->json([
            'message' => 'Batch job started successfully',
            'batch_id' => $result->id
        ]);
    }
}
```

#### Method Explanation

- **`setRepository(RealtimeJobBatchInterface $repository)`**: Sets the repository class to use
- **`execute(string $name)`**: Executes the job batch with a custom name

### 📊 JavaScript Setup

#### For Laravel Blade Views

Add this script to your blade template:

```html
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Initialize Pusher
    var pusher = new Pusher('YOUR_PUSHER_APP_KEY', {
        cluster: 'mt1'
    });

    // Listen for progress updates
    var progressChannel = pusher.subscribe('channel-job-batching');
    progressChannel.bind('broadcast-job-batching', function(data) {
        console.log('Progress Update:', data);
        
        // Update your progress bar
        updateProgressBar(data.progress);
        updateStats(data);
    });

    // Listen for completion
    var finishChannel = pusher.subscribe('channel-finished-job');
    finishChannel.bind('request-finished-job', function(data) {
        if (data.finished === true) {
            console.log('Job completed!');
            resetProgressBar();
        }
    });

    // Helper functions
    function updateProgressBar(progress) {
        document.getElementById('progress-bar').style.width = progress + '%';
        document.getElementById('progress-text').textContent = progress + '%';
    }

    function updateStats(data) {
        document.getElementById('total-jobs').textContent = data.total;
        document.getElementById('pending-jobs').textContent = data.pending;
    }

    function resetProgressBar() {
        updateProgressBar(0);
        // Add your completion logic here
    }
</script>
```

#### For Other Frameworks

For React, Vue, or other JavaScript frameworks, check the [Pusher documentation](https://pusher.com/docs/channels/getting_started/javascript/?ref=docs-index).

### 📈 API Response

The WebSocket will send progress updates in this format:

```json
{
    "finished": false,
    "progress": 10,
    "pending": 90,
    "total": 100,
    "data": {
        "batch_id": "uuid-string",
        "name": "User Verification Process",
        "started_at": "2024-01-01T10:00:00Z"
    }
}
```

#### Response Fields

- **`finished`**: Boolean indicating if the batch is complete
- **`progress`**: Number of completed jobs
- **`pending`**: Number of remaining jobs  
- **`total`**: Total number of jobs in the batch
- **`data`**: Additional batch information

### 🤝 Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for details.

### 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

### 👥 Credits

- [Yoga Meleniawan Pamungkas](https://github.com/yogameleniawan) - *Original Author*
- [All Contributors](../../contributors) - *Community Contributors*

### 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 📖 Dokumentasi Bahasa Indonesia

### 🎯 Ringkasan

Laravel Job Batching with Realtime Progress adalah package yang memungkinkan Anda menjalankan batch job dengan pelacakan progres real-time. Pantau eksekusi job Anda secara langsung menggunakan teknologi WebSocket yang didukung oleh Pusher.

### 🎬 Demo Langsung

![Preview Job Batching](https://github.com/yogameleniawan/job-batching-with-realtime-progress/assets/64576201/039aacca-dcab-4fc2-a5e9-b99e1e0202ab)

### 📑 Daftar Isi

- [✨ Fitur](#-fitur)
- [📋 Persyaratan](#-persyaratan)
- [⚙️ Instalasi](#️-instalasi)
- [🔧 Konfigurasi](#-konfigurasi)
- [🛠️ Implementasi](#️-implementasi)
- [📊 Setup JavaScript](#-setup-javascript)
- [📈 Response API](#-response-api)
- [🤝 Kontribusi](#-kontribusi)
- [📝 Changelog](#-changelog-1)
- [👥 Kredit](#-kredit)
- [📄 Lisensi](#-lisensi)

### ✨ Fitur

- 🔄 **Pelacakan Progres Real-time** - Pantau progres eksekusi job secara real-time
- 📡 **Integrasi WebSocket** - Menggunakan Pusher untuk update instan
- 🎯 **Pemrosesan Batch** - Menangani multiple job secara efisien
- 🔌 **Integrasi Mudah** - Setup sederhana dengan proyek Laravel
- 📊 **Metrik Progres** - Dapatkan informasi progres yang detail
- 🎨 **Dapat Dikustomisasi** - Implementasi fleksibel untuk berbagai kasus penggunaan

### 📋 Persyaratan

- ![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-blue) [PHP 7.4 atau Lebih Tinggi](https://www.php.net/)
- ![Laravel](https://img.shields.io/badge/Laravel-%3E%3D8.0-red) [Laravel 8 atau Lebih Tinggi](https://www.laravel.com/)
- 🔗 [Laravel Job Queue](https://laravel.com/docs/10.x/queues#jobs-and-database-transactions)
- 📡 [Akun Pusher](https://pusher.com/)

### ⚙️ Instalasi

#### Langkah 1: Setup Database

Buat tabel migrasi yang diperlukan:

```bash
# Buat tabel job
php artisan queue:table

# Buat tabel job batches
php artisan queue:batches-table

# Jalankan migrasi
php artisan migrate
```

#### Langkah 2: Install Package

```bash
composer require yogameleniawan/realtime-job-batching
```

#### Langkah 3: Setup Pusher

Install Pusher PHP SDK:

```bash
composer require pusher/pusher-php-server
```

### 🔧 Konfigurasi

#### Konfigurasi Pusher

1. **Buat Aplikasi Pusher**: Kunjungi [Dashboard Pusher](https://dashboard.pusher.com/channels) untuk membuat aplikasi Channels baru

2. **Variabel Environment**: Tambahkan variabel ini ke file `.env` Anda:

```env
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

### 🛠️ Implementasi

#### Buat Service Repository

1. **Buat Direktori Repository**: Buat folder `app/Repositories` di proyek Anda

2. **Buat Class Repository**: Misalnya, `VerificationRepository.php`

3. **Implement Interface**: Repository Anda harus mengimplementasikan `RealtimeJobBatchInterface`

```php
<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;

class VerificationRepository implements RealtimeJobBatchInterface
{
    /**
     * Dapatkan semua data yang akan diproses
     * @return Collection
     */
    public function get_all(): Collection
    {
        // Return collection data yang akan diproses
        return collect([
            // Data Anda di sini
        ]);
    }

    /**
     * Proses item data individual
     * @param mixed $data
     * @return void
     */
    public function save($data): void
    {
        // Logic bisnis Anda di sini
        // Update/delete/proses data
    }
}
```

#### Buat Method Controller

Buat method controller untuk menangani proses job Anda:

```php
<?php

namespace App\Http\Controllers;

use YogaMeleniawan\JobBatchingWithRealtimeProgress\RealtimeJobBatch;
use App\Repositories\VerificationRepository;

class BatchController extends Controller
{
    public function executeVerification()
    {
        $result = RealtimeJobBatch::setRepository(new VerificationRepository())
                                 ->execute(name: 'Proses Verifikasi User');
        
        return response()->json([
            'message' => 'Batch job berhasil dimulai',
            'batch_id' => $result->id
        ]);
    }
}
```

#### Penjelasan Method

- **`setRepository(RealtimeJobBatchInterface $repository)`**: Mengatur class repository yang akan digunakan
- **`execute(string $name)`**: Menjalankan job batch dengan nama kustom

### 📊 Setup JavaScript

#### Untuk Laravel Blade Views

Tambahkan script ini ke template blade Anda:

```html
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Inisialisasi Pusher
    var pusher = new Pusher('YOUR_PUSHER_APP_KEY', {
        cluster: 'mt1'
    });

    // Listen untuk update progres
    var progressChannel = pusher.subscribe('channel-job-batching');
    progressChannel.bind('broadcast-job-batching', function(data) {
        console.log('Update Progres:', data);
        
        // Update progress bar Anda
        updateProgressBar(data.progress);
        updateStats(data);
    });

    // Listen untuk penyelesaian
    var finishChannel = pusher.subscribe('channel-finished-job');
    finishChannel.bind('request-finished-job', function(data) {
        if (data.finished === true) {
            console.log('Job selesai!');
            resetProgressBar();
        }
    });

    // Helper functions
    function updateProgressBar(progress) {
        document.getElementById('progress-bar').style.width = progress + '%';
        document.getElementById('progress-text').textContent = progress + '%';
    }

    function updateStats(data) {
        document.getElementById('total-jobs').textContent = data.total;
        document.getElementById('pending-jobs').textContent = data.pending;
    }

    function resetProgressBar() {
        updateProgressBar(0);
        // Tambahkan logic completion Anda di sini
    }
</script>
```

#### Untuk Framework Lain

Untuk React, Vue, atau framework JavaScript lainnya, periksa [dokumentasi Pusher](https://pusher.com/docs/channels/getting_started/javascript/?ref=docs-index).

### 📈 Response API

WebSocket akan mengirim update progres dalam format ini:

```json
{
    "finished": false,
    "progress": 10,
    "pending": 90,
    "total": 100,
    "data": {
        "batch_id": "uuid-string",
        "name": "Proses Verifikasi User",
        "started_at": "2024-01-01T10:00:00Z"
    }
}
```

#### Field Response

- **`finished`**: Boolean yang menunjukkan apakah batch sudah selesai
- **`progress`**: Jumlah job yang sudah selesai
- **`pending`**: Jumlah job yang tersisa
- **`total`**: Total jumlah job dalam batch
- **`data`**: Informasi tambahan batch

### 🤝 Kontribusi

Kami menyambut kontribusi! Silakan lihat [Panduan Kontribusi](CONTRIBUTING.md) kami untuk detail.

### 📝 Changelog

Silakan lihat [CHANGELOG](CHANGELOG.md) untuk informasi lebih lanjut tentang perubahan terbaru.

### 👥 Kredit

- [Yoga Meleniawan Pamungkas](https://github.com/yogameleniawan) - *Penulis Asli*
- [Semua Kontributor](../../contributors) - *Kontributor Komunitas*

### 📄 Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE](LICENSE) untuk detail.

---

<div align="center">

**[⬆ Back to top](#-laravel-job-batching-with-realtime-progress) | [⬆ Kembali ke atas](#-laravel-job-batching-with-realtime-progress) | [⬆ トップに戻る](#-laravel-job-batching-with-realtime-progress)**

---

### 🌟 Star this repository if you find it helpful!

Made with ❤️ by [Yoga Meleniawan Pamungkas](https://github.com/yogameleniawan)

[![GitHub stars](https://img.shields.io/github/stars/yogameleniawan/laravel-queue-realtime-progress.svg?style=social&label=Star)](https://github.com/yogameleniawan/laravel-queue-realtime-progress)
[![GitHub forks](https://img.shields.io/github/forks/yogameleniawan/laravel-queue-realtime-progress.svg?style=social&label=Fork)](https://github.com/yogameleniawan/laravel-queue-realtime-progress/fork)

</div>
