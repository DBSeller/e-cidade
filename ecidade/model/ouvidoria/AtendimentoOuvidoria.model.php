<?php

require_once(modification(ECIDADE_PATH . 'model/ouvidoria/AtendimentoProcessoEletronico.model.php'));

class AtendimentoOuvidoria
{


    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $situacao;
    /**
     * @var integer
     */
    private $tipoProcessoId;
    /**
     * @var integer
     */
    private $formaReclamacaoId;
    /**
     * @var integer
     */
    private $tipoIdentificacao;
    /**
     * @var integer
     */
    private $usuarioId;
    /**
     * @var integer
     */
    private $departamentoId;
    /**
     * @var integer
     */
    private $instituicaoId;
    /**
     * @var integer
     */
    private $numero;
    /**
     * @var integer
     */
    private $anoUsuario;
    /**
     * @var null|String
     */
    private $data;
    /**
     * @var null|String
     */
    private $hora;
    /**
     * @var String
     */
    private $requerente;

    /**
     * @var String
     */
    private $solicitacao;

    /**
     * @var String
     */
    private $executado;

    /**
     * ov01_sequencial
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param int $situacao
     * @return $this
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoProcessoId()
    {
        return $this->tipoProcessoId;
    }

    /**
     * @param int $tipoProcessoId
     * @return $this
     */
    public function setTipoProcessoId($tipoProcessoId)
    {
        $this->tipoProcessoId = $tipoProcessoId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFormaReclamacaoId()
    {
        return $this->formaReclamacaoId;
    }

    /**
     * @param int $formaReclamacaoId
     * @return $this
     */
    public function setFormaReclamacaoId($formaReclamacaoId)
    {
        $this->formaReclamacaoId = $formaReclamacaoId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoIdentificacao()
    {
        return $this->tipoIdentificacao;
    }

    /**
     * @param int $tipoIdentificacao
     * @return $this
     */
    public function setTipoIdentificacao($tipoIdentificacao)
    {
        $this->tipoIdentificacao = $tipoIdentificacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * @param int $usuarioId
     * @return $this
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartamentoId()
    {
        return $this->departamentoId;
    }

    /**
     * @param int $departamentoId
     * @return $this
     */
    public function setDepartamentoId($departamentoId)
    {
        $this->departamentoId = $departamentoId;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstituicaoId()
    {
        return $this->instituicaoId;
    }

    /**
     * @param int $instituicaoId
     * @return $this
     */
    public function setInstituicaoId($instituicaoId)
    {
        $this->instituicaoId = $instituicaoId;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     * @return $this
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @return int
     */
    public function getAnoUsuario()
    {
        return $this->anoUsuario;
    }

    /**
     * @param int $anoUsuario
     * @return $this
     */
    public function setAnoUsuario($anoUsuario)
    {
        $this->anoUsuario = $anoUsuario;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param String|null $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return String|null
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param String|null $hora
     * @return $this
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
        return $this;
    }

    /**
     * @return String
     */
    public function getRequerente()
    {
        return $this->requerente;
    }

    /**
     * @param String $requerente
     * @return $this
     */
    public function setRequerente($requerente)
    {
        $this->requerente = $requerente;
        return $this;
    }

    /**
     * @return String
     */
    public function getSolicitacao()
    {
        return $this->solicitacao;
    }

    /**
     * @param String $solicitacao
     * @return $this
     */
    public function setSolicitacao($solicitacao)
    {
        $this->solicitacao = $solicitacao;
        return $this;
    }

    /**
     * @return String
     */
    public function getExecutado()
    {
        return $this->executado;
    }

    /**
     * @param String $executado
     * @return $this
     */
    public function setExecutado($executado)
    {
        $this->executado = $executado;
        return $this;
    }

    /**
     * @param $id
     * @return AtendimentoOuvidoria|false
     */
    public static function find($id)
    {
        if (empty($id)) {
            return false;
        }

        $ouvidoriaAtendimento = new cl_ouvidoriaatendimento();
        $sql = $ouvidoriaAtendimento->sql_query_file($id);
        $rs = $ouvidoriaAtendimento->sql_record($sql);

        if ($ouvidoriaAtendimento->numrows < 1) {
            return false;
        }

        $objOuvidoriaAtendimento = pg_fetch_object($rs);
        return self::fromDao($objOuvidoriaAtendimento);
    }

    /**
     * @return AtendimentoProcessoEletronico|false
     */
    public function atendimentoProcessoEletronico()
    {
        return AtendimentoProcessoEletronico::findByAtendimento($this->getId());
    }

    /**
     * @param $numero
     * @param $ano
     * @param $instituicao
     * @return AtendimentoOuvidoria|false
     */
    public static function findByNumeroAnoInstituicao($numero, $ano, $instituicao)
    {

        $ouvidoriaAtendimento = new cl_ouvidoriaatendimento();
        $dbwhere = "ov01_numero = {$numero} and ov01_anousu = {$ano} and ov01_instit = {$instituicao}";
        $sql = $ouvidoriaAtendimento->sql_query_file(null, "*", null,$dbwhere);
        $rs = $ouvidoriaAtendimento->sql_record($sql);
        if ($ouvidoriaAtendimento->numrows < 1) {
            return false;
        }

        $objOuvidoriaAtendimento = pg_fetch_object($rs);
        return self::fromDao($objOuvidoriaAtendimento);
    }

    /**
     * @param $resultDao
     * @return AtendimentoOuvidoria
     */
    private static function fromDao($resultDao)
    {
        $atendimento = new AtendimentoOuvidoria();

        return $atendimento
            ->setAnoUsuario($resultDao->ov01_anousu)
            ->setHora($resultDao->ov01_horaatend)
            ->setData($resultDao->ov01_dataatend)
            ->setDepartamentoId($resultDao->ov01_depart)
            ->setExecutado($resultDao->ov01_executado)
            ->setFormaReclamacaoId($resultDao->ov01_formareclamacao)
            ->setInstituicaoId($resultDao->ov01_instit)
            ->setNumero($resultDao->ov01_numero)
            ->setRequerente($resultDao->ov01_requerente)
            ->setSituacao($resultDao->ov01_situacaoouvidoriaatendimento)
            ->setTipoIdentificacao($resultDao->ov01_tipoidentificacao)
            ->setId($resultDao->ov01_sequencial)
            ->setUsuarioId($resultDao->ov01_usuario)
            ->setSolicitacao($resultDao->ov01_solicitacao)
            ->setTipoProcessoId($resultDao->ov01_tipoprocesso)
            ->setId($resultDao->ov01_sequencial);
    }


}
