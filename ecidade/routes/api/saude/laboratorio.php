<?php

use App\Domain\Saude\Ambulatorial\Middlewares\HasCgsMiddleware;

Route::middleware(['clientCredential', HasCgsMiddleware::class])->group(function () {
    Route::prefix('exames')->group(function () {
        Route::get('requisicao/{idRequisicao}', 'RequisicaoExamesController@get');
        Route::get('resultado/{idRequisicaoExame}', 'ResultadoExamesController@get');
    });
});
