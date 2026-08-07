<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class Imovel extends Entity
{
    const TIPO_IMOVEL_CODIGO    = 'TIPOIMOVELCODIGO';
    const TIPO_IMOVEL_DESCRICAO = 'TIPOIMOVELDESCRICAO';
    const MATRICULA             = 'MATRICULA';
    const EXERCICIO             = 'EXERCICIO';
    const NOTIFICACAO           = 'NOTIFICACAO';
    const ZONA_ENTREGA          = 'ZONAENTREGA';
    const ZONA_FISCAL_LOTE      = 'ZONAFISCALLOTE';
    const SETOR_FISCAL          = 'SETORFISCAL';
    const SETOR_CARTOGRAFICA    = 'SETORCARTOGRAFICA';
    const QUADRACARTOGRAFICA    = 'QUADRACARTOGRAFICA';
    const LOTE_CARTOGRAFICA     = 'LOTECARTOGRAFICA';
    const SUBLOTE               = 'SUBLOTE';

    private $tipoImovelCodigo = '';

    private $tipoImovelDescricao = '';

    private $matricula = '';

    private $exercicio = '';

    private $notificacao = '';

    private $zonaEntrega = 0;

    private $zonaFiscalLote = '';

    private $setorFiscal = '';

    private $setorCartografica = '';

    private $quadraCartografica = '';

    private $loteCartografica = '';

    private $sublote = '';

    public function setTipoImovelCodigo($tipoImovelCodigo)
    {
        $this->tipoImovelCodigo = $tipoImovelCodigo;
    }

    public function setTipoImovelDescricao($tipoImovelDescricao)
    {
        $this->tipoImovelDescricao = $tipoImovelDescricao;
    }

    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
    }

    public function setNotificacao($notificacao)
    {
        $this->notificacao = $notificacao;
    }

    public function setZonaEntrega($zonaEntrega)
    {
        $this->zonaEntrega = $zonaEntrega;
    }

    public function setZonaFiscalLote($zonaFiscalLote)
    {
        $this->zonaFiscalLote = $zonaFiscalLote;
    }

    public function setSetorFiscal($setorFiscal)
    {
        $this->setorFiscal = $setorFiscal;
    }

    public function setSetorCartografica($setorCartografica)
    {
        $this->setorCartografica = $setorCartografica;
    }

    public function setQuadraCartografica($quadraCartografica)
    {
        $this->quadraCartografica = $quadraCartografica;
    }

    public function setLoteCartografica($loteCartografica)
    {
        $this->loteCartografica = $loteCartografica;
    }

    public function setSublote($sublote)
    {
        $this->sublote = $sublote;
    }

    public function getTipoImovelCodigo()
    {
        return $this->tipoImovelCodigo;
    }

    public function getTipoImovelDescricao()
    {
        return $this->tipoImovelDescricao;
    }

    public function getMatricula()
    {
        return $this->matricula;
    }

    public function getExercicio()
    {
        return $this->exercicio;
    }

    public function getNotificacao()
    {
        return $this->notificacao;
    }

    public function getZonaEntrega()
    {
        return $this->zonaEntrega;
    }

    public function getZonaFiscalLote()
    {
        return $this->zonaFiscalLote;
    }

    public function getSetorFiscal()
    {
        return $this->setorFiscal;
    }

    public function getSetorCartografica()
    {
        return $this->setorCartografica;
    }

    public function getQuadraCartografica()
    {
        return $this->quadraCartografica;
    }

    public function getLoteCartografica()
    {
        return $this->loteCartografica;
    }

    public function getSublote()
    {
        return $this->sublote;
    }
}
