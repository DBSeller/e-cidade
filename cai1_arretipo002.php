<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_arretipo_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

use App\Domain\Tributario\Arrecadacao\Repositories\ConfiguracoesteftipodebitoRepository;
use App\Domain\Tributario\Arrecadacao\Models\Configuracoesteftipodebito;
use App\Domain\Tributario\Arrecadacao\Models\Operacoesteftipodebito;
use App\Domain\Tributario\Arrecadacao\Repositories\OperacoesteftipodebitoRepository;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clarretipo = new cl_arretipo;

$db_opcao   = 22;
$db_botao   = false;
$lErro      = false;

$configuracoesteftipodebitoRepository = new ConfiguracoesteftipodebitoRepository();
$operacoesteftipodebitoRepository = new OperacoesteftipodebitoRepository();

if (isset($alterar)) {
    $db_opcao = 2;
    $sMensagem = null;

    if (!empty($k00_dtVencimentoDay)) {
        $clarretipo->k00_dtvencimento = $k00_dtVencimentoDay;
    }

    if (!empty($k00_dtVencimentoDate)) {
        $oDate = new \DBDate($k00_dtVencimentoDate);
        $clarretipo->k00_dtvencimento = $oDate->getDate();
    }

    if ($selectTipoVenc == 1) {
        $clarretipo->k00_dtvencimento =  db_getsession("DB_anousu");
    }

    if (empty($clarretipo->k00_dtvencimento)) {
        $clarretipo->k00_dtvencimento  = null;
    }

    db_inicio_transacao();


    $clarretipo->alterar($k00_tipo);
    if ($clarretipo->erro_status == '0') {
        var_dump($clarretipo->erro_msg);
        die;
        $lErro = true;
    }

    $configuracoesteftipodebito = new Configuracoesteftipodebito();
    $configuracoesteftipodebito->setSequencial($k196_sequencial);
    $configuracoesteftipodebito->setTipo($k00_tipo);
    $configuracoesteftipodebito->setAceitatef(isset($k196_aceitatef) ? "t" : "f");
    $configuracoesteftipodebito->setMaximoparcelas($k196_maximoparcelas);
    $configuracoesteftipodebito->setValorminimoparcelafisica($k196_valorminimoparcelafisica);
    $configuracoesteftipodebito->setValorminimoparcelajuridica($k196_valorminimoparcelajuridica);

    $k196_sequencial = $configuracoesteftipodebitoRepository->salvar($configuracoesteftipodebito);

    $operacoesteftipodebito = new Operacoesteftipodebito();
    $operacoesteftipodebito->setConfiguracoesteftipodebito($k196_sequencial);

    $operacoesteftipodebitoRepository->deleteByConfig($k196_sequencial);

    foreach ($operacoes as $operacao) {
        if (intval($operacao) == 3 && empty($k196_maximoparcelas)) {
            $sMensagem = "Preencha o campo Máximo de Parcelas.";
            $lErro = true;
        }

        $operacoesteftipodebito->setOperacoestef($operacao);
        $operacoesteftipodebitoRepository->salvar($operacoesteftipodebito);
    }

    db_fim_transacao($lErro);
} elseif (isset($chavepesquisa)) {
    $db_opcao = 2;
    $result   = $clarretipo->sql_record($clarretipo->sql_query($chavepesquisa));
    $db_botao = true;

    db_fieldsmemory($result, 0);

    $receitacreditodescr = isset($k02_descr) ? $k02_descr : '';
    $tipodescr           = $k00_descr;

    $oConfiguracoesteftipodebito = $configuracoesteftipodebitoRepository->getByTipo($chavepesquisa);

    db_postmemory((array) $oConfiguracoesteftipodebito);

    if ($oConfiguracoesteftipodebito->k196_sequencial) {
        $aOperacoes = $operacoesteftipodebitoRepository->getByConfig($oConfiguracoesteftipodebito->k196_sequencial);

        $aOperacoesSalvas = array_map(function ($oOperacao) {
            return $oOperacao->k197_operacoestef;
        }, $aOperacoes);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<?php

    db_app::load('prototype.js, scripts.js, DBAbas.widget.js, DBAbasItem.widget.js');
    db_app::load("estilos.css, DBtab.style.css, time.js");

?>


<style>
body {
  padding: 0;
  margin: 16px 0 0 0;
}

.fieldsetPrincipal{
    width: 750px;
    margin: 20px auto 0 auto;
}

.fieldsetInterno{
    width: 750px;
    margin: 25px auto 0 auto;
}

.fieldsetSecundario{
    width: 700px;
    margin: 0 auto 0 auto;
}

fieldset legend {
 font-weight: bold;
}

</style>
</head>
<body bgcolor=#CCCCCC>
  <?php
    require_once(modification("forms/db_frmarretipo001.php"));
        db_menu();
    ?>
</body>
<?php
if (isset($alterar)) {
    if ($clarretipo->erro_status == "0" && !$lErro) {
        $clarretipo->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

        if ($clarretipo->erro_campo != "") {
            echo "<script> document.form1.".$clarretipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clarretipo->erro_campo.".focus();</script>";
        }
    } else {
        if (empty($sMensagem)) {
            $clarretipo->erro(true, true);
        } else {
            db_msgbox($sMensagem);
        }
    }
}

if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","k00_codage",true,1,"k00_codage",true);
</script>
</html>
