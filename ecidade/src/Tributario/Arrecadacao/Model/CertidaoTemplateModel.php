<?php


namespace ECidade\Tributario\Arrecadacao\Model;

/**
 * Class CertidaoTemplateModel
 * @package ECidade\Tributario\Arrecadacao\Model
 */
class CertidaoTemplateModel
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $idUsuario;
    /**
     * @var char
     */
    private $tipo;
    /**
     * @var date
     */
    private $data;
    /**
     * @var char
     */
    private $hora;
    /**
     * @var char
     */
    private $ip;
    /**
     * @var string
     */
    private $historico;
    /**
     * @var bool
     */
    private $web;
    /**
     * @var int
     */
    private $codigoProcesso;
    /**
     * @var int
     */
    private $exercicio;
    /**
     * @var char
     */
    private $codigoImpresso;
    /**
     * @var int
     */
    private $instituicao;
    /**
     * @var string
     */
    private $arquivo;
    /**
     * @var int
     */
    private $diasvalidade;
    /**
     * @var string
     */
    private $nomeServico;
    /**
     * @var string
     */
    private $resultadoWebservice;
    /**
     * @var timestamp
     */
    private $dataHoraConsulta;
 
    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param int $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * @return char
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param char $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return date
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param date $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return char
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param char $hora
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    /**
     * @return char
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param char $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param string $historico
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;
    }

    /**
     * @return bool
     */
    public function isWeb()
    {
        return $this->web;
    }

    /**
     * @param bool $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return int
     */
    public function getCodigoProcesso()
    {
        return $this->codigoProcesso;
    }

    /**
     * @param int $codidoProcesso
     */
    public function setCodigoProcesso($codigoProcesso)
    {
        $this->codigoProcesso = $codigoProcesso;
    }

    /**
     * @return int
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * @param int $exercicio
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
    }

    /**
     * @return char
     */
    public function getCodigoImpresso()
    {
        return $this->codigoImpresso;
    }

    /**
     * @param char $codigoImpresso
     */
    public function setCodigoImpresso($codigoImpresso)
    {
        $this->codigoImpresso = $codigoImpresso;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param int $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return string
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * @param string $arquivo
     */
    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    /**
     * @return int
     */
    public function getDiasvalidade()
    {
        return $this->diasvalidade;
    }

    /**
     * @param int $diasvalidade
     */
    public function setDiasvalidade($diasvalidade)
    {
        $this->diasvalidade = $diasvalidade;
    }

    /**
     * @return string
     */
    public function getNomeServico()
    {
        return $this->nomeServico;
    }

    /**
     * @param string $resultadoWebservice
     */
    public function setNomeServico($nomeServico)
    {
        $this->nomeServico = $nomeServico;
    }
    
    /**
     * @return string
     */
    public function getResultadoWebservice()
    {
        return $this->resultadoWebservice;
    }

    /**
     * @param string $resultadoWebservice
     */
    public function setResultadoWebservice($resultadoWebservice)
    {
        $this->resultadoWebservice = $resultadoWebservice;
    }

        /**
     * @return timestamp
     */
    public function getDataHoraConsulta()
    {
        return $this->dataHoraConsulta;
    }

    /**
     * @param timestamp $dataHoraConsulta
     */
    public function setDataHoraConsulta($dataHoraConsulta)
    {
        $this->dataHoraConsulta = $dataHoraConsulta;
    }
}
