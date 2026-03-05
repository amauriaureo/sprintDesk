<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recupera todos os projetos existentes para associar tickets de exemplo.
        $projects = Project::all();

        if ($projects->isEmpty()) {
            return;
        }

        foreach ($projects as $project) {
            Ticket::create([
                'project_id' => $project->id,
                'title' => 'Configurar ambiente inicial',
                'description' => 'Preparar ambiente de desenvolvimento e homologação para o projeto.',
                'status' => 'open',
            ]);

            Ticket::create([
                'project_id' => $project->id,
                'title' => 'Reunião de alinhamento com o cliente',
                'description' => 'Definir escopo inicial e principais funcionalidades.',
                'status' => 'in_progress',
            ]);

            Ticket::create([
                'project_id' => $project->id,
                'title' => 'Primeira entrega de features',
                'description' => 'Entregar primeira versão com funcionalidades básicas.',
                'status' => 'done',
            ]);
        }
    }
}
