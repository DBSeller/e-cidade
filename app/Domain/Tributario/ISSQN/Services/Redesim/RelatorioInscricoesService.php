<?php

namespace App\Domain\Tributario\ISSQN\Services\Redesim;

use App\Domain\Tributario\ISSQN\Model\Redesim\InscricaoRedesim;
use App\Domain\Tributario\ISSQN\Reports\RelatorioInscricoes;

class RelatorioInscricoesService extends RelatorioInscricoes
{
    public function gerar()
    {
        $this->buscarDados();
        parent::gerar();
    }

    /**
     * @throws \BusinessException
     */
    private function buscarDados()
    {
        $this->validarFiltros();

        $aInscricoesRedesim = InscricaoRedesim::with("issBase.cgm")
                                              ->betweenDataCadastroInscricao($this->dataInicio, $this->dataFim)
                                              ->join("ativprinc", "q88_inscr", "q179_inscricao")
                                              ->join("tabativ", function ($join) {
                                                  $join->on("tabativ.q07_inscr", "ativprinc.q88_inscr");
                                                  $join->on("tabativ.q07_seq", "ativprinc.q88_seq");
                                              })
                                              ->join("ativid", "q03_ativ", "q07_ativ")
                                              ->join("issruas", "issruas.q02_inscr", "q179_inscricao")
                                              ->join("ruas", "ruas.j14_codigo", "issruas.j14_codigo")
                                              ->join("ruastipo", "j88_codigo", "j14_tipo")
                                              ->join("issbairro", "q13_inscr", "q179_inscricao")
                                              ->join("bairro", "j13_codi", "q13_bairro")
                                              ->get();

        $this->setInscricoes($aInscricoesRedesim);
    }

    private function validarFiltros()
    {
        if (empty($this->dataInicio)) {
            throw new \BusinessException("Informe algum filtro.");
        } else {
            if (empty($this->dataFim)) {
                throw new \BusinessException("Informe a data final.");
            }
        }
    }
}
