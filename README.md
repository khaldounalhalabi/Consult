# Consult API

Consult is a Laravel-based RESTful API that facilitates appointments between users and experts in various fields. The project was developed as a school project to learn Laravel and does not include any frontend interfaces.

## Features
- User authentication (registration, login, logout, and profile management)
- Expert authentication and profile management
- Appointment booking system
- Messaging system between users and experts
- Rating and reviews for experts
- Favorite experts functionality
- Category and expert listing

## Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/your-username/Consult.git
   cd Consult
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Configure environment:
    - Copy the `.env.example` file and rename it to `.env`
    - Update database credentials and other configurations
   ```sh
   php artisan key:generate
   ```
4. Run database migrations:
   ```sh
   php artisan migrate:fresh --seed
   ```
5. Run the Laravel development server:
   ```sh
   php artisan serve
   ```
## Technologies Used
- Laravel
- MySQL
- Sanctum for authentication
