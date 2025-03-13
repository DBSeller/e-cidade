<?php

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\InformacaoComplementarLancamentoRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaInformacaoComplementarRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use Exception;

/**
 * Class LinhaInformacaoComplementar
 * @package ECidade\Configuracao\RelatorioLegal\Modelo
 */
class LinhaInformacaoComplementar
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var string
     */
    private $valor;
    /**
     * @var int
     */
    private $informacaoComplementar;
    /**
     * @var Relatorio
     */
    private $relatorio;
    /**
     * @var Linha
     */
    private $linha;
    /**
     * @var string
     */
    private $sigla;
    /**
     * @var bool
     */
    private $padrao;
    /**
     * @var InformacaoComplementarLancamento
     */
    private $informacaoComplementarLancamento;

    /**
     * @param array $state
     * @return LinhaInformacaoComplementar
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o157_sequencial', $state)) {
            $self->setSequencial($state['o157_sequencial']);
        }

        if (array_key_exists('o157_valor', $state)) {
            $self->setValor($state['o157_valor']);
        }

        if (array_key_exists('o157_conplanoinfocomplementar', $state)) {
            $self->setInformacaoComplementar($state['o157_conplanoinfocomplementar']);
        }

        if (array_key_exists('o157_relatorio', $state)) {
            $relatorio = RelatorioRegistry::get($state['o157_relatorio']);

            $self->setRelatorio($relatorio);

            if (array_key_exists('o157_linha', $state)) {
                $self->setLinha(LinhaRegistry::get($relatorio, $state['o157_linha']));
            }
        }

        if (array_key_exists('c121_sigla', $state)) {
            $self->setSigla($state['c121_sigla']);
        }

        if (array_key_exists('o157_padrao', $state)) {
            $self->setPadrao($state['o157_padrao'] === 't');
        }

        if (array_key_exists('o157_infocomplementarlancamento', $state)) {
            $self->setInformacaoComplementarLancamento(
                InformacaoComplementarLancamentoRegistry::get($state['o157_infocomplementarlancamento'])
            );
        }

        LinhaInformacaoComplementarRegistry::set($self);

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $codigoInformacaoComplementar = null;
        if ($this->getInformacaoComplementarLancamento() instanceof InformacaoComplementarLancamento) {
            $codigoInformacaoComplementar = $this->getInformacaoComplementarLancamento()->getSequencial();
        }

        $linha = $ordemLinha = null;
        if ($this->getLinha() instanceof Linha) {
            $linha = $this->getLinha()->getLinha();
            $ordemLinha = $this->getLinha()->getOrdem();
        }

        return array(
            'sequencial' => $this->getSequencial(),
            'valor' => $this->getValor(),
            'informacaoComplementar' => $this->getInformacaoComplementar(),
            'relatorio' => $this->getRelatorio() instanceof Relatorio ? $this->getRelatorio()->getSequencial() : null,
            'linha' => $linha,
            'ordemLinha' => $ordemLinha,
            'sigla' => $this->getSigla(),
            'padrao' => $this->isPadrao(),
            'linhaInformacaoComplementarContaCorrente' => $codigoInformacaoComplementar,
        );
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
     * @return int
     */
    public function getInformacaoComplementar()
    {
        return (int)$this->informacaoComplementar;
    }

    /**
     * @param int $informacaoComplementar
     */
    public function setInformacaoComplementar($informacaoComplementar)
    {
        $this->informacaoComplementar = (int)$informacaoComplementar;
    }

    /**
     * @return Relatorio
     */
    public function getRelatorio()
    {
        return $this->relatorio;
    }

    /**
     * @param Relatorio $relatorio
     */
    public function setRelatorio(Relatorio $relatorio)
    {
        $this->relatorio = $relatorio;
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
    public function getSigla()
    {
        return (string)$this->sigla;
    }

    /**
     * @param string $sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = (string)$sigla;
    }

    /**
     * @return bool
     */
    public function isPadrao()
    {
        return (bool)$this->padrao;
    }

    /**
     * @param bool $padrao
     */
    public function setPadrao($padrao)
    {
        $this->padrao = (bool)$padrao;
    }

    /**
     * @return InformacaoComplementarLancamento
     */
    public function getInformacaoComplementarLancamento()
    {
        return $this->informacaoComplementarLancamento;
    }

    /**
     * @param InformacaoComplementarLancamento $informacaoComplementarLancamento
     */
    public function setInformacaoComplementarLancamento(
        InformacaoComplementarLancamento $informacaoComplementarLancamento
    ) {
        $this->informacaoComplementarLancamento = $informacaoComplementarLancamento;
    }
}
