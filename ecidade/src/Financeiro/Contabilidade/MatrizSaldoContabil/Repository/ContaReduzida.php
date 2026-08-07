<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository;

use \cl_infocomplementarvalor;
use \cl_conplanoreduz;
use \cl_conlancaminfocomplementarvalor;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model;

class ContaReduzida extends \BaseClassRepository
{
    /**
     * Sobrescreve o atributro da classe pai para manter referencia atual
     * @var Lancamento
     */
    protected static $oInstance;

    /**
     * @var integer $anoLancamento
     */
    protected $anoLancamento;

    /**
     * Cria coleção de dados de conta reduzida e suas respctivas informações complementares de acordo com o lançamento
     *
     * @param \stdClass[] $contasReduzida
     * @param int $codigoLancamento
     * @param             $instituicao
     * @return \stdClass[]
     * @throws \DBException
     */
    public function montarColecaoContaReduzidaInfoComplemetar(array $contasReduzida, $codigoLancamento, $instituicao)
    {
        $daoConplanoReduz = new cl_conplanoreduz();

        foreach ($contasReduzida as $conta) {
            $this->buscarInformacoesConta($conta);

            $where = "c61_reduz = {$conta->numero} and c61_anousu = {$this->anoLancamento}";
            $listaAtributos = implode(', ', MatrizSaldoContabil::getAtributos($this->anoLancamento));
            $where .= "and c121_sequencial in({$listaAtributos})";
            $sqlInfoComplementar = $daoConplanoReduz->sql_query_infocomplementar_obrigatorias(
                "c121_sequencial, c121_sigla, c121_descricao",
                $where
            );
            $rs = \db_query($sqlInfoComplementar);

            $aInfocomplementares = \db_utils::makeCollectionFromRecord($rs, function ($infoComplementar) {
                $informacaoComplementar = new \stdClass();
                $informacaoComplementar->codigo = $infoComplementar->c121_sequencial;
                $informacaoComplementar->sigla = $infoComplementar->c121_sigla;
                $informacaoComplementar->descricao = urlencode($infoComplementar->c121_descricao);

                return $informacaoComplementar;
            });

            $conta->informacoesComplementares = $aInfocomplementares;
            $this->buscarValorInformacaoComplementares($conta, $codigoLancamento, $instituicao);
        }

        return $contasReduzida;
    }


    /**
     * Busca as informações da conta por ano
     * @param \stdClass $conta
     */
    protected function buscarInformacoesConta(\stdClass $conta)
    {
        $daoConplanoReduz = new cl_conplanoreduz();
        $campos = "c60_estrut, c61_instit, c60_codcon, c60_descr";
        $sqlConplanoReduz = $daoConplanoReduz->sql_query($conta->numero, $this->anoLancamento, $campos);
        $rs = db_query($sqlConplanoReduz);

        if (!$rs || pg_num_rows($rs) == 0) {
            throw new \DBException("Erro ao buscar as informações da conta.");
        }

        $dados = \db_utils::fieldsMemory($rs, 0);
        $conta->estrutural = $dados->c60_estrut;
        $conta->descricao = $dados->c60_descr;
        $conta->codcon = $dados->c60_codcon;
        $conta->instituicao = $dados->c61_instit;
    }

    /**
     * @param $aInfocomplementares
     * @param $codigoLancamento
     * @param $instituicao
     */
    protected function buscarValorInformacaoComplementares(\stdClass $conta, $codigoLancamento, $instituicao)
    {
        $daoConlancamInfoComplementar = new cl_conlancaminfocomplementarvalor();
        $oInfocomplementar = new Model\InformacaoComplementar();

        $sqlConlancamInfo = $daoConlancamInfoComplementar->sql_query_informacao_complementar_valor(
            "c126_sequencial, c126_infocomplementar, c126_tiposistema, c126_valor, c121_sigla",
            "c126_codlan = {$codigoLancamento} and c126_reduz = {$conta->numero}"
        );
        $rsConlancamInfo = \db_query($sqlConlancamInfo);

        $aInfoComplementar = array();
        for ($i = 0; $i < pg_num_rows($rsConlancamInfo); $i++) {
            $infoComplementar = \db_utils::fieldsMemory($rsConlancamInfo, $i);

            $aInfoComplementar[$infoComplementar->c121_sigla] = new \stdClass();
            $aInfoComplementar[$infoComplementar->c121_sigla]->valor = $infoComplementar->c126_valor;
            $aInfoComplementar[$infoComplementar->c121_sigla]->codigo = $infoComplementar->c126_infocomplementar;
            $aInfoComplementar[$infoComplementar->c121_sigla]->tipoSistema = $infoComplementar->c126_tiposistema;
        }

        if (empty($aInfoComplementar)) {
            foreach ($conta->informacoesComplementares as $infoComplementar) {
                $oInfocomplementar->setContaReduzida($conta->numero);
                $oInfocomplementar->setConta($conta->codcon);
                $oInfocomplementar->setCodigoInstituicao($conta->instituicao);
                $oInfocomplementar->setCodigoLancamento($codigoLancamento);
                $oInfocomplementar->setAnousu($this->anoLancamento);
                $oInfocomplementar->setSigla($infoComplementar->sigla);
                $oInfocomplementar->atualizarValor(true);
                $infoComplementar->valor = $oInfocomplementar->getValor();
            }

            $conta->excluir = false;
        } else {
            foreach ($conta->informacoesComplementares as $infoComplementar) {
                if (empty($aInfoComplementar[$infoComplementar->sigla])) {
                    continue;
                }
                $infoComplementar->valor = $aInfoComplementar[$infoComplementar->sigla]->valor;
                $infoComplementar->codigo = $aInfoComplementar[$infoComplementar->sigla]->codigo;
                $infoComplementar->tipoSistema = $aInfoComplementar[$infoComplementar->sigla]->tipoSistema;
            }

            $conta->excluir = true;
        }
    }

