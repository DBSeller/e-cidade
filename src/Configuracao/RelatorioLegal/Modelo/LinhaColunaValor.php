<?php

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\PeriodoRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaRepositorio;
use Exception;
use Instituicao;
use InstituicaoRepository;

/**
 * Class LinhaColuna
 * @package ECidade\Configuracao\RelatorioLegal\Modelo
 */
class LinhaColunaValor
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var LinhaColuna
     */
    private $linhaColuna;
    /**
     * @var Linha
     */
    private $linha;
    /**
     * @var string
     */
    private $valor;
    /**
     * @var Instituicao
     */
    private $instituicao;
    /**
     * @var Periodo
     */
    private $periodo;
    /**
     * @var int
     */
    private $ano;

    /**
     * @param array $state
     * @return LinhaColunaValor
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o117_sequencial', $state)) {
            $self->setSequencial($state['o117_sequencial']);
        }

        if (array_key_exists('o117_orcparamseqorcparamseqcoluna', $state)) {
            $linhaColuna = LinhaColunaRepositorio::find($state['o117_orcparamseqorcparamseqcoluna']);
            $self->setLinhaColuna($linhaColuna);

            if (array_key_exists('o117_linha', $state)) {
                $self->setLinha(LinhaRegistry::get($linhaColuna->getRelatorio(), $state['o117_linha']));
            }
        }

        if (array_key_exists('o117_valor', $state)) {
            $self->setValor($state['o117_valor']);
        }

        if (array_key_exists('o117_instit', $state)) {
            $self->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($state['o117_instit']));
        }

        if (array_key_exists('o117_periodo', $state)) {
            $self->setPeriodo(PeriodoRegistry::get($state['o117_periodo']));
        }

        if (array_key_exists('o117_anousu', $state)) {
            $self->setAno($state['o117_anousu']);
        }

        return $self;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
    }

    /**
     * @return LinhaColuna
     */
    public function getLinhaColuna()
    {
        return $this->linhaColuna;
    }

    /**
     * @param LinhaColuna $linhaColuna
     */
    public function setLinhaColuna(LinhaColuna $linhaColuna)
    {
        $this->linhaColuna = $linhaColuna;
    }

    /**
     * @return Linha
     */
    public function getLinha()
    {
        return $this->linha;
    }

    /**
     * @param Linha $linha
     */
    public function setLinha(Linha $linha)
    {
        $this->linha = $linha;
    }

    /**
     * @return string
     */
    public function getValor()
    {
        return (string)$this->valor;
    }

    /**
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = (string)$valor;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return Periodo
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param Periodo $periodo
     */
    public function setPeriodo(Periodo $periodo)
    {
        $this->periodo = $periodo;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return (int)$this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = (int)$ano;
    }
}
