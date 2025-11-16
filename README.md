# API Blog – Teste Devs UEFS 

API RESTful construída com **Laravel 12**, **Vue** e **PostgreSQL**, com autenticação, CRUD de usuários, posts e tags, e cobertura de testes de API (Feature Tests). 

## Stack 
PHP 8.3.20  
Laravel 12 (12.38.1) 
PostgreSQL 
Docker + Laravel Sail 
PHPUnit (Feature / API tests) 

## Rodr o projeto 

git clone https://github.com/aderaldoneto/test-devs-uefs 
cd test-devs-uefs 
cp .env.example .env

(PS: eu uso sail porque criei um alias para não precisar digita './vendor/bin/sail') 
php artisan sail:install 
sail up -d 
sail composer install 
sail npm install 
sail npm run build 
sail artisan key:generate 
sail artisan migrate 
sail artisan db:seed 

sail npm run dev -- --host

## Seeders
sail artisan db:seed --class=TagSeeder 
sail artisan db:seed --class=PostSeeder 

## Rota API 
/Api/V1

## Rotas 

### Publicas 
users.index users.show
posts.index posts.show
tags.index tags.show

### Logar 
http://localhost/api/v1/login 
{
  "email": "teste@gmail.com",
  "password": "password"
}

Header:
Authorization: Bearer 1|xxxxxxxxxxxxxxxxx
Content-Type: application/json

### Usuários 
GET /api/v1/users – Lista usuários 
POST /api/v1/users – Criar usuário 
GET /api/v1/users/{id} – Mostrar usuário 
PUT /api/v1/users/{id} – Atualizar usuário 
DELETE /api/v1/users/{id} – Deletar usuário (softdelete) 

### Tags 
GET /api/v1/tags – Listar
POST /api/v1/tags – Criar
GET /api/v1/tags/{id} – Exibir 
PUT /api/v1/tags/{id} – Atualizar 
DELETE /api/v1/tags/{id} – Deletar 

### Posts 
GET /api/v1/posts – Listar 
POST /api/v1/posts – Criar 
GET /api/v1/posts/{id} – Exibir 
PUT /api/v1/posts/{id} – Atualizar 
DELETE /api/v1/posts/{id} – Deletar 

## Testes 
sail php artisan test tests/Feature/Api/V1/PostApiTest.php 
sail php artisan test tests/Feature/Api/V1/TagApiTest.php 
sail php artisan test tests/Feature/Api/V1/UserApiTest.php 
