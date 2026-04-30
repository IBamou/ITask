# ITask - Task Management Application

A Laravel-based task management web application built with Laravel 13 and PHP 8.3.

## Features

- **User Authentication** - Secure registration and login using Laravel Breeze
- **Category Management** - Create, read, update, and delete task categories
- **Task Management** - Full CRUD operations for tasks within categories
- **Subtask Support** - Break down tasks into manageable subtasks
- **Status Tracking** - Toggle completion status for tasks and subtasks
- **User Profile** - Edit and manage user profiles

## Technology Stack

- **Backend**: Laravel 13 (PHP 8.3)
- **Frontend**: Blade Templates + Tailwind CSS
- **Authentication**: Laravel Breeze
- **Database**: SQLite (default) / MySQL (configurable)
- **Development Tools**: Laravel Telescope, Pint

## Project Structure

```
ITask/
├── app/
│   ├── Http/Controllers/   # CategoryController, TaskController, SubtaskController, ProfileController
│   ├── Models/             # User, Task, Subtask, Category
│   └── Policies/           # CategoryPolicy, TaskPolicy, SubtaskPolicy
├── routes/
│   ├── web.php             # Main application routes
│   └── auth.php            # Authentication routes
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
└── docs/                   # Project documentation
```

---

## Database Design

### MCD (Modèle Conceptuel de Données)

```
┌─────────────────┐         ┌─────────────────┐         ┌─────────────────┐
│      USER       │         │    CATEGORY     │         │      TASK       │
├─────────────────┤         ├─────────────────┤         ├─────────────────┤
│ id (PK)         │──1,N──┐ │ id (PK)         │──1,N──┐ │ id (PK)         │
│ name            │       │ │ name            │       │ │ title           │
│ email           │       └─┤ user_id (FK)    │       │ │ description     │
│ password        │         └─────────────────┘       │ │ status          │
│ remember_token  │                                    │ │ priority        │
│ email_verified  │                                    │ │ due_date        │
└─────────────────┘                                    │ category_id(FK) │
                                                         │ user_id (FK)    │
                                                         └────────┬────────┘
                                                                  │ 1,N
                                                                  │
                                                         ┌────────▼────────┐
                                                         │    SUBTASK      │
                                                         ├─────────────────┤
                                                         │ id (PK)         │
                                                         │ task (title)    │
                                                         │ done (boolean)  │
                                                         │ task_id (FK)    │
                                                         └─────────────────┘
```

#### Relationships

| Entity 1 | Relationship | Entity 2 | Description |
|----------|---------------|----------|-------------|
| User | 1,N | Category | A user can have multiple categories |
| User | 1,N | Task | A user can have multiple tasks |
| Category | 1,N | Task | A category can contain multiple tasks |
| Task | 1,N | Subtask | A task can have multiple subtasks |

---

### MLD (Modèle Logique de Données)

#### Table: users
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AUTO_INCREMENT | User identifier |
| name | VARCHAR(255) | NOT NULL | User full name |
| email | VARCHAR(255) | NOT NULL, UNIQUE | User email |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

#### Table: categories
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AUTO_INCREMENT | Category identifier |
| name | VARCHAR(255) | NOT NULL | Category name |
| user_id | BIGINT | FK (users.id), ON DELETE CASCADE | Owner user |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

#### Table: tasks
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AUTO_INCREMENT | Task identifier |
| title | VARCHAR(255) | NOT NULL | Task title |
| description | TEXT | NULLABLE | Task description |
| status | ENUM | pending, in_progress, done | Task status |
| priority | ENUM | low, medium, high | Task priority |
| due_date | DATE | NULLABLE | Task due date |
| category_id | BIGINT | FK (categories.id), NULLABLE, ON DELETE CASCADE | Parent category |
| user_id | BIGINT | FK (users.id), NULLABLE, ON DELETE CASCADE | Owner user |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

#### Table: subtasks
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PK, AUTO_INCREMENT | Subtask identifier |
| task | VARCHAR(255) | NOT NULL | Subtask title |
| done | BOOLEAN | DEFAULT false | Completion status |
| task_id | BIGINT | FK (tasks.id), NULLABLE, ON DELETE CASCADE | Parent task |
| created_at | TIMESTAMP | | Creation timestamp |
| updated_at | TIMESTAMP | | Update timestamp |

---

### Physical Model (MPD)

```sql
-- Users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Categories table
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tasks table
CREATE TABLE tasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status ENUM('pending', 'in_progress', 'done') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    due_date DATE NULL,
    category_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Subtasks table
CREATE TABLE subtasks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255) NOT NULL,
    done TINYINT(1) DEFAULT 0,
    task_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE
);
```

---

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy environment file:
   ```bash
   cp .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Build frontend assets:
   ```bash
   npm run build
   ```

## Running the Application

Start the development server:
```bash
php artisan serve
```

Or run the full dev environment (server + queue + vite):
```bash
npm run dev
```

## API Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | / | Welcome page |
| GET | /dashboard | Task dashboard |
| GET/POST | /categories | List/Create categories |
| GET/PUT/DELETE | /categories/{id} | Show/Update/Delete category |
| POST | /categories/{id}/task-store | Create task in category |
| POST | /categories/{id}/{task}/toggle | Toggle task status |
| PUT | /categories/{id}/{task}/update | Update task |
| DELETE | /categories/{id}/{task}/delete | Delete task |
| POST | /tasks/{task}/subtask-store | Create subtask |
| PUT | /subtask/{subtask}/update | Update subtask |
| PUT | /subtask/{subtask}/toggle | Toggle subtask status |
| DELETE | /subtask/{subtask}/delete | Delete subtask |

## Testing

Run tests with Pest:
```bash
npm run test
```

## Seeding

Run database seeders to populate sample data:
```bash
php artisan db:seed
```

Or seed specific seeders:
```bash
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=TaskSeeder
php artisan db:seed --class=SubtaskSeeder
```

To migrate and seed in one command:
```bash
php artisan migrate:fresh --seed
```

## License

MIT