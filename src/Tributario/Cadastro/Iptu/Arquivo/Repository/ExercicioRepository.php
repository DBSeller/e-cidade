<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Exercicio;
use \stdClass;

final class ExercicioRepository extends DataBaseRepository
{
    public function find($matricula, $ano)
    {
        $exercicio = new Exercicio();
        $objetoRetorno  = (object)array(
             'iptucor'         => 0
            ,'iptujuros'       => 0
            ,'iptumulta'       => 0
            ,'iptudesconto'    => 0
            ,'iptutotal'       => 0
            ,'total_j21_valor_taxas' => 0
            ,'quant_taxas_taxas'     => 0
            ,'total_j21_valor_iptu'  => 0
            ,'quant_taxas_iptu'      => 0
            ,'j22_valor'    => 0
            ,'j23_vlrter'   => 0
            ,'j23_aliq'     => 0
        );

        $sqlCalculoIPTU  = " select sum(j21_valor) as total_j21_valor,   ";
        $sqlCalculoIPTU .= "        count(*)       as quant_taxas        ";
        $sqlCalculoIPTU .= "   from iptucalv                             ";
        $sqlCalculoIPTU .= "  where j21_anousu = {$ano}                  ";
        $sqlCalculoIPTU .= "    and j21_matric = {$matricula}            ";
        $resultCalculoIPTU = $this->dataBase->execute($sqlCalculoIPTU);

        if(is_resource($resultCalculoIPTU)) {

            $objCalculoIPTU                       = $this->dataBase->fetchRow($resultCalculoIPTU);
            $objCalculoIPTU->total_j21_valor_iptu = $objCalculoIPTU->total_j21_valor;
            $objCalculoIPTU->quant_taxas_iptu     = $objCalculoIPTU->quant_taxas;

            unset($objCalculoIPTU->total_j21_valor);
            unset($objCalculoIPTU->quant_taxas);

            $objetoRetorno  = (object) array_merge(
               (array)$objetoRetorno
              ,(array)$objCalculoIPTU
            );
        }

        $sqlCalculoTaxa = "
            select sum(iptutaxacalv.j152_valor) as total_j152_valor,
                   count(*) as quant_taxas
              from iptutaxanump
                   inner join iptutaxacalv on iptutaxacalv.j152_iptutaxanump = iptutaxanump.j151_codigo 
                   inner join iptucadtaxaexe on iptucadtaxaexe.j08_iptucadtaxaexe = iptutaxanump.j151_iptucadtaxaexe 
             where iptutaxanump.j151_matric = {$matricula}
               and iptutaxanump.j151_numpre is not null
               and iptucadtaxaexe.j08_anousu = {$ano}
        ";

        $resultCalculoTaxa = $this->dataBase->execute($sqlCalculoTaxa);

        if(is_resource($resultCalculoTaxa)) {

            $objCalculoTaxa = $this->dataBase->fetchRow($resultCalculoTaxa);

            $objCalculoTaxa->total_j21_valor_taxas = $objCalculoTaxa->total_j152_valor;
            $objCalculoTaxa->quant_taxas_taxas     = $objCalculoTaxa->quant_taxas;

            unset($objCalculoTaxa->total_j152_valor);
            unset($objCalculoTaxa->quant_taxas);

            $objetoRetorno = (object) array_merge(
               (array)$objetoRetorno
              ,(array)$objCalculoTaxa
            );
        }

        // $sqlIPTU  = " select fc_calcula(k00_numpre, k00_numpar, 0, current_date, current_date, {$ano})       ";
        // $sqlIPTU .= "   from ( select distinct                                                               ";
        // $sqlIPTU .= "                 arrecad.k00_numpre, arrecad.k00_numpar                                 ";
        // $sqlIPTU .= "            from iptunump                                                               ";
        // $sqlIPTU .= "                 inner join arrematric on iptunump.j20_numpre = arrematric.k00_numpre   ";
        // $sqlIPTU .= "                 inner join arrecad    on iptunump.j20_numpre = arrecad.k00_numpre      ";
        // $sqlIPTU .= "           where j20_anousu = ({$ano} - 1)                                              ";
        // $sqlIPTU .= "             and k00_matric = {$matricula} ) as x                                       ";
        // $resultIPTU = $this->dataBase->execute($sqlIPTU);

        // if(is_resource($resultIPTU)) {

        //     $objetoIPTU = $this->dataBase->getCollectionByRecord($resultIPTU);

        //     if(!empty($objetoIPTU)) {

        //         foreach ($objetoIPTU as $calcIptu) {
        //             $objetoRetorno->iptucor      += (float) substr($calciptu->fc_calcula,14,13);
        //             $objetoRetorno->iptujuros    += (float) substr($calciptu->fc_calcula,27,13);
        //             $objetoRetorno->iptumulta    += (float) substr($calciptu->fc_calcula,40,13);
        //             $objetoRetorno->iptudesconto += (float) substr($calciptu->fc_calcula,53,13);
        //             $objetoRetorno->iptutotal    += (float) substr($calciptu->fc_calcula,14,13) 
        //                                                     + (float) substr($calciptu->fc_calcula,27,13)
        //                                                     + (float) substr($calciptu->fc_calcula,40,13)
        //                                                     - (float) substr($calciptu->fc_calcula,53,13);
        //         }
        //     }
        // }

        $sSqlIptuPrincipal  = " select j23_vlrter                  ";
        $sSqlIptuPrincipal .= "       ,j23_aliq                    ";
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

        $sSqlIPTUCale  = " select sum(j22_valor) as j22_valor   ";
        $sSqlIPTUCale .= "   from iptucale                      ";
        $sSqlIPTUCale .= "  where j22_anousu = {$ano}           ";
        $sSqlIPTUCale .= "    and j22_matric = {$matricula}     ";
        $resultIPTUCale = $this->dataBase->execute($sSqlIPTUCale);

        if(is_resource($resultIPTUCale)) {
            $objCalculoIPTUCale = $this->dataBase->fetchRow($resultIPTUCale);
            $objetoRetorno = (object) array_merge(
               (array)$objetoRetorno
              ,(array)$objCalculoIPTUCale
            );
        }

        $exercicio->setIsencaoDescricao('');
        $exercicio->setIsencaoDataLancamento('');

        $exercicio->setTotalIptuTaxa($objetoRetorno->total_j21_valor_iptu  + $objetoRetorno->total_j21_valor_taxas);
        $exercicio->setQuantidadeIptuTaxa($objetoRetorno->quant_taxas_iptu + $objetoRetorno->quant_taxas_taxas);
        $exercicio->setTotalTaxa($objetoRetorno->total_j21_valor_taxas);
        $exercicio->setQuantidadeTaxa($objetoRetorno->quant_taxas_taxas);

        // $exercicio->setValorCorrigidoIptu($objetoRetorno->iptucor);
        // $exercicio->setValorJurosIptu($objetoRetorno->iptujuros);
        // $exercicio->setValorMultaIptu($objetoRetorno->iptumulta);
        // $exercicio->setValorDescontoIptu($objetoRetorno->iptudesconto);
        // $exercicio->setValorTotalIptu($objetoRetorno->iptutotal);

        $exercicio->setValorCorrigidoIptu(0);
        $exercicio->setValorJurosIptu(0);
        $exercicio->setValorMultaIptu(0);
        $exercicio->setValorDescontoIptu(0);
        $exercicio->setValorTotalIptu(0);

        $exercicio->setCodigoFace('');
        $exercicio->setValorM2TerrenoFace('');
        $exercicio->setValorM2ConstrucaoFace('');

        $exercicio->setValorVenalTerreno($objetoRetorno->j23_vlrter);
        $exercicio->setValorVenalEdificacoes($objetoRetorno->j22_valor);
        $exercicio->setValorVenalTotal($objetoRetorno->j23_vlrter + $objetoRetorno->j22_valor);
        $exercicio->setAliquota($objetoRetorno->j23_aliq);

        return $exercicio;
    }
}
