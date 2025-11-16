<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Blog API – Teste Devs UEFS",
 *     version="1.0.0",
 *     description="API RESTful para gerenciamento de usuários, posts e tags.",
 * )
 *
 * @OA\Server(
 *     url="http://localhost",
 *     description="Ambiente local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class ApiInfo {}
