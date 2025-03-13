<?php
Route::prefix('consulta')->group(function() {
    Route::get('demanda-reprimida/by-paciente/{cgs}', 'DemandaReprimidaController@getByPaciente');
    Route::post('medicamento/estoque', 'MedicamentoController@getEstoque');
});

Route::prefix('cadastro')->group(function() {
    Route::prefix('demanda-reprimida')->group(function() {
        Route::post('delete', 'DemandaReprimidaController@apagar');
        Route::post('save', 'DemandaReprimidaController@salvar');
    });
});

Route::prefix('relatorio')->group(function() {
    Route::post('demanda-reprimida', 'DemandaReprimidaController@relatorio');
});
