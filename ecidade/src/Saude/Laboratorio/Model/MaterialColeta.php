<?php


namespace ECidade\Saude\Laboratorio\Model;

/**
 * Class MaterialColeta
 * Referente a tabela lab_materialcoleta
 * @package ECidade\Saude\Laboratorio\Model
 */
class MaterialColeta
{
    /**
     * @var int
     */
    private $codigo;

    /**
     * @var String
     */
    private $descricao;

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
     * @return String
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param String $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @param array $state
     * @return MaterialColeta
     */
    public static function fromState(array $state)
    {
        $materialColeta = new self();

        if (array_key_exists(('la15_i_codigo'), $state)) {
            $materialColeta->setCodigo((int) $state['la15_i_codigo']);
        }

        if (array_key_exists(('la15_c_descr'), $state)) {
            $materialColeta->setDescricao($state['la15_c_descr']);
        }

        return $materialColeta;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'la15_i_codigo' => $this->getCodigo(),
            'la15_c_descr' => $this->getDescricao()
        );
    }
}
