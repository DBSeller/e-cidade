<?PHP

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_cone"."cta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_movrel_classe.php"));
require_once(modification("classes/db_convenio_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rharqbanco_classe.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/JSON.php"));


$db_botao = true;


$oPost = db_utils::postMemory($_POST);

if(isset($oPost->gerar)) {

    try{

        $sLogin = db_getsession('DB_login');
        $iIdUsuario = db_getsession("DB_id_usuario");

        //if (db_getsession('DB_login') === "dbseller" && db_getsession("DB_id_usuario") === "1") {

        if (  ($sLogin != "dbseller" ) ||  db_getsession("DB_id_usuario") != "1"  ) {
            throw new Exception("Contate suporte para execussão da rotina.");
        }

        db_inicio_transacao();

        $sHora = date('hms');

        $nomearq = $_FILES["planilha"]["name"];

        // Nome do arquivo temporário gerado no /tmp
        $nometmp = $_FILES["planilha"]["tmp_name"];

        // Seta o nome do arquivo destino do upload
        $arquivoprocessa = "tmp/planilhaCorrecaoContratosPad_{$sHora}.csv";

        // Faz um upload do arquivo para o local especificado
        if (  !move_uploaded_file($nometmp, $arquivoprocessa )  ) {

            throw new Exception("Erro ao Mover arquivo, verifique permissões de Servidor.");
        }

        $sSql = "

          DROP TABLE  IF EXISTS  w_contratos;
          DROP TABLE  IF EXISTS  w_ajuste_contratos;

          create temp table w_contratos (data_lancamento  varchar,
                                         cod_lancamento   varchar,
                                         empenho          varchar,
                                         nro_contrato     varchar,
                                         ano_contrato     varchar,
                                         valor_liquidacao varchar,
                                         instrumento      int4
                                   );

         ";

         if ( !db_query($sSql ) ){
            throw new Exception("Erro ao Criar Tabelas Temporárias.");
         }


         $iLinha = 0;
         $file = new SplFileObject($arquivoprocessa);

         while ( !$file->eof() ) {

             $aLinha = $file->fgetcsv(";");

             // ignora o cabecalho
            if ( ($iLinha == "0" || $iLinha == 0) ||
                  $aLinha[0] == "data_lancamento" || $aLinha[0] == "") {

                $iLinha = 1;
                continue;
            }

            if (count($aLinha) <= 0) {
                throw new Exception("Erro Buscar Delimitador do Arquivo. Verifique o separador de campos.");
            }


            $dtLancamento = $aLinha[0];
            $CodLancamento = $aLinha[1];
            $empenho = $aLinha[2];
            $Contrato = $aLinha[3];
            $AnoContrato = $aLinha[4];
            $nValorLiquidado = $aLinha[5];
            $instrumento = $aLinha[6];

            if ($dtLancamento == '') {

                continue;
            }

            $sSqlTemporario = "
              insert into w_contratos select '$dtLancamento',
                                             '$CodLancamento',
                                             '$empenho',
                                             '$Contrato',
                                             '$AnoContrato',
                                             '$nValorLiquidado',
                                             '$instrumento'
            ";
            if ( !db_query($sSqlTemporario) ) {
                throw new Exception("Erro ao incluir dados temporário.");
            }

        }

        // com os dados preparados na tabela tempo?aria

        $sSql = <<<SQL

             create temp table w_ajuste_contratos as
             select plugins.contratospadrs.*,
                    w_contratos.nro_contrato,
                    w_contratos.ano_contrato,
                    instrumento
               from plugins.contratospadrs
             inner join w_contratos on plugins.contratospadrs.lancamento = w_contratos.cod_lancamento::integer ;

             update plugins.contratospadrs
                set numero = w_ajuste_contratos.nro_contrato,
                    ano = w_ajuste_contratos.ano_contrato,
                    instrumentocontratual = instrumento
               from w_ajuste_contratos
              where plugins.contratospadrs.lancamento = w_ajuste_contratos.lancamento;

SQL;

             if ( !db_query($sSql) ) {
                 throw new Exception("Erro ao Copiar dados temporário." . pg_last_error());
             }


        db_msgbox("Arquivo Processado com Sucesso.");

        db_fim_transacao(false);

    } catch ( Exception $Exception ) {

        db_fim_transacao(true);
        db_msgbox($Exception->getMessage());
    }
}


?>
    <html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">


        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>

    </head>
    <body class="body-default" onLoad="a=1" >
    <div class="container">

        <form name="form1" method="post" action="" enctype="multipart/form-data">
            <fieldset style="width: 520px;">
                <legend>Ajuste Contratos PAD Via Planilha </legend>
                <table class="form-container">

                   <tr>
                     <td>Ano:</td>
                     <td>
                     <?php
                            $iAno = db_getsession('DB_anousu');
                            db_input('iAno', 10, "", true, 'text', 1 );
                            ?>
                     </td>
                   </tr>
                    <tr>
                        <td align='left' nowrap>

                            <strong>Planilha:</strong>
                        </td>
                        <td nowrap align='left'>
                            <?php
                            db_input('planilha', 49, "", true, 'file', 1 );
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>

            <input name="gerar"
                   type="submit"
                   id="db_opcao"
                   value="Processar"
                <?=($db_botao == false ? "disabled" : "")?>
                   onclick="return js_verifica_campos();">
        </form>
        <?php  db_menu();  ?>
    </div>
    </body>

<script>

    function js_verifica_campos(){


        var arquivo = $F('planilha');

        if ( arquivo == '' ) {

            alert('Selecione uma Planilha para Correção.');
            return false;
        }

    }

</script>

