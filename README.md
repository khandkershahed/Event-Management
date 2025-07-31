# Laravel 12 Boilerplate

Customized Laravel Breeze to support multi-auth (separate admin from user table), and multiple user roles for Laravel 12.

## Installation

**Install project dependencies:**

`composer install`

**Copy .env from Example and Edit:**

`cp .env.example .env`

**Generate a new application key:**

`php artisan key:generate`

**Run database migrations:**

`php artisan migrate --seed`

## Usage

**Admin Login:**

`http://localhost:8000/admin/login`
