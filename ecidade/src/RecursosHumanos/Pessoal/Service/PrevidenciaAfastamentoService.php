<?php


namespace ECidade\RecursosHumanos\Pessoal\Service;

use BusinessException;
use cl_inssirf;
use cl_rhrubricas;
use ECidade\RecursosHumanos\Pessoal\Model\ControleAfastamento;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleAfastamentoRepository;
use Exception;
use Instituicao;
use RubricaRepository;

class PrevidenciaAfastamentoService
{

    /**
     * ControleAfastamentoService constructor.
     * @param
     */
    public function __construct($inssirf)
    {
        $this->inssirf = $inssirf;
    }

    /**
     * @return PrevidenciaAfastamentoService
     * @throws Exception
     */
    public function addAfastamentosConfigurados()
    {
        $this->rubAfastamentoLista = [];
        foreach ($this->inssirf as $key => $value) {
            if (in_array($key, [
            "r33_rubmat", "r33_rubsau", "r33_rubprorrogacaomaternidade",
            "r33_rubac", "r33_rubfamiliar", "r33_rublicencapremio"])
            && $value != null
            ) {
                $this->rubAfastamentoLista[$key] = $value;
            }
        }

        $this->rubAfastamentoLista;

        return $this;
    }

    public function getAfastamentosConfigurados()
    {
        return $this->rubAfastamentoLista;
    }

    public function toInner()
    {

        $sRubAfastamentoParaCalculo = '';
        foreach ($this->rubAfastamentoLista as $key => $value) {
            if (in_array($key, [
            "r33_rubmat", "r33_rubsau", "r33_rubprorrogacaomaternidade",
            "r33_rubac", "r33_rubfamiliar", "r33_rublicencapremio"])
            && $value != null
            ) {
                $sRubAfastamentoParaCalculo .= "'{$value}', ";
            }
        }
        $sRubAfastamentoParaCalculo = substr($sRubAfastamentoParaCalculo, 0, -2);

        return $sRubAfastamentoParaCalculo;
    }
}
