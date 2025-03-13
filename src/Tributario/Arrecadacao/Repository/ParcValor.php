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

use Exception;
use \cl_parcvalor;
use ECidade\Tributario\Arrecadacao\Custas\Service\Calendario;
use ECidade\Tributario\Cadastro\Repository\CadTipoParcRepository;

/**
 * Class ParcValor - Classe destinada a guardar os valores dos reparcelamentos
 * @package ECidade\Tributario\Arrecadacao\Repository
 */
class ParcValor
{
    /**
     * Função que deleta os débitos de um determinado numpre
     *
     * @param Integer numpre
     * @throws Exception
     */
    public static function deletaDebitos($k189_numpre)
    {
        $rs = db_query("DELETE FROM arrecadacao.parcvalor where k189_numpre = {$k189_numpre}");

        if (!$rs) {
            throw new Exception("Erro ao deletar parcelas salvas anteriormente.");
        }
    }

    /**
     * Função que atualiza os debitos da arrecad de acordo com os valores na parcvalor
     *
     * @param Integer termo
     * @throws Exception
     */
    public static function atualizaArrecad($termo)
    {
        $rs = db_query("SELECT * from termo where v07_parcel = $termo");

        if (!$rs) {
            throw new Exception("Erro ao buscar termo.");
        }

        $termo = pg_fetch_object($rs, 0);

        $rsParcValor = db_query("SELECT * from parcvalor where k189_numpre in ($termo->v07_numpre)");

        if (!$rsParcValor) {
            throw new Exception("Erro ao buscar débitos.");
        }

        $arrArrecad = array();
        while ($parcvalor = pg_fetch_array($rsParcValor)) {
            $campos = "sum(k00_valor) total,k00_receit,k00_numpre";
            $where  = "k00_numpre = ".$parcvalor['k189_numpre'];
            $group  = "k00_receit,k00_numpre";
            $sSqlPercReceita = "select $campos from arrecad where $where group by $group";
            $rsPercReceita = db_query($sSqlPercReceita);

            $campos = "sum(k00_valor) total,k00_numpre";
            $sSqlTotalArrecad = "select $campos from arrecad where $where group by k00_numpre";
            $rsTotalArrecad = db_query($sSqlTotalArrecad);
            $totalArrecad = pg_fetch_object($rsTotalArrecad, 0);

            while ($perc_receita = pg_fetch_array($rsPercReceita)) {
                $perc = (100*$perc_receita['total'])/$totalArrecad->total;
                $valor = ($parcvalor['k189_valor']*$perc)/100;

                $set  = "set";
                $set .= (!empty($valor)) ? " k00_valor = ".$valor : "";

                if (!empty($parcvalor['k189_data'])) {
                    if (!empty($parcvalor['k189_valor'])) {
                        $set .= ", k00_dtvenc = '".$parcvalor['k189_data']."'";
                    } else {
                        $set .= " k00_dtvenc = '".$parcvalor['k189_data']."'";
                    }
                }

                $where  = " k00_numpre in (".$parcvalor['k189_numpre'].") ";
                $where .= " and k00_numpar = ".$parcvalor['k189_numpar']   ;
                $where .= " and k00_receit = ".$perc_receita['k00_receit'] ;
                $rsArrecad = db_query("UPDATE arrecad ".$set." where $where");

                if (!$rsArrecad) {
                    throw new Exception("Erro ao atualizar débitos.");
                }

               //Seta as datas de vencimento da primeira e segunda parcela no termo
                if ($parcvalor['k189_numpar'] == 1 && !empty($parcvalor['k189_data'])) {
                    $update = "v07_datpri = '" . $parcvalor['k189_data'] . "'";
                    $rsTermo = db_query("UPDATE termo set $update where v07_parcel = $termo->v07_parcel");
                    if (!$rsTermo) {
                        throw new Exception("Erro ao atualizar data do primeiro vencimento do termo.");
                    }
                } elseif ($parcvalor['k189_numpar'] == 2 && !empty($parcvalor['k189_data'])) {
                    $update = "v07_dtvenc = '" . $parcvalor['k189_data'] . "'";
                    $rsTermo = db_query("UPDATE termo set $update where v07_parcel = $termo->v07_parcel");
                    if (!$rsTermo) {
                        throw new Exception("Erro ao atualizar data do segundo vencimento do termo.");
                    }
                }
            }
        }

        ParcValor::atualizaDataArrecadCalendario($termo->v07_parcel);

        unset($_SESSION['DB_parcelaseditadas']);
    }

