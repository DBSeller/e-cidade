<?php

namespace ECidade\Patrimonial\Protocolo\Repositorio;

use ECidade\Patrimonial\Protocolo\Modelo\TipoDocumentoProcesso;
use Exception;

class TipoDocumentoProcessoRepository
{

    /**
     * Códigos inseridos pelas migrations da DBSeller.
     * Não devem ser excluídos.
     * @var array
     */
    const CODIGOS_TIPOS_DOCUMENTOS_DB = array(
        '1' => 'Processo',
        '2' => 'Memorando',
        '3' => 'Ofício',
        '4' => 'Decreto'
    );

    /**
     * @var \cl_prottipodocumentoprocesso
     */
    private $dao;

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * TipoDocumentoProcessoRepository constructor.
     * @param  \cl_prottipodocumentoprocesso $dao
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param  int $id
     * @param  array $columns
     * @return TipoDocumentoProcesso
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query_file($id, implode(', ', $columns));
        $postgresObject = db_query($sql);

        if (!$postgresObject) {
            throw new Exception('Não foi possível encontrar o Tipo de Documento de Processo.');
        }

        if (pg_num_rows($postgresObject) === 0) {
            return false;
        }

        $rs = pg_fetch_assoc($postgresObject);

        return TipoDocumentoProcesso::fromState($rs);
    }

    /**
     * @return array TipoDocumentoProcesso
     */
    public function getAll()
    {
        $sql = $this->dao->sql_query_file(null, '*', 'p91_sequencial');
        $postgresObject = db_query($sql);

        $tiposDocumentoProcesso = array();

        while ($row = pg_fetch_assoc($postgresObject)) {
            $tiposDocumentoProcesso[] = TipoDocumentoProcesso::fromState($row);
        }

        return $tiposDocumentoProcesso;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $sql = $this->dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $postgresObject = db_query($sql);

        if (pg_num_rows($postgresObject) === 0) {
            throw new Exception('Não foi possível buscar os Tipos de Documento de Processo.');
        }

        $tiposDocumentoProcesso = array();
        while ($tipo = pg_fetch_assoc($postgresObject)) {
            $tiposDocumentoProcesso[] = $tipo;
        }

        return $tiposDocumentoProcesso;
    }

    /**
     * @param  TipoDocumentoProcesso $tipoDocumentoProcesso
     * @return TipoDocumentoProcesso
     */
    public function save($tipoDocumentoProcesso)
    {
        if (array_key_exists($tipoDocumentoProcesso->getSequencial(), self::CODIGOS_TIPOS_DOCUMENTOS_DB)) {
            throw new Exception('Não pode deletar os tipos de documentos cujos códigos são: 1, 2, 3, 4');
        }

        if ($this->existeSigla($tipoDocumentoProcesso->getSigla()) && empty($tipoDocumentoProcesso->getSequencial())) {
            throw new Exception("Sigla {$tipoDocumentoProcesso->getSigla()} já existe.");
        }

        if (empty($tipoDocumentoProcesso)) {
            return null;
        }

        $this->dao->p91_descricao = $tipoDocumentoProcesso->getDescricao();
        $this->dao->p91_sigla = $tipoDocumentoProcesso->getSigla();
        $tipoDocumentoProcesso->getSequencial() ?
            $this->dao->alterar($tipoDocumentoProcesso->getSequencial()) :
            $this->dao->incluir();

        if ($this->dao->erro_status == 0) {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte." . pg_last_error());
        }

        $tipoDocumentoProcesso->setSequencial($this->dao->p91_sequencial);

        return $tipoDocumentoProcesso;
    }

    /**
     * @param  int $id
     * @return boolean
     */
    public function remove($id)
    {
        $tipoDocumentoProcesso = $this->find($id, array('p91_sequencial'));
        if (array_key_exists($id, self::CODIGOS_TIPOS_DOCUMENTOS_DB)) {
            throw new Exception('Não pode deletar os tipos de documentos cujos códigos são: 1, 2, 3, 4');
        }

        if (empty($tipoDocumentoProcesso)) {
            throw new Exception('Não foi possível excluir o Tipo de Documento de Processo.');
        }

        $this->dao->excluir($id);

        if ($this->dao->erro_status === '0') {
            return false;
        }

        return true;
    }

    /**
     * @param string $sigla
     * @param string $operator
     * @return $this
     */
    public function scopeSigla($sigla, $operator = '=')
    {
        $this->scopes['sigla'] = "p91_sigla {$operator} '{$sigla}'";
        return $this;
    }

    /**
     * @param  String $sigla
     * @return boolean
     */
    public function existeSigla($sigla)
    {
        try {
            $this->scopeSigla($sigla);
            $this->get();
        } catch (Exception $e) {
            $this->removeScope('sigla');
            return false;
        }

        $this->removeScope('sigla');

        return true;
    }

    /**
     * @param $key
     * @return TipoDocumentoProcessoRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}
