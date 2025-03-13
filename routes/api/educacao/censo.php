<?php
// php5.6 artisan route:list --path=v4/api/educacao/censo
Route::prefix('tabelas-censo')->group(function () {
    Route::get('areas-pos-graduacao', "TabelasCensoController@getAreasPosGraduacao");
    Route::get('tipos-pos-graduacao', "TabelasCensoController@getTiposPosGraduacao");
});