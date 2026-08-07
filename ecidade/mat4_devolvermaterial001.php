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

$DB_SERVIDOR = "dev20";
$DB_BASE     = "auto_osorio_20130220_v2_3_7";
$DB_PORTA    = "5433";
$DB_USUARIO  = "postgres";
$DB_SENHA    = "";
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conn.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/estoque/Almoxarifado.model.php"));


  $rsOpen  = fopen("/tmp/log_retorno_transferencia.log", "w");
  $conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
  if (!$conn) {
    die("Impossível conectar-se com a base de dados.");
  }

  session_start();
  db_putsession("DB_datausu", time());
  db_putsession("DB_id_usuario", 1);
  db_putsession("DB_acessado", 1);

  db_query("select fc_startsession()");
  db_query("select fc_putsession('DB_anousu', '2013')");

  try {

    db_inicio_transacao();

    $sSqlMaterial  = " select distinct matestoqueini.m80_codigo as codigolancamento,                                                 ";
    $sSqlMaterial .= "                 matestoque.m70_coddepto as departamento_origem,                                               ";
    $sSqlMaterial .= "                 matestoquetransf.m83_coddepto as depart_destino,                                              ";
    $sSqlMaterial .= "                 m82_quant as quantidade_transferida,                                                          ";
    $sSqlMaterial .= "                 (m82_quant * m89_precomedio) as valor_total,                                                  ";
    $sSqlMaterial .= "                 m83_matestoqueini                                                                             ";
    $sSqlMaterial .= "   from matestoquetransf                                                                                       ";
    $sSqlMaterial .= "        inner join db_depart a on a.coddepto = matestoquetransf.m83_coddepto                                   ";
    $sSqlMaterial .= "        inner join matestoqueini on matestoqueini.m80_codigo = matestoquetransf.m83_matestoqueini              ";
    $sSqlMaterial .= "        inner join db_depart on db_depart.coddepto = matestoqueini.m80_coddepto                                ";
    $sSqlMaterial .= "        inner join db_usuarios on db_usuarios.id_usuario = matestoqueini.m80_login                             ";
    $sSqlMaterial .= "        inner join matestoqueinimei on matestoqueinimei.m82_matestoqueini = matestoqueini.m80_codigo           ";
    $sSqlMaterial .= "        inner join matestoqueinimeipm on matestoqueinimeipm.m89_matestoqueinimei = matestoqueinimei.m82_codigo ";
    $sSqlMaterial .= "        inner join matestoqueitem on matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem          ";
    $sSqlMaterial .= "        inner join matestoque on matestoque.m70_codigo = matestoqueitem.m71_codmatestoque                      ";
    $sSqlMaterial .= "        left join matestoqueinil on matestoqueinil.m86_matestoqueini = matestoqueini.m80_codigo                ";
    $sSqlMaterial .= "        left join matestoqueinill on matestoqueinill.m87_matestoqueinil = matestoqueinil.m86_codigo            ";
    $sSqlMaterial .= "        left join matestoqueini b on b.m80_codigo = matestoqueinill.m87_matestoqueini                          ";
    $sSqlMaterial .= "  where b.m80_codigo is null                                                                                   ";
    $sSqlMaterial .= "  order by m83_matestoqueini                                                                                  ";

    $rsBuscaMaterial = db_query($sSqlMaterial);
    if ( !$rsBuscaMaterial ) {
      throw new Exception("Ñão foi possível executar o SQL para verificar as transferências em aberto.");
    }

    $iTotalTransferencias = pg_num_rows($rsBuscaMaterial);
    if ( $iTotalTransferencias == 0 ) {
      throw new Exception("Nao foi localizada nenhuma transferencia em aberto.");
    }

    $aCodigosAlmoxarifado = array();

    echo "### Iniciando Processamento ###\n";
    echo "Total de Transferencias Pendentes: {$iTotalTransferencias}\n\n";

    for ($iRowTransferencia = 0; $iRowTransferencia < $iTotalTransferencias; $iRowTransferencia++) {

      $oStdDadosTransferencia  = db_utils::fieldsMemory($rsBuscaMaterial, $iRowTransferencia);
      db_putsession("DB_coddepto", $oStdDadosTransferencia->departamento_origem);
      if ( !array_key_exists($oStdDadosTransferencia->departamento_origem, $aCodigosAlmoxarifado) ) {

        $oDaoAlmox           = db_utils::getDao("db_almox");
        $sSqlAlmoxarifado    = $oDaoAlmox->sql_query_file(null,
                                                          "m91_codigo",
                                                          null,
                                                          "m91_depto = {$oStdDadosTransferencia->departamento_origem}");
        $iCodigoAlmoxarifado = db_utils::fieldsMemory($oDaoAlmox->sql_record($sSqlAlmoxarifado), 0 )->m91_codigo;
        $aCodigosAlmoxarifado[$oStdDadosTransferencia->departamento_origem] = $iCodigoAlmoxarifado;
        unset($oDaoAlmox);
      }

      addLog($rsOpen, "[INFO] - Cancelando transferencia: {$oStdDadosTransferencia->m83_matestoqueini}");
      echo "[INFO] - Cancelando transferencia: {$oStdDadosTransferencia->m83_matestoqueini}\n";

      $oAlmoxarifado = new Almoxarifado($aCodigosAlmoxarifado[$oStdDadosTransferencia->departamento_origem]);
      $oAlmoxarifado->cancelarTransferencia($oStdDadosTransferencia->m83_matestoqueini);

    }

    addLog($rsOpen, "\n FIM DO PROCESSAMENTO\n\n");
    echo "[INFO] - Arquivo de log salvo em: /tmp/log_retorno_transferencia.log\n\n";
    $rsClose = fclose($rsOpen);
    db_fim_transacao(false);

  } catch (Exception $eErro) {

    db_fim_transacao(true);
    addLog($rsOpen, "[ERRO] - Erro ao cancelar:\n {$eErro->getMessage()}\n\n");
    echo "\n\n[ERRO]: {$eErro->getMessage()}\n\n";
  }


  function addLog($rsOpen, $sLog) {
    $rsWhite = fwrite($rsOpen, $sLog."\n");
  }
?>