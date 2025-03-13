<?php


namespace ECidade\Saude\Laboratorio\Model;

use ECidade\Saude\Laboratorio\Repository\ImportacaoRequisicaoInconsistenciaRepository;

class ImportacaoRequisicaoInconsistencia
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $idRequisicao;

    /**
     * @var string
     */
    private $inconsistencias;

    public function __construct($id = null)
    {
        if (!empty($id)) {
            $repository = new ImportacaoRequisicaoInconsistenciaRepository(
                new \cl_lab_importacaorequisicaoinconsistencia
            );

            $inconsistenciaRequisicao = $repository->find($id);

            $this->id = $inconsistenciaRequisicao->getId();
            $this->idRequisicao = $inconsistenciaRequisicao->getIdRequisicao();
            $this->inconsistencias = $inconsistenciaRequisicao->getInconsistencias();
        }
    }

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
    public function getIdRequisicao()
    {
        return $this->idRequisicao;
    }

    /**
     * @param int $idRequisicao
     */
    public function setIdRequisicao($idRequisicao)
    {
        $this->idRequisicao = $idRequisicao;
    }

    /**
     * @return string
     */
    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }

    /**
     * @param string $inconsistencias
     */
    public function setInconsistencias($inconsistencias)
    {
        $this->inconsistencias = $inconsistencias;
    }

    /**
     * @param $state
     * @return ImportacaoRequisicaoInconsistencia
     */
    public static function fromState($state)
    {
        $importacaoRequisicaoInconsistencia = new self;

        if (array_key_exists('la64_sequencial', $state)) {
            $importacaoRequisicaoInconsistencia->setId((int) $state['la64_sequencial']);
        }

        if (array_key_exists('la64_sequencial', $state)) {
            $importacaoRequisicaoInconsistencia->setIdRequisicao((int) $state['la64_requisicao']);
        }

        if (array_key_exists('la64_sequencial', $state)) {
            $importacaoRequisicaoInconsistencia->setInconsistencias($state['la64_inconsistencias']);
        }

        return $importacaoRequisicaoInconsistencia;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'la64_sequencial' => $this->getId(),
            'la64_requisicao' => $this->getIdRequisicao(),
            'la64_inconsistencias' => $this->getInconsistencias()
        );
    }
}
