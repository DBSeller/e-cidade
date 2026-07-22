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

final class FixaJuridicaParcelamentoEmissao implements Interfaces\Validador
{
    private $termo;

    private $processoForo;

    public function __construct(Termo $termo, ProcessoForo $processoForo)
    {
        $this->termo = $termo;
        $this->processoForo = $processoForo;
    }

    public function processarValidacao()
    {
        // |-0.5 Processo do foro tem termotaxafixa de termo nao anulado?
        // |--sim
        // |  | parcelamento nao tem fixas
        // |--nao
        // |  | continua processamento
        // |
        // |-1 O processo tem mais alguma inicial em aberto quando eu sou feito?
        // |--nao
        // |  | parcelamento tem fixas
        // |--sim
        // |  |-2 Eu tenho alguma inicial do ultimo recibo valido com custas fixas?
        // |  |--sim
        // |  |  | parcelamento tem fixas
        // |  |--nao
        // |  |  |-3 Eu tenho os ultimos debitos sem recibo?
        // |  |  |--sim
        // |  |  |  | parcelamento tem fixas
        // |  |  |--nao
        // |  |  |  | parcelamento nao tem fixas

        $fixas = false;

        $termo = $this->termo->getCodigo();
        $processoForo = $this->processoForo->getCodigo();

        $sql = "select 1 as total 
                  from termotaxafixa 
                       inner join termo on termo.v07_parcel = termotaxafixa.ar42_parcel
                                       and termo.v07_situacao = 1
                 where ar42_processoforo = $processoForo 
                   and ar42_fixa is true ";

        $rs = db_query($sql);

        if (pg_num_rows($rs) > 0) {
            $result = pg_fetch_object($rs, 0);

            if ($result->total > 0) {
                return false;
            }
        }

        $sql = "
            select count(distinct inicial.v50_inicial) as total 
              from processoforoinicial
                   inner join inicial on inicial.v50_inicial = processoforoinicial.v71_inicial
                   inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
                   left join termoini on termoini.inicial = inicial.v50_inicial
                   left join termo on termo.v07_parcel = termoini.parcel
                                   and termo.v07_situacao = 1
             where processoforoinicial.v71_processoforo = $processoForo
               and termo.v07_parcel is null 
        ";

        $resource = db_query($sql);

        $result = pg_fetch_object($resource, 0);

        $this->log(" 1 - total: ".$result->total);

        if ($result->total == 0) {
            $fixas = true;
        } else {
            $sql = "
                select count(distinct recibopaga.k00_numnov) as total
                  from processoforoinicial
                       inner join inicial on inicial.v50_inicial = processoforoinicial.v71_inicial
                       inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
                       inner join termoini on termoini.inicial = inicial.v50_inicial
                       inner join termo on termo.v07_parcel = termoini.parcel
                                       and termo.v07_situacao = 1
                       inner join recibopaga on recibopaga.k00_numpre = inicialnumpre.v59_numpre
                       inner join taxa on taxa.ar36_receita = recibopaga.k00_receit
                       left join disbanco on disbanco.k00_numpre = recibopaga.k00_numnov
                 where processoforoinicial.v71_processoforo = $processoForo 
                   and termo.v07_parcel = $termo
                   and taxa.ar36_valor > 0
                   and recibopaga.k00_dtpaga >= CURRENT_DATE
                   and disbanco.k00_numpre is null
                 limit 1
            ";

            $resource = db_query($sql);

            $result = pg_fetch_object($resource, 0);

            $this->log(" 2 - total: ".$result->total);

            if ($result->total > 0) {
                $fixas = true;
            } else {
                $sql = "
                    select (
                            (
                            select array_agg(distinct inicial.v50_inicial)
                              from processoforoinicial
                                   inner join inicial on inicial.v50_inicial = processoforoinicial.v71_inicial
                                   inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
                             where processoforoinicial.v71_processoforo = $processoForo
                               and processoforoinicial.v71_anulado is false        
                            ) <> (
                            select array_agg(distinct v50_inicial) 
                              from (
                                    select inicial.v50_inicial
                                      from processoforoinicial
                                           inner join inicial on inicial.v50_inicial = processoforoinicial.v71_inicial
                                           inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial
                                           left join termoini on termoini.inicial = inicial.v50_inicial
                                           left join termo on termo.v07_parcel = termoini.parcel
                                                           and termo.v07_situacao = 1
                                           inner join recibopaga on recibopaga.k00_numpre = inicialnumpre.v59_numpre
                                           left join disbanco on disbanco.k00_numpre = recibopaga.k00_numnov
                                     where processoforoinicial.v71_processoforo = $processoForo
                                       and termo.v07_parcel is null
                                       and recibopaga.k00_dtpaga >= CURRENT_DATE
                                       and disbanco.k00_numpre is null
                                       and processoforoinicial.v71_anulado is false 
                            
                                    union 
                                       
                                    select termoini.inicial
                                      from termoini
                                     inner join processoforoinicial 
                                            on processoforoinicial.v71_inicial = termoini.inicial
                                     where parcel = $termo
                                       and processoforoinicial.v71_processoforo = $processoForo
                                    ) as x
                            )
                        )::int as nao_tem_tudo ";

                $resource = db_query($sql);

                $result = pg_fetch_object($resource, 0);

                $this->log(" 3 - nao_tem_tudo: ".$result->nao_tem_tudo);

                if ($result->nao_tem_tudo == 1) {
                    $fixas = false;
                    $this->log(" 3 - nao_tem_tudo false");
                } else {
                    $fixas = true;
                    $this->log(" 3 - nao_tem_tudo true");
                }
            }
        }

        $this->log(" FIXAS: ".$fixas);

        return $fixas;
    }

    private function log($s)
    {
        // file_put_contents("tmp/log_simulacao.txt", $s."\n", FILE_APPEND);
    }
}
