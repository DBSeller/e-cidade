<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\RH\ConcessaoDireitos\Repository;

class ConcessaoAssentRelatorio
{
    public static function relatorioPrevia(
        $matricula,
        $rh504_seqassentconf,
        $datainicio,
        $datafinal,
        array $assentamentos
    ) {
        $where = [];
        if (!empty($assentamentos)) {
            $where[] = "exists (
                select
                    1
                from
                    recursoshumanos.assenta
                where
                    h16_regist = rh01_regist
                and h16_assent in (" . implode(",", $assentamentos) . ")
                and '$datafinal' > h16_dtconc
            )";
        }

        if (!empty($matricula)) {
            $where[] = "rh504_regist = $matricula";
        }

        $where[] = "rh504_data between '$datainicio' and '$datafinal'";
        $where[] = "not exists (
            select 
                1 
            from 
                concessaoassent where concessaocalculo.rh504_sequencial = concessaoassent.rh505_concessaocalculo)";
        $where[] = "rh504_seqassentconf = $rh504_seqassentconf";

        $sql = "
        select
            rh504_regist as matricula,
            z01_nome as nome,
            rh501_ordem as ordem,
            rh501_perc as percentual,
            rh504_data as data
        from
            recursoshumanos.concessaocalculo
        inner join recursoshumanos.assentperc on
            rh501_sequencial = rh504_seqassentperc
            inner join rhpessoal on
            rh01_regist  = rh504_regist
            inner join cgm on
            rh01_numcgm  = z01_numcgm
            inner join recursoshumanos.assentconf on rh504_seqassentconf = rh500_sequencial
        where
            " . implode(" and ", $where) . "
            order by z01_nome,rh504_data";
        return pg_fetch_all(db_query($sql));
    }

    public static function admissao($matricula)
    {
        $sql = "
        select
            rh01_admiss
        from
            rhpessoal
        where
            rh01_regist = $matricula";

        return pg_fetch_all(db_query($sql));
    }

    public static function assentServidor($sequencial, $rh504_seqassentconf, $matricula, $data1, $ordem)
    {
        if ($ordem == 1) {
            $sql = "
        select
            *
        from
            recursoshumanos.assenta
        where
            h16_assent = $sequencial
            and h16_regist = $matricula
            and h16_dtconc < '$data1'";

            return pg_fetch_all(db_query($sql));
        } else {
            $sql = "
            select
                rh504_data
            from
                recursoshumanos.concessaocalculo
            where
                rh504_data < '$data1'
                and rh504_regist = $matricula
                and rh504_seqassentconf = $rh504_seqassentconf
                order by rh504_data desc
                limit 1";

            $data2 = pg_fetch_all(db_query($sql));

            $sql = "
            select
                *
            from
                recursoshumanos.assenta
            where
                h16_assent = $sequencial
                and h16_regist = $matricula
                and h16_dtconc between '$data1' and '$data2'";
        }


        return pg_fetch_all(db_query($sql));
    }

    public static function portariatipo($assentam)
    {
        $sql = "
        select
            portariatipo.h30_sequencial, portariatipo.h30_amparolegal, h41_descr
        from
            portariatipo
            inner join recursoshumanos.tipoasse on h12_codigo = h30_tipoasse
            inner join portariatipoato on h41_sequencial = h30_portariatipoato
        where
            h12_codigo =  $assentam
        limit 1";
        return pg_fetch_all(db_query($sql));
    }

    // RELATORIO GERAL
    public static function relatorio($object)
    {
        $rh504_seqassentconf = $object->rh500_sequencial;

        $where = [];

        if (!empty($object->matricula)) {
            $where[] = "rh504_regist = $object->matricula";
        }
        $where[] = "rh504_seqassentconf = $rh504_seqassentconf";


        if ($object->filtro == 2) {
            $where[] = "exists (
                select 
                    1 
                from 
                    concessaoassent where concessaocalculo.rh504_sequencial = concessaoassent.rh505_concessaocalculo)";
        } elseif ($object->filtro == 3) {
            $where[] = "rh01_admiss > rh504_data";
        }

        $sql = "
            select
            rh504_regist as matricula,
            z01_nome as nome,
            rh501_ordem as ordem,
            rh501_perc as percentual,
            rh504_data as data,
            h16_dtconc as dataassenta,
            case
                when rh01_admiss > rh504_data then 'Tempo Averbado'
                when h31_amparolegal is null then 'Sem portaria'
                else h31_amparolegal
            end as portaria
        from
            recursoshumanos.concessaocalculo
        left join recursoshumanos.concessaoassent on
            rh505_concessaocalculo = rh504_sequencial
        left join recursoshumanos.assenta on
            rh505_codigo = h16_codigo
        left join portariaassenta on
            h33_assenta = rh505_codigo
        left join portaria on
            h31_sequencial = h33_portaria
        inner join recursoshumanos.assentperc on
            rh501_sequencial = rh504_seqassentperc
        inner join rhpessoal on
            rh01_regist = rh504_regist
        inner join cgm on
            rh01_numcgm = z01_numcgm
        inner join recursoshumanos.assentconf on
            rh504_seqassentconf = rh500_sequencial
        where
                " . implode(" and ", $where) . "
                order by z01_nome,rh504_data";
        return pg_fetch_all(db_query($sql));
    }
}
