<?php

namespace ECidade\Tributario\Arrecadacao\Model;

/**
 * Class TaxaEspecifica
 * @package ECidade\Tributario\Arrecadacao\Model
 */
class TaxaEspecifica
{
    const CODIGO_HISTORICO = 11505;
    const DESCRICAO_TAXA_EXPEDIENTE = 'Taxa de Expediente';

    /**
     * @var string
     */
    private $codigoInflator;

    /**
     * @var int
     */
    private $codigoReceita;

    /**
     * @var int
     */
    private $codigoSubReceita;

    /**
     * @var \DateTime
     */
    private $dataCriacao;

    /**
     * @var string
     */
    private $descricaoSubReceita;

    /**
     * @var int
     */
    private $codigoInstituicao;

    /**
     * @var float
     */
    private $valorCalculadoInflator;

    /**
     * @var float
     */
    private $valorFixo;

    /**
     * Método criado para compatibilidade com o BaseClassRepository
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigoSubReceita;
    }

    /**
     * @return string
     */
    public function getCodigoInflator()
    {
        return $this->codigoInflator;
    }

    /**
     * @return int
     */
    public function getCodigoReceita()
    {
        return $this->codigoReceita;
    }

    /**
     * @return int
     */
    public function getCodigoSubReceita()
    {
        return $this->codigoSubReceita;
    }

    /**
     * @return \DateTime
     */
    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    /**
     * @return int
     */
    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    /**
     * @return float
     */
    public function getValorCalculadoInflator()
    {
        return $this->valorCalculadoInflator;
    }

    /**
     * @return float
     */
    public function getValorFixo()
    {
        return $this->valorFixo;
    }

    /**
     * @param string $codigoInflator
     */
    public function setCodigoInflator($codigoInflator)
    {
        $this->codigoInflator = $codigoInflator;
    }

    /**
     * @param int $codigoReceita
     */
    public function setCodigoReceita($codigoReceita)
    {
        $this->codigoReceita = $codigoReceita;
    }

    /**
     * @param int $codigoSubReceita
     */
    public function setCodigoSubReceita($codigoSubReceita)
    {
        $this->codigoSubReceita = $codigoSubReceita;
    }

    /**
     * @param \DateTime $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    /**
     * @param string $descricaoSubReceita
     */
    public function setDescricaoSubReceita($descricaoSubReceita)
    {
        $this->descricaoSubReceita = $descricaoSubReceita;
    }

    /**
     * @param int $codigoInstituicao
     */
    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
    }

    /**
     * @param float $valorCalculadoInflator
     */
    public function setValorCalculadoInflator($valorCalculadoInflator)
    {
        $this->valorCalculadoInflator = $valorCalculadoInflator;
    }

    /**
     * @param float $valorFixo
     */
    public function setValorFixo($valorFixo)
    {
        $this->valorFixo = $valorFixo;
    }
}
