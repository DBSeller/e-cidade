<?php
Route::group(["prefix" => "auth", "middleware" => ["api"]], function () {

    $path = "\App\Domain\Client\Controllers\\";
    $controller = $path . "ClientController";

    Route::post("usuario", $controller . "@autenticacaoUsuario");
});
