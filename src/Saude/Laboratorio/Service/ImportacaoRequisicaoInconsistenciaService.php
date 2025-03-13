<?php


namespace ECidade\Saude\Laboratorio\Service;

use ECidade\Saude\Laboratorio\Model\ImportacaoRequisicaoInconsistencia;
use JSON;
use RequisicaoLaboratorial;
use stdClass;
use Exception;
use ECidade\Saude\Laboratorio\Repository\ImportacaoRequisicaoInconsistenciaRepository;
use ECidade\Saude\Laboratorio\Integracao\Luckmann\Builder\ImportacaoInconsistencia;

class ImportacaoRequisicaoInconsistenciaService
{
    /**
     * @var ImportacaoRequisicaoInconsistenciaRepository
     */
    private $repository;

    /**
     * @var ImportacaoInconsistencia
     */
    private $importacaoInconsistencia;

    /**
     * @var JSON
     */
    private $jsonObject;

    /**
     * ImportacaoRequisicaoInconsistenciaService constructor.
     * @param ImportacaoRequisicaoInconsistenciaRepository $repository
     */
    public function __construct(ImportacaoRequisicaoInconsistenciaRepository $repository)
    {
        $this->repository = $repository;
        $this->importacaoInconsistencia = ImportacaoInconsistencia::getInstance();
        $this->jsonObject = JSON::create();
    }

    /**
     * @param stdClass $parametros
     * @return ImportacaoRequisicaoInconsistencia
     * @throws Exception
     */
    public function save(stdClass $parametros)
    {
        if (empty($parametros->requisicao)) {
            throw new Exception('É necessário informar a Requisição.');
        }

        $id = !empty($parametros->id) ? $parametros->id : null;
        $inconsistenciaImportacao = new ImportacaoRequisicaoInconsistencia($id);

        if (empty($id)) {
            $jsonObject = JSON::create();
            $jsonString = $jsonObject->stringify($parametros->inconsistencias);
            $jsonString = pg_escape_string($jsonString);
            $inconsistenciaImportacao->setIdRequisicao($parametros->requisicao);
            $inconsistenciaImportacao->setInconsistencias($jsonString);
        }

        try {
            $this->repository->save($inconsistenciaImportacao);
        } catch (Exception $e) {
            throw new Exception('Não foi possível vincular as Inconsistências a Requisição.');
        }

        return $inconsistenciaImportacao;
    }

    /**
     * @param stdClass $parametros
     * @throws Exception
     */
    public function delete(stdClass $parametros)
    {
        if (empty($parametros->id) && empty($parametros->requisicao)) {
            throw new Exception(
                'É necessário informar o ID da Inconsistência da Requisição '
                . ' ou o ID da Requisição ao qual a Inconsistência está vinculada.'
            );
        }

        $flagInconsistenciaRequisicao = true;
        $inconsistenciaRequisicao = null;

        try {
            $inconsistenciaRequisicao = $this->repository->scopeRequisicao($parametros->requisicao)->get();
        } catch (Exception $e) {
            $flagInconsistenciaRequisicao = false;
        }

        if ($flagInconsistenciaRequisicao) {
            try {
                $this->repository->delete(
                    ImportacaoRequisicaoInconsistencia::fromState(
                        $inconsistenciaRequisicao
                    )
                );
            } catch (Exception $e) {
                throw new Exception('Não foi possível excluir as Inconsistências da Requisição.');
            }
        }
    }

    /**
     * @param $idRequisicao
     * @return array
     * @throws Exception
     */
    public function getInconsistenciasByRequisicao($idRequisicao)
    {
        try {
            return $this->repository->scopeRequisicao($idRequisicao)->get();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $idLaboratorio
     * @param null $idSetor
     * @return array
     * @throws Exception
     */
    public function getInconsistenciasJsonByLaboratorioSetor($idLaboratorio, $idSetor = null)
    {
        try {
            $rsInconsistencias = $this->repository->getInconsistenciasByLaboratorioSetor($idLaboratorio, $idSetor);
            $inconsistencias = array();

            /**
             * Usa a requisição como chave do array para evitar repetições
             */
            foreach ($rsInconsistencias as $inconsistencia) {
                $inconsistencias[$inconsistencia['la21_i_requisicao']] = $inconsistencia;
            }

            return $this->filterInconsistenciasJSONByLaboratorioSetor(
                $inconsistencias,
                $idLaboratorio,
                $idSetor
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $inconsistencias array
     * @param $idLaboratorio int
     * @param null $idSetor int
     * @return array
     */
    private function filterInconsistenciasJSONByLaboratorioSetor($inconsistencias, $idLaboratorio, $idSetor = null)
    {
        $jsonObject = JSON::create();
        $filteredJson = $dados = array();

        foreach ($inconsistencias as $inconsistencia) {
            $jsonInconsistencia = $jsonObject->parse(
                $inconsistencia['la64_inconsistencias'],
                JSON::UTF8_DECODE,
                true,
                true
            );

            foreach ($jsonInconsistencia as $idRequisicao => $requisicao) {
                foreach ($requisicao['exames'] as $idExame => $exame) {
                    if ($exame['codigoLaboratorio'] != $idLaboratorio) {
                        continue;
                    }

                    if (!empty($idSetor) && $exame['codigoSetor'] != $idSetor) {
                        continue;
                    }

                    if (empty($filteredJson[$idRequisicao])) {
                        $filteredJson[$idRequisicao] = array();
                        $filteredJson[$idRequisicao]['requisicao'] = $idRequisicao;
                        $filteredJson[$idRequisicao]['exames'] = array();
                    }

                    $filteredJson[$idRequisicao]['exames'][$idExame] = $exame;
                }
                $dados[] = $filteredJson;
            }
        }

        return $dados;
    }
}
