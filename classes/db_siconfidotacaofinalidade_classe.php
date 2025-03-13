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
 * Class cl_siconfidotacaofinalidade
 * @property integer c119_sequencial
 * @property integer c119_coddot
 * @property integer c119_anous
 * @property integer c119_tipo
 *
 * c119_tipo = 1 = MDE
 * c119_tipo = 2 = ASPS
 */
class cl_siconfidotacaofinalidade extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('contabilidade.siconfidotacaofinalidade');
    }

    public function sql_query_dados_dotacao($campos="*", $ordem=null, $where=null){

        $sWhere = "";
        if (!empty($where)) {
            $sWhere = "where {$where} ";
        }

        $sOrdem = "";
        if (!empty($ordem)) {
            $sOrdem = "order by {$ordem} ";
        }

        $sSql = "   select c119_sequencial as sequencial, 
                           c119_coddot as dotacao, 
                           o41_descr as descricao, 
                           c119_tipo as tipo 
                      from siconfidotacaofinalidade 
                           inner join orcdotacao on orcdotacao.o58_coddot = siconfidotacaofinalidade.c119_coddot 
                                                and siconfidotacaofinalidade.c119_anousu = orcdotacao.o58_anousu 
                           inner join orcunidade on orcunidade.o41_unidade = orcdotacao.o58_unidade 
                                                and orcunidade.o41_orgao   = orcdotacao.o58_orgao 
                                                and orcunidade.o41_anousu  = orcdotacao.o58_anousu 
                     $sWhere
                     $sOrdem ";
        return $sSql;

    }


}
