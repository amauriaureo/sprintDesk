<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Este arquivo define as rotas da API.
| Todas as rotas aqui serão automaticamente prefixadas com /api
| (por exemplo: /api/projects, /api/tickets, etc.).
|
*/

// Rotas para gestão de projetos (CRUD completo).
Route::get('projects', [ProjectController::class, 'index']);      // GET /api/projects
Route::post('projects', [ProjectController::class, 'store']);     // POST /api/projects
Route::get('projects/{id}', [ProjectController::class, 'show']);  // GET /api/projects/{id}
Route::put('projects/{id}', [ProjectController::class, 'update']); // PUT /api/projects/{id}
Route::delete('projects/{id}', [ProjectController::class, 'destroy']); // DELETE /api/projects/{id}

// Rotas para listar e criar tickets de um projeto específico.
// Usamos {projectId} para bater exatamente com o nome do parâmetro do controller.
Route::get('projects/{projectId}/tickets', [TicketController::class, 'index']);  // GET /api/projects/{projectId}/tickets
Route::post('projects/{projectId}/tickets', [TicketController::class, 'store']); // POST /api/projects/{projectId}/tickets

// Rotas para operações em um ticket específico (por ID do ticket).
Route::get('tickets/{id}', [TicketController::class, 'show']);     // GET /api/tickets/{id}
Route::patch('tickets/{id}', [TicketController::class, 'update']); // PATCH /api/tickets/{id}
Route::delete('tickets/{id}', [TicketController::class, 'destroy']); // DELETE /api/tickets/{id}

