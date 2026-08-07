<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Repository;

use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\Advogado;

/**
 * Class AdvogadoRepository
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Repository
 */
class AdvogadoRepository
{

    /**
     * @param $inicial
     * @return Advogado
     */
    public function getAdvogado($inicial)
    {

        $oAdvog = new \cl_advog();

        $aCampos = array(
            "v57_oab",
            "z01_nome",
            "z01_cgccpf",
            "z01_cep",
            "z01_numero",
            "z01_ender",
            "z01_munic",
            "z01_uf",
            "z01_compl",
            "z01_bairro",
            "v57_matriculaadvogado"
        );

        $sWhere = " v50_inicial = " . $inicial;

        $sSql = $oAdvog->sql_query_advog_inicial(null, implode(",", $aCampos), " 1 limit 1 ", $sWhere);

        $rsAdvog = $oAdvog->sql_record($sSql);

        $Advog = \db_utils::fieldsMemory($rsAdvog, 0);

        $oAdvogado = new Advogado();

        $oAdvogado->setNome($Advog->z01_nome);
        $oAdvogado->setNumero($Advog->z01_numero);
        $oAdvogado->setEndereco($Advog->z01_ender);
        $oAdvogado->setBairro($Advog->z01_bairro);
        $oAdvogado->setComplemento($Advog->z01_compl);
        $oAdvogado->setCep($Advog->z01_cep);
        $oAdvogado->setMunicipio($Advog->z01_munic);
        $oAdvogado->setCgccpf($Advog->z01_cgccpf);
        $oAdvogado->setOab($Advog->v57_oab);
        $oAdvogado->setMatriculaadvogado($Advog->v57_matriculaadvogado);


        $oAdvogado->setUf($Advog->z01_uf);


        return $oAdvogado;
    }

}