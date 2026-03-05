<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Executa os seeders de projetos e tickets.
        $this->call([
            ProjectSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
