<?php
// php5.6 artisan route:list --path=v4/api/educacao/secretaria

Route::prefix('parametros')->group(function () {
//   Route::get('globais',  "NotificacaoController@getParametrosGlobais"); // ainda nao existe
    Route::get('notificacoes', "ParamentrosNotificacaoController@show");
    Route::post('notificacoes', "ParamentrosNotificacaoController@update");
});

Route::get('tipo-base/estrutura-curricular', "TiposBaseController@getEstruturasCurriculares");
Route::get('tipo-base/tipos-itinerario', "TiposBaseController@getTiposItinerarioFormativo");
Route::get(
    'tipo-base/composicoes-itinerario-inegrado', 
    "TiposBaseController@getComposicaoItinerarioFormativoIntegrado"
);
Route::get(
    'tipo-base/tipos-curso-formacao-tec-prof', 
    "TiposBaseController@getTiposCursoItinFormacaoTecnicaProfissional"
);
Route::get('tipo-base/buscarTodos', "TiposBaseController@getTiposBase");
Route::post('tipo-base/salvar', "TiposBaseController@salvar");
Route::post('tipo-base/excluir', "TiposBaseController@excluir");