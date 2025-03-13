<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ImovelAnterior;
use \stdClass;

final class ImovelAnteriorRepository extends DataBaseRepository
{
    public function find($matricula, $ano)
    {
        $imovelAnterior = new ImovelAnterior();
        $objetoRetorno  = (object)array(
             'j36_testad'  => 0
            ,'j39_area'    => 0
            ,'j40_refant'  => 0
            ,'j23_arealo'  => 0
            ,'j23_m2terr'  => 0
        );

        $sSqlTestada  = " select j36_testad                                              ";
        $sSqlTestada .= "   from iptubase                                                ";
        $sSqlTestada .= "        inner join testada on j36_idbql = j01_idbql             ";
        $sSqlTestada .= "        inner join testpri on j49_idbql = testada.j36_idbql     ";
        $sSqlTestada .= "  where j01_matric = {$matricula}                               ";
        $resultTestada = $this->dataBase->execute($sSqlTestada);
        if(is_resource($resultTestada)) {
            $objetoTestada = $this->dataBase->fetchRow($resultTestada);
            $objetoRetorno = (object) array_merge(
               (array)$objetoRetorno
              ,(array)$objetoTestada
            );
        }

        // $sSqlArea  = ' select sum(j39_area) as j39_area   ';
        // $sSqlArea .= '   from iptuconstr                  ';
        // $sSqlArea .= '  where j39_dtdemo is null          ';
        // $sSqlArea .= '    and j39_matric = $1             ';
        // $resultArea = $this->dataBase->execute($sSqlArea);
        // $objetoArea = $this->dataBase->fetchRow($resultArea);

        $sSqlAreaConstruida  = " select sum(j39_area) as j39_area   ";
        $sSqlAreaConstruida .= "   from iptuconstr                  ";
        $sSqlAreaConstruida .= "  where j39_dtdemo is null          ";
        $sSqlAreaConstruida .= "    and j39_matric = {$matricula}   ";
        $resultAreaConstruida = $this->dataBase->execute($sSqlAreaConstruida);
        if(is_resource($resultAreaConstruida)) {
            $objetoAreaConstruida = $this->dataBase->fetchRow($resultAreaConstruida);
            $objetoRetorno = (object) array_merge(
               (array)$objetoRetorno
              ,(array)$objetoAreaConstruida
            );
        }

        $sSqlIptuAnterior  = " select j40_refant                  ";
        $sSqlIptuAnterior .= "   from iptuant                     ";
        $sSqlIptuAnterior .= "  where j40_matric = {$matricula}   ";
        $resultIptuAnterior = $this->dataBase->execute($sSqlIptuAnterior);
        if(is_resource($resultIptuAnterior)) {
            $objetoIptuAnterior = $this->dataBase->fetchRow($resultIptuAnterior);
            $objetoRetorno = (object) array_merge(
               (array)$objetoRetorno
              ,(array)$objetoIptuAnterior
            );
        }

        $sSqlIptuPrincipal  = " select j23_arealo                  ";
        $sSqlIptuPrincipal .= "       ,j23_m2terr                  ";
        $sSqlIptuPrincipal .= "   from iptucalc                    ";
        $sSqlIptuPrincipal .= "  where j23_matric = {$matricula}   ";
        $sSqlIptuPrincipal .= "    and j23_anousu = {$ano}         ";
        $resultIptuPrincipal = $this->dataBase->execute($sSqlIptuPrincipal);
        if(is_resource($resultIptuPrincipal)) {
            $objetoIptuPrincipal = $this->dataBase->fetchRow($resultIptuPrincipal);
            $objetoRetorno = (object) array_merge(
               (array)$objetoRetorno
              ,(array)$objetoIptuPrincipal
            );
        }

        $imovelAnterior->setTestadaLote($objetoRetorno->j36_testad);
        $imovelAnterior->setAreaLote('0'); //@todo retirar valor fixo
        $imovelAnterior->setAreaTotalConstruida($objetoRetorno->j39_area);
        $imovelAnterior->setReferenciaAnterior($objetoRetorno->j40_refant);
        $imovelAnterior->setAreaLoteCalculo($objetoRetorno->j23_arealo);
        $imovelAnterior->setValorM2Calculo($objetoRetorno->j23_m2terr);

        return $imovelAnterior;
    }
}
