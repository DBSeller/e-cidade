<?php

namespace ECidade\Configuracao\RelatorioLegal\Servico;

use ECidade\Configuracao\RelatorioLegal\Modelo\InformacaoComplementarLancamento;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaInformacaoComplementar;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\InformacaoComplementarLancamentoRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\InformacaoComplementarLancamentoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaValorRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaFiltroPadraoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaInformacaoComplementarRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaRepositorio;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\InformacaoComplementar;
use ECidade\Financeiro\Contabilidade\Relatorio\RelatoriosLegaisBaseMSC;
use Exception;
use JSON;
use stdClass;

/**
 * Class LinhaServico
 * @package ECidade\Configuracao\RelatorioLegal\Servico
 */
class LinhaServico
{
    /**
     * @var stdClass
     */
    private $parametros;

    /**
     * LinhaServico constructor.
     * @param stdClass $parametros
     */
    public function __construct(stdClass $parametros = null)
    {
        $this->parametros = $parametros;
    }

    /**
     * @param bool $padrao
     * @return LinhaInformacaoComplementar[]
     * @throws Exception
     */
    public function salvarLinhaInformacaoComplementar($padrao = false)
    {
        if (empty($this->parametros->relatorio)) {
            throw new Exception('O relatório é obrigatório');
        }

        if (empty($this->parametros->linha)) {
            throw new Exception('A linha é obrigatória');
        }

        if (empty($this->parametros->informacoesComplementares)) {
            throw new Exception('A informação complementar é obrigatória');
        }

        if (empty($this->parametros->exclusao)) {
            throw new Exception('A informação de exclusão é obrigatória.');
        }

        $relatorio = RelatorioRegistry::get($this->parametros->relatorio);

        if ($relatorio === false) {
            throw new Exception("O relatório {$this->parametros->relatorio} não existe.");
        }

        $linha = LinhaRegistry::get($relatorio, $this->parametros->linha);

        if ($linha === false) {
            throw new Exception("A linha {$this->parametros->linha} não existe.");
        }

        $informacoesComplementares = JSON::create()->parse($this->parametros->informacoesComplementares);
        $informacoesComplementaresSalvas = array();
        $atributos = array();
        $codigosAtributos = array();

        foreach ($informacoesComplementares as $informacoesComplementar) {
            $valores = explode(',', $informacoesComplementar->valor);
            $atributos[$informacoesComplementar->sigla] = array_unique($valores);
            $codigosAtributos[$informacoesComplementar->sigla] = $informacoesComplementar->codigo;
        }

        $informacoesComplementares = $this->combinarAtributos($atributos);

        foreach ($informacoesComplementares as $informacaoComplementar) {
            $informacaoComplementarLancamento = new InformacaoComplementarLancamento();
            $informacaoComplementarLancamento->setExclusao($this->parametros->exclusao === 'true');
            $informacaoComplementarLancamento = InformacaoComplementarLancamentoRepositorio::save(
                $informacaoComplementarLancamento
            );

            foreach ($informacaoComplementar as $sigla => $valor) {
                switch ($sigla) {
                    case InformacaoComplementar::INFO_COMP_TIPO_ND:
                    case InformacaoComplementar::INFO_COMP_TIPO_NR:
                        $valor .= '%';
                        break;
                }

                $valor = trim(str_replace(array('X', '.'), array('_', ''), $valor));

                $linhaInformacaoComplementar = new LinhaInformacaoComplementar();
                $linhaInformacaoComplementar->setValor($valor);
                $linhaInformacaoComplementar->setInformacaoComplementar($codigosAtributos[$sigla]);
                $linhaInformacaoComplementar->setRelatorio($relatorio);
                $linhaInformacaoComplementar->setLinha($linha);
                $linhaInformacaoComplementar->setPadrao($padrao);
                $linhaInformacaoComplementar->setInformacaoComplementarLancamento($informacaoComplementarLancamento);
                $linhaInformacaoComplementar->setSigla($sigla);

                $informacoesComplementaresSalvas[] = LinhaInformacaoComplementarRepositorio::save(
                    $linhaInformacaoComplementar
                );
            }
        }

        return $informacoesComplementaresSalvas;
    }

    /**
     * @param array $atributos
     * @return array
     */
    private function combinarAtributos(array $atributos)
    {
        $informacoesComplementares = array(array());

        foreach ($atributos as $sigla => $valores) {
            $aux = array();

            foreach ($informacoesComplementares as $informacaoComplementar) {
                foreach ($valores as $valor) {
                    if ($valor = trim($valor)) {
                        $informacaoComplementar[$sigla] = $valor;
                        $aux[] = $informacaoComplementar;
                    }
                }
            }

            $informacoesComplementares = $aux;
        }

        return $informacoesComplementares;
    }

