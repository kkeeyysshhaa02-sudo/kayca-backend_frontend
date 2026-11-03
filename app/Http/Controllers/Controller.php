<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "To-Do List API - Duo Developer",
    description: "Dokumentasi API To-Do List Pribadi dengan Laravel Sanctum & L5-Swagger"
)]
#[OA\Server(
    url: "http://localhost:8000/api",
    description: "Local Development Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Sanctum Token",
    description: "Masukkan token yang didapat dari endpoint login atau register"
)]
abstract class Controller
{
    //
}
