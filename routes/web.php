<?php

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/mostrarArquivos', 'UploadController@mostrarArquivos');

Route::get('abrir/{nome}', ['as' => 'abrirArquivo','uses' => 'ArquivoController@abrirArquivo']);

Route::get('excluir/{nome}', ['uses' => 'ArquivoController@excluirArquivo']);

Route::post('upload', 'UploadController@upload');

Auth::routes();

Route::get('/home', 'VisitaController@carregarTodasVisitasAtivasHome');

// Route::get('/home', 'HomeController@index')->name('home');

// Visita

Route::get('/cadastroVisita/{nome}/{visitanteID}', ['as' => 'cadastroVisita', 'uses' => 'VisitaController@carregarInformacoes']);

Route::get('/apagarVisita/{visitaID}', ['as' => 'apagarVisita', 'uses' => 'VisitaController@apagarVisita']);

Route::get('/registrarSaida/{visitaID}', ['as' => 'registrarSaida', 'uses' => 'VisitaController@registrarSaida']);

Route::get('/verVisita/{visitaID}', ['as' => 'verVisita', 'uses' => 'VisitaController@verVisita']);

Route::get('/pesquisarVisitas', 'VisitaController@carregarTodasVisitas');

Route::get('/procurarVisitante/dataInicial/{dataInicial}/dataFinal/{dataFinal}', ['as'=> 'mostrarVisitas', 'uses'=>'VisitaController@mostrarVisitas']);

Route::get('/carregarTodasVisitasAtivasHome', 'VisitaController@carregarTodasVisitasAtivasHome');

Route::get('/carregarTodasVisitasAtivas', 'VisitaController@carregarTodasVisitasAtivas');

Route::post('/cadastrarVisita', 'VisitaController@cadastrarVisita');

Route::post('/editarVisita', 'VisitaController@editarVisita');

Route::post('/procurarVisita', 'VisitaController@procurarVisita');

// Visitante

Route::get('/cadastroVisitante', function () {
    return view('visitante/cadastroVisitante');
});

Route::get('/verPerfilVisitante/{visitanteID}', ['as' => 'verPerfilVisitante', 'uses' => 'VisitanteController@carregarInformacoesVisitante']);

Route::get('/pesquisarVisitantes', 'VisitanteController@carregarTodosVisitantes');

Route::get('/apagarVisitante/{visitanteID}', ['as' => 'apagarVisitante', 'uses' => 'VisitanteController@apagarVisitante']);

Route::get('/procurarVisitante/nomeOuDocumento/{nomeOuDocumento}', ['as'=> 'mostrarVisitantes', 'uses'=>'VisitanteController@mostrarVisitantes']);

Route::get('/procurarVisitanteHome/nomeOuDocumento/{nomeOuDocumento}', ['as'=> 'mostrarVisitantesHome', 'uses'=>'VisitanteController@mostrarVisitantesHome']);

Route::post('/cadastrarVisitante', 'VisitanteController@cadastrarVisitante');

Route::post('/procurarVisitante', 'VisitanteController@procurarVisitante');

Route::post('/procurarVisitanteHome', 'VisitanteController@procurarVisitanteHome');

Route::post('/editarVisitante', 'VisitanteController@editarVisitante');

//Usuário

Route::get('/pesquisarUsuarios', 'UsuarioController@carregarTodosUsuarios');

Route::get('/procurarUsuario/nomeOuDocumento/{nomeOuDocumento}', ['as'=> 'mostrarUsuarios', 'uses'=>'UsuarioController@mostrarUsuarios']);

Route::get('/verPerfilUsuario/{id}', ['as' => 'verPerfilUsuario', 'uses' => 'UsuarioController@carregarInformacoesUsuario']);

Route::get('/apagarUsuario/{id}', ['as' => 'apagarUsuario', 'uses' => 'UsuarioController@apagarUsuario']);

Route::post('/procurarUsuario', 'UsuarioController@procurarUsuario');

Route::post('/cadastrarUsuario', 'UsuarioController@cadastrarUsuario');

Route::post('/editarUsuario', 'UsuarioController@editarUsuario');

// Relatórios

Route::get('/relatorioVisitantesSEMFA', function () {
	return view('relatorios/relatorioVisitantesSEMFA');
});

Route::get('/relatorioVisitantesSEMFA/dataInicial/{dataInicial}/dataFinal/{dataFinal}', ['as'=> 'mostrarVisitasSEMFA', 'uses'=>'VisitaController@mostrarVisitasSEMFA']);

Route::get('/relatorioVisitantesPorSetor', function () {
	return view('relatorios/relatorioVisitantesPorSetor');
});

Route::get('/relatorioPDF/{dataInicial}/{dataFinal}', ['as' => '/relatorioPDF', 'uses' => 'VisitaController@gerarRelatorio']);

Route::post('/carregarRelatorioVisitantesSEMFA', 'VisitaController@relatorioVisitantesSEMFA');

Route::post('/carregarRelatorioVisitantesSetor', 'VisitaController@relatorioVisitantesPorSetor');

// Local

Route::get('/cadastroLocal', function () {
	return view('local/cadastroLocal');
});

Route::get('/pesquisarLocal', 'LocalController@carregarTodosLocais');

Route::get('/verLocal/{localID}', ['as' => 'verLocal', 'uses' => 'LocalController@carregarInformacoesLocal']);

Route::get('/apagarLocal/{localID}', ['as' => 'apagarLocal', 'uses' => 'LocalController@apagarLocal']);

Route::get('/pesquisarLocalNome/nome/{nomeLocal}', ['as'=> 'mostrarLocais', 'uses'=>'LocalController@mostrarLocais']);

Route::post('/cadastrarLocal', 'LocalController@cadastrarLocal');

Route::post('/pesquisarLocalNome', 'LocalController@pesquisarLocalNome');

Route::post('/editarLocal', 'LocalController@editarLocal');
