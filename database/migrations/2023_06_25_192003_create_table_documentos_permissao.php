<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDocumentosPermissao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_permissao', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('documento_id');
            $table->boolean("pode_ver")->default(false);
            $table->boolean("pode_editar")->default(false);
            $table->boolean("pode_excluir")->default(false);
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('documento_id')->references('id')->on('documentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos_permissao');
    }
}
