# API Blog – Teste Devs UEFS 

API RESTful construída com **Laravel 12**, **Vue 3 + Inertia.js**, **Vite**, **PostgreSQL** e **Docker**. Com autenticação, CRUD de usuários, posts e tags, e cobertura de testes de API (Feature Tests). 

# Stack 
## Back-end
PHP 8.3.20  
Laravel 12 (12.38.1)  
PostgreSQL  
Autenticação com Laravel Breeze + Sanctum  
Testes de API usando PHPUnit  
API versionada em `/api/v1`  

## Frontend 
Vue 3  
Inertia.js  
TailwindCSS  
Vite (build)  

## Infra 
Dockerfile customizado  
Docker compose (API + PostgreSQL)  
Compatível com Laravel Sail (modo dev) 


## Rodar o projeto 

# COm Docker
git clone https://github.com/aderaldoneto/test-devs-uefs  
cd test-devs-uefs  
cp .env.example .env  

docker compose -f compose.netra.yaml up -d --build  
docker compose -f compose.netra.yaml exec app php artisan migrate --seed  
docker compose -f compose.netra.yaml exec app php artisan test  

# Com Sail (eu costumo usar o Sail)
(PS: eu uso apenas `sail` porque criei um alias para não precisar digita `./vendor/bin/sail`)  
composer install  
npm install  
npm run build  
php artisan sail:install  
sail up -d  
sail artisan migrate  
sail artisan db:seed  
sail npm run dev -- --host  


## Acessar o projeto
Web: http://localhost  
API: http://localhost/api/v1  

## Seeders 
sail artisan db:seed --class=TagSeeder  
sail artisan db:seed --class=PostSeeder  

## Rota API 
/api/v1

## Rotas 

### Publicas 
GET /users  
GET /tags  
GET /tags/{id}  
GET /posts  
GET /posts/{id}  

### Logar 
POST /api/v1/login  
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
# Rodar todos os testes 
sail php artisan test  

# Rodar meus testes  
sail php artisan test tests/Feature/Api/V1/PostApiTest.php  
sail php artisan test tests/Feature/Api/V1/TagApiTest.php  
sail php artisan test tests/Feature/Api/V1/UserApiTest.php  
