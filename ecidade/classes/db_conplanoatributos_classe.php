<?php
/*
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

/**
 * Class cl_conplanoatributos
 * @property integer c120_sequencial
 * @property integer c120_anousu
 * @property integer c120_conplano
 * @property integer c120_infocomplementar
 * @property integer c120_conplanosistema
 */
class cl_conplanoatributos extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("contabilidade.conplanoatributos");
    }

    /**
     *
     * @param string $campos
     * @param null   $where
     * @param null   $order
     * @return string
     */
    public function sql_query_reduzido($campos = '*', $where = null, $groupBy = null, $order = null)
    {

        $sql  = "select {$campos} ";
        $sql .= " from conplanoatributos ";
        $sql .= "      inner join conplano on c120_conplano = c60_codcon and c120_anousu = c60_anousu ";
        $sql .= "      inner join conplanoreduz on c60_anousu = c61_anousu and c61_codcon = c60_codcon ";
        $sql .= "      inner join conplanosistema on c120_conplanosistema = c122_sequencial ";
        if (!empty($where)) {
            $sql .= " where {$where} ";
        }

        if (!empty($groupBy)) {
            $sql .= " group by  {$groupBy} ";
        }

        if (!empty($order)) {
            $sql .= " order by  {$order} ";
        }

        return $sql;
    }


    /**
     *
     * @param string $campos
     * @param null   $where
     * @param null   $order
     * @return string
     */
    public function sql_query_atributosPorReduzido($campos = '*', $where = null, $groupBy = null, $order = null)
    {

        $sql  = "select {$campos} ";
        $sql .= " from conplanoatributos ";
        $sql .= " join conplanoinfocomplementar on c121_sequencial = c120_infocomplementar";
        $sql .= " join conplanosistema on c122_sequencial = c120_conplanosistema";
        $sql .= " join conplano on (c60_codcon, c60_anousu) = (c120_conplano, c120_anousu)";
        $sql .= " join conplanoreduz on (c61_codcon, c61_anousu) = (c60_codcon, c60_anousu)";
        $sql .= " join orctiporec on orctiporec.o15_codigo = conplanoreduz.c61_codigo";
        $sql .= " left join conplanosistemaatributos on c129_conplanoinfocomplementar = c121_sequencial";
        if (!empty($where)) {
            $sql .= " where {$where} ";
        }

        if (!empty($groupBy)) {
            $sql .= " group by  {$groupBy} ";
        }

        if (!empty($order)) {
            $sql .= " order by  {$order} ";
        }

        return $sql;
    }






}
