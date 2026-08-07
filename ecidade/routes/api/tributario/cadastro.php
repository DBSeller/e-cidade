<?php

    Route::middleware(["api", "clientCredential"])->group(function () {
        $path = "\App\Domain\Tributario\Cadastro\Controllers\\";

        Route::get("dados-matricula", "{$path}CadastroController@dadosImovel");
        Route::get("setor-registro-imoveis", "{$path}CadastroController@getSetorRegImoveis");
        Route::get("localidade-rural", "{$path}CadastroController@getLocalidadeRural");
        Route::get("bairros", "{$path}CadastroController@getBairros");
        Route::get("logradouros", "{$path}CadastroController@getLogradouros");
    });

    Route::middleware(["api", "clientCredential"])->group(function () {
        $path = "\App\Domain\Tributario\Cadastro\Controllers\\";

        Route::get("dados-isencao/{id}", "{$path}CadastroIsencaoIptuController@getDadosIsencao");
    });
        
    Route::middleware(["auth:api"])->group(function () { 
        
       Route::prefix('configuracao')->group(function () {
         
        $path = "\App\Domain\Tributario\Cadastro\Controllers\\";
         Route::get("listar", "{$path}ParametrosController@listar");
         Route::post("salvar", "{$path}ParametrosController@salvar");            
       });  
    }); 
    