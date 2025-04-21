# 📘 Laravel JWT API

[![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](https://github.com/eriktrs/api_conectala/blob/main/README.pt-br.md)

> 🇪️ English version

As part of the challenge proposed by the company **Conecta Lá**, the task was to develop a RESTful API in PHP, with the following requirements:

- The API must create, update, delete, and list all users.
- Information must be stored in a MySQL database.
- The endpoint must return data in JSON format and allow GET, POST, PUT, and DELETE operations to manipulate user records.
- The API must address aspects such as security, input validation, and error handling.

---

## 🚀 Features

- Laravel 11 + Sail + PHP 8
- JWT authentication
- Throttled login route
- Protected `/me`, `/logout`, `/refresh` endpoints
- Full User CRUD (protected)
- Custom pagination, filtering, and sorting

---

## 📦 Requirements

### Runtime Requirements

- PHP 8
- Laravel 11 or above
- Docker Desktop
- Ubuntu 24.04
- WSL
- Windows 10 or above
- Laravel Sail (`./vendor/bin/sail`)
- Postman

### API Requirements

All requests must:
- Use `Content-Type: application/json` where applicable
- Use a valid JWT in the `Authorization: Bearer <token>` header (for protected routes)

---

## 🛠️ Installation

```bash
git clone https://github.com/eriktrs/api_conectala
cd api_conectala
cp .env.example .env
composer install
./vendor/bin/sail up -d
./vendor/bin/sail php artisan migrate
./vendor/bin/sail php artisan jwt:secret
```

---

## 🔑 Authentication Routes

| Method | Endpoint     | Description            |
|--------|--------------|------------------------|
| POST   | /register    | Register new user      |
| POST   | /login       | Authenticate user (JWT)|
| POST   | /refresh     | Refresh token          |
| POST   | /logout      | Logout (invalidate JWT)|
| GET    | /me          | Authenticated user info|

### Usage Tutorial 

#### 📍 Route: **Register**
- **Method**: `POST`
- **URL**: `/api/register`
- **Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Expected Result**:
  ```json
  {
    "status": "success",
    "message": "User created successfully",
    "user": {
      "name": "John Doe",
      "email": "john@example.com"
    },
    "authorization": {
      "token": "<JWT_TOKEN>",
      "type": "bearer"
    }
  }
  ```

#### 📍 Route: **Login**
- **Method**: `POST`
- **URL**: `/api/login`
- **Body**:
  ```json
  {
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Expected Result**:
  ```json
  {
    "status": "success",
    "user": {
      "name": "John Doe",
      "email": "john@example.com"
    },
    "authorization": {
      "token": "<JWT_TOKEN>",
      "type": "bearer",
      "expires_in": 3600
    }
  }
  ```

#### 📍 Route: **Me**
- **Method**: `GET`
- **URL**: `/api/me`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Expected Result**:
  ```json
  {
    "status": "success",
    "user": {
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
  ```

#### 📍 Route: **Logout**
- **Method**: `POST`
- **URL**: `/api/logout`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Expected Result**:
  ```json
  {
    "status": "success",
    "message": "Successfully logged out"
  }
  ```

#### 📍 Route: **Refresh**
- **Method**: `POST`
- **URL**: `/api/refresh`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Expected Result**:
  ```json
  {
    "status": "success",
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "authorization": {
      "token": "<NEW_JWT_TOKEN>",
      "type": "bearer",
      "expires_in": 3600
    }
  }
  ```

---

## 👤 User Routes (protected)

| Method | Endpoint         | Description            |
|--------|------------------|------------------------|
| GET    | /users           | List users (paginated) |
| GET    | /users/{id}      | Show user              |
| PUT    | /users/{id}      | Update user            |
| DELETE | /users/{id}      | Delete user            |

### Usage Tutorial

#### 📍 Route: **List Users**
- **Method**: `GET`
- **URL**: `/api/users?per_page=10&sort_by=name&sort_order=asc`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Expected Result**:
  - JSON list of users with pagination

#### 📍 Route: **Show User by ID**
- **Method**: `GET`
- **URL**: `/api/users/{id}`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Expected Result**:
  - JSON with user data

#### 📍 Route: **Update User**
- **Method**: `PUT`
- **URL**: `/api/users/{id}`
- **Header**:
  - `Authorization: Bearer <JWT_TOKEN>`
  - `Content-Type: application/json`
- **Body**:
  ```json
  {
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "newpassword123"
  }
  ```
- **Expected Result**:
  ```json
  {
    "status": "success",
    "message": "User updated successfully"
  }
  ```

#### 📍 Route: **Delete User**
- **Method**: `DELETE`
- **URL**: `/api/users/{id}`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Expected Result**:
  ```json
  {
    "status": "success",
    "message": "User deleted successfully"
  }
  ```

---

## 📣 Contact

Feel free to open an issue or pull request if you'd like to contribute or report any issues.

---



