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

class cl_arquivoestorage extends DAOBasica
{
    function __construct()
    {
        parent::__construct('configuracoes.arquivoestorage');
    }

    /**
     * Busca id e url do arquivo do estorage pelo código da portaria.
     * 
     * @param int $codigoTipoPortaria
     * @return String
     */
    public function sql_query_estorage_por_portaria($codigoPortaria)
    {
        return "
            SELECT
                db177_idestorage,
                db177_url,
                portaria.h31_numero,
                portaria.h31_anousu
            FROM
                arquivoestorage
            INNER JOIN documentoportaria
                ON documentoportaria.rh235_documento = arquivoestorage.db177_idestorage
            INNER JOIN portaria
                ON portaria.h31_sequencial = documentoportaria.rh235_portaria
            WHERE
                documentoportaria.rh235_portaria = {$codigoPortaria}
            ORDER BY
                documentoportaria.rh235_portaria desc
            LIMIT 1
        ";
    }
}