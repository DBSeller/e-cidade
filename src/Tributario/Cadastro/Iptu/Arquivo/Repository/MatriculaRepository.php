<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Cadastro\Entity\Repository\MatriculaRepository as BaseMatriculaRepository;

final class MatriculaRepository extends BaseMatriculaRepository
{
    public function findAll($ano, $quantidade = null, $matriculas = null, $quantidadeParcela = null)
    {
        $sql = "
            select iptubase.*,
                   substr(fc_iptuender, 001, 40) as j23_ender, 
                   substr(fc_iptuender, 042, 10) as j23_numero, 
                   substr(fc_iptuender, 053, 20) as j23_compl, 
                   substr(fc_iptuender, 115, 40) as j23_munic, 
                   substr(fc_iptuender, 156, 02) as j23_uf 
              from (
            select iptubase.*,
                   fc_iptuender(iptubase.j01_matric) as fc_iptuender
              from iptubase 
             where j01_matric in (select j23_matric 
                                    from iptucalc 
                                   where j23_anousu = ".$ano.") ";
        
        if (!empty($matriculas)) {
            $sql .= "and j01_matric in (".implode(',', $matriculas).") ";
        }

        if (!empty($quantidadeParcela)) {

            $sql .= "
              and (select true
                     from arrematric 
                          inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre
                    where arrematric.k00_matric = j01_matric
                      and arrecad.k00_numpar = $quantidadeParcela
                    limit 1)
            ";
        }

        $sql .= " ) as iptubase ";
        $sql .= " order by j23_munic, j23_uf, j23_ender, j23_numero, j23_compl ";

        if (!empty($quantidade)) {
            $sql .= "limit ".$quantidade;
        }

        return parent::findAll($sql);
    }
}
