<?php
namespace ECidade\Tributario\Arrecadacao\Entity;

use ECidade\Tributario\Library\Entity;

use \CgmBase;
use \endereco;

class Contribuinte extends Entity {

    const CGM = 'C';
    const MATRICULA = 'M';
    const INSCRICAO = 'I';

    /**
     * Contém o identificador do contribuinte
     * No caso de ser uma inscrição esse campo contem o NÚMERO DE INSCRIÇÃO
     * No caso de ser um CGM esse campo contém o NÚMERO DE CGM
     *
     * @var integer
     */
    protected $identificador;

    /**
     * Contém o cgm do contribuinte
     * mesmo que seja uma inscrição
     * essa também possui um CGMs
     *
     * @var CgmBase
     */
    protected $cgm;
    
    public function getIdentificador()
    {
        return $this->identificador;
    }
    
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;
    }

    public function getCgm()
    {
        return $this->cgm;
    }
    
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }
    
    public function setEndereco(endereco $endereco)
    {
        $this->endereco = $endereco;
    }

    public function getCpfCnpj()
    {
        if($this->cgm instanceof \CgmFisico) {
            return $this->cgm->getCpf();
        }

        if($this->cgm instanceof \CgmJuridico) {
            return $this->cgm->getCnpj();
        }
    }

    public function getTipo()
    {
        return self::CGM;
    }
}