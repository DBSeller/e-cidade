<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_libsys.php"));
require_once(modification("fpdf151/pdf1.php"));
require_once(modification("dbagata/classes/core/AgataAPI.class"));
require_once(modification("model/documentoTemplate.model.php"));
require_once(modification("std/DBLargeObject.php"));
require_once("std/db_stdClass.php");
require_once(modification("dbforms/db_funcoes.php"));

try {
    $oGet  = db_utils::postmemory($_GET);

    $sTemplateAlvara = '';

    ini_set("error_reporting","E_ALL & ~NOTICE");

    $clisstipoalvara = new cl_isstipoalvara;

    $sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");
    $sNomeRelatorio   = "tmp/bicveiculo{$sDescrDoc}.pdf";
    $sCaminhoSalvoSxw = "tmp/bicveiculo_{$sDescrDoc}_{$oGet->codigoVeiculo}.sxw";

    $sAgt = "issqn/alvaraveiculo.agt";

    /**
    * Retorna o modelo do alvara a ser impresso
    */
    $sSqlTipoAlvara  = " select db82_sequencial, db82_templatetipo ";
    $sSqlTipoAlvara .= "  from issqn.parissqn ";
    $sSqlTipoAlvara .= "       inner join db_documentotemplate on db82_sequencial = q60_templatebicveiculo";
    $sSqlTipoAlvara .= " LIMIT 1 ";

    $rsTipoAlvara    = $clisstipoalvara->sql_record($sSqlTipoAlvara);

    if(pg_num_rows($rsTipoAlvara) < 1){
        throw new Exception("Configuração de Template BIC de veículos não encontrada");
    }

    $oTipoAlvara     = db_utils::fieldsMemory($rsTipoAlvara,0);

    $variaveisSessao = getVariaveisSessao();

    $aParam                  = array();
    $aParam['$codigoveiculo'] = $oGet->codigoVeiculo;
    $aParam['$instituicao']  = $variaveisSessao->instit;
    $aParam['$usuario']      = $variaveisSessao->id_usuario;
    $aParam['$data']         = $variaveisSessao->datausu;
    $aParam['$login']        = $variaveisSessao->login;

    db_stdClass::oo2pdf(
        $oTipoAlvara->db82_templatetipo,
        $oTipoAlvara->db82_sequencial,
        $sAgt,
        $aParam,
        $sCaminhoSalvoSxw,
        $sNomeRelatorio
    );

    exit;

} catch (Exception $e) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Erro ao gerar alvará de veiculos: {$e->getMessage()}");
}

function getVariaveisSessao()
{
    return (object) array(
        "instit"     => db_getsession('DB_instit'),
        "anousu"     => db_getsession('DB_anousu'),
        "id_usuario" => db_getsession('DB_id_usuario'),
        "datausu"    => db_getsession('DB_datausu'),
        "ip"         => db_getsession('DB_ip'),
        "base"       => db_getsession('DB_base'),
        "login"      => db_getsession('DB_login')
    );
}