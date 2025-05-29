<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assunto extends Model
{
    use HasFactory;

    protected $primaryKey = 'CodAs';
    public $incrementing = true;

    protected $fillable = [
        'Descricao',
    ];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'livro_assunto', 'Assunto_CodAs', 'Livro_CodL');
    }
}