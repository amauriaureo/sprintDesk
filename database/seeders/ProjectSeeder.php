<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria alguns projetos de exemplo para facilitar os testes da API.
        Project::create([
            'name' => 'Portal Corporativo',
            'description' => 'Portal interno para colaboradores acessarem informações e serviços da empresa.',
        ]);

        Project::create([
            'name' => 'Aplicativo Mobile de Suporte',
            'description' => 'Aplicativo para abertura e acompanhamento de chamados de suporte.',
        ]);

        Project::create([
            'name' => 'Site Institucional',
            'description' => 'Site público com informações sobre a empresa e contato.',
        ]);
    }
}
