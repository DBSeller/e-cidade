<?php


namespace ECidade\Saude\Laboratorio\Repository;

use Exception;
use ECidade\Saude\Laboratorio\Model\ImportacaoRequisicaoInconsistencia;
use RequisicaoLaboratorial;

class ImportacaoRequisicaoInconsistenciaRepository
{
    /**
     * @var \cl_lab_importacaorequisicaoinconsistencia
     */
    private $dao;

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * ImportacaoRequisicaoInconsistenciaRepository constructor.
     * @param $dao
     */
    public function __construct(\cl_lab_importacaorequisicaoinconsistencia $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param array $columns
     * @return ImportacaoRequisicaoInconsistencia
     * @throws Exception
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query_file($id, implode(', ', $columns));
        $postgresObject = db_query($sql);

        if (pg_num_rows($postgresObject) === 0) {
            throw new Exception('Não foi possível encontrar as Inconsistências.');
        }

        $rs = pg_fetch_assoc($postgresObject);

        return ImportacaoRequisicaoInconsistencia::fromState($rs);
    }

    /**
     * @param $inconsistenciaRequisicao ImportacaoRequisicaoInconsistencia
     * @return mixed
     * @throws Exception
     */
    public function save(ImportacaoRequisicaoInconsistencia $inconsistenciaRequisicao)
    {
        if (empty($inconsistenciaRequisicao)) {
            return null;
        }

        $this->dao->la64_sequencial = $inconsistenciaRequisicao->getId();
        $this->dao->la64_requisicao = $inconsistenciaRequisicao->getIdRequisicao();
        $this->dao->la64_inconsistencias = $inconsistenciaRequisicao->getInconsistencias();

        $this->dao->la64_sequencial ?
            $this->dao->alterar($inconsistenciaRequisicao->getId()) :
            $this->dao->incluir();

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte.");
        }

        return $inconsistenciaRequisicao;
    }

    /**
     * @param ImportacaoRequisicaoInconsistencia $inconsistenciaRequisicao
     * @return bool
     * @throws Exception
     */
    public function delete(ImportacaoRequisicaoInconsistencia $inconsistenciaRequisicao = null)
    {
        $id = $inconsistenciaRequisicao instanceof ImportacaoRequisicaoInconsistencia ?
            $inconsistenciaRequisicao->getId() :
            null;

        $this->dao->excluir($id, implode(' AND ', $this->scopes));

        if ($this->dao->erro_status === '0') {
            throw new Exception('Não foi possível excluir o vínculo de Inconsistência com a Requisição');
        }

        return true;
    }

    /**
     * @param $idRequisicao
     * @param string $operator
     * @return $this
     */
    public function scopeRequisicao($idRequisicao, $operator = '=')
    {
        $this->scopes['requisicao'] = "la64_requisicao {$operator} {$idRequisicao}";
        return $this;
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
            throw new Exception('Não foi possível buscar as Inconsistências.');
        }

        $inconsistencias = array();
        while ($inconsistencia = pg_fetch_assoc($postgresObject)) {
            $inconsistencias[] = $inconsistencia;
        }

        return $inconsistencias;
    }

    /**
     * @param $idLaboratorio int
     * @param null $idSetor int
     * @return array
     * @throws Exception
     */
    public function getInconsistenciasByLaboratorioSetor($idLaboratorio, $idSetor = null)
    {
        $sql = $this->dao->sql_query_file_inconsistencia_laboratorio_setor($idLaboratorio, $idSetor);
        $postgresObject = db_query($sql);

        if (pg_num_rows($postgresObject) === 0) {
            $messageSetor = '';
            if (!empty($idSetor)) {
                $messageSetor = ' Setor '. $idSetor;
            }

            throw new Exception(
                'Não foram encontradas requisições com Inconsistências com o filtro informado.\n'
                . 'Laboratório ' . $idLaboratorio . $messageSetor
            );
        }

        $inconsistencias = array();
        while ($inconsistencia = pg_fetch_assoc($postgresObject)) {
            $inconsistencias[] = $inconsistencia;
        }

        return $inconsistencias;
    }
}
