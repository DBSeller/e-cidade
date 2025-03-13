<?php

namespace ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao;

use ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao\InterfacePosProcessamento;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\ProcessamentoMatriz;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository\Lancamento as LancamentoMSCRepository;

/**
 * Class Conta
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao
 */
class Atributos implements InterfacePosProcessamento
{

    public function processar($codigoLancamento)
    {
        $this->validaAtributos($codigoLancamento);
    }

    /**
     * @param $codigoLancamento
     * @throws \ParameterException
     */
    public function validaAtributos($codigoLancamento)
    {
        $instituicao = \InstituicaoRepository::getInstituicaoSessao();
        $competencia = new \DBDate(date('Y-m-d', db_getsession('DB_datausu')));
        $competencia = $competencia->getCompetencia();
        $ano = $competencia->getAno();
        $mes = $competencia->getMes();
        $lancamentoAtributos = LancamentoMSCRepository::getInstance();
        $lancamentoAtributos->setSistema(1);
        $lancamentoAtributos->setAtributosParaProcessamento(MatrizSaldoContabil::getAtributos($ano));
        $sqlLancamentoAtributos = $lancamentoAtributos->getQueryLancamentosPorCompetencia(
            $mes,
            $ano,
            $instituicao->getCodigo(),
            array($codigoLancamento)
        );
        $rsAtributos = db_query($sqlLancamentoAtributos);
        if (!$rsAtributos) {
            throw new \Exception(pg_last_error() . " -- Ocorreu algo inexperado ao buscar os lançamentos ");
        }

        $numeroLinhas = pg_num_rows($rsAtributos);
        $sMensagem = "";
        for ($iLinhas = 0; $iLinhas < $numeroLinhas; $iLinhas++) {
            $oLancamento = \db_utils::fieldsMemory($rsAtributos, $iLinhas);
            $aAtributosValidar = explode(',', $oLancamento->nome_infos_complementares);
            $siglasAtributos   = explode(',', $oLancamento->siglas_atributos);
            foreach ($aAtributosValidar as $indiceAtributo => $campo) {
                if ($oLancamento->{$campo} == "") {
                    $sigla = $siglasAtributos[$indiceAtributo];
                    $sMensagem .= "Valor não encontrado para o campo: [{$sigla}] no lançamento: ";
                    $sMensagem .= "[$oLancamento->codigo_lancamento] e na conta [$oLancamento->conta] \n";
                }
            }
        }
        if (empty($sMensagem)) {
            return;
        }
        $daoLogAtributos = new \cl_conlancamlogatributos();
        $daoLogAtributos->c134_codlan = $codigoLancamento;
        $daoLogAtributos->c134_mensagem = $sMensagem;
        $daoLogAtributos->incluir();
        if ($daoLogAtributos->erro_status == "0") {
            throw new \Exception(pg_last_error() . " -- Ocorreu algo inexperado ao salvar log de inconsistencias");
        }
    }
}
