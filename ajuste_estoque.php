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
 
require_once modification("libs/db_utils.php");
require_once modification("libs/db_stdlib.php");
$DB_SERVIDOR = "dev01";
$DB_BASE     = "virada2014_marica_ecidade_20131210_v2_3_19";
$DB_PORTA    = "5433";
$DB_USUARIO  = "postgres";
$DB_SENHA    = "";

/**
 * ORDEM DOS PARÂMETROS
 *
 * 1 - Implantar o Estoque, por padrão = FALSE
 * 2 - Apaga os Lançamentos contábeis, padrão : FALSE
 * 3 - Nome do Arquivo - padrão false (somente arquivo '.txt')
 * === Caso não seja informado um arquivo, o programa irá buscar todos os materiais cadastrado na base de dados.
 * 
 * 4 - Apagar lançamentos dos materiais : padrão false
 * 5 - Comitar o processamento executado. Padrão FALSE
 * 
 * Como utilizar o programa:
 * Sintaxe: php ajuste_estoque.php [implantar_estoque] [apagar_lancamento_contabil] [arquivo] [apagar_lancamento_material] [commit]
 * Ex: php ajuste_estoque.php true false arquivo.txt true true
 * Ex: php ajuste_estoque.php true false null true true
 */


$oParametros = validarParametros($argv);

echo ("<pre>".print_r($oParametros, 1)."</pre>\n\n");

$pArquivoAberto = fopen("tmp/ajuste_estoque_".date("Y_m_d_his").".log", "w");

$sParametrosUtilizados  = "Implantar Estoque: {$oParametros->lImplantarEstoque}\n";
$sParametrosUtilizados .= "Apagar Lançamentos Contábeis: {$oParametros->lApagarLancamentosContabeis}\n";
$sParametrosUtilizados .= "Utilizou Arquivo: {$oParametros->lUtilizaArquivo}\n";
$sParametrosUtilizados .= "Nome do Arquivo: {$oParametros->sNomeArquivo}\n";
$sParametrosUtilizados .= "Comitou: {$oParametros->lComitar}\n";
$sParametrosUtilizados .= "Apagar Lancamentos Contabeis: {$oParametros->lApagarLancamentosMateriais}\n\n";


db_log($pArquivoAberto, $sParametrosUtilizados);

$iAnoUsu  = 2013;
if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {

  db_log($pArquivoAberto, "\n\nNão foi possível se conectar com a base de dados.\n\n");
  die("\n\nNão foi possível se conectar com a base de dados.\n\n");
}
 
if ($oParametros->lUtilizaArquivo) {
  
  $aLinhas  = file($oParametros->sNomeArquivo);
  $iCodItem = str_replace(array("\n", "\r", "\t",), "", implode(",", $aLinhas));

} else {

  db_query("select fc_startsession();");
  $sSqlBuscaMateriais  = " select array_to_string(array_accum(m70_codmatmater), ',') as m70_codmatmater ";
  $sSqlBuscaMateriais .= "   from matestoque ";
  $sSqlBuscaMateriais .= "        inner join db_depart on db_depart.coddepto = matestoque.m70_coddepto ";
 // $sSqlBuscaMateriais .= "  where db_depart.instit = (select codigo from db_config where prefeitura is true) ";
  $rsExecutaBusca      = db_query($sSqlBuscaMateriais);
  if (!$rsExecutaBusca) {

    echo "Erro na busca dos materiais.\n";
    exit;
  }
  $iCodItem = db_utils::fieldsMemory($rsExecutaBusca, 0)->m70_codmatmater;
}

db_query("begin;");

$sSql  = "select fc_startsession();";
$aArrayComandos[] = $sSql;

$sSql  = "select fc_putsession('db_anousu', '{$iAnoUsu}');";
$aArrayComandos[] = $sSql;

$sSql = "select fc_putsession('__status_tg_matestoque_alt', 'disable');";
$aArrayComandos[] = $sSql;

$sSql = " alter table matestoqueitem disable trigger tg_matestoqueitem_inc_alt;";
$aArrayComandos[] = $sSql;

