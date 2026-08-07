<?php

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use \App\Domain\Saude\Ambulatorial\Models\Cid;

Route::middleware(["auth:api"])->group(function () {
    Route::prefix('')->group(function () {
        Route::get('parametros', 'ParametrosController@get');
    });

    Route::prefix('cgs')->group(function () {
        Route::get('{cgs}', 'CgsController@get');
        Route::get('familiamicroarea/{cgs}', 'CgsController@getFamiliamicroarea');
    });

    Route::prefix('consulta')->group(function () {
        Route::get('problemas', 'ProblemasController@getAll');
        Route::get('problemaspaciente/by-paciente/{id}', 'ProblemasPacienteController@getByPaciente');
        Route::get('microarea', 'FamiliaMicroareaController@getMicroareas');
    });

    Route::prefix('procedimento')->group(function () {
        Route::prefix('acompanhamento-acs')->group(function () {
            Route::get('{id}', 'AcompanhamentoAcsController@get');
            Route::get('paciente/{id}', 'AcompanhamentoAcsController@getByPaciente');
            Route::post('save', 'AcompanhamentoAcsController@save');
            Route::post('delete', 'AcompanhamentoAcsController@delete');
        });

        Route::prefix('problemaspaciente')->group(function () {
            Route::post('salvar', 'ProblemasPacienteController@salvar');
            Route::post('apagar', 'ProblemasPacienteController@apagar');
        });
    });

    Route::prefix('relatorio')->group(function () {
        Route::post('acompanhamento-acs', 'AcompanhamentoAcsController@relatorio');
    });
});

Route::middleware(['clientCredential'])->group(function () {
     Route::get('/cid', function (Request $request) {
         $cid = Cid::select(
             DB::raw("
                 sd70_i_codigo as codigo ,
                 CONCAT(sd70_c_cid,' - ',sd70_c_nome)  as descricao
            ")
         )->whereRaw(
             "CONCAT(sd70_c_cid,' - ',sd70_c_nome) ilike '%{$request->get("descricao")}%'"
         )->limit(50)->get();
         return new DBJsonResponse($cid);
     });

     Route::post('cgs/find-or-create', 'CgsController@findOrCreate');
});
