<?php
// php5.6 artisan route:list --path=v4/api/educacao/escola
Route::prefix('relatorios')->group(function () {
    $path = "\App\Domain\Educacao\Escola\Controllers\Relatorios\\";

    Route::post('diarioclasse/turmasEspeciais', $path  . "DiarioClasse@turmasEspeciais");
    Route::post('diarioclasse/turmasEscolarizacao', $path  . "DiarioClasse@turmasEscolarizacao");

    Route::post('vacinacao', $path . "Vacinacao@emitir");
    Route::post('emitirRelatorioTurmasAee/', "{$path}HorariosTurma@emitirHorariosTurmaAee");
});

$path = "\App\Domain\Educacao\Escola\Controllers\\";
Route::get('turma/{codigo}', "{$path}TurmasController@buscar")->name("turma");
Route::get('matriculas-por-turma/{turma}/{etapa}', "{$path}TurmasController@matriculasEtapa");
Route::get('vagas-por-turma/{turma}', "{$path}TurmasController@vagas");
Route::get('regencias-turmas/{turmaOrigem}/{turmaDestino}/{etapa}', "{$path}TurmasController@regenciasTurmas");
Route::post('procedimento/troca-de-turma', "{$path}TurmasController@trocarAlunosTurma");
Route::post('turmasEspeciais/', "{$path}TurmasController@buscarTurmasEspeciaisPorCalendarioEscola");
Route::get('calendario/{escola}', "{$path}CalendarioController@buscarCalendariosAtivosEscola");
Route::post('debug', function () {
    return response()->json(true);
})->name("debug");

Route::get('/vacinas', $path  . "VacinasController@index");
Route::get('/vacinas/{vacina}', $path  . "VacinasController@show");
Route::get('/profissional/{profissional}/vacinas', $path  . "ProfissionalController@vacinas");
Route::post('/profissional/{profissional}/salvar-vacinacao', $path  . "ProfissionalController@vacinar");
Route::post('/profissional/excluir-vacinacao/{profissionalVacinacao}', $path  . "ProfissionalController@deleteVacinacao");

Route::prefix('recursos-humanos')->group(function () {
    $path = "\App\Domain\Educacao\Escola\Controllers\\";
    Route::get('profissionais-com-superior/{escola}/{ativos}', $path . "ProfissionalController@getProfissionaisComSuperior");
    Route::post('salvar-formacao-superior-profissional', $path . "ProfissionalController@salvarFormacaoSuperiorPofissional");
    Route::post('buscar-formacoes-superior-profissional', $path . "ProfissionalController@getFormacoesSuperiorDoProfissional");
    Route::post('excluir-formacao-superior-profissional', $path . "ProfissionalController@excluirFormacaoSuperiorDoProfissional");
    Route::post('buscar-documento-pos-graducao', $path . "ProfissionalController@buscarDocumentoPosGraduacao");  
    Route::post('salvar-documento-pos-graducao', $path . "ProfissionalController@salvarDocumentoPosGraduacao");
    Route::post('excluir-documento-pos-graducao', $path . "ProfissionalController@excluirDocumentoPosGraduacao");   
}); 