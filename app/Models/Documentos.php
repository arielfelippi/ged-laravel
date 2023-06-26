<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentos extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'usuario_id',
        'nome',
        'caminho_arquivo',
        'conteudo',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function permissao()
    {
        return $this->hasOne(DocumentosPermissao::class, 'documento_id');
    }

    public static function getDocumentos($documentoId = 0)
    {
        $userId = auth()->user()->id;

        $sql = (
            "SELECT doc.id AS id,
                doc.nome,
                doc.extensao,
                IFNULL(doc.caminho_arquivo, '') AS caminho_arquivo,
                IFNULL(doc.conteudo, '') AS conteudo,
                users.id AS usuario_id,
                users.name AS nome_usuario,
                CASE
                    WHEN doc.usuario_id = {$userId} THEN 'todas'
                    ELSE CONCAT(IFNULL(IF(dp.pode_ver, 'visualizar | ', ''), ''), '',
                                IFNULL(IF(dp.pode_editar, 'editar | ', ''), ''), '',
                                IFNULL(IF(dp.pode_excluir, 'excluir', ''), ''))
                END AS permissoes,
                doc.created_at AS criacao,
                doc.updated_at AS atualizacao
            FROM documentos doc
            INNER JOIN users ON doc.usuario_id = users.id
            LEFT JOIN documentos_permissao dp ON doc.id = dp.documento_id
            WHERE doc.usuario_id = {$userId}
            OR doc.id IN (
                SELECT documento_id
                FROM documentos_permissao
                WHERE usuario_id = {$userId}
            )"
        );

        if (!empty($documentoId)) {
            $sql = ("SELECT * FROM ({$sql}) AS doc_tmp WHERE id = {$documentoId}");
        }

        $documentos = DB::select($sql);

        return $documentos;
    }
}
