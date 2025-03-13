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

namespace ECidade\Tributario\Arrecadacao\Repository;

use cl_arreforo;
use ECidade\Tributario\Library\Repository;
use Exception;
use BusinessException;

/**
 * Class ArreforoRepository
 * @package ECidade\Tributario\Arrecadacao\Repository
 */
class RegraEmissaoReciboCustaRepository extends Repository
{
    public static function getRegraEmissao($data, $instit, $tipoDebito, $minNumpar, $maxNumpar, $tipoModelo)
    {
        // TODO Verificar a necessidade de utilizar o ip de acesso
        $sql = "
            select
                *
            from (select
                    min(k48_sequencial) as k48_sequencial,
                    k49_tipo as tipo,
                    k36_ip as ip,
                    k48_parcini as parcini,
                    k48_parcfim as parcfim,
                    k48_cadconvenio as convenio,
                    k48_cadtipomod as cadtipomod,
                    ar11_cadtipoconvenio,
                    ar11_cadtipoconvenio as tipoconvenio,
                    k03_tipo,
                    k03_tipo as tipoemissaocustas
                from
                    modcarnepadrao
                    left join modcarnepadraotipo on
                        modcarnepadraotipo.k49_modcarnepadrao = modcarnepadrao.k48_sequencial
                    left  join modcarneexcessao on modcarneexcessao.k36_modcarnepadrao = modcarnepadrao.k48_sequencial
                    inner join cadconvenio on cadconvenio.ar11_sequencial = modcarnepadrao.k48_cadconvenio
                    left  join arretipo on modcarnepadraotipo.k49_tipo = arretipo.k00_tipo
                where
                    '$data' between k48_dataini and k48_datafim
                    and k48_instit = {$instit}
                    and (case
                            when modcarnepadraotipo.k49_modcarnepadrao is not null then
                                modcarnepadraotipo.k49_tipo = {$tipoDebito}
                            else true
                        end)
                    and (case
                            when modcarneexcessao.k36_modcarnepadrao is not null then
                                modcarneexcessao.k36_ip = '".db_getsession('DB_ip')."'
                            else true
                        end)
                    and ({$maxNumpar} between k48_parcini and k48_parcfim
                        or
                        {$minNumpar} between k48_parcini and k48_parcfim)
                    and k48_cadtipomod in ({$tipoModelo})
                group by k49_tipo, k36_ip, k48_parcini, k48_parcfim,
                    k48_cadconvenio, ar11_cadtipoconvenio, k03_tipo, k48_cadtipomod
            ) as x";
        $rs = \db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar regras de emissão.");
        }

        if (pg_numrows($rs) == 0) {
            throw new BusinessException("Nenhuma regra de emissão configurada para o modelo {$tipoModelo}.");
        }

        $regras = \db_utils::makeCollectionFromRecord($rs, function ($dado) {
            return $dado;
        });
        // Verificando se a regra é especifica ou não
        // Logica extraida da geral financeira
        $aRegraGeral = array();
        $aRegraEspecifica = array();

        foreach ($regras as $regra) {
            if (!empty($regra->tipo) || !empty($regra->ip)) {
                $aRegraEspecifica[] = $regra;
            } else {
                $aRegraGeral[] = $regra;
            }
        }

        if ($aRegraEspecifica) {
            return $aRegraEspecifica;
        } else {
            return $aRegraGeral;
        }
    }
}
