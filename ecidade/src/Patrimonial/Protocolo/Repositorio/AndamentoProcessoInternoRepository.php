<?php

namespace ECidade\Patrimonial\Protocolo\Repositorio;

use ECidade\Patrimonial\Protocolo\Modelo\AndamentoProcessoInterno;
use Exception;

class AndamentoProcessoInternoRepository
{

    /**
     * @var \cl_procandamint
     */
    private $dao;

    /**
     * AndamentoProcessoInternoRepository constructor.
     * @param  \cl_procandamint $dao
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * save
     *
     * @param  AndamentoProcessoInterno $andamentoInterno
     * @return AndamentoProcessoInterno
     */
    public function save($andamentoInterno)
    {
        $this->dao->p78_sequencial = $andamentoInterno->getId();
        $this->dao->p78_codandam = $andamentoInterno->getIdAndamento();
        $this->dao->p78_data = $andamentoInterno->getData();
        $this->dao->p78_hora = $andamentoInterno->getHora();
        $this->dao->p78_usuario = $andamentoInterno->getIdUsuario();
        $this->dao->p78_despacho = $andamentoInterno->getDespacho();
        $this->dao->p78_publico = $andamentoInterno->isPublico() ? 't' : 'f';
        $this->dao->p78_transint = $andamentoInterno->isTransitoInterno() ? 't' : 'f';
        $this->dao->p78_tipodespacho = $andamentoInterno->getIdTipoDespacho();

        if (!$andamentoInterno->getId()) {
            $this->dao->incluir(null, true);
        } else {
            $this->dao->alterar($andamentoInterno->getId());
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o Andamento Interno.\nContate o suporte.");
        }

        $andamentoInterno->setId($this->dao->p78_sequencial);

        return $andamentoInterno;
    }
}
