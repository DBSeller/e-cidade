<?php

namespace ECidade\RecursosHumanos\Pessoal\Model;

use ECidade\RecursosHumanos\Pessoal\Repository\RubricasUsuarioRepository;
use Exception;
use Instituicao;
use InstituicaoRepository;
use Rubrica;
use RubricaRepository;
use UsuarioSistema;
use UsuarioSistemaRepository;

/**
 * Class RubricasUsuario
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class RubricasUsuario
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var UsuarioSistema
     */
    private $usuario;
    /**
     * @var Instituicao
     */
    private $instituicao;
    /**
     * @var Rubrica
     */
    private $rubrica;

    /**
     * RubricasUsuario constructor.
     * @param null $sequencial
     */
    public function __construct($sequencial = null)
    {
        if ($sequencial) {
            $rubricasUsuario = RubricasUsuarioRepository::find($sequencial);

            $this->sequencial = $rubricasUsuario->getSequencial();
            $this->usuario = $rubricasUsuario->getUsuario();
            $this->instituicao = $rubricasUsuario->getInstituicao();
            $this->rubrica = $rubricasUsuario->getRubrica();
        }
    }

    /**
     * @param array $state Linha do banco de dados
     * @return RubricasUsuario
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $instituicao = InstituicaoRepository::getInstituicaoByCodigo($state['rh219_instituicao']);

        $rubricasUsuario = new self();
        $rubricasUsuario->setSequencial($state['rh219_sequencial']);
        $rubricasUsuario->setUsuario(UsuarioSistemaRepository::getPorCodigo($state['rh219_usuario']));
        $rubricasUsuario->setInstituicao($instituicao);
        $rubricasUsuario->setRubrica(RubricaRepository::getInstanciaByCodigo($state['rh219_rubrica'],
            $instituicao->getCodigo()));

        return $rubricasUsuario;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return UsuarioSistema
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param UsuarioSistema $usuario
     */
    public function setUsuario(UsuarioSistema $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return Rubrica
     */
    public function getRubrica()
    {
        return $this->rubrica;
    }

    /**
     * @param Rubrica $rubrica
     */
    public function setRubrica(Rubrica $rubrica)
    {
        $this->rubrica = $rubrica;
    }
}
