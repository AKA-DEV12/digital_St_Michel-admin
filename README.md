# Digital St Michel - Admin Panel

Welcome to the administration dashboard for **Digital St Michel**. This application is built with Laravel and provides a comprehensive suite of tools for managing reservations, rooms, activities, and registrations.

## 🚀 Key Features

- **📊 Dashboard**: Real-time overview of system activities and statistics.
- **📅 Reservation Management**: Create, validate, cancel, and export reservations.
- **🕒 Time Slot Management**: Define and manage available time slots for activities.
- **🏨 Room Management**: Manage physical spaces and facilities.
- **🚶 Movement Tracking**: Monitor and manage movements within the system.
- **📝 Activity & Registration**: Full lifecycle management of activity registrations.
- **👤 Profile Management**: Secure administrator profile updates and authentication.

## 🛠️ Tech Stack

- **Backend**: [Laravel 11](https://laravel.com)
- **Frontend**: [Tailwind CSS v4](https://tailwindcss.com), [Vite](https://vitejs.dev), [Alpine.js](https://alpinejs.dev)
- **Icons**: [Flaticon](https://www.flaticon.com)
- **Dependencies**: Managed via Composer and NPM.

## 📦 Installation & Setup

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (or another supported database)

### Steps
1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-repo/digital_St_Michel-admin.git
   cd digital_St_Michel-admin
   ```
2. **Install PHP dependencies**:
   ```bash
   composer install
   ```
3. **Install JS dependencies**:
   ```bash
   npm install
   ```
4. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. **Database Migration**:
   ```bash
   php artisan migrate --seed
   ```
6. **Start Development Server**:
   ```bash
   npm run dev
   # In another terminal:
   php artisan serve
   ```

## 📜 Available Commands

- `npm run dev`: Starts the Vite development server.
- `npm run build`: Compiles assets for production.
- `php artisan migrate`: Runs database migrations.
- `php artisan db:seed`: Seeds the database with initial data.

## 📂 Project Structure Highlights

- `app/Http/Controllers`: Contains the logic for managing various entities.
- `resources/views`: Blade templates for the admin interface.
- `routes/web.php`: Defines all administration routes and middleware logic.
- `tailwind.config.js`: Configuration for the Tailwind CSS design system.

---

Built with ❤️ for St Michel.