    /**
     * Função que atualiza as parcelas dos numpres passados de acordo com o calendário do tributário
     *
     * @param number codigo do termo de parcelamento
     * @throws Exception
     */
    public static function atualizaDataArrecadCalendario($termo)
    {

        $rs = db_query("SELECT * from termo where v07_parcel = $termo");

        if (!$rs) {
            throw new Exception("Erro ao buscar termo.");
        }

        $termo = pg_fetch_object($rs, 0);

        $rsArrecad = db_query("SELECT distinct on (k00_numpre, k00_numpar)
                                      * 
                                 from arrecad 
                                where k00_numpre in ($termo->v07_numpre)");
 
        if (!$rsArrecad) {
            throw new Exception("Erro ao buscar débitos de parcelamento na Arrecad.");
        }

        $cadTipoParc = CadTipoParcRepository::find($termo->v07_desconto);

        /*
         * Itera sobre a tabela arrecad buscando o numpre do débito para
         * atualizar as datas de acordo com o calendário do triburário
         */
        while ($arrecad = pg_fetch_array($rsArrecad)) {
            $data = new \DBDate(date("Y-m-d", strtotime($arrecad['k00_dtvenc'])));
            if ($cadTipoParc->getControlavencimento() == 't') {
                $dLimite = new \DBDate(date("Y-12-t", strtotime($arrecad['k00_dtvenc'])));
                $dVencimento = self::getProximoDiaUtil($data);
                if (strtotime($dVencimento->getDate()) > strtotime($dLimite->getDate())) {
                    $dVencimento = self::getUltimoDiaUtil($data);
                }
            } else {
                $dVencimento = self::getProximoDiaUtil($data);
            }

            db_query(" UPDATE arrecad
                        set k00_dtvenc = '{$dVencimento->getDate()}'
                        where k00_numpar = {$arrecad['k00_numpar']} and k00_numpre = {$arrecad['k00_numpre']}");

            db_query(" UPDATE parcvalor
                        set k189_data = '{$dVencimento->getDate()}'
                        where k189_numpar = {$arrecad['k00_numpar']} and k189_numpre = {$arrecad['k00_numpre']}");
        }
    }

    /**
     * Função que salva as parcelas vinculadas com o numpre do termo passado
     *
     * @param Integer numpre
     * @throws Exception
     */
    public static function adicionaParcelas($termo, $arrDados)
    {
        $rs = db_query("SELECT * from termo where v07_parcel in ($termo)");
        if (!$rs) {
            throw new Exception("Erro ao buscar termo.");
        }

        $termo = pg_fetch_object($rs, 0);

        ParcValor::deletaDebitos($termo->v07_numpre);

        foreach ($arrDados as $dado) {
            if (strpos($dado['valor'], ',')) {
                $valor = str_replace(',', '.', str_replace(".", "", $dado['valor']));
            } else {
                $valor = $dado['valor'];
            }

            $cl_parcvalor = new cl_parcvalor;
            $cl_parcvalor->k189_numpre = $termo->v07_numpre;
            $cl_parcvalor->k189_numpar = $dado['numpar'];
            $cl_parcvalor->k189_valor = floatval($valor);
            $cl_parcvalor->k189_data = $dado['data'];

            $cl_parcvalor->incluir();
        }
    }

    /**
     * Função que retorna as parcelas editadas para o termo passado
     *
     * @param Integer termo
     * @throws Exception
     */
    public static function getParcelas($termo)
    {
        $rs = db_query("SELECT * from termo where v07_parcel in ($termo)");

        if (!$rs) {
            throw new Exception("Erro ao buscar termo.");
        }

        $termo = pg_fetch_object($rs, 0);

        $rsParcValor = db_query("SELECT * from parcvalor where k189_numpre in ($termo->v07_numpre)");

        if (!$rsParcValor) {
            throw new Exception("Erro ao buscar débitos.");
        }

        return $rsParcValor;
    }

    /**
     * Função que retorna o ultimo dia util de acordo com o calendário do tributário
     *
     * @param DBDate data
     * @throws Exception
     */
    public function getUltimoDiaUtil(\DBDate $data)
    {
        $calendarioService = new Calendario();

        while (!$calendarioService->isUtil($data->getDate())) {
            $data = $data->getDiaAnterior();
        }

        return $data;
    }

    /**
     * Função que retorna o ultimo dia util de acordo com o calendário do tributário
     *
     * @param DBDate data
     * @throws Exception
     */
    public static function getProximoDiaUtil(\DBDate $data)
    {
        $calendarioService = new Calendario();

        while (!$calendarioService->isUtil($data->getDate())) {
            $data = $data->getProximoDia();
        }

        return $data;
    }

    public static function isTermoRefisa($termo)
    {
        $rs = db_query("SELECT * from termo where v07_parcel = $termo");

        if (!$rs) {
            throw new Exception("Erro ao buscar termo.");
        }

        $termo = pg_fetch_object($rs, 0);

        $cadTipoParc = CadTipoParcRepository::find($termo->v07_desconto);

        return ($cadTipoParc->getPermvalcadparc() == 't' || $cadTipoParc->getPermdataparc() == 't');
    }
}
