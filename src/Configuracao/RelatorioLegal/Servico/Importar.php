<?php
/*
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

namespace ECidade\Configuracao\RelatorioLegal\Servico;

use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\ColunaEstrutural;
use ECidade\Configuracao\RelatorioLegal\Modelo\InformacaoComplementarLancamento;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaColuna;
use ECidade\Configuracao\RelatorioLegal\Modelo\ConfiguracaoPadrao;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaInformacaoComplementar;
use ECidade\Configuracao\RelatorioLegal\Modelo\Periodo;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Modelo\RelatorioPeriodo;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\InformacaoComplementarLancamentoRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\PeriodoRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaEstruturalRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\InformacaoComplementarLancamentoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaFiltroPadraoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaInformacaoComplementarRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\PeriodoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\RelatorioPeriodoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\RelatorioRepositorio;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;

use Exception;
use JSON;
use stdClass;

/**
 * Class Importar
 * @package ECidade\Configuracao\RelatorioLegal\Servico
 */
class Importar
{
    /**
     * objeto do Relatorio a ser importado
     * @var stdClass
     */
    private $relatorio;

    /**
     * Periodos a serem importados
     * @var array
     */
    private $periodos = array();

    /**
     * Colunas a serem importadas
     *
     * @var array
     */
    private $colunas = array();

    /**
     * Importar constructor.
     * @param $jsonString
     * @throws Exception
     */
    public function __construct($jsonString)
    {
        $dados = JSON::create()->parse($jsonString, JSON::UTF8_DECODE, false);

        if (empty($dados->relatorio) || !is_object($dados->relatorio)) {
            throw new Exception("Formato do Json inválido.");
        }

        $this->setRelatorio($dados->relatorio);

        if (isset($dados->periodos)) {
            $this->setPeriodos($dados->periodos);
        }

        if (isset($dados->colunas)) {
            $this->setColunas($dados->colunas);
        }
    }

    /**
     * define o objeto a ser percorrido e incluido
     * @param stdClass $oRelatorioJson
     */
    private function setRelatorio($oRelatorioJson)
    {
        $this->relatorio = $oRelatorioJson;
    }

    /**
     * @param array $periodos
     */
    private function setPeriodos($periodos)
    {
        $this->periodos = $periodos;
    }

    /**
     * @param array $colunas
     */
    private function setColunas($colunas)
    {
        $this->colunas = $colunas;
    }

    /**
     * @throws Exception
     */
    public function processar()
    {
        foreach ($this->periodos as $periodo) {
            $this->salvarPeriodo($periodo);
        }

        foreach ($this->colunas as $dadosColuna) {
            $coluna = $this->salvarColuna($dadosColuna);
            foreach ($dadosColuna->contas as $configuracao) {
                $this->salvarConfiguracoesColuna($configuracao, $coluna);
            }
        }

        $relatorio = $this->salvarRelatorio($this->relatorio);

        foreach ($this->relatorio->informacoesComplementaresLancamentos as $informacaoComplementarLancamento) {
            $this->salvarInformacaoComplementarLancamento($informacaoComplementarLancamento);
        }

        foreach ($this->relatorio->linhas as $linhaDados) {
            $linha = $this->salvarLinha($linhaDados, $relatorio);
            foreach ($linhaDados->colunas as $colunaDados) {
                $this->salvarLinhaColuna($colunaDados, $linha);
            }

            foreach ($linhaDados->filtroPadrao as $filtroPadraoDados) {
                $this->salvarFiltroPadrao($filtroPadraoDados, $linha);
            }

            foreach ($linhaDados->informacaoComplementar as $informacaoComplementarDados) {
                $this->salvarInformacaoComplementar($informacaoComplementarDados, $linha);
            }
        }

        foreach ($this->relatorio->periodos as $periodoRelatorioDados) {
            $this->salvarPeriodoRelatorio($periodoRelatorioDados, $relatorio);
        }
    }

