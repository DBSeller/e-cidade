<?php
namespace ECidade\RecursosHumanos\ESocial\Model;

use DBDate;

class ServidorAlteracao
{
    /**
     * @var \DBDate | null
     */
    private $dataS2205 = null;

    /**
     * @var bool
     */
    private $processamentoS2205 = false;

    /**
     * @var \DBDate | null
     */
    private $dataS2206 = null;

    /**
     * @var bool
     */
    private $processamentoS2206 = false;

    /**
     * @var \DBDate | null
     */
    private $dataS2306 = null;

    /**
     * @var bool
     */
    private $processamentoS2306 = false;

    /**
     * @var \DBDate | null
     */
    private $dataS2405 = null;

    /**
     * @var bool
     */
    private $processamentoS2405 = false;

    /**
     * @var \DBDate | null
     */
    private $dataS2416 = null;

    /**
     * @var bool
     */
    private $processamentoS2416 = false;

    /**
     * @var int
     */
    private $codigo;

    /**
     * @var int
     */
    private $matricula;

    public function __construct($matricula = null)
    {
        if (!empty($matricula)) {
            $this->setMatricula($matricula);
        }
    }

    public function save()
    {
        $dao = new \cl_servidoralteracao();
        $dao->eso38_sequencial = $this->getCodigo();
        $dao->eso38_matricula = $this->getMatricula();

        if (!empty($this->getDataS2205())) {
            $dao->eso38_s2205data = $this->getDataS2205()->getDate();
        } else {
            $dao->eso38_s2205data = null;
        }
        $dao->eso38_s2205processado = ($this->isProcessamentoS2205() === true) ? 't' : 'f';

        if (!empty($this->getDataS2206())) {
            $dao->eso38_s2206data = $this->getDataS2206()->getDate();
        } else {
            $dao->eso38_s2206data = null;
        }
        $dao->eso38_s2206processado = ($this->isProcessamentoS2206() === true) ? 't' : 'f';

        if (!empty($this->getDataS2306())) {
            $dao->eso38_s2306data = $this->getDataS2306()->getDate();
        } else {
            $dao->eso38_s2306data = null;
        }
        $dao->eso38_s2306processado = ($this->isProcessamentoS2306() === true) ? 't' : 'f';

        if (!empty($this->getDataS2405())) {
            $dao->eso38_s2405data = $this->getDataS2405()->getDate();
        } else {
            $dao->eso38_s2405data = null;
        }
        $dao->eso38_s2405processado = ($this->isProcessamentoS2405() === true) ? 't' : 'f';

        if (!empty($this->getDataS2416())) {
            $dao->eso38_s2416data = $this->getDataS2416()->getDate();
        } else {
            $dao->eso38_s2416data = null;
        }
        $dao->eso38_s2416processado = ($this->isProcessamentoS2416() === true) ? 't' : 'f';

        if (!empty($this->getCodigo())) {
            $dao->alterar($this->getCodigo());
        } else {
            $dao->incluir(null);
        }
        if ($dao->erro_status == "0") {
            throw new \BusinessException($dao->erro_msg);
        }
    }

    public function delete()
    {
        $dao = new \cl_servidoralteracao();
        $dao->eso38_sequencial = $this->getCodigo();
        $dao->excluir($this->getCodigo());
    }

    /**
     * @return \DBDate|null
     */
    public function getDataS2205()
    {
        return $this->dataS2205;
    }

    /**
     * @param \DBDate|null $dataS2205
     */
    public function setDataS2205($dataS2205)
    {
        $this->dataS2205 = $dataS2205;
    }

    /**
     * @return bool
     */
    public function isProcessamentoS2205()
    {
        return $this->processamentoS2205;
    }

    /**
     * @param bool $processamentoS2205
     */
    public function setProcessamentoS2205($processamentoS2205)
    {
        if ($processamentoS2205 == 't' || $processamentoS2205 === true) {
            $this->processamentoS2205 = true;
        } else {
            $this->processamentoS2205 = false;
        }
    }

    /**
     * @return \DBDate|null
     */
    public function getDataS2206()
    {
        return $this->dataS2206;
    }

    /**
     * @param \DBDate|null $dataS2206
     */
    public function setDataS2206($dataS2206)
    {
        $this->dataS2206 = $dataS2206;
    }

    /**
     * @return bool
     */
    public function isProcessamentoS2206()
    {
        return $this->processamentoS2206;
    }

    /**
     * @param bool $processamentoS2206
     */
    public function setProcessamentoS2206($processamentoS2206)
    {
        if ($processamentoS2206 == 't' || $processamentoS2206 === true) {
            $this->processamentoS2206 = true;
        } else {
            $this->processamentoS2206 = false;
        }
    }

