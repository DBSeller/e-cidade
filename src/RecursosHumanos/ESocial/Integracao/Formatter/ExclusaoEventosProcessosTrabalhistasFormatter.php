<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use stdClass;

/**
 * Formata os dados da Exclusão de Eventos
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andre Mello <andre.mello@dbseller.com.br>
 */
class ExclusaoEventosProcessosTrabalhistasFormatter extends Formatter
{
    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param array $dados
     * @return array
     */
    public function formatar($dados)
    {
        $dadosExclusao = [];
        foreach ($dados as $exclusao) {
            $dadosExclusao[] = $this->processamento($exclusao);
        }

        return $dadosExclusao;
    }

        /**
     * @param  $dadosFormatado
     * @return mixed
     * @throws \BusinessException
     * @throws \DBException
     */
    private function processamento($exclusao)
    {
        $dadoExclusao = new stdClass();

        $dadoExclusao->inscricao_empregador = $this->getEmpregador()->getCnpj();
        $dadoExclusao->referencia = $exclusao->getReferencia();
        $dadoExclusao->infoExclusao->tpEvento = $exclusao->getTipoEvento();
        $dadoExclusao->infoExclusao->nrRecEvt = $exclusao->getRecibo();
        $dadoExclusao->ideProcTrab->nrProcTrab = $exclusao->getNumeroProcesso();
        if ($exclusao->getTipoEvento() == 'S-2500') {
            $dadoExclusao->ideProcTrab->cpfTrab = $exclusao->getCpf();
        }
        if ($exclusao->getTipoEvento() == 'S-2501') {
            $dadoExclusao->ideProcTrab->perApurPgto = $exclusao->getPeriodoPagamento();
        }

        return $dadoExclusao;
    }
}
