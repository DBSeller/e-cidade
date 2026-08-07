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

class cl_assinaturadocumentodesignacao extends DAOBasica
{
    function __construct()
    {
        parent::__construct('configuracoes.assinaturadocumentodesignacao');
    }

    /**
     * Busca assinantes pelo código do tipo da portaria
     * 
     * @param int $codigoTipoPortaria
     * @return String
     */
    public function sql_query_assinantes_tipo_portaria($codigoTipoPortaria)
    {
        $sql = "
            SELECT
                assinaturadocumentodesignacao.*,
                cgm.z01_cgccpf as cpf_cnpj,
                cgm.z01_nome as nome
            FROM
                assinaturadocumentodesignacao
            INNER JOIN db_relatorio
                ON db_relatorio.db63_sequencial = assinaturadocumentodesignacao.db59_relatorio
            INNER JOIN portariatipodocindividual
                ON  portariatipodocindividual.h37_modportariaindividual = db_relatorio.db63_sequencial
            INNER JOIN portariatipo
                ON portariatipo.h30_sequencial = portariatipodocindividual.h37_portariatipo
            INNER JOIN tipoasse
                ON tipoasse.h12_codigo = portariatipo.h30_tipoasse
            INNER JOIN db_usuacgm
                ON db_usuacgm.id_usuario = assinaturadocumentodesignacao.db59_usuario
            INNER JOIN cgm
                ON cgm.z01_numcgm = db_usuacgm.cgmlogin
            WHERE 
                portariatipo.h30_sequencial = {$codigoTipoPortaria};
        ";

        return $sql;
    }
}