    /**
     * @return \DBDate|null
     */
    public function getDataS2306()
    {
        return $this->dataS2306;
    }

    /**
     * @param \DBDate|null $dataS2306
     */
    public function setDataS2306($dataS2306)
    {
        $this->dataS2306 = $dataS2306;
    }

    /**
     * @return bool
     */
    public function isProcessamentoS2306()
    {
        return $this->processamentoS2306;
    }

    /**
     * @param bool $processamentoS2306
     */
    public function setProcessamentoS2306($processamentoS2306)
    {
        if ($processamentoS2306 == 't' || $processamentoS2306 === true) {
            $this->processamentoS2306 = true;
        } else {
            $this->processamentoS2306 = false;
        }
    }

    /**
     * @return \DBDate|null
     */
    public function getDataS2405()
    {
        return $this->dataS2405;
    }

    /**
     * @param \DBDate|null $dataS2405
     */
    public function setDataS2405($dataS2405)
    {
        $this->dataS2405 = $dataS2405;
    }

    /**
     * @return bool
     */
    public function isProcessamentoS2405()
    {
        return $this->processamentoS2405;
    }

    /**
     * @param bool $processamentoS2405
     */
    public function setProcessamentoS2405($processamentoS2405)
    {
        if ($processamentoS2405 == 't' || $processamentoS2405 ===  true) {
            $this->processamentoS2405 = true;
        } else {
            $this->processamentoS2405 = false;
        }
    }

    /**
     * @return \DBDate|null
     */
    public function getDataS2416()
    {
        return $this->dataS2416;
    }

    /**
     * @param \DBDate|null $dataS2416
     */
    public function setDataS2416($dataS2416)
    {
        $this->dataS2416 = $dataS2416;
    }

    /**
     * @return bool
     */
    public function isProcessamentoS2416()
    {
        return $this->processamentoS2416;
    }

    /**
     * @param bool $processamentoS2416
     */
    public function setProcessamentoS2416($processamentoS2416)
    {
        if ($processamentoS2416 == 't' || $processamentoS2416 === true) {
            $this->processamentoS2416 = true;
        } else {
            $this->processamentoS2416 = false;
        }
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
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

    public static function fromState(array $state)
    {
        $servidorAlteracao = new self();

        if (array_key_exists('eso38_sequencial', $state)) {
            $servidorAlteracao->setCodigo((int)$state['eso38_sequencial']);
        }

        if (array_key_exists('eso38_matricula', $state)) {
            $servidorAlteracao->setMatricula((int)$state['eso38_matricula']);
        }

        if (array_key_exists('eso38_s2205data', $state) && (!empty($state['eso38_s2205data']))) {
            $servidorAlteracao->setDataS2205(new DBDate($state['eso38_s2205data']));
        }
        if (array_key_exists('eso38_s2205processado', $state)) {
            $servidorAlteracao->setProcessamentoS2205($state['eso38_s2205processado']);
        }

        if (array_key_exists('eso38_s2206data', $state) && (!empty($state['eso38_s2206data']))) {
            $servidorAlteracao->setDataS2206(new DBDate($state['eso38_s2206data']));
        }

        if (array_key_exists('eso38_s2206processado', $state)) {
            $servidorAlteracao->setProcessamentoS2206($state['eso38_s2206processado']);
        }

        if (array_key_exists('eso38_s2306data', $state) && (!empty($state['eso38_s2306data']))) {
            $servidorAlteracao->setDataS2306(new DBDate($state['eso38_s2306data']));
        }

        if (array_key_exists('eso38_s2306processado', $state)) {
            $servidorAlteracao->setProcessamentoS2306($state['eso38_s2306processado']);
        }

        if (array_key_exists('eso38_s2405data', $state) && (!empty($state['eso38_s2405data']))) {
            $servidorAlteracao->setDataS2405(new DBDate($state['eso38_s2405data']));
        }

        if (array_key_exists('eso38_s2405processado', $state)) {
            $servidorAlteracao->setProcessamentoS2405($state['eso38_s2405processado']);
        }

        if (array_key_exists('eso38_s2416data', $state) && (!empty($state['eso38_s2416data']))) {
            $servidorAlteracao->setDataS2416(new DBDate($state['eso38_s2416data']));
        }

        if (array_key_exists('eso38_s2416processado', $state)) {
            $servidorAlteracao->setProcessamentoS2416($state['eso38_s2416processado']);
        }

        return $servidorAlteracao;
    }
}
