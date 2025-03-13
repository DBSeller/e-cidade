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

namespace ECidade\Tributario\Arrecadacao\Custas\Validador;

use ECidade\Tributario\Arrecadacao\Custas\Interfaces;
use ECidade\Tributario\Divida\Termo\Termo;
use ECidade\Tributario\Juridico\ProcessoForo\ProcessoForo;

final class FixaJuridicaParcelamento implements Interfaces\Validador 
{
    private $validacao;

    private $termo;

    private $processoForo;

    public function __construct(Termo $termo, ProcessoForo $processoForo)
    {
        $this->termo = $termo;
        $this->processoForo = $processoForo;
    }

    public function processarValidacao()
    {
        // |-0.5 Processo do foro tem termotaxafixa de termo nao anulado que e desse termo?
        // |--sim               
        // |  | nao remove as fixas
        // |--nao               
        // |  | continua processamento
        // | 
        // |-1 Eu termo tenho todas as iniciais desse processo?
        // |--sim:
        // |  | nao remove as fixas
        // |--nao 
        // |  |-2 Exite inicial desse processo em aberto que não esta em min termo?            
        // |  |--sim:                
        // |  |  | remove as fixas
        // |  |--nao:              
        // |  |  |-4 Sou o ultimo termo valido desse processo?
        // |  |  |--sim:
        // |  |  |  | nao remove as fixas
        // |  |  |--nao:
        // |  |  |  | remove as fixas

        // echo("ENTROU <br>\n");

        $remover = true;

        $processoForo = $this->processoForo->getCodigo();
        $termo = $this->termo->getCodigo();

        $sql = "select 1 as total 
                  from termotaxafixa 
                 where ar42_parcel = $termo
                   and ar42_processoforo = $processoForo 
                   and ar42_fixa is true ";

        $rs = db_query($sql);

        if (pg_num_rows($rs) > 0) {
            return false;
        }

        $sql = "select 1 as total 
                  from termotaxafixa 
                       inner join termo on termo.v07_parcel = termotaxafixa.ar42_parcel
                                       and termo.v07_situacao = 1
                   and ar42_processoforo = $processoForo 
                   and ar42_fixa is true ";
                   
        $rs = db_query($sql);

        if (pg_num_rows($rs) > 0) {
            return true;
        }

        // 1
        $sql = "select (
                        select count(processoforoinicial.v71_sequencial)
                          from processoforoinicial
                         where processoforoinicial.v71_anulado is false
                           and processoforoinicial.v71_processoforo = $processoForo
                       ) - (
                        select count(termoini.inicial) 
                          from termoini
                               inner join processoforoinicial on processoforoinicial.v71_inicial = termoini.inicial
                         where termoini.parcel = $termo
                           and processoforoinicial.v71_processoforo = $processoForo
                       ) as total
        ";

        $rs = db_query($sql);

        $result = pg_fetch_object($rs, 0);

        // echo("1 <br>\n");

        if ($result->total == 0) {
            $remover = false;
        } else {

            // 2
            $sql = "select count(distinct processoforoinicial.v71_sequencial) as total
                      from processoforoinicial
                           inner join inicial on inicial.v50_inicial = processoforoinicial.v71_inicial
                           inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
                           inner join arrecad on arrecad.k00_numpre = inicialnumpre.v59_numpre
                           left join termoini on termoini.inicial = inicial.v50_inicial
                           left join termo on termo.v07_parcel = termoini.parcel
                                          and termo.v07_situacao = 1
                     where processoforoinicial.v71_anulado is false
                       and processoforoinicial.v71_processoforo = $processoForo
                       and termo.v07_parcel is null
            ";

            $rs = db_query($sql);

            $result = pg_fetch_object($rs, 0);

            // echo("2 <br>\n");

            if ($result->total > 0) {

                $remover = true;

            } else {

                // 4
                $sql = "select distinct termo.v07_parcel
                          from processoforoinicial
                               inner join inicial on inicial.v50_inicial = processoforoinicial.v71_inicial
                               inner join termoini on termoini.inicial = inicial.v50_inicial
                               inner join termo on termo.v07_parcel = termoini.parcel
                                               and termo.v07_situacao = 1
                         where processoforoinicial.v71_anulado is false
                           and processoforoinicial.v71_processoforo = $processoForo
                         order by termo.v07_parcel desc
                ";

                $rs = db_query($sql);

                $result = pg_fetch_object($rs, 0);

                // echo("4 <br>\n");

                if ($result->v07_parcel == $termo) {
                    $remover = false;
                } else {
                    $remover = true;
                }
            }
        }


        // echo("<br>\n");
        // echo("<br>\n");

        return $remover;
    }
}