    /**
     * @param $conta
     * @throws \DBException
     */
    public function persistConlancamInfoComplementarValor($conta)
    {
        $this->excluirInformacaoComplementarLancamento($conta->codigoLancamento, $conta->contaReduzida);
        foreach ($conta->informacoesComplementares as $infoComplementar) {
            $this->incluirConlancamInfoComplementarValor($conta, $infoComplementar);
        }
    }

    /**
     * @param \stdClass $conta
     * @param \stdClass $infoComplementar
     * @throws \DBException
     */
    protected function alterarConlancamInfoComplementarValor(\stdClass $conta, \stdClass $infoComplementar)
    {
        $daoConlancamInfoComplementarValor = new cl_conlancaminfocomplementarvalor();
        $where = " c126_sequencial = {$infoComplementar->codigo} ";
        $daoConlancamInfoComplementarValor->alterarValorInfoComplementarPorCondicao($infoComplementar->valor, $where);
    }

    /**
     * @param \stdClass $conta
     * @param \stdClass $infoComplementar
     */
    protected function incluirConlancamInfoComplementarValor(\stdClass $conta, \stdClass $infoComplementar)
    {
        $daoConlancamInfoComplementarValor = new cl_conlancaminfocomplementarvalor();

        $daoConlancamInfoComplementarValor->c126_sequencial = null;
        $daoConlancamInfoComplementarValor->c126_codlan = $conta->codigoLancamento;
        $daoConlancamInfoComplementarValor->c126_reduz = $conta->contaReduzida;
        $daoConlancamInfoComplementarValor->c126_infocomplementar = $infoComplementar->codigo;
        $daoConlancamInfoComplementarValor->c126_tiposistema = $infoComplementar->tipoSistema;
        $daoConlancamInfoComplementarValor->c126_valor = $infoComplementar->valor;

        $daoConlancamInfoComplementarValor->incluir(null);

        if ($daoConlancamInfoComplementarValor->erro_status == 0) {
            throw new \DBException("Erro ao incluir a configuração de informação complementar para a conta.");
        }
    }

    /**
     * Exclui os valores das informações complementares a partir do código do lançamento e reduzido da conta
     * @param int $iCodLancamento
     * @param int $iReduzidoConta
     */
    public function excluirInformacaoComplementarLancamento($iCodLancamento, $iReduzidoConta)
    {
        $daoConlancamInfoComplementar = new cl_conlancaminfocomplementarvalor();
        $daoConlancamInfoComplementar->excluirInformacaoComplementarLancamento($iCodLancamento, $iReduzidoConta);
    }

    /**
     * Busca as contas reduzidas de um lançamento
     * @param int $codigoLancamento
     * @return \stdClass[]
     * @throws \DBException
     */
    public function buscarContasReduzidasPorLancamento($codigoLancamento)
    {
        $daoLancamento = new \cl_conlancamval();
        $sqlLancamento = $daoLancamento->sql_query_file(
            null,
            'c69_credito, c69_debito, c69_anousu',
            null,
            "c69_codlan = {$codigoLancamento}"
        );
        $rsLancamento = \db_query($sqlLancamento);

        if (!$rsLancamento) {
            throw new \DBException("Erro ao buscar as contas reduzidas do lançamento.");
        }

        $contasLancamento = \db_utils::makeCollectionFromRecord($rsLancamento, function ($contasReduzidas) {
            $lancamento = new \stdClass();
            $lancamento->contaDebito = $contasReduzidas->c69_debito;
            $lancamento->contaCredito = $contasReduzidas->c69_credito;

            return $lancamento;
        });

        return $contasLancamento;
    }

    /**
     * @param $codigoLancamento
     * @return int
     * @throws \DBException
     */
    public function getAnoLancamento($codigoLancamento)
    {

        $daoAno = new \cl_conlancamval();
        $sqlAno = $daoAno->sql_query_file(null, 'c69_anousu', null, "c69_codlan = {$codigoLancamento}");
        $rsAno = \db_query($sqlAno);

        if (!$rsAno) {
            throw new \DBException("Erro ao buscar as contas reduzidas do lançamento.");
        }

        $this->anoLancamento = \db_utils::fieldsMemory($rsAno, 0)->c69_anousu;
        return $this->anoLancamento;
    }
}
