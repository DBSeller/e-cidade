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

use \ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\RazaoContaCorrente as RazaoContaCorrenteModel;
use \BaseClassRepository;
use \InstituicaoRepository;
use \cl_conlancaminfocomplementarvalor;
use \cl_infocomplementarvalor;
use \cl_configuracaoinstituicaosiconfi;
use \cl_conplanoinfocomplementar;
use \cl_conplanosistema;
use \DBException;
use \ParameterException;
use \BusinessException;

/**
 * Class RazaoContaCorrente
 * @package ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository
 */
class RazaoContaCorrente extends BaseClassRepository
{
    /**
     * Representa a instancia a classe.
     *
     * @var RazaoContaCorrente
     * @access protected
     */
    protected static $oInstance;

    /**
     * @var RazaoContaCorrenteModel[][]
     */
    private $colecaoRazaoContaCorrente = array();

    private $unidadeGestora;

    private $codigoContaCorrente;
    /**
     * @var bool
     */
    private $rodouEstrutura = false;

    /**
     * Retorna os dados referente ao lançamento de cada conta por data.
     *
     * @param string $whereFiltros
     * @return array
     * @throws DBException
     */
    private function buscarDadosMovimentacoesContaCorrentePorFiltro($whereFiltros, $ano)
    {
        $infoComplementarValor = new cl_conlancaminfocomplementarvalor();
        $codigoInstituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
        if (!$this->validarConfiguracaoInstituicaoMatrizSaldoContabil($codigoInstituicao)) {
            throw new \Exception("Não foi processado a matriz de saldo contábil para a instituição acessada.\nConfigure a instituição em: Contabilidade > Procedimentos > Matriz de Saldos Contábeis > Configurações > Instituições e reprocesse a matriz.");
        }

        if (!$this->rodouEstrutura) {

            $infoComplementarValor->montarEstrutura($ano, $codigoInstituicao, $this->codigoContaCorrente, 2, $this->unidadeGestora);
            $this->rodouEstrutura = true;
        }

        $sql = $infoComplementarValor->sqlQueryRazaoContaCorrente('*', $whereFiltros);
        $rs = \db_query($sql);
        if (!$rs) {
            throw new DBException('Erro ao buscar dados para o processamento da Razão por Conta Corrente.');
        }
        $totalDeRegistros = pg_num_rows($rs);
        if ($totalDeRegistros == 0) {
            throw new DBException('Não foram encontradas movimentações para os filtros informados.');
        }
        $dados = \db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            return $retorno;
        });

        return $dados;
    }

    public function setFiltroUnidadeGestora ($unidadeGestora) {
        $this->unidadeGestora = $unidadeGestora;
    }

    /**
     * @param \DBDate $dataInicio
     * @param \DBDate $dataFim
     * @param \stdClass $filtros
     * @return string
     */
    private function prepararFiltrosParaBuscarMovimentacoes(\DBDate $dataInicio, \DBDate $dataFim, \stdClass $filtros)
    {
        $where = array();
        $dataInicial = $dataInicio->getDate();
        $dataFinal = $dataFim->getDate();
        $where[] = " data between '{$dataInicial}' and '{$dataFinal}'";

        if (!empty($filtros->estrutural)) {
            $where[] = " estrutural ilike '{$filtros->estrutural}%'";
        }

        if (!empty($filtros->documentos)) {
            //$where[] = " documento in (" . implode(',', $filtros->documentos) . ") ";
        }

        if (!empty($filtros->contas)) {
            $where[] = " reduzido in (" . implode(',', $filtros->contas) . ") ";
        }

        if (!empty($filtros->atributos)) {
            $orAtributos = array();
            foreach ($filtros->atributos as $atributo) {
                $orAtributos[] = " atributos ilike '%{$atributo->sigla}#{$atributo->valor}%'";
            }

            $where[] = '(' . implode(' or ', $orAtributos) . ')';
        }

        $where[] = ' lancamento is not null ';

        return implode(' and ', $where);
    }

    /**
     * @param RazaoContaCorrenteModel $razaocontaCorrenteModel
     * @return string
     */
    private function prepararFiltrosParaCalcularSaldoFinal(\ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\RazaoContaCorrente $razaocontaCorrenteModel)
    {
        $where = array();
        $dataInicial = $razaocontaCorrenteModel->getDataMovimentacao()->getDate();
        $where[] = " data < '{$dataInicial}'";
        //$where[] = " documento = " . $razaocontaCorrenteModel->getCodigoDocumento();
        $where[] = " reduzido = " . $razaocontaCorrenteModel->getReduzido();

        foreach ($razaocontaCorrenteModel->getAtributos() as $sigla => $atributo) {
            $where[] = " atributos ilike '%{$sigla}#{$atributo}%'";
        }

        return implode(' and ', $where);
    }

    /**
     * @param \stdClass[] $dados
     * @return \ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\RazaoContaCorrente[][]
     */
    private function criarColecaoRazaoContaCorrente(array $dados)
    {
        foreach ($dados as $objetoStd) {
            $razaoContaCorrente = $this->criarObjetoRazaoContaCorrente($objetoStd);

            if (empty($this->colecaoRazaoContaCorrente[$razaoContaCorrente->getReduzido()][$razaoContaCorrente->getHashAtributos()])) {
                $this->colecaoRazaoContaCorrente[$razaoContaCorrente->getReduzido()][$razaoContaCorrente->getHashAtributos()] = $razaoContaCorrente;
            } else {
                $valorDebito = $razaoContaCorrente->getMovimentacaoDebito();
                $valorCredito = $razaoContaCorrente->getMovimentacaoCredito();

                $this->colecaoRazaoContaCorrente[$razaoContaCorrente->getReduzido()][$razaoContaCorrente->getHashAtributos()]->somarMovimentacaoDebito($valorDebito);
                $this->colecaoRazaoContaCorrente[$razaoContaCorrente->getReduzido()][$razaoContaCorrente->getHashAtributos()]->somarMovimentacaoCredito($valorCredito);
            }
        }

        foreach ($this->colecaoRazaoContaCorrente as $razaoContaCorrentes) {
            foreach ($razaoContaCorrentes as $razaoContaCorrenteAdicionada) {
                $this->calcularSaldoAnteriorRazaoContaCorrente($razaoContaCorrenteAdicionada);
                $this->calcularSaldoFinalRazaoContaCorrente($razaoContaCorrenteAdicionada);
            }
        }

        return $this->colecaoRazaoContaCorrente;
    }

    /**
     * @param RazaoContaCorrenteModel $razaocontaCorrenteModel
     * @return \stdClass
     * @throws DBException
     */
    public function calcularSaldoAnteriorRazaoContaCorrente(\ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\RazaoContaCorrente $razaocontaCorrenteModel)
    {
        $saldoAnterior = new \stdClass();

        $where = $this->prepararFiltrosParaCalcularSaldoFinal($razaocontaCorrenteModel);
        $whereDebito = $where . " and natureza = 'D'";
        $whereCredito = $where . " and natureza = 'C'";

        $infoComplementarValor = new cl_conlancaminfocomplementarvalor();
        $codigoInstituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
        $sql = $infoComplementarValor->sqlQueryRazaoContaCorrente('sum(valor_lancamento) as saldo_anterior_debito', $whereDebito);
        $rsAnteriorDebito = \db_query($sql);
        if (!$rsAnteriorDebito) {
            throw new DBException('Erro ao buscar dados para cálculo do saldo anterior da conta corrente para a natureza de débito.');
        }

        $sql = $infoComplementarValor->sqlQueryRazaoContaCorrente('sum(valor_lancamento) as saldo_anterior_credito', $whereCredito);
        $rsAnteriorCredito = \db_query($sql);
        if (!$rsAnteriorCredito) {
            throw new DBException('Erro ao buscar dados para cálculo do saldo anterior da conta corrente para a natureza de crédito.');
        }

        $saldoAnterior->saldo = (float)\db_utils::fieldsMemory($rsAnteriorDebito, 0)->saldo_anterior_debito - (float)\db_utils::fieldsMemory($rsAnteriorCredito, 0)->saldo_anterior_credito;
        if ($saldoAnterior->saldo < 0) {
            $saldoAnterior->saldo = (float)$saldoAnterior->saldo * -1;
            $saldoAnterior->natureza = 'C';

            $razaocontaCorrenteModel->setSaldoAnterior($saldoAnterior->saldo);
            $razaocontaCorrenteModel->setNaturezaSaldoAnterior($saldoAnterior->natureza);
        } else {
            $saldoAnterior->natureza = 'D';

            $razaocontaCorrenteModel->setSaldoAnterior($saldoAnterior->saldo);
            $razaocontaCorrenteModel->setNaturezaSaldoAnterior($saldoAnterior->natureza);
        }

        return $saldoAnterior;
    }

    /**
     * @param RazaoContaCorrenteModel $razaoContaCorrenteAdicionada
     * @return \stdClass
     */
    public function calcularSaldoFinalRazaoContaCorrente(\ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\RazaoContaCorrente $razaoContaCorrenteAdicionada)
    {
        $saldoFinal = new \stdClass();
        $saldoFinal->valor = $razaoContaCorrenteAdicionada->getSaldoAnterior();
        $saldoFinal->natureza = $razaoContaCorrenteAdicionada->getNaturezaSaldoAnterior();

        if ($saldoFinal->natureza === 'C') {
            $saldoFinal->valor += $razaoContaCorrenteAdicionada->getMovimentacaoCredito();
            $saldoFinal->valor -= $razaoContaCorrenteAdicionada->getMovimentacaoDebito();
        }

        if ($saldoFinal->natureza === 'D') {
            $saldoFinal->valor += $razaoContaCorrenteAdicionada->getMovimentacaoDebito();
            $saldoFinal->valor -= $razaoContaCorrenteAdicionada->getMovimentacaoCredito();
        }

        if ($saldoFinal->valor < 0) {
            $saldoFinal->valor = $saldoFinal->valor * -1;
            $saldoFinal->natureza = $saldoFinal->natureza === 'C' ? 'D' : 'C';
        }

        $razaoContaCorrenteAdicionada->setSaldoFinal($saldoFinal->valor);
        $razaoContaCorrenteAdicionada->setNaturezaSaldoFilnal($saldoFinal->natureza);

        return $saldoFinal;
    }

    /**
     * @param \stdClass $dado
     * @return RazaoContaCorrenteModel
     * @throws \ParameterException
     */
    public function criarObjetoRazaoContaCorrente(\stdClass $dado)
    {
        $data = new \DBDate($dado->data);
        $razaoContaCorrente = new RazaoContaCorrenteModel($dado->reduzido, $dado->documento, $data, $dado->atributos);
        $razaoContaCorrente->setEstrutural($dado->estrutural);
        $razaoContaCorrente->setDescricaoEstrutural($dado->descricao_estrutural);
        $razaoContaCorrente->setDescricaoDocumento($dado->documento_descricao);
        $razaoContaCorrente->setCodigoRecurso($dado->codigo_recurso);
        $razaoContaCorrente->setDescricaoRecurso($dado->descricao_recurso);

        if ($razaoContaCorrente->existeAtributoNd()) {
            $descricao = $this->buscarDescricaoNdPorCodigoLancamento($dado->lancamento);
            $razaoContaCorrente->setDescricaoNd($descricao);
        }

        if ($razaoContaCorrente->existeAtributoNr()) {
            $descricao = $this->buscarDescricaoNrPorCodigoLancamento($dado->lancamento);
            $razaoContaCorrente->setDescricaoNr($descricao);
        }

        if ($dado->natureza === 'D') {
            $razaoContaCorrente->somarMovimentacaoDebito($dado->valor_lancamento);
        }

        if ($dado->natureza === 'C') {
            $razaoContaCorrente->somarMovimentacaoCredito($dado->valor_lancamento);
        }

        return $razaoContaCorrente;
    }

    public function setContaCorrente($codigoContaCorrente){
        $this->codigoContaCorrente = $codigoContaCorrente;
    }

    /**
     * @param \DBDate $dataInicio
     * @param \DBDate $dataFim
     * @param \stdClass $filtros
     * @throws DBException
     */
    public function buscarLancamentosRazaoContaCorrentePorPeriodo(\DBDate $dataInicio, \DBDate $dataFim, \stdClass $filtros)
    {
        if ($dataInicio->getTimeStamp() > $dataFim->getTimeStamp()) {
            throw new ParameterException('Data de inicio deve ser menor que data final.');
        }

        if (empty($dataInicio) || empty($dataFim)) {
            throw new ParameterException('Preencha a data inícial e data final a serem processadas.');
        }

        $where = $this->prepararFiltrosParaBuscarMovimentacoes($dataInicio, $dataFim, $filtros);
        $dados = $this->buscarDadosMovimentacoesContaCorrentePorFiltro($where, $dataFim->getAno());

        $this->criarColecaoRazaoContaCorrente($dados);

        return $this->colecaoRazaoContaCorrente;
    }

    /**
     * @param $codigoLancamento
     * @return mixed
     * @throws DBException
     */
    public function buscarDescricaoNdPorCodigoLancamento($codigoLancamento)
    {
        $inforComplementar = new cl_infocomplementarvalor();
        $sql = $inforComplementar->sql_query_infocomplementar_nd_by_lancamento($codigoLancamento);
        $rs = \db_query($sql);
        if (!$rs) {
            throw new DBException('Erro ao buscar descrição da Natureza da Despesa.');
        }

        $descricao = \db_utils::fieldsMemory($rs, 0);

        return empty($descricao->descricao_nd) ? 'NATUREZA DA DESPESA PADRÃO.' : $descricao->descricao_nd;
    }

    /**
     * @param int $codigoLancamento
     * @return mixed
     * @throws DBException
     */
    public function buscarDescricaoNrPorCodigoLancamento($codigoLancamento)
    {
        $inforComplementar = new cl_infocomplementarvalor();
        $sql = $inforComplementar->sql_query_infocomplementar_nr_by_lancamento($codigoLancamento);
        $rs = \db_query($sql);
        if (!$rs) {
            throw new DBException('Erro ao buscar descrição da Natureza da Receita');
        }

        $descricao = \db_utils::fieldsMemory($rs, 0);
        return empty($descricao->descricao_nr) ? 'NATUREZA DA RECEITA PADRÃO.' : $descricao->descricao_nr;
    }

    /**
     * @param int $codigoInstituicao
     * @return bool
     */
    public function validarConfiguracaoInstituicaoMatrizSaldoContabil($codigoInstituicao)
    {
        $configSiconf = new cl_configuracaoinstituicaosiconfi();
        $rs = \db_query($configSiconf->sql_query());

        $instituicoes = \db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            return $retorno->c125_db_config;
        });

        return in_array($codigoInstituicao, $instituicoes);
    }

    /**
     * @return array
     * @throws DBException
     */
    public function buscarAtributosContasCorrente()
    {
        $infoComplementar = new cl_conplanoinfocomplementar();
        $sql = $infoComplementar->sql_query('', "c121_sigla as sigla, c121_sigla || ' - ' || c121_descricao as descricao", 'c121_sequencial asc');
        $rs = \db_query($sql);

        if (!$rs) {
            throw new DBException("Ocorreu um erro ao buscar informações, por favor tente novamente.\nCaso o problema persista, contate o suporte.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new BusinessException("Nenhum atríbuto encontrado.");
        }

        return \db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            $retorno->descricao = urlencode($retorno->descricao);
            return $retorno;
        });
    }

    public function buscarContasCorrente()
    {
        $daoConplanoSistema = new cl_conplanosistema();
        $sql = $daoConplanoSistema->sql_query_file(null, "c122_sequencial as codigo, c122_descricao as descricao", "2,1", "c122_tipo = 2");
        $rs = \db_query($sql);

        if (!$rs) {
            throw new DBException("Ocorreu um erro ao buscar informações do conta corrent, por favor tente novamente.\nCaso o problema persista, contate o suporte.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new BusinessException("Nenhum conta corrente encontrado.");
        }

        return \db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            $retorno->descricao = urlencode($retorno->descricao);
            return $retorno;
        });
    }




}
