# API Blog – Teste Devs UEFS 

API RESTful construída com **Laravel 11**, **Vue** e **PostgreSQL**, com autenticação, CRUD de usuários, posts e tags, e cobertura de testes de API (Feature Tests). 

## Stack 
PHP 8.3+ 
Laravel 11 
PostgreSQL 
Docker + Laravel Sail 
PHPUnit (Feature / API tests) 

## Rodr o projeto 

git clone https://github.com/aderaldoneto/test-devs-uefs 
cd test-devs-uefs 
cp .env.example .env

sail up -d
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
http://localhost/api/v1/users

### Tags 
http://localhost/api/v1/tags

### Posts 
http://localhost/api/v1/posts


## Testes 
sail php artisan test tests/Feature/Api/V1/PostApiTest.php 
sail php artisan test tests/Feature/Api/V1/TagApiTest.php 
sail php artisan test tests/Feature/Api/V1/UserApiTest.php 
