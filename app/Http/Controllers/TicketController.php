<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    /**
     * Lista todos os tickets de um projeto específico.
     *
     * Este método será chamado pelo endpoint:
     * GET /api/projects/{projectId}/tickets
     *
     * @param  int  $projectId  ID do projeto ao qual os tickets pertencem.
     */
    public function index(int $projectId): JsonResponse
    {
        // Primeiro verificamos se o projeto existe.
        // Se não existir, o Laravel retornará 404 automaticamente.
        $project = Project::findOrFail($projectId);

        // Recupera os tickets relacionados a este projeto com paginação simples.
        // paginate(10) retorna 10 tickets por página, controlado por ?page=1,2,...
        $tickets = $project->tickets()->paginate(10);

        return response()->json($tickets);
    }

    /**
     * Cria um novo ticket associado a um projeto.
     *
     * Este método será chamado pelo endpoint:
     * POST /api/projects/{projectId}/tickets
     *
     * Regras importantes:
     * - O projeto deve existir (project_id válido).
     * - O status deve estar entre: "open", "in_progress", "done".
     */
    public function store(StoreTicketRequest $request, int $projectId): JsonResponse
    {
        // Verifica se o projeto existe; se não existir, retorna 404.
        $project = Project::findOrFail($projectId);

        // Dados já validados pelo Form Request (regras serão definidas depois).
        $data = $request->validated();

        // Garantimos que o ticket será sempre associado ao projeto da URL,
        // ignorando qualquer "project_id" que possa vir no corpo da requisição.
        $data['project_id'] = $project->id;

        // Validação simples de regra de negócio para o status.
        // Mesmo tendo validação no Form Request, deixamos claro aqui a regra de domínio.
        if (isset($data['status']) && !in_array($data['status'], ['open', 'in_progress', 'done'], true)) {
            return response()->json([
                'message' => 'Status inválido. Use: open, in_progress ou done.',
            ], 422);
        }

        // Cria o ticket no banco de dados.
        $ticket = Ticket::create($data);

        // Retorna o ticket criado com status 201 (Created).
        return response()->json($ticket, 201);
    }

    /**
     * Exibe um ticket específico pelo ID.
     *
     * Este método será chamado pelo endpoint:
     * GET /api/tickets/{id}
     */
    public function show(int $id): JsonResponse
    {
        // Busca o ticket pelo ID ou retorna 404 se não existir.
        $ticket = Ticket::findOrFail($id);

        return response()->json($ticket);
    }

    /**
     * Atualiza um ticket existente.
     *
     * Este método será chamado pelo endpoint:
     * PATCH /api/tickets/{id}
     *
     * Permitimos atualizar:
     * - title
     * - description
     * - status
     */
    public function update(UpdateTicketRequest $request, int $id): JsonResponse
    {
        // Busca o ticket ou retorna 404 se não existir.
        $ticket = Ticket::findOrFail($id);

        // Dados validados vindos do Form Request.
        $data = $request->validated();

        // Se o status estiver sendo atualizado, garantimos que ele é válido
        // de acordo com as regras de negócio.
        if (isset($data['status']) && !in_array($data['status'], ['open', 'in_progress', 'done'], true)) {
            return response()->json([
                'message' => 'Status inválido. Use: open, in_progress ou done.',
            ], 422);
        }

        // Atualizamos apenas os campos enviados na requisição.
        $ticket->fill($data);
        $ticket->save();

        return response()->json($ticket);
    }

    /**
     * Remove um ticket do banco de dados.
     *
     * Este método será chamado pelo endpoint:
     * DELETE /api/tickets/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        // Busca o ticket pelo ID ou retorna 404 se não existir.
        $ticket = Ticket::findOrFail($id);

        // Deleta o ticket.
        $ticket->delete();

        // Retorna status 204 (No Content) indicando sucesso sem corpo na resposta.
        return response()->json(null, 204);
    }
}
