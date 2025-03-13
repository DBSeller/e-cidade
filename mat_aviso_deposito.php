<?php

use ECidade\Patrimonial\Material\Repositories\DepositoRepository;

$isDeposito = false;
try {
    $deposito = (new DepositoRepository)->scopeDepartamento(db_getsession("DB_coddepto"))->first();
    if (!is_null($deposito)) {
        $isDeposito = true;
    }
} catch (Exception $exception) {
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $exception->getMessage());
}
?>
<div class="container" <?= !$isDeposito ?: 'style="display: none"' ?>>
    <fieldset>
        <legend>Aviso Importante:</legend>
        <div style="padding: 10px 30px">
            <p>O Departamento logado não é um depósito. </p>

            <button class="btn btn-light" onclick="Desktop.Window.createSettingModal(CurrentWindow)">
                <i class="fa fa-cog"></i> Alterar Departamento
            </button>
        </div>
    </fieldset>
</div>
