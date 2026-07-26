# Workout Planner

Lightweight workout planner for personal trainers.  
Stack: **Laravel** (API) + **Vue 3** (SPA) + **Tailwind CSS** / DaisyUI.

Authenticated users are personal trainers. They can manage clients, exercises and workout plans.

## Features

- Register, login and logout (Laravel Sanctum cookie/session auth)
- Create, edit and delete clients (`first name`, `last name`, `email`)
- Create, edit and delete exercises (`name`, `description`, `active/inactive`)
- Create, edit and delete workout plans:
  - each plan belongs to a client
  - each plan contains multiple exercises
  - each exercise can be configured with `sets` and `reps`

## Repository layout

This project is split into two applications:

| App | Folder | Default URL |
|---|---|---|
| Backend API | `workout-planner-backend` | http://localhost:8000 |
| Frontend SPA | `workout-planner-frontend` | http://localhost:5173 |

## Prerequisites

- PHP **8.3+**
- Composer
- Node.js **22.18+** (or **>= 24.12**)
- npm

SQLite is used by default (no MySQL required).

---

## 1. Backend setup (`workout-planner-backend`)

```sh
cd workout-planner-backend
composer install
cp .env.example .env
php artisan key:generate
```

p.s. AI has only been used to generate this very helpful README 🙂