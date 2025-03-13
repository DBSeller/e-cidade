<?php

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_autoload.php");
require_once ("libs/db_conn.php");

$hoje = date('Y-m-d');
$nome_arquivo = "tmp/LOG_inativar_vinculo_professor_".$hoje."_".date('His').".txt";
$erro = false;
db_log( 'INATIVAR VÍNCULO DO PROFESSOR...', $nome_arquivo, 0);
$conn = @pg_connect("host=".$_SESSION["DB_servidor"]." dbname=".$_SESSION["DB_base"]." port=".$_SESSION["DB_porta"]." user=".$_SESSION["DB_usuario"]." password=".$_SESSION["DB_senha"]);

db_log( "- BASE PARA AJUSTES: host=".$_SESSION["DB_servidor"]." dbname=".$_SESSION["DB_base"]." port=".$_SESSION["DB_porta"]." user=".$_SESSION["DB_usuario"]." password=".$_SESSION["DB_senha"], $nome_arquivo, 0);

if (!($conn)) {
  db_log("Erro ao conectar no Banco de Dados...".pg_last_error(), $nome_arquivo, 0);
  die();
}

db_log( 'Início do Processamento...', $nome_arquivo, 0);
pg_query($conn, "BEGIN;");
$sSqlBuscaInativosDia = "
SELECT DISTINCT ed22_i_codigo,
                ed75_i_codigo,
                ed75_i_rechumano,
                ed75_i_escola FROM (
    SELECT ed22_i_codigo,
           ed75_i_codigo,
           ed75_i_rechumano,
           ed75_i_escola
    FROM rechumanoescola
    INNER JOIN rechumanoativ ON ed22_i_rechumanoescola = ed75_i_codigo
    INNER JOIN atividaderh ON ed22_i_atividade = ed01_i_codigo
    WHERE ed01_funcaoatividade = 1
      AND ed01_c_regencia = 'S'
      AND ed22_datafim = '$hoje'
    UNION ALL
    SELECT ed22_i_codigo,
           ed75_i_codigo,
           ed75_i_rechumano,
           ed75_i_escola
    FROM rechumanoescola
    INNER JOIN rechumanoativ ON ed22_i_rechumanoescola = ed75_i_codigo
    INNER JOIN atividaderh ON ed22_i_atividade = ed01_i_codigo
    WHERE ed01_funcaoatividade IN (2,3,4)
      AND ed01_c_regencia = 'N'
      AND ed22_datafim = '$hoje') as x" ;
$rsBuscaInativosDia = pg_query($conn, $sSqlBuscaInativosDia);
if (!$rsBuscaInativosDia) {
  $erro = true;
  db_log( 'Ocorreu um erro ao buscar Registros - rsBuscaInativosDia...'.pg_last_error(), $nome_arquivo, 0);
}

db_log( 'Consultando Servidores com Fim do Exercício nesta data...'.pg_num_rows($rsBuscaInativosDia).' Servidores', $nome_arquivo, 0 );

