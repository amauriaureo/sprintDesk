<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Define que essa classe representa a tabela projects do banco.
// Laravel faz isso automaticamente por convenção.
class Project extends Model
{
    use HasFactory;

    // Define os campos que podem ser preenchidos via mass assignment.
    protected $fillable = [
        'name',
        'description',
    ];

    // Define a relação entre projetos e tickets.
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
