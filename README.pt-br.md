# 📘 Laravel JWT API

[![en](https://img.shields.io/badge/lang-en-blue.svg)](https://github.com/eriktrs/api_conectala/blob/main/README.md)

> 🇧🇷 Versão em Português

Como parte do desafio proposto pela empresa **Conecta Lá**, a tarefa foi desenvolver uma API RESTful em PHP, com os seguintes requisitos:

- A API deve criar, atualizar, deletar e listar todos os usuários.
- As informações devem ser armazenadas em um banco de dados MySQL.
- O endpoint deve retornar dados em formato JSON e permitir operações GET, POST, PUT e DELETE para manipular registros de usuários.
- A API deve contemplar aspectos como segurança, validação de entradas e tratamento de erros.

---

## 🚀 Funcionalidades

- Laravel 11 + Sail + PHP 8
- Autenticação via JWT
- Rota de login com limite de tentativas
- Endpoints protegidos `/me`, `/logout`, `/refresh`
- CRUD completo de usuários (protegido)
- Paginação, filtragem e ordenação customizadas

---

## 📦 Requisitos

### Requisitos de Execução

- PHP 8
- Laravel 11 ou superior
- Docker Desktop
- Ubuntu 24.04
- WSL
- Windows 10 ou superior
- Laravel Sail (`./vendor/bin/sail`)
- Postman

### Requisitos da API

Todas as requisições devem:
- Utilizar `Content-Type: application/json` quando aplicável
- Usar um JWT válido no cabeçalho `Authorization: Bearer <token>` (para rotas protegidas)

---

## 🛠️ Instalação

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

## 🔑 Rotas de Autenticação

| Método | Endpoint     | Descrição                   |
|--------|--------------|-----------------------------|
| POST   | /register    | Registrar novo usuário      |
| POST   | /login       | Autenticar usuário (JWT)    |
| POST   | /refresh     | Renovar token JWT           |
| POST   | /logout      | Logout (invalidar JWT)      |
| GET    | /me          | Obter dados do usuário logado|

### Tutorial de Uso (Estilo Postman)

#### 📍 Rota: **Registrar usuário**
- **Método**: `POST`
- **URL**: `/api/register`
- **Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Resultado Esperado**:
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

#### 📍 Rota: **Login**
- **Método**: `POST`
- **URL**: `/api/login`
- **Body**:
  ```json
  {
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Resultado Esperado**:
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

#### 📍 Rota: **Me**
- **Método**: `GET`
- **URL**: `/api/me`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Resultado Esperado**:
  ```json
  {
    "status": "success",
    "user": {
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
  ```

#### 📍 Rota: **Logout**
- **Método**: `POST`
- **URL**: `/api/logout`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Resultado Esperado**:
  ```json
  {
    "status": "success",
    "message": "Successfully logged out"
  }
  ```

#### 📍 Rota: **Refresh**
- **Método**: `POST`
- **URL**: `/api/refresh`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Resultado Esperado**:
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

## 👤 Rotas de Usuário (protegidas)

| Método | Endpoint         | Descrição                |
|--------|------------------|--------------------------|
| GET    | /users           | Listar usuários          |
| GET    | /users/{id}      | Mostrar usuário          |
| PUT    | /users/{id}      | Atualizar usuário        |
| DELETE | /users/{id}      | Deletar usuário          |

### Tutorial de Uso

#### 📍 Rota: **Listar Usuários**
- **Método**: `GET`
- **URL**: `/api/users?per_page=10&sort_by=name&sort_order=asc`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Resultado Esperado**:
  - Lista JSON com usuários e paginação

#### 📍 Rota: **Mostrar Usuário por ID**
- **Método**: `GET`
- **URL**: `/api/users/{id}`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Resultado Esperado**:
  - JSON com dados do usuário

#### 📍 Rota: **Atualizar Usuário**
- **Método**: `PUT`
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
- **Resultado Esperado**:
  ```json
  {
    "status": "success",
    "message": "User updated successfully"
  }
  ```

#### 📍 Rota: **Deletar Usuário**
- **Método**: `DELETE`
- **URL**: `/api/users/{id}`
- **Header**: `Authorization: Bearer <JWT_TOKEN>`
- **Resultado Esperado**:
  ```json
  {
    "status": "success",
    "message": "User deleted successfully"
  }
  ```

---

## 📣 Contato

Sinta-se à vontade para abrir uma issue ou pull request se quiser contribuir ou relatar algum problema.

---