for($abc = 0; $abc < pg_num_rows($rsBuscaInativosDia); $abc++) {
  $oInativos = db_utils::fieldsMemory($rsBuscaInativosDia, $abc);

  db_log( 'Buscando Horários da Regência para a Função Exercida: '.$oInativos->ed22_i_codigo.' RecHumanoAtiv: '.$oInativos->ed75_i_codigo.' RecHumano: '.$oInativos->ed75_i_rechumano.' - Na escola: '.$oInativos->ed75_i_escola."\n", $nome_arquivo, 0);

  $sSqlBuscaRecHumanoHoraDisp = "SELECT ed33_i_codigo,
                                        ed33_i_diasemana,
                                        ed33_i_periodo
                                   FROM rechumanohoradisp
                                  WHERE ed33_rechumanoativ = $oInativos->ed22_i_codigo";
  //echo $sSqlBuscaRecHumanoHoraDisp."\n\n";
  $rsBuscaRecHumanoHoraDisp = pg_query($conn, $sSqlBuscaRecHumanoHoraDisp);
  if (!$rsBuscaRecHumanoHoraDisp) {
    $erro = true;
    db_log( 'Ocorreu um erro ao buscar Registros - rsBuscaRecHumanoHoraDisp...'.pg_last_error(), $nome_arquivo, 0);
  }

  for($bcd = 0; $bcd < pg_num_rows($rsBuscaRecHumanoHoraDisp); $bcd++) {
    $oRecHumanoHoraDisp = db_utils::fieldsMemory($rsBuscaRecHumanoHoraDisp, $bcd);

    db_log( 'Convertendo Regencia Horário para Sem Regente: '.$oInativos->ed75_i_rechumano.' - DiaSemana: '.$oRecHumanoHoraDisp->ed33_i_diasemana.' - Período: '.$oRecHumanoHoraDisp->ed33_i_periodo.' - Registro: '.$bcd.' - Na escola: '.$oInativos->ed75_i_escola."\n", $nome_arquivo, 0);
    $sqlBuscaRegenciaHorario = "SELECT regenciahorario.*
                                    FROM regenciahorario
                              INNER JOIN regencia ON ed58_i_regencia = ed59_i_codigo
                              INNER JOIN turma ON ed59_i_turma = ed57_i_codigo
                              INNER JOIN calendario on ed57_i_calendario = ed52_i_codigo
                                   WHERE ed58_datafim >= '$hoje'
                                     AND ed52_d_fim >= '$hoje'
                                     AND ed58_i_diasemana = $oRecHumanoHoraDisp->ed33_i_diasemana
                                     AND ed58_i_periodo = $oRecHumanoHoraDisp->ed33_i_periodo
                                     AND ed58_i_rechumano = $oInativos->ed75_i_rechumano
                                     AND ed57_i_escola = $oInativos->ed75_i_escola";
    $rsBuscaRegenciaHorario = pg_query($conn, $sqlBuscaRegenciaHorario);
    if (!$rsBuscaRegenciaHorario) {
      $erro = true;
      db_log( 'Ocorreu um erro ao buscar Registros - rsBuscaRegenciaHorario...'.pg_last_error(), $nome_arquivo, 0);
    }

    for($cde = 0; $cde < pg_num_rows($rsBuscaRegenciaHorario); $cde++) {
      $oRegenciaHorario = db_utils::fieldsMemory($rsBuscaRegenciaHorario, $cde);

      $sInsRegenciaHorarioSemRegente = "INSERT INTO escola.regenciahorariodiscsemreg(
        ed175_regencia,
        ed175_diasemana,
        ed175_periodo,
        ed175_rechumano,
        ed175_ativo,
        ed175_tipovinculo,
        ed175_datainicio,
        ed175_datafim) VALUES (
        $oRegenciaHorario->ed58_i_regencia,
        $oRegenciaHorario->ed58_i_diasemana,
        $oRegenciaHorario->ed58_i_periodo,
        0,
        '$oRegenciaHorario->ed58_ativo',
        $oRegenciaHorario->ed58_tipovinculo,
        '$oRegenciaHorario->ed58_datainicio',
        '$oRegenciaHorario->ed58_datafim')";
      $rsInsRegenciaHorarioSemRegente = pg_query($conn, $sInsRegenciaHorarioSemRegente);
      if (!$rsInsRegenciaHorarioSemRegente) {
        $erro = true;
        db_log( 'Ocorreu um erro ao inserir Registros - rsInsRegenciaHorarioSemRegente...'.pg_last_error(), $nome_arquivo, 0);
      }

      $sDelRegenciaHorario = "UPDATE regenciahorario SET ed58_datafim = '$hoje', ed58_ativo = 'f' WHERE ed58_i_codigo = $oRegenciaHorario->ed58_i_codigo";      $rsDelRegenciaHorario = pg_query($conn, $sDelRegenciaHorario);
      if (!$rsDelRegenciaHorario) {
        $erro = true;
        db_log( 'Ocorreu um erro ao Excluir Registros - rsDelRegenciaHorario...'.pg_last_error(), $nome_arquivo, 0);
      }
    }

    db_log( 'Atualizando RecHumanoHoraDisp: '.$oInativos->ed75_i_rechumano.' - DiaSemana: '.$oRecHumanoHoraDisp->ed33_i_diasemana.' - Período: '.$oRecHumanoHoraDisp->ed33_i_periodo.' - Registro: '.$bcd.' - Na escola: '.$oInativos->ed75_i_escola."\n", $nome_arquivo, 0);
    $sAtuRecHumanoHoraDisp = "UPDATE rechumanohoradisp
                                 SET ed33_ativo = 'f'
                               WHERE ed33_i_codigo = $oRecHumanoHoraDisp->ed33_i_codigo";
    $rsAtuRecHumanoHoraDisp = pg_query($conn, $sAtuRecHumanoHoraDisp);
    if (!$rsAtuRecHumanoHoraDisp) {
      $erro = true;
      db_log( 'Ocorreu um erro ao Atualizar Registros - rsAtuRecHumanoHoraDisp...'.pg_last_error(), $nome_arquivo, 0);
    }
  }

  $sqlValidaAlunoTurma = "
    select 
      * 
    from 
      pg_tables 
    where 
    tablename = 'profissionalalunoturma' 
    and schemaname = 'plugins'
  ";
  $rsSqlValidaAlunoTurma = pg_query($conn, $sqlValidaAlunoTurma);
  
  if (pg_num_rows($rsSqlValidaAlunoTurma) > 0) {
    db_log( 'Removendo da profissional aluno turma: '.$oInativos->ed75_i_rechumano.' - Registro: '.$abc.' - Na escola: '.$oInativos->ed75_i_escola."\n", $nome_arquivo, 0);
  
    $sSqlDelProfAlunoTurma = " DELETE FROM plugins.profissionalalunoturma
                                  WHERE ed03_sequencial IN (
                                    SELECT ed03_sequencial
                                      FROM plugins.profissionalalunoturma
                                INNER JOIN turma ON ed03_turma = ed57_i_codigo
                                INNER JOIN calendario on ed57_i_calendario = ed52_i_codigo
                                    WHERE ed03_rechumano = $oInativos->ed75_i_rechumano
                                      AND ed57_i_escola = $oInativos->ed75_i_escola
                                      AND ed52_d_fim >= '$hoje')";

    $rsSqlDelProfAlunoTurma = pg_query($conn, $sSqlDelProfAlunoTurma);
    if (!$rsSqlDelProfAlunoTurma) {
      $erro = true;
      db_log( 'Ocorreu um erro ao Excluir Registros - rsSqlDelProfAlunoTurma...'.pg_last_error(), $nome_arquivo, 0);
    }
  }

  db_log( 'Removendo da Turma Outros Profissionais: '.$oInativos->ed75_i_rechumano.' - Registro: '.$abc.' - Na escola: '.$oInativos->ed75_i_escola."\n", $nome_arquivo, 0);
  $sSqlDelTurmaOutrosProfissionais = " DELETE FROM turmaoutrosprofissionais
                         WHERE ed347_rechumano = $oInativos->ed75_i_rechumano
                         AND ed347_turma IN (SELECT ed57_i_codigo
                                               FROM turma
                                         INNER JOIN calendario on ed57_i_calendario = ed52_i_codigo
                                              WHERE ed57_i_escola = $oInativos->ed75_i_escola
                                                AND ed52_d_fim >= '$hoje')";
  $rsSqlDelTurmaOutrosProfissionais = pg_query($conn, $sSqlDelTurmaOutrosProfissionais);
  if (!$rsSqlDelTurmaOutrosProfissionais) {
    $erro = true;
    db_log( 'Ocorreu um erro ao Excluir Registros - rsSqlDelTurmaOutrosProfissionais...'.pg_last_error(), $nome_arquivo, 0);
  }

  db_log( 'Convertendo Regencia da Turma AC para Sem Regente: '.$oInativos->ed75_i_rechumano.' - DiaSemana: '.$oRecHumanoHoraDisp->ed33_i_diasemana.' - Período: '.$oRecHumanoHoraDisp->ed33_i_periodo.' - Registro: '.$bcd.' - Na escola: '.$oInativos->ed75_i_escola."\n", $nome_arquivo, 0);
  $sBuscaProfissionalTurmaAc = " SELECT turmaachorarioprofissional.* FROM turmaachorarioprofissional
                 INNER JOIN turmaac ON ed346_turmaac = ed268_i_codigo
                 INNER JOIN calendario ON ed268_i_calendario = ed52_i_codigo
                      WHERE ed268_i_escola = $oInativos->ed75_i_escola
                        AND ed52_d_fim >= '$hoje'
                        AND ed346_rechumano = $oInativos->ed75_i_rechumano ";
  $rsBuscaProfissionalTurmaAc = pg_query($conn, $sBuscaProfissionalTurmaAc);
  if (!$rsBuscaProfissionalTurmaAc) {
    $erro = true;
    db_log( 'Ocorreu um erro ao Buscar Registros - rsBuscaProfissionalTurmaAc...'.pg_last_error(), $nome_arquivo, 0);
  }

  for ($def = 0; $def < pg_num_rows($rsBuscaProfissionalTurmaAc); $def++) {
    $oProfTurmaAc = db_utils::fieldsMemory($rsBuscaProfissionalTurmaAc, $def);

    $sInsTurmaAcProfSemRec = "INSERT INTO turmaachorarioprofissionalsemrec (
      ed176_turmaac,
      ed176_funcaoatividade,
      ed176_rechumano,
      ed176_diasemana,
      ed176_horainicial,
      ed176_horafinal) VALUES (
      $oProfTurmaAc->ed346_turmaac,
      0,
      0,
      $oProfTurmaAc->ed346_diasemana,
      '$oProfTurmaAc->ed346_horainicial',
      '$oProfTurmaAc->ed346_horafinal'
    )";
    $rsInsTurmaAcProfSemRec = pg_query($conn, $sInsTurmaAcProfSemRec);
    if (!$rsInsTurmaAcProfSemRec) {
      $erro = true;
      db_log( 'Ocorreu um erro ao Inserir Registros - rsInsTurmaAcProfSemRec...'.pg_last_error(), $nome_arquivo, 0);
    }

    $sDelTurmaAcProf = " DELETE FROM turmaachorarioprofissional WHERE ed346_sequencial = $oProfTurmaAc->ed346_sequencial ";
    $rsDelTurmaAcProf = pg_query($conn, $sDelTurmaAcProf);
    if (!$rsDelTurmaAcProf) {
      $erro = true;
      db_log( 'Ocorreu um erro ao Excluir Registros - rsDelTurmaAcProf...'.pg_last_error(), $nome_arquivo, 0);
    }
  }
}
if(!$erro){
  $transacao = "COMMIT";
} else {
  $transacao = "ROLLBACK";
}

pg_query($conn, $transacao);
db_log( 'Fim do Processamento...'.$transacao, $nome_arquivo, 0);

function db_log($sLog = "", $sArquivo = "", $iTipo = 0, $lLogDataHora = true, $lQuebraAntes = true) {

  $aDataHora = getdate();

  $sQuebraAntes = $lQuebraAntes ? "\n" : "";

  if ($lLogDataHora) {
    $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s", $sQuebraAntes, $aDataHora ["mday"], $aDataHora ["mon"], $aDataHora ["year"], $aDataHora ["hours"], $aDataHora ["minutes"], $aDataHora ["seconds"], $sLog);
  } else {
    $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
  }

  // Se habilitado saida na tela...
  if ($iTipo == 0 or $iTipo == 1) {
    echo $sOutputLog;
  }

  // Se habilitado saida para arquivo...
  if ($iTipo == 0 or $iTipo == 2) {
    if (! empty($sArquivo)) {
      $fd = fopen($sArquivo, "a+");
      if ($fd) {
        fwrite($fd, $sOutputLog);
        fclose($fd);
      }
    }
  }

  return $aDataHora;
}

?>
