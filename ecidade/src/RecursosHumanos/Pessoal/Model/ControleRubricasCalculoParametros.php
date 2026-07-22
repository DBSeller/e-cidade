<?php


namespace ECidade\RecursosHumanos\Pessoal\Model;

use DBCompetencia;
use Instituicao;
use Rubrica;
use Servidor;

class ControleRubricasCalculoParametros
{
    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var DBCompetencia
     */
    private $competencia;

    /**
     * @var Servidor
     */
    private $servidor;

    /**
     * @var Rubrica
     */
    private $rubrica;

    /**
     * @var float
     */
    private $quantidadeAdicionada = 0;

    /**
     * @var bool
     */
    private $isAlteracao = false;

    /**
     * @var string
     */
    private $tabela;

    /**
     * ControleHorasExtrasCalculoParametros constructor.
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @param Servidor $servidor
     * @param Rubrica $rubrica
     * @param float $quantidadeAdicionada
     * @param bool $isAlteracao
     * @param string $tabela
     */
    public function __construct(
        Instituicao $instituicao,
        DBCompetencia $competencia,
        Servidor $servidor,
        Rubrica $rubrica,
        $quantidadeAdicionada,
        $isAlteracao,
        $tabela
    ) {
        $this->instituicao = $instituicao;
        $this->competencia = $competencia;
        $this->servidor = $servidor;
        $this->rubrica = $rubrica;
        $this->quantidadeAdicionada = $quantidadeAdicionada;
        $this->isAlteracao = $isAlteracao;
        $this->tabela = $tabela;
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
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return DBCompetencia
     */
    public function getCompetencia()
    {
        return $this->competencia;
    }

    /**
     * @param DBCompetencia $competencia
     */
    public function setCompetencia($competencia)
    {
        $this->competencia = $competencia;
    }

    /**
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * @param Servidor $servidor
     */
    public function setServidor($servidor)
    {
        $this->servidor = $servidor;
    }

    /**
     * @return Rubrica
     */
    public function getRubrica()
    {
        return $this->rubrica;
    }

    /**
     * @param Rubrica $rubrica
     */
    public function setRubrica($rubrica)
    {
        $this->rubrica = $rubrica;
    }

    /**
     * @return float
     */
    public function getQuantidadeAdicionada()
    {
        return $this->quantidadeAdicionada;
    }

    /**
     * @param float $quantidadeAdicionada
     */
    public function setQuantidadeAdicionada($quantidadeAdicionada)
    {
        $this->quantidadeAdicionada = $quantidadeAdicionada;
    }

    /**
     * @return bool
     */
    public function isAlteracao()
    {
        return $this->isAlteracao;
    }

    /**
     * @param bool $isAlteracao
     */
    public function setIsAlteracao($isAlteracao)
    {
        $this->isAlteracao = $isAlteracao;
    }

    /**
     * @return string
     */
    public function getTabela()
    {
        return $this->tabela;
    }

    /**
     * @param string $tabela
     */
    public function setTabela($tabela)
    {
        $this->tabela = $tabela;
    }

    /**
     * @return int|null
     */
    public function getMatriculaServidor()
    {
        if (empty($this->servidor)) {
            return null;
        }
        return $this->servidor->getMatricula();
    }

    /**
     * @return string|null
     */
    public function getCodigoRubrica()
    {
        if (empty($this->rubrica)) {
            return null;
        }

        return $this->rubrica->getCodigo();
    }
}
