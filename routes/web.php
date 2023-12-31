<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'register'])->name('register');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');

Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos');
Route::post('/documentos/upload', [DocumentosController::class, 'upload'])->name('documentos.upload');
Route::post('/documentos/pesquisar', [DocumentosController::class, 'pesquisar'])->name('documentos.pesquisar');
Route::post('/documentos/compartilhar', [DocumentosController::class, 'compartilhar'])->name('documentos.compartilhar');
Route::get('/documentos/conteudo/{id?}', [DocumentosController::class, 'showConteudo'])->name('documentos.conteudo');
Route::post('/documentos/conteudo/update', [DocumentosController::class, 'updateConteudo'])->name('documentos.conteudo.update');

Route::get('/documentos/visualizar/{caminhoArquivo}', [DocumentosController::class, 'visualizar'])->name('documentos.visualizar');
Route::get('/documentos/excluir/{id}', [DocumentosController::class, 'excluir'])->name('documentos.excluir');