/**
 * Verifica se o SCRIPT já foi executado.
 * 
 */
if (pg_num_rows( db_query("select * from pg_tables where tablename = 'acerto_estoque_saldo_ordem_compra'") ) > 0) {

  db_log($pArquivoAberto, "\n\nO script já foi executado. Realize backup da tabela 'acerto_estoque_saldo_ordem_compra'\n\n");
  die("\n\nO script já foi executado. Realize backup da tabela 'acerto_estoque_saldo_ordem_compra'\n\n");
}

$sSql  = "drop table if exists acerto_estoque_saldo_ordem_compra; ";
$sSql .= "create table acerto_estoque_saldo_ordem_compra ( ";
$sSql .= "   codigo_item_ordem integer, ";
$sSql .= "   quantidade_final numeric(15, 2), ";
$sSql .= "   valor_final numeric(15, 2) ";
$sSql .= "); ";

$aArrayComandos[] = $sSql;

$sSql = "create index acerto_estoque_saldo_ordem_compra_codigo_item_ordem_in on acerto_estoque_saldo_ordem_compra(codigo_item_ordem); ";
$aArrayComandos[] = $sSql;

$sSql  = "insert into acerto_estoque_saldo_ordem_compra  ";
$sSql .= "       select m73_codmatordemitem,saldo_quantidade, saldo_valor from (select m73_codmatordemitem,  ";
$sSql .= "                             (select m52_quant  ";
$sSql .= "                                from matordemitem ";
$sSql .= "                               where m52_codlanc = m73_codmatordemitem) as qtd_ordem, ";
$sSql .= "       sum(case when m80_codtipo = 12 then m71_quant when m80_codtipo = 19 then m71_quant * -1 else  0 end) as saldo_quantidade, ";
$sSql .= "       sum(case when m80_codtipo = 12 then m71_valor when m80_codtipo = 19 then m71_valor * -1 else  0 end) as saldo_valor ";
$sSql .= "  from matestoqueitem  ";
$sSql .= "       inner join matestoqueitemoc on m73_codmatestoqueitem = m71_codlanc ";
$sSql .= "       inner join matestoqueinimei on m71_codlanc           = m82_matestoqueitem ";
$sSql .= "       inner join matestoqueini    on m82_matestoqueini     = m80_codigo ";
$sSql .= " where m80_codtipo in(12,19) ";
$sSql .= " group by m73_codmatordemitem) as x; ";
$aArrayComandos[] = $sSql;

$sSql  = "drop table if exists w_registros_apagar_acerto_estoque; ";
$aArrayComandos[] = $sSql;

$sSql  = "create table w_registros_apagar_acerto_estoque  as  ";
$sSql .= "select *   ";
$sSql .= "  from matestoque  ";
$sSql .= "       inner join matestoqueitem   on m70_codigo        = m71_codmatestoque ";
$sSql .= "       inner join matestoqueinimei on m71_codlanc       = m82_matestoqueitem ";
$sSql .= "       inner join matestoqueini    on m82_matestoqueini = m80_codigo ";
$sSql .= "       left join conlancammatestoqueinimei  on c103_matestoqueinimei = m82_codigo ";
$sSql .= "where  m70_codmatmater in ($iCodItem) ";
$aArrayComandos[] = $sSql;

$sSql = "drop table if exists w_dados_saldo_matestoque; ";
$aArrayComandos[] = $sSql;

$sSql  = "create table w_dados_saldo_matestoque as  ";
$sSql .= "select * from matestoque where m70_codmatmater in ($iCodItem); ";
$aArrayComandos[] = $sSql;

/** 
 *Deletar lancamentos contabeis 
 */ 
