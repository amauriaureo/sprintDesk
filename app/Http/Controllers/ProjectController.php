<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        // Lê o parâmetro de busca "q" da query string (ex.: ?q=site).
        $search = request('q');

        // Cria a query base para a tabela de projetos.
        $query = Project::query();

        // Quando "q" é informado, filtra os projetos pelo nome usando LIKE.
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Aplica paginação simples (10 registros por página, controlado por ?page=1,2,...).
        $projects = $query->paginate(10);

        // Retorna o resultado paginado em JSON.
        return response()->json($projects);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        // Obtém apenas os campos validados pelo Form Request.
        $data = $request->validated();

        // Cria o projeto com os dados validados.
        $project = Project::create($data);

        return response()->json($project, 201);
    }

    public function show(int $id): JsonResponse
    {
        // Busca o projeto pelo ID ou lança 404 quando não encontrado.
        $project = Project::findOrFail($id);

        return response()->json($project);
    }

    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        // Busca o projeto ou lança 404 quando não encontrado.
        $project = Project::findOrFail($id);

        // Obtém apenas os campos validados pelo Form Request.
        $data = $request->validated();

        // Atualiza o projeto com os dados validados.
        $project->update($data);

        return response()->json($project);
    }

    public function destroy(int $id): JsonResponse
    {
        // Busca o projeto ou lança 404 quando não encontrado.
        $project = Project::findOrFail($id);

        // Verifica se o projeto possui tickets relacionados.
        $hasTickets = $project->tickets()->exists();

        if ($hasTickets) {
            return response()->json([
                'message' => 'Não é possível excluir um projeto que possui tickets associados.',
            ], 409);
        }

        $project->delete();

        return response()->json(null, 204);
    }
}
