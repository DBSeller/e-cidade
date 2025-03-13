<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

class cl_arquivoestorageassinaturas extends DAOBasica
{
    function __construct()
    {
        parent::__construct('configuracoes.arquivoestorageassinaturas');
    }

    function sql_query_limite_assinaturas($sequencial, $campos, $order, $where)
    {
        if(empty($campos)) {

            $campos = implode(", ", array(
                 "portaria.h31_sequencial"
                ,"portaria.h31_numero"
                ,"portaria.h31_anousu"
                ,"count(distinct arquivoestorageassinaturas.db179_assinatura) as db179_assinatura"
                ,"count(distinct assinaturadocumentodesignacao.db59_usuario) as db59_usuario"
            ));
        } else {

            if(is_array($campos)) {
               $campos = implode(", ", $campos); 
            }
        }

        if(!empty($order)) {

            if(is_array($order)) {
                $order = " ORDER BY ". implode(', ', $order);
            } else {
                $order = " ORDER BY {$order}";
            }
        }

        if(!empty($where)) {

            if(is_array($where)) {
                $where = " WHERE ". implode(' AND ', $where);
            } else {
                $where = " WHERE {$order}";
            }
        }

        return $sql = "
            SELECT 
                {$campos} 
            FROM
                arquivoestorageassinaturas
            INNER JOIN assinaturasdocumento ON db178_sequencial = db179_assinatura
            INNER JOIN arquivoestorage ON db177_idestorage = db179_arquivo
            INNER JOIN documentoportaria ON rh235_documento = db177_idestorage
            INNER JOIN portaria ON h31_sequencial = rh235_portaria
            LEFT  JOIN portariatipodocindividual ON h37_portariatipo = h31_portariatipo
            LEFT  JOIN db_relatorio ON db63_sequencial = h37_modportariaindividual
            LEFT  JOIN assinaturadocumentodesignacao ON db59_relatorio = db63_sequencial
            {$where}
            GROUP BY
                portaria.h31_sequencial
                ,portaria.h31_numero
                ,portaria.h31_anousu

            {$order}
            ;
        ";
    }
}