    /**
     * @param bool $padrao
     * @return array
     * @throws Exception
     */
    public function buscarLinhaInformacaoComplementar($padrao = false)
    {
        if (empty($this->parametros->relatorio)) {
            throw new Exception('O relatório é obrigatório');
        }

        if (empty($this->parametros->linha)) {
            throw new Exception('A linha é obrigatória');
        }

        $relatorio = RelatorioRegistry::get($this->parametros->relatorio);

        if ($relatorio === false) {
            throw new Exception("O relatório {$this->parametros->relatorio} não existe.");
        }

        $linha = LinhaRegistry::get($relatorio, $this->parametros->linha);
        if ($linha === false) {
            throw new Exception("A linha {$this->parametros->linha} não existe.");
        }

        $linhaInformacaoComplementarRepositorio = new LinhaInformacaoComplementarRepositorio();

        return $linhaInformacaoComplementarRepositorio
            ->resetScopes()
            ->scopeRelatorio($relatorio)
            ->scopeLinha($linha)
            ->scopePadrao($padrao)
            ->get();
    }

    /**
     * @param bool $padrao
     * @throws Exception
     */
    public function excluirLinhaInformacaoComplementar($padrao = false)
    {
        if (empty($this->parametros->relatorio)) {
            throw new Exception('O relatório é obrigatório');
        }

        if (empty($this->parametros->linha)) {
            throw new Exception('A linha é obrigatória');
        }

        if (empty($this->parametros->lancamento)) {
            throw new Exception('Código do lançamento não informado.');
        }

        $relatorio = RelatorioRegistry::get($this->parametros->relatorio);

        if ($relatorio === false) {
            throw new Exception("O relatório {$this->parametros->relatorio} não existe.");
        }

        $linha = LinhaRegistry::get($relatorio, $this->parametros->linha);

        if ($linha === false) {
            throw new Exception("A linha {$this->parametros->linha} não existe.");
        }

        $lancamento = $this->parametros->lancamento;
        $informacaoComplementarLancamento = InformacaoComplementarLancamentoRegistry::get($lancamento);

        $repositorio = new LinhaInformacaoComplementarRepositorio();
        $linhasColunaInfoComplementar = $repositorio
            ->scopeRelatorio($relatorio)
            ->scopeLinha($linha)
            ->scopePadrao($padrao)
            ->scopeInformacaoComplementarLancamento($informacaoComplementarLancamento)
            ->get();

        foreach ($linhasColunaInfoComplementar as $linhaColunaInfoComplementar) {
            $repositorio->delete($linhaColunaInfoComplementar);
        }

        $informacaoComplementarLancamentoRepositorio = new InformacaoComplementarLancamentoRepositorio();
        $informacaoComplementarLancamentoRepositorio->delete($informacaoComplementarLancamento);
    }

    /**
     * @return stdClass
     * @throws Exception
     */
    public function decomporValor()
    {
        if (empty($this->parametros->relatorio)) {
            throw new Exception('É necessário informar o relatório.');
        }

        if (empty($this->parametros->periodo)) {
            throw new Exception('É necessário informar o período.');
        }

        if (empty($this->parametros->linha)) {
            throw new Exception('É necessário informar a linha.');
        }

        if (empty($this->parametros->anoSessao)) {
            throw new Exception("É necessário informar o ano.");
        }

        $relatoriosLegaisBaseMSC = new RelatoriosLegaisBaseMSC(
            $this->parametros->anoSessao,
            $this->parametros->relatorio,
            $this->parametros->periodo
        );

        $relatorio = RelatorioRegistry::get($this->parametros->relatorio);

        $linhaRepositorio = new LinhaRepositorio();
        $linha = $linhaRepositorio->scopeRelatorio($relatorio)
            ->scopeLinha($this->parametros->linha)
            ->scopePeriodo($this->parametros->periodo)
            ->scopeConfiguracao()
            ->addOrder('o158_estrutural')
            ->first();

        return $relatoriosLegaisBaseMSC->decomporValoresMSCPorLinha($linha);
    }

    /**
     * @param Relatorio $relatorio
     * @param Linha $linha
     * @throws Exception
     */
    public function excluirLinha(Relatorio $relatorio, Linha $linha)
    {
        $linhaInformacaoComplementarRepositorio = new LinhaInformacaoComplementarRepositorio();
        $linhaInformacaoComplementarRepositorio->scopeRelatorio($relatorio)->scopeLinha($linha)->delete();

        $linhaColunaRepositorio = new LinhaColunaRepositorio();
        $linhaColunas = $linhaColunaRepositorio->scopeRelatorio($relatorio)->scopeLinha($linha)->get();

        $linhaColunaValorRepositorio = new LinhaColunaValorRepositorio();

        foreach ($linhaColunas as $linhaColuna) {
            $linhaColunaValorRepositorio->scopeLinhaColuna($linhaColuna)->delete();
            $linhaColunaRepositorio->resetScopes()->delete($linhaColuna);
        }

        $linhaFiltroPadraoRepositorio = new LinhaFiltroPadraoRepositorio();
        $linhaFiltroPadraoRepositorio->scopeLinha($linha)->scopeRelatorio($relatorio)->delete();

        $linhaRepositorio = new LinhaRepositorio();
        $linhaRepositorio->delete($linha);
    }
}
