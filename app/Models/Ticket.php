<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Define que essa classe representa a tabela tickets do banco.
// Laravel faz isso automaticamente por convenção.
class Ticket extends Model
{
    // Permite criar dados fake para testes com Factories.
    // Isso é útil para testes e desenvolvimento.
    use HasFactory;

    // Define os campos que podem ser preenchidos via mass assignment.
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
    ];

    // Define a relação entre tickets e projetos.
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
