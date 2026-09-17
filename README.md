# Мои задачи (Tasks SPA)

SPA для управления личными задачами на Laravel 11 + Vue 3.

## Стек

- **Backend:** Laravel 11/12, MySQL, Sanctum (API auth)
- **Frontend:** Vue 3 (Composition API), Vue Router, Axios
- **Стили:** Custom CSS (без Tailwind)

## Быстрый старт

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=tasks_db
DB_USERNAME=tasks_user
DB_PASSWORD=secret
```

```bash
# Клонировать репозиторий
git clone <repo-url>
cd tasks-backend

# Запустить и инициализировать
docker-compose up -d --build
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

## Доступ

- **Приложение:** http://localhost:8080
- **База данных:** localhost:3306 (tasks_user / secret)

## API Endpoints

| Метод | URL | Описание |
|-------|-----|----------|
| GET | /api/tasks | Получить все задачи |
| POST | /api/tasks | Создать задачу |
| PUT | /api/tasks/{id} | Обновить задачу |
| DELETE | /api/tasks/{id} | Удалить задачу |
| POST | /api/tasks/{id}/reminder | Установить напоминание |
| DELETE | /api/tasks/{id}/reminder | Удалить напоминание |

## Функционал

- Регистрация / авторизация (Sanctum)
- CRUD задач (pending/completed)
- Напоминания (одно на задачу, макс. 3 активных)
- Валидация на бэкенде
