<?php

namespace App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\SessoesJson;

class DadosContadorService
{
    /**
     * @var object
     */
    private $oDados;

    /**
     * @param object $oDados
     */
    public function setDados($oDados)
    {
        $this->oDados = $oDados;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function build()
    {
        $oSessao = new \stdClass();
        $oSessao->nome = "outros_dados";
        $oSessao->tipo = "form";
        $oSessao->label = "Dados Contador";
        $oSessao->campos = [];
        $oSessao->campos[] = $this->buildCampoEscritorioContabil();

        return $oSessao;
    }

    private function buildCampoEscritorioContabil()
    {
        $oCampo = new \stdClass();
        $oCampo->nome = "escritorio_contabil";
        $oCampo->tipo = "lista_dinamica";
        $oCampo->label = "Escritorio Contabil";
        $oCampo->resposta = "";

        return $oCampo;
    }
}
