# Laravel Boilerplate with Spatie Packages

A Laravel starter boilerplate integrated with essential Spatie packages: **Permissions**, **Backup**, and **Sluggable**, along with CRUD for Users, Roles, and Permissions. Preloaded with sensible defaults, pre-defined roles/permissions, and demo users for faster development.

---

## 🚀 Features

-   ✅ Role & Permission management using `spatie/laravel-permission`
-   ✅ Slug generation for models using `spatie/laravel-sluggable`
-   ✅ Database & file backups using `spatie/laravel-backup`
-   ✅ CRUD for:
    -   Users
    -   Roles
    -   Permissions
-   ✅ Predefined roles, permissions & users via seeders
-   ✅ Clean UI built with Blade templating

---

## 📦 Packages Used

| Package                                                                                | Purpose                                   |
| -------------------------------------------------------------------------------------- | ----------------------------------------- |
| [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v6/introduction) | Role & permission management              |
| [spatie/laravel-sluggable](https://github.com/spatie/laravel-sluggable)                | Automatically generate SEO-friendly slugs |
| [spatie/laravel-backup](https://spatie.be/docs/laravel-backup/v9/introduction)         | Backup your app and database              |

---

## ⚙️ Setup Instructions

```bash
# 1. Clone the repo
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name

# 2. Install dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate --seed
```

> Make sure your `.env` file contains the correct **DB** and **mail** configurations before running the seeders.

---

## 🌱 Seeded Data

After running `php artisan migrate --seed`, the following data will be seeded into your application:

### ✅ Permissions

These granular permissions are seeded by `PermissionsTableSeeder`:

```
create/view/update/delete user
activate/deactivate user
create/view/update/delete role
create/view/update/delete permission
view/delete session
view/delete backup
```

### ✅ Roles

Seeded by `RolesTableSeeder`:

-   `super-admin` – Has all permissions
-   `admin` – Can be customized to have limited permissions
-   `user` – Basic role for app users

> The `super-admin` role automatically gets **all permissions**.

### ✅ Users

Seeded by `UsersTableSeeder`, with the following credentials:

| Name                | Email                                             | Role        | Password |
| ------------------- | ------------------------------------------------- | ----------- | -------- |
| Sami Ullah Ata      | [sami@gmail.com](mailto:sami@gmail.com)           | super-admin | 123456   |
| Shahmir Khan Jadoon | [shahmirkj@gmail.com](mailto:shahmirkj@gmail.com) | super-admin | 123456   |
| Syed Talha Masood   | [talha@gmail.com](mailto:talha@gmail.com)         | admin       | 123456   |
| Harris Khan         | [harris@gmail.com](mailto:harris@gmail.com)       | admin       | 123456   |
| Mehrunisa           | [mehrunisa@gmail.com](mailto:mehrunisa@gmail.com) | user        | 123456   |
| Rizwan Khan         | [rizwan@gmail.com](mailto:rizwan@gmail.com)       | user        | 123456   |
| Shamshad Bano       | [shamshad@gmail.com](mailto:shamshad@gmail.com)   | user        | 123456   |
| Akbar Khan          | [akbar@gmail.com](mailto:akbar@gmail.com)         | user        | 123456   |
| Ahmed Nabi          | [ahmed@gmail.com](mailto:ahmed@gmail.com)         | user        | 123456   |
| Nabeel Suleman      | [nabeel@gmail.com](mailto:nabeel@gmail.com)       | user        | 123456   |
| Farhan Khan         | [farhan@gmail.com](mailto:farhan@gmail.com)       | user        | 123456   |
| Sudais Masood       | [sudais@gmail.com](mailto:sudais@gmail.com)       | user        | 123456   |
| Mumraiz Nuqashband  | [mumraiz@gmail.com](mailto:mumraiz@gmail.com)     | user        | 123456   |

> Use one of the `super-admin` credentials to log in and manage the app.

---

## 🛠 Backup Commands

```bash
php artisan backup:run        # Run a new backup
php artisan backup:list       # List all backups
php artisan backup:clean      # Clean old backups
```

You can also schedule these via cron in `App\Console\Kernel`.

---

## 📁 Folder Structure Overview

```
app/
├── Models/                    # Custom models for Role, Permission, User
database/
├── seeders/                  # Seeder files for roles, permissions, users
resources/
├── views/                    # Blade templates for layout and CRUD pages
routes/
├── web.php                   # Web routes
```

---

## 📌 Next Steps

-   ✅ Add API endpoints (optional)
-   ✅ Integrate frontend (Vue/React) if needed
-   ✅ Extend CRUD for other models
-   ✅ Add unit/integration tests

## 🤝 Contributing

Pull requests and issues are welcome! If you spot bugs or have suggestions, open an issue or fork and contribute.

---

## 📄 License

This project is licensed under the [MIT License](https://chatgpt.com/c/LICENSE).

---

## 👨‍💻 Maintained By

**Shahmir Khan Jadoon**  
GitHub: [@shahmir811](https://github.com/shahmir811)

---
