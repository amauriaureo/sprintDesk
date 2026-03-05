<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function index(int $projectId): JsonResponse
    {
        // Garante que o projeto existe; caso contrário, lança 404.
        $project = Project::findOrFail($projectId);

        // Busca os tickets do projeto com paginação simples (10 por página).
        $tickets = $project->tickets()->paginate(10);

        return response()->json($tickets);
    }

    public function store(StoreTicketRequest $request, int $projectId): JsonResponse
    {
        // Garante que o projeto existe; caso contrário, lança 404.
        $project = Project::findOrFail($projectId);

        // Obtém os campos já validados pelo Form Request.
        $data = $request->validated();

        // Força a associação do ticket ao projeto vindo da URL, ignorando qualquer project_id no payload.
        $data['project_id'] = $project->id;

        if (isset($data['status']) && !in_array($data['status'], ['open', 'in_progress', 'done'], true)) {
            return response()->json([
                'message' => 'Status inválido. Use: open, in_progress ou done.',
            ], 422);
        }

        $ticket = Ticket::create($data);

        return response()->json($ticket, 201);
    }

    public function show(int $id): JsonResponse
    {
        // Busca o ticket pelo ID ou lança 404 quando não encontrado.
        $ticket = Ticket::findOrFail($id);

        return response()->json($ticket);
    }

    public function update(UpdateTicketRequest $request, int $id): JsonResponse
    {
        // Busca o ticket ou lança 404 quando não encontrado.
        $ticket = Ticket::findOrFail($id);

        // Obtém os campos já validados pelo Form Request.
        $data = $request->validated();

        if (isset($data['status']) && !in_array($data['status'], ['open', 'in_progress', 'done'], true)) {
            return response()->json([
                'message' => 'Status inválido. Use: open, in_progress ou done.',
            ], 422);
        }

        // Preenche somente os campos enviados e persiste no banco.
        $ticket->fill($data);
        $ticket->save();

        return response()->json($ticket);
    }

    public function destroy(int $id): JsonResponse
    {
        // Busca o ticket pelo ID ou lança 404 quando não encontrado.
        $ticket = Ticket::findOrFail($id);

        $ticket->delete();

        return response()->json(null, 204);
    }
}