    /**
     * @param $dadosPeriodo
     * @throws Exception
     */
    private function salvarPeriodo($dadosPeriodo)
    {
        $periodo = new Periodo();

        $periodo->setSequencial($dadosPeriodo->sequencial);
        $periodo->setDescricao($dadosPeriodo->descricao);
        $periodo->setQuantidadePorAno($dadosPeriodo->quantidadePorAno);
        $periodo->setDiaInicial($dadosPeriodo->diaInicial);
        $periodo->setMesInicial($dadosPeriodo->mesInicial);
        $periodo->setDiaFinal($dadosPeriodo->diaFinal);
        $periodo->setMesFinal($dadosPeriodo->mesFinal);
        $periodo->setSigla($dadosPeriodo->sigla);
        $periodo->setOrdem($dadosPeriodo->ordem);

        $periodoRepositorio = new PeriodoRepositorio();
        $periodoRepositorio->import($periodo);
    }

    /**
     * @param $dadosColuna
     * @return Coluna
     * @throws Exception
     */
    private function salvarColuna($dadosColuna)
    {
        $coluna = new Coluna();
        $coluna->setSequencial($dadosColuna->sequencial);
        $coluna->setAno($dadosColuna->ano);
        $coluna->setDescricao($dadosColuna->descricao);
        $coluna->setTipo($dadosColuna->tipo);
        $coluna->setDefault($dadosColuna->default);
        $coluna->setNome($dadosColuna->nome);
        $coluna->setFormula($dadosColuna->formula);
        $coluna->setOrigem($dadosColuna->origem);

        if ($dadosColuna->relatorio != null) {
            $coluna->setRelatorio(RelatorioRegistry::get($dadosColuna->relatorio));
        }

        $colunaRepositorio = new ColunaRepositorio();
        return $colunaRepositorio->import($coluna);
    }

    /**
     * @param $dadosConfiguracao
     * @param Coluna $coluna
     * @throws Exception
     */
    private function salvarConfiguracoesColuna($dadosConfiguracao, Coluna $coluna)
    {
        $colunaEstrutural = new ColunaEstrutural();
        $colunaEstrutural->setSequencial($dadosConfiguracao->sequencial);
        $colunaEstrutural->setExclusao($dadosConfiguracao->exclusao);
        $colunaEstrutural->setEstrutural($dadosConfiguracao->estrutural);
        $colunaEstrutural->setAno($dadosConfiguracao->ano);
        $colunaEstrutural->setColuna($coluna);

        $colunaEstruturalRepositorio = new ColunaEstruturalRepositorio();
        $colunaEstruturalRepositorio->import($colunaEstrutural);
    }

    /**
     * @param stdClass $dadosRelatorio
     * @return Relatorio
     * @throws Exception
     */
    private function salvarRelatorio(stdClass $dadosRelatorio)
    {
        $relatorio = new Relatorio();
        $relatorio->setSequencial($dadosRelatorio->sequencial);
        $relatorio->setDescricao($dadosRelatorio->descricao);
        $relatorio->setGrupo($dadosRelatorio->grupo);
        $relatorio->setNotaPadrao($dadosRelatorio->notaPadrao);

        $relatorioRepositorio = new RelatorioRepositorio();
        return $relatorioRepositorio->import($relatorio);
    }

    /**
     * @param $linhaDados
     * @param Relatorio $relatorio
     * @return Linha
     * @throws Exception
     */
    private function salvarLinha($linhaDados, Relatorio $relatorio)
    {
        $linha = new Linha();
        $linha->setrelatorio($relatorio);
        $linha->setlinha($linhaDados->linha);
        $linha->setdescricao($linhaDados->descricao);
        $linha->setgrupo($linhaDados->grupo);
        $linha->setgrupoExclusao($linhaDados->grupoExclusao);
        $linha->setnivel($linhaDados->nivel);
        $linha->setliberaNivel($linhaDados->liberaNivel);
        $linha->setliberaRecurso($linhaDados->liberaRecurso);
        $linha->setliberaSubFuncao($linhaDados->liberaSubFuncao);
        $linha->setliberaFuncao($linhaDados->liberaFuncao);
        $linha->setverificaAno($linhaDados->verificaAno);
        $linha->setlabel($linhaDados->label);
        $linha->setmanual($linhaDados->manual);
        $linha->settotalizadora($linhaDados->totalizadora);
        $linha->setordem($linhaDados->ordem);
        $linha->setnivelLinha($linhaDados->nivelLinha);
        $linha->setobservacao($linhaDados->observacao);
        $linha->setdesdobra($linhaDados->desdobra);
        $linha->setorigem($linhaDados->origem);

        $linhaRepositorio = new LinhaRepositorio();
        return $linhaRepositorio->import($linha);
    }

