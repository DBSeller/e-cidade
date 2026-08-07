<?php
Route::middleware(['api', 'auth:api'])->group(function () {
    Route::prefix('instituicao')->namespace('Instituicao\Controller\\')->group(function () {
        Route::post('departamento-principal', 'InstituicaoController@configurarDepartamentoPrincipal');
    });

    Route::prefix('organograma')->namespace('Configuracao\Controllers\\')->group(function () {
        Route::get('{instit}', 'OrganogramaController@get');
        Route::get('{instit}/{departamento}', 'OrganogramaController@get');
        Route::post('filtrar', 'OrganogramaController@getByDepartamento');
        Route::post('salvar', 'OrganogramaController@salvar');
    });

    Route::prefix("bancos")
        ->namespace('Banco\Controller\\')
        ->group(function () {
            Route::get('', 'BancoController@index');
        });

    Route::prefix("banco-pix")->namespace("Banco")->group(function () {
        $path           = "\App\Domain\Configuracao\Banco\Controller\\";
        $pathController = $path . "BancoPixController";

        Route::get("/listar", $pathController . "@listar");
        // Route::post("/salvar", $pathController . "@salvar");
        Route::post("/validar/{db90_codban?}", $pathController . "@validationData");
        Route::post("/atualizar/{db90_codban}", $pathController . "@atualizar");
        Route::get("/{db90_codban}", $pathController . "@pegarBancoPix");

        Route::delete("/excluir/{db90_codban}", $pathController . "@deletar");
    });

    Route::prefix('menu')->namespace('Menu\Controllers\\')->group(function () {
        Route::post('permissoes/saude/duplicar', 'PermissoesController@duplicarSaude');
    });
});

Route::prefix('')->namespace('Instituicao')->group(function () {
    $routePrefix = 'instituicao';
    $path = "\App\Domain\Configuracao\Instituicao\Controller\\";
    $controller = $path . "InstituicaoController";

    Route::get($routePrefix, $controller . "@index");
    Route::get($routePrefix . '/{id}', $controller . "@show");
});

Route::prefix('usuario')->namespace('Usuario')->group(function () {

    $routePrefix = 'assinantes';
    $path = "\App\Domain\Configuracao\Usuario\Controller\\";
    $controller = $path . "AssinanteController";

    Route::get($routePrefix, $controller . "@index");
    Route::get('{idUsuario}/' . $routePrefix, $controller . "@getByIdUsuario");
});

Route::prefix('documentos-assinar')->namespace('DocumentosAssinatura')->group(function () {

    $path = "\App\Domain\Configuracao\DocumentosAssinatura\Controller\\";
    $controller = $path . "DocumentosAssinatura";

    Route::get('documento/{file_id}/assinado-por', $controller . "@getSignersSignedFromFile");
    Route::get('documento/{file_id}/assinantes', $controller . "@getSignersFromFile");
    Route::get('assinante', $controller . "@toSign");
    Route::get('{file_id}', $controller . "@getFile");
    Route::post('novo-documento', $controller . "@newSignFile");
    Route::post('atualizar-assinantes', $controller . "@updateSigners");
    Route::post('atualizar-assinado-por', $controller . "@updateSignedSigners");
});

Route::prefix('documento-assinado')->namespace('DocumentosAssinatura')->group(function () {

    $path = "\App\Domain\Configuracao\DocumentosAssinatura\Controller\\";
    $controller = $path . "DocumentosAssinatura";

    Route::post('', $controller . "@newSignFile");
});

Route::prefix('assinantes')->namespace('Assinantes')->group(function () {

    $path = "\App\Domain\Configuracao\Usuario\Controller\\";
    $controller = $path . "AssinanteController";

    // Route::post('',             $controller . "@newSignerPermission");
    // Route::put('',              $controller . "@updateSignerPermission");
    Route::post('', $controller . "@saveSignerPermission");
    Route::get('', $controller . "@getAllSignersPermission");
    Route::delete('{file_id}', $controller . "@deleteSignerPermission");
});

Route::prefix('entrega-continua')->middleware(['api'])->group(function () {
    $path = "\App\Domain\Configuracao\EntregaContinua\Controllers\\";
    Route::post('/migrate', $path . "MigrateController@migrate");
});

