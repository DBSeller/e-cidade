<?php

class ServidorDeficiente
{
    /**
     * @table rhdeficiente
     */

    const AUDITIVA = 1;
    const FISICA = 2;
    const INTELECTUAL = 3;
    const MENTAL = 4;
    const VISUAL = 5;

    /**
     * @column rh253_sequencial
     * @var integer
     */
    protected $id;

    /**
     * @column rh253_matricula
     * @var integer
     */
    protected $matricula;

    /**
     * @column rh253_fisica
     * @var boolean
     */
    protected $fisica;

    /**
     * @column rh253_instit
     * @var integer
     */
    protected $instituicao;

    /**
     * @column rh253_visual
     * @var boolean
     */
    protected $visual;

    /**
     * @column rh253_auditiva
     * @var boolean
     */
    protected $auditiva;

    /**
     * @column rh253_mental
     * @var boolean
     */
    protected $mental;

    /**
     * @column rh253_intelectual
     * @var boolean
     */
    protected $intelectual;

    /**
     * @column rh253_reabilitado
     * @var boolean
     */
    protected $reabilitado;

    /**
     * @column rh253_cota
     * @var boolean
     */
    protected $cota;

    /**
     * @column rh253_observacao
     * @var string
     */
    protected $observacao;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return bool
     */
    public function isFisica()
    {
        return $this->fisica;
    }

    /**
     * @param bool $fisica
     */
    public function setFisica($fisica)
    {
        $this->fisica = $fisica;
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
     * @return bool
     */
    public function isVisual()
    {
        return $this->visual;
    }

    /**
     * @param bool $visual
     */
    public function setVisual($visual)
    {
        $this->visual = $visual;
    }

    /**
     * @return bool
     */
    public function isAuditiva()
    {
        return $this->auditiva;
    }

    /**
     * @param bool $auditiva
     */
    public function setAuditiva($auditiva)
    {
        $this->auditiva = $auditiva;
    }

    /**
     * @return bool
     */
    public function isMental()
    {
        return $this->mental;
    }

    /**
     * @param bool $mental
     */
    public function setMental($mental)
    {
        $this->mental = $mental;
    }

    /**
     * @return bool
     */
    public function isIntelectual()
    {
        return $this->intelectual;
    }

    /**
     * @param bool $intelectual
     */
    public function setIntelectual($intelectual)
    {
        $this->intelectual = $intelectual;
    }

    /**
     * @return bool
     */
    public function isReabilitado()
    {
        return $this->reabilitado;
    }

    /**
     * @param bool $reabilitado
     */
    public function setReabilitado($reabilitado)
    {
        $this->reabilitado = $reabilitado;
    }

    /**
     * @return bool
     */
    public function isCota()
    {
        return $this->cota;
    }

    /**
     * @param bool $cota
     */
    public function setCota($cota)
    {
        $this->cota = $cota;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }


    public static function findByMatricula($matricula)
    {
        $deficienteDao = new cl_rhdeficiente();
        $dbwhere = "rh253_matricula = {$matricula}";
        $sql = $deficienteDao->sql_query_file(null, "*", null, $dbwhere);
        $rs = pg_query($sql);
        $servidorDeficiente = pg_fetch_object($rs);
        if (empty($servidorDeficiente)) {
            return false;
        }
        return self::fromDao($servidorDeficiente);
    }


    public static function fromDao(stdClass $servidorDeficiente)
    {
        $servidorDeficienteModel = new self();
        $servidorDeficienteModel->setId($servidorDeficiente->rh253_sequencial);
        $servidorDeficienteModel->setMatricula($servidorDeficiente->rh253_matricula);
        $servidorDeficienteModel->setFisica($servidorDeficiente->rh253_fisica);
        $servidorDeficienteModel->setInstituicao($servidorDeficiente->rh253_instit);
        $servidorDeficienteModel->setVisual($servidorDeficiente->rh253_visual);
        $servidorDeficienteModel->setAuditiva($servidorDeficiente->rh253_auditiva);
        $servidorDeficienteModel->setMental($servidorDeficiente->rh253_mental);
        $servidorDeficienteModel->setIntelectual($servidorDeficiente->rh253_intelectual);
        $servidorDeficienteModel->setReabilitado($servidorDeficiente->rh253_reabilitado);
        $servidorDeficienteModel->setCota($servidorDeficiente->rh253_cota);
        $servidorDeficienteModel->setObservacao($servidorDeficiente->rh253_observacao);
        return $servidorDeficienteModel;
    }


    public function save()
    {
        /**
         * Verifica se existe alguma transação ativa
         */
        if (!db_utils::inTransaction()) {
            throw new Exception("nenhuma transação encontrada!");
        }

        $deficienteDao = new cl_rhdeficiente();
        $deficienteDao->rh253_sequencial = $this->getId();
        $deficienteDao->rh253_matricula = $this->getMatricula();
        $deficienteDao->rh253_fisica = $this->isFisica();
        $deficienteDao->rh253_instit = $this->getInstituicao();
        $deficienteDao->rh253_visual = $this->isVisual();
        $deficienteDao->rh253_auditiva = $this->isAuditiva();
        $deficienteDao->rh253_mental = $this->isMental();
        $deficienteDao->rh253_intelectual = $this->isIntelectual();
        $deficienteDao->rh253_reabilitado = $this->isReabilitado();
        $deficienteDao->rh253_cota = $this->isCota();
        $deficienteDao->rh253_observacao = $this->getObservacao();

        if (empty($this->getId())) {
            $deficienteDao->incluir();
            if ($deficienteDao->erro_status == "0") {
                throw new \Exception($deficienteDao->erro_msg);
            }
        } else {
            $deficienteDao->alterar($this->getId());
            if ($deficienteDao->erro_status == "0") {
                throw new \Exception($deficienteDao->erro_msg);
            }
        }
    }
}
