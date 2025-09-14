````markdown
# 📌 Laravel Todo API

Project ini adalah RESTful API berbasis **Laravel** untuk manajemen Todo List dengan fitur:
- ✅ CRUD Task
- ✅ Export Todo List ke **Excel** dengan filter
- ✅ Chart Data (Status, Priority, Assignee)
- ✅ Filtering berdasarkan title, assignee, due_date, time_tracked, status, dan priority

---

## 🚀 Setup Project

### 1. Clone Repository
```bash
git clone https://github.com/username/laravel-todo-api.git
cd laravel-todo-api
````

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

Copy file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Lalu edit konfigurasi database sesuai kebutuhan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Migrasi Database

```bash
php artisan migrate
```

Opsional: jalankan seeder jika tersedia

```bash
php artisan db:seed
```

### 6. Jalankan Server

```bash
php artisan serve
```

Server akan berjalan di:

```
http://127.0.0.1:8000
```