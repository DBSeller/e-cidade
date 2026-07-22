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


require_once(modification("model/empenho/AutorizacaoEmpenho.model.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conn.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$DB_SERVIDOR  = "localhost";
$DB_BASE      = "riopardo";
$DB_USUARIO   = "postgres";
$DB_SENHA     = "";
$DB_PORTA     = "5432";
$DB_PORTA_ALT = "5432";

$rsConexao = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
if (!$rsConexao) {
  echo "Impossivel conectar-se a base de dados."; exit;
}

pg_query("select fc_startsession();");

$rsArquivo = fopen('tmp/gerarreservasaldoautorizacao.log','w');

try {

  $sSqlBuscaAutorizacao = "select e54_autori, e54_valor, e54_emiss, e56_coddot 
                             from empautoriza 
                                  inner join empautidot    on empautidot.e56_autori    = empautoriza.e54_autori
                                  left  join orcreservaaut on orcreservaaut.o83_autori = empautoriza.e54_autori
                                  left  join empempaut     on empempaut.e61_autori     = empautoriza.e54_autori
                            where empautoriza.e54_anulad   is null
                              and orcreservaaut.o83_autori is null
                              and empempaut.e61_autori     is null  
                              and empautoriza.e54_emiss >= '2013-10-14' ";

  $rsBuscaAutorizacao = pg_query($sSqlBuscaAutorizacao);
  if (!$rsBuscaAutorizacao) {
    fwrite($rsArquivo,"Ocorreu um erro ao executar a query.\n\n[QUERY] - ".$sSqlBuscaAutorizacao."\n\n");
    throw new Exception("Ocorreu um erro ao executar a query.\n\n[QUERY] - {$sSqlBuscaAutorizacao}\n\n");
  }
  
  echo "-> Inicio do Processamento\n";  

  $iTotalLinhas = pg_num_rows($rsBuscaAutorizacao);
  for ($iRow = 0; $iRow < $iTotalLinhas; $iRow++) {

    pg_query("begin");
    $oStdAutorizacao      = pg_fetch_object($rsBuscaAutorizacao, $iRow);
    $iSequenciaOrcReserva = pg_fetch_object(pg_query("select nextval('orcreserva_o80_codres_seq') as sequencial"))->sequencial;
    $sSqlInsereOrcReserva = "insert into orcreserva ( o80_codres
                                                     ,o80_anousu
                                                     ,o80_coddot
                                                     ,o80_dtfim 
                                                     ,o80_dtini 
                                                     ,o80_dtlanc
                                                     ,o80_valor 
                                                     ,o80_descr )
                                             values ({$iSequenciaOrcReserva}
                                                     ,2013
                                                     ,{$oStdAutorizacao->e56_coddot}
                                                     ,'2013-12-31' 
                                                     ,'{$oStdAutorizacao->e54_emiss}'
                                                     ,'{$oStdAutorizacao->e54_emiss}'
                                                     ,{$oStdAutorizacao->e54_valor}
                                                     ,'Reserva de saldo automatica')";

    $rsInsereOrcReserva = pg_query($sSqlInsereOrcReserva);
    
    if (!$rsInsereOrcReserva) {
       fwrite($rsArquivo,"ERRO na orcreserva / autorizacao: ".$oStdAutorizacao->e54_autori." - ".pg_last_error()."\n");
       pg_query("rollback");
       continue;
    }

    $sSqlInsereVinculo = "insert into orcreservaaut (o83_codres, o83_autori)
                                             values ({$iSequenciaOrcReserva}, {$oStdAutorizacao->e54_autori})";
    
    $rsInsereVinculo   = pg_query($sSqlInsereVinculo);
    
    if (!$rsInsereVinculo) {
      fwrite($rsArquivo,"ERRO na orcreservaaut / autorizacao: ".$oStdAutorizacao->e54_autori." - ".pg_last_error()."\n");
      pg_query("rollback");
      continue;
    }

    pg_query("commit");
    fwrite($rsArquivo,"Autorizacao: ".$oStdAutorizacao->e54_autori." - OK\n");
    echo "Autorizacao: {$oStdAutorizacao->e54_autori} - OK\n";
  }

  echo "-> Fim do Processamento\n";

} catch (Exception $eErro) {

  pg_query("rollback");
  echo "[ERRO] - {$eErro->getMessage()}\n";
} 
?>