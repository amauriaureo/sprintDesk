<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Lista os projetos com paginação simples.
     *
     * Este método será chamado pelo endpoint:
     * GET /api/projects
     */
    public function index(): JsonResponse
    {
        // Aqui buscamos todos os projetos usando a paginação padrão do Laravel.
        // paginate(10)
        // A página é controlada pelo parâmetro ?page=1, ?page=2, etc.
        $projects = Project::paginate(10);

        // Retornamos o resultado em JSON.
        // O objeto de paginação já vem com informações como:
        // current_page, per_page, total, data, etc.
        return response()->json($projects);
    }

    /**
     * Cria um novo projeto.
     *
     * Este método será chamado pelo endpoint:
     * POST /api/projects
     *
     * Usamos StoreProjectRequest para centralizar as regras de validação
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        // $request->validated() retorna apenas os dados
        // que passaram na validação definida no Form Request.
        $data = $request->validated();

        // Cria um novo registro de projeto no banco de dados.
        $project = Project::create($data);

        return response()->json($project, 201);
    }

    /**
     * Exibe um projeto específico pelo ID.
     *
     * Este método será chamado pelo endpoint:
     * GET /api/projects/{id}
     */
    public function show(int $id): JsonResponse
    {
        // findOrFail tenta buscar o projeto pelo ID.
        // Se não encontrar, o Laravel lança uma exceção que
        // automaticamente retorna resposta 404 (Not Found) em JSON.
        $project = Project::findOrFail($id);

        return response()->json($project);
    }

    /**
     * Atualiza um projeto existente.
     *
     * Este método será chamado pelo endpoint:
     * PUT /api/projects/{id}
     */
    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        // Busca o projeto ou retorna 404 se não existir.
        $project = Project::findOrFail($id);

        // Recupera apenas os dados validados pelo Form Request.
        $data = $request->validated();

        // Atualiza o projeto com os novos dados.
        $project->update($data);

        // Retorna o projeto atualizado.
        return response()->json($project);
    }

    /**
     * Remove um projeto do banco de dados.
     *
     * Este método será chamado pelo endpoint:
     * DELETE /api/projects/{id}
     *
     * Regra de negócio importante:
     * - NÃO permitir deletar um projeto que possua tickets associados.
     */
    public function destroy(int $id): JsonResponse
    {
        // Busca o projeto pelo ID ou retorna 404 se não existir.
        $project = Project::findOrFail($id);

        // Verifica se o projeto possui tickets relacionados.
        // A relação "tickets" foi definida no model Project.
        $hasTickets = $project->tickets()->exists();

        if ($hasTickets) {
            // Se existirem tickets, NÃO permitimos a exclusão.
            // Retornamos status HTTP 409 (Conflict) com uma mensagem explicando.
            return response()->json([
                'message' => 'Não é possível excluir um projeto que possui tickets associados.',
            ], 409);
        }

        // Se não houver tickets, podemos deletar o projeto normalmente.
        $project->delete();

        // Retornamos status 204 (No Content), que indica sucesso sem corpo na resposta.
        return response()->json(null, 204);
    }
}
