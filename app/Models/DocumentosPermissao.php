<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentosPermissao extends Model
{
    protected $table = 'documentos_permissao';
    protected $primaryKey = 'id';

    protected $fillable = [
        'usuario_id',
        'documento_id',
        'pode_ver',
        'pode_editar',
        'pode_excluir',
    ];
}
