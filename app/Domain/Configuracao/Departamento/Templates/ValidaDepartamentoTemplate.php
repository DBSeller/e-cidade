<?php

namespace App\Domain\Configuracao\Departamento\Templates;

/**
 * Classe usado template para validar departamento.
 *
 * @see app/Domain/Saude/Ambulatorial/Services/ValidaUnidadeService.php
 * @example - Sobrescreva o metodo callbackInvalido caso queira uma saída diferente!
 */
abstract class ValidaDepartamentoTemplate
{
    protected $mensagem = 'Departamento inválido';

    /**
     * Valida se o departamento é um departamento valido
     * @return boolean
     */
    final public function validar()
    {
        if (!$this->isValido()) {
            $this->callbackInvalido();
            return false;
        }

        return true;
    }

    /**
     * @return boolean
     */
    abstract protected function isValido();

    /**
     * Mostra um container para trocar de departamento na tela e sai do programa.
     */
    protected function callbackInvalido()
    {
        echo  "
            <div class='container'>
                <fieldset>
                    <legend>Aviso Importante:</legend>
                    <div style='padding: 10px 30px'>
                        <p>{$this->mensagem}</p>
                        <button class='btn btn-light' onclick='Desktop.Window.createSettingModal(CurrentWindow)'>
                            <i class='fa fa-cog'></i> Alterar Departamento
                        </button>
                    </div>
                </fieldset>
            </div>
        ";
        db_menu();
        die();
    }
}