$sSql = " delete from conlancamval                where c69_codlan in(select c103_conlancam From w_registros_apagar_acerto_estoque where c103_conlancam is not null); ";
$aArrayComandos[] = $sSql;
$sSql = " delete from conlancamcompl              where c72_codlan in(select c103_conlancam From w_registros_apagar_acerto_estoque where c103_conlancam is not null); ";
$aArrayComandos[] = $sSql;
$sSql = " delete from conlancamdoc                where c71_codlan in(select c103_conlancam From w_registros_apagar_acerto_estoque where c103_conlancam is not null); ";
$aArrayComandos[] = $sSql;
$sSql = " delete from conlancammatestoqueinimei   where c103_conlancam in(select c103_conlancam From w_registros_apagar_acerto_estoque where c103_conlancam is not null); ";
$aArrayComandos[] = $sSql;
$sSql = " delete from conlancam                   where c70_codlan in(select c103_conlancam From w_registros_apagar_acerto_estoque where c103_conlancam is not null); ";
$aArrayComandos[] = $sSql;

/** 
 *deletar registros do estoque 
 */ 
$sSql = "delete from matestoqueinimeimdi  where m50_codmatestoqueinimei   in (select m82_codigo  from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueinimeipm  where m89_matestoqueinimei    in (select m82_codigo  from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueinimeiari where m49_codmatestoqueinimei in (select m82_codigo  from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueinimeimatpedidoitem where m99_matestoqueinimei in (select m82_codigo  from w_registros_apagar_acerto_estoque)";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueinimei   where m82_codigo           in (select m82_codigo  from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from atendrequiitemmei    where m44_codmatestoqueitem     in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueitemoc     where m73_codmatestoqueitem     in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueitemnota   where m74_codmatestoqueitem     in (select m71_codlanc from w_registros_apagar_acerto_estoque);  ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueitemunid   where m75_codmatestoqueitem     in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueitemfabric   where m78_matestoqueitem     in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueitemlote   where m77_matestoqueitem        in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from far_retiradaitemlote where fa09_i_matestoqueitem     in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoquedevitemmei     where m47_codmatestoqueitem     in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoquetransferencia  where m84_matestoqueitem    in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from empnotaitembenspendente  where e137_matestoqueitem   in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueitemnotafiscalmanual where m79_matestoqueitem  in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoqueitem           where m71_codlanc               in (select m71_codlanc from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;
$sSql = "delete from matestoque               where m70_codigo in (select m70_codigo from w_registros_apagar_acerto_estoque); ";
$aArrayComandos[] = $sSql;

$sSql = "delete from matestoquedevitem where m46_codmatrequiitem in(select m41_codigo from matrequiitem where m41_codmatmater in({$iCodItem}))";
$aArrayComandos[] = $sSql;

$sSql = "delete from atendrequiitem where m43_codmatrequiitem in(select m41_codigo from matrequiitem where m41_codmatmater in({$iCodItem}))";
$aArrayComandos[] = $sSql;

$sSql = "delete from matanulitemrequi where m102_matrequiitem in (select m41_codigo from matrequiitem where m41_codmatmater in({$iCodItem}))";
$aArrayComandos[] = $sSql;

$sSql = "delete from matrequiitem where m41_codmatmater in({$iCodItem})";
$aArrayComandos[] = $sSql;

$sSql = "delete from matmaterprecomedioini where m88_matmaterprecomedio in(select m85_sequencial from matmaterprecomedio  where m85_matmater in( {$iCodItem}))";
$aArrayComandos[] = $sSql;

$sSql = "delete from matmaterprecomedio  where m85_matmater in ({$iCodItem})";
$aArrayComandos[] = $sSql;


/** 
 * Recriando os dados do item, apenas com os saldos finais 
 * 1 incluir novamente na matestoque 
 * 2 incluir na tabela matestoqueitem 
 * 3 incluir na tabela matestoqueini 
 * 3 incluir na tabela matestoqueinimei 
 */ 
foreach ($aArrayComandos as $sComando) {
  
  echo "RODANDO COMANDO : $sComando \n";
  $rsRecord = @db_query($sComando);
  
  db_log($pArquivoAberto, $sComando);
  
  if (!$rsRecord) {

    $sMensagem = "Erro ao rodar comando : {$sComando} \n Erro : ".pg_last_error();
    db_log($pArquivoAberto, $sMensagem);
    die("\n\n{$sMensagem}\n\n");
    
  }
}


if ($oParametros->lApagarLancamentosContabeis) {

  echo "\n\nReprocessando Saldo\n\n";

  $sSqlMatEstoque = "select * From w_dados_saldo_matestoque";
  $rsMatEstoque   = db_query($sSqlMatEstoque);
  $iTotalLinhas   = pg_num_rows ($rsMatEstoque);
  for ($i = 0; $i < $iTotalLinhas; $i++) { 
     
    $oDadosEstoque = db_utils::fieldsMemory($rsMatEstoque, $i);

    if ($oDadosEstoque->m70_quant == '') {
      db_log($pArquivoAberto, "[ERRO] - Entrou no continue, quantidade nula. Material ".$oDadosEstoque->m70_codmatmater." Depto ".$oDadosEstoque->m70_coddepto);
      continue;
    }
    if (empty($oDadosEstoque->m70_quant))  {
      db_log($pArquivoAberto, "[ERRO] - Quantidade zerada. Material ".$oDadosEstoque->m70_codmatmater." Depto ".$oDadosEstoque->m70_coddepto);
      $oDadosEstoque->m70_quant = 0;
    }

    if (empty($oDadosEstoque->m70_valor))  {
      db_log($pArquivoAberto, "[ERRO] - Valor zerado. Material ".$oDadosEstoque->m70_codmatmater." Depto ".$oDadosEstoque->m70_coddepto);
      $oDadosEstoque->m70_valor = 0;
    }

    if ($oDadosEstoque->m70_quant > 0 && $oDadosEstoque->m70_valor <= 0) {
      
      db_log($pArquivoAberto, "[ERRO] - Quantidade Superior a 0 (zero) e valor 0 (zero).");
      echo "\n\n[ERRO] - Quantidade Superior a 0 (zero) e valor 0 (zero)\n";
      echo "Material {$oDadosEstoque->m70_codmatmater} - DPTO: {$oDadosEstoque->m70_coddepto} - Qtd: {$oDadosEstoque->m70_quant} - Vlr: {$oDadosEstoque->m70_valor}\n\n";
      exit;
    }

    $iCodigoMatestoque = db_utils::fieldsMemory(db_query("select nextval('matestoque_m70_codigo_seq') as codigo"), 0)->codigo;
    $sInsertMatestoque = "insert into matestoque values ({$iCodigoMatestoque}, {$oDadosEstoque->m70_codmatmater}, {$oDadosEstoque->m70_coddepto}, '{$oDadosEstoque->m70_quant}', '{$oDadosEstoque->m70_valor}')";
    if (! db_query($sInsertMatestoque) ){

      db_log($pArquivoAberto, "\n Erro ao rodar comando : {$sInsertMatestoque} \n Erro : ".pg_last_error()."\n");
      die("\n Erro ao rodar comando : {$sInsertMatestoque} \n Erro : ".pg_last_error()."\n");
    }
    
    $iCodigoMatestoqueItem  = db_utils::fieldsMemory(db_query("select nextval('matestoqueitem_m71_codlanc_seq') as codigo"), 0)->codigo;
    $sInsertMatestoqueItem  = " insert into matestoqueitem ( m71_codlanc, m71_codmatestoque ,m71_data, m71_quant, m71_valor, m71_quantatend, m71_servico ) ";
    $sInsertMatestoqueItem .= "       values ({$iCodigoMatestoqueItem}, {$iCodigoMatestoque}, current_date, {$oDadosEstoque->m70_quant}, {$oDadosEstoque->m70_valor}, 0, false ) ";
    if (! db_query($sInsertMatestoqueItem) ){

      db_log($pArquivoAberto, "\n Erro ao rodar comando : {$sInsertMatestoqueItem} \n Erro : ".pg_last_error()."\n");
      die("\n Erro ao rodar comando : {$sInsertMatestoqueItem} \n Erro : ".pg_last_error()."\n");
    }

    $sInsertMatestoqueItemUnid  = "insert into matestoqueitemunid (m75_codmatestoqueitem, m75_codmatunid, m75_quant, m75_quantmult) ";
    $sInsertMatestoqueItemUnid .= "       values ({$iCodigoMatestoqueItem}, 1, {$oDadosEstoque->m70_quant}, 1)";
    if (!db_query($sInsertMatestoqueItemUnid) ){

      db_log($pArquivoAberto, "\n Erro ao rodar comando : {$sInsertMatestoqueItemUnid} \n Erro : ".pg_last_error()."\n");
      die("\n Erro ao rodar comando : {$sInsertMatestoqueItemUnid} \n Erro : ".pg_last_error()."\n");
    }
    

    $iCodigoMatestoqueIni  = db_utils::fieldsMemory(db_query("select nextval('matestoqueini_m80_codigo_seq') as codigo"), 0)->codigo;
    $sInsertMatEstoqueIni  = "insert into matestoqueini (m80_codigo,m80_login, m80_data, m80_obs,m80_codtipo,m80_coddepto,  m80_hora)";     
    $sInsertMatEstoqueIni .= "     values({$iCodigoMatestoqueIni}, 1, current_date, 'Implantacao PCASP', 1, {$oDadosEstoque->m70_coddepto}, current_time)";
    if (!db_query($sInsertMatEstoqueIni) ){
      
      db_log($pArquivoAberto, "\n Erro ao rodar comando : {$sInsertMatestoqueIni} \n Erro : ".pg_last_error()."\n");
      die("\n Erro ao rodar comando : {$sInsertMatestoqueIni} \n Erro : ".pg_last_error()."\n");
    }

    $iCodigoMatestoqueIniMei  = db_utils::fieldsMemory(db_query("select nextval('matestoqueinimei_m82_codigo_seq') as codigo"), 0)->codigo;
    $sInsertMatEstoqueIniMei  = " insert into matestoqueinimei (m82_codigo, m82_matestoqueini, m82_matestoqueitem, m82_quant) ";
    $sInsertMatEstoqueIniMei .= "      values ({$iCodigoMatestoqueIniMei}, {$iCodigoMatestoqueIni}, {$iCodigoMatestoqueItem}, {$oDadosEstoque->m70_quant} ) ";
    if (!db_query($sInsertMatEstoqueIniMei) ){
      
      db_log($pArquivoAberto, "\n Erro ao rodar comando : {$sInsertMatEstoqueIniMei} \n Erro : ".pg_last_error()."\n");
      die("\n Erro ao rodar comando : {$sInsertMatEstoqueIniMei} \n Erro : ".pg_last_error()."\n");
    }

  }
}


if ($oParametros->lApagarLancamentosMateriais) {

  $aArrayComandos = array();
  echo "\n\nAPAGANDO LANÇAMENTOS DOS MATERIAIS\n\n\n";
  db_log($pArquivoAberto, "Apagando lancamento dos materiais");
  $aListaDocumentos = array(400,401,402,402,403,404);

  $sSql  = "create temp table w_lista_lancamentos_apagar as  ";
  $sSql .= "select c70_codlan from conlancam inner join conlancamdoc on c71_codlan = c70_codlan where c71_coddoc in(".implode(",", $aListaDocumentos).")";
  $aArrayComandos[] = $sSql;

  $sSql = "create temp table w_CONDATACONF as select * from condataconf;";
  $aArrayComandos[] = $sSql;

  $sSql = "delete from condataconf;";
  $aArrayComandos[] = $sSql;
  /** 
   *Deletar lancamentos contabeis 
   */ 
  $sSql = " alter table  conlancamval  disable trigger all;";
  $aArrayComandos[] = $sSql;
  $sSql = " delete from conlancamval                where c69_codlan in(select c70_codlan from w_lista_lancamentos_apagar); ";
  $aArrayComandos[] = $sSql;
  $sSql = " delete from conlancamcompl              where c72_codlan in(select c70_codlan from w_lista_lancamentos_apagar); ";
  $aArrayComandos[] = $sSql;
  $sSql = " delete from conlancamdoc                where c71_codlan in(select c70_codlan from w_lista_lancamentos_apagar); ";
  $aArrayComandos[] = $sSql;
  $sSql = " delete from conlancammatestoqueinimei   where c103_conlancam in(select c70_codlan from w_lista_lancamentos_apagar); ";
  $aArrayComandos[] = $sSql;
  $sSql = " delete from conlancam                   where c70_codlan in(select c70_codlan from w_lista_lancamentos_apagar); ";
  $aArrayComandos[] = $sSql;

  $sSql = "insert into condataconf select * from w_CONDATACONF";
  $aArrayComandos[] = $sSql;
  $sSql = " alter table  conlancamval enable trigger all;";
  $aArrayComandos[] = $sSql;

  foreach ($aArrayComandos as $sComando) {
    
    echo "RODANDO COMANDO : $sComando \n";
    $rsRecord = @db_query($sComando);
    if (!$rsRecord) {

      db_log($pArquivoAberto, "Erro ao rodar comando : {$sComando} \n Erro : ".pg_last_error());
      die("\n Erro ao rodar comando : {$sComando} \n Erro : ".pg_last_error()."\n");
    }
  }
}

$sSql1 = "select fc_putsession('__status_tg_matestoque_alt', 'enable');";
db_query($sSql1);

$sSql1 = " alter table matestoqueitem enable trigger tg_matestoqueitem_inc_alt;";
db_query($sSql1);

/**
 * Verifica o numnero de registro atuais da tabela matestoque e compara com o anterior
 */
$sSqlMatEstoqueNew = "select * From matestoque";
$rsMatEstoqueNew   = db_query($sSqlMatEstoqueNew);
$iTotalLinhasNew   = pg_num_rows($rsMatEstoqueNew);

if ($iTotalLinhasNew <> $iTotalLinhas){
  $oParametros->lComitar = false;
  db_log($pArquivoAberto, "Numero de registro da matestoque esta diferente.");
}

if ($oParametros->lComitar) {
  
  echo "\nCOMMIT\n";
  db_log($pArquivoAberto, "commit");
  db_query("commit;");
} else {

  echo "\nROLLBACK\n";
  db_log($pArquivoAberto, "rollback");
  db_query("rollback;");
}

echo "\n\nFIM DO PROCESSAMENTO\n\n";
db_log($pArquivoAberto, "FIM DO PROCESSAMENTO");

function db_log($pArquivo, $sMensagem) {
  $pEscreveArquivo = fwrite($pArquivo, $sMensagem."\n");
}

function validarParametros($aArgumentos) {

  $oStdParametros                              = new stdClass();
  $oStdParametros->lImplantarEstoque           = false;
  $oStdParametros->lApagarLancamentosContabeis = false;
  $oStdParametros->lUtilizaArquivo             = false;
  $oStdParametros->sNomeArquivo                = "";
  $oStdParametros->lComitar                    = false;
  $oStdParametros->lApagarLancamentosMateriais = false;

  if (!empty($aArgumentos[1]) && $aArgumentos[1] === "true") {
    $oStdParametros->lImplantarEstoque = true;
  }

  if (!empty($aArgumentos[2]) && $aArgumentos[2] === "true") {
    $oStdParametros->lApagarLancamentosContabeis = true;
  }

  if (!empty($aArgumentos[3]) && substr(trim($aArgumentos[3]), -3) === "txt") {
    
    $oStdParametros->lUtilizaArquivo = true;
    $oStdParametros->sNomeArquivo    = trim($aArgumentos[3]);
  }

  if (!empty($aArgumentos[4]) && $aArgumentos[4] === "true") {
    $oStdParametros->lApagarLancamentosMateriais = true;
  }

  if (!empty($aArgumentos[5]) && $aArgumentos[5] === "true") {
    $oStdParametros->lComitar = true;
  }

  return $oStdParametros;
}