    /**
     * @param $colunaDados
     * @param Linha $linha
     * @return LinhaColuna
     * @throws Exception
     */
    private function salvarLinhaColuna($colunaDados, Linha $linha)
    {
        $relacao = new LinhaColuna();

        $coluna = ColunaRegistry::get($colunaDados->coluna->sequencial);

        $relacao->setSequencial($colunaDados->sequencial);
        $relacao->setColuna($coluna);
        $relacao->setLinha($linha);
        $relacao->setRelatorio($linha->getRelatorio());
        $relacao->setOrdem($colunaDados->ordem);
        $relacao->setPeriodo($colunaDados->periodo);
        $relacao->setFormula($colunaDados->formula);

        $linhaColunaRepositorio = new LinhaColunaRepositorio();
        return $linhaColunaRepositorio->import($relacao);
    }

    /**
     * @param $filtroPadraoDados
     * @param Linha $linha
     * @throws Exception
     */
    private function salvarFiltroPadrao($filtroPadraoDados, Linha $linha)
    {
        $filtroPadrao = new ConfiguracaoPadrao();
        $filtroPadrao->setSequencial($filtroPadraoDados->sequencial);
        $filtroPadrao->setRelatorio($linha->getRelatorio());
        $filtroPadrao->setLinha($linha);
        $filtroPadrao->setAno($filtroPadraoDados->ano);
        $filtroPadrao->setFiltro($filtroPadraoDados->filtro);

        $filtroRepositorio = new LinhaFiltroPadraoRepositorio();
        $filtroRepositorio->import($filtroPadrao);
    }

    /**
     * @param $informacaoComplementarDados
     * @param Linha $linha
     * @throws Exception
     */
    private function salvarInformacaoComplementar($informacaoComplementarDados, Linha $linha)
    {
        $informacaoComplementar = new LinhaInformacaoComplementar();
        $informacaoComplementar->setSequencial($informacaoComplementarDados->sequencial);
        $informacaoComplementar->setValor($informacaoComplementarDados->valor);
        $informacaoComplementar->setInformacaoComplementar($informacaoComplementarDados->informacaoComplementar);
        $informacaoComplementar->setRelatorio($linha->getRelatorio());
        $informacaoComplementar->setLinha($linha);
        $informacaoComplementar->setSigla($informacaoComplementarDados->sigla);
        $informacaoComplementar->setPadrao(true);
        $informacaoComplementar->setInformacaoComplementarLancamento(
            InformacaoComplementarLancamentoRegistry::get(
                $informacaoComplementarDados->linhaInformacaoComplementarContaCorrente
            )
        );

        $informacaoComplementarRepositorio = new LinhaInformacaoComplementarRepositorio();
        $informacaoComplementarRepositorio->import($informacaoComplementar);
    }

    /**
     * @param $relatorioPeriodoDados
     * @param Relatorio $relatorio
     * @throws Exception
     */
    private function salvarPeriodoRelatorio($relatorioPeriodoDados, Relatorio $relatorio)
    {
        $relatorioPeriodo = new RelatorioPeriodo();
        $relatorioPeriodo->setSequencial($relatorioPeriodoDados->sequencial);
        $relatorioPeriodo->setRelatorio($relatorio);
        $relatorioPeriodo->setPeriodo(PeriodoRegistry::get($relatorioPeriodoDados->periodo->sequencial));

        $relatorioPeriodoRepositorio = new RelatorioPeriodoRepositorio();
        $relatorioPeriodoRepositorio->import($relatorioPeriodo);
    }

    /**
     * @param stdClass $stdInformacaoComplementarLancamento
     * @throws Exception
     */
    private function salvarInformacaoComplementarLancamento(stdClass $stdInformacaoComplementarLancamento)
    {
        $informacaoComplementarLancamento = new InformacaoComplementarLancamento();
        $informacaoComplementarLancamento->setSequencial($stdInformacaoComplementarLancamento->sequencial);
        InformacaoComplementarLancamentoRepositorio::import($informacaoComplementarLancamento);
    }
}
