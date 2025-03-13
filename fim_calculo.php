<?php

/**
 * Deletamos as rubricas R913, R914 e R815 das tabelas de calculo, quando o servidor possuir moléstia e o seu
 * vinculo for INATIVO ou PENSIONISTA
 */

LogCalculoFolha::write('Deletando R919, R914 ou R915 para servidores que possuem moléstia e são Inativos ou Pensionistas $opcao_geral:' . $opcao_geral);

$aFolhasNoCalculo = array(1, 3, 4, 5, 8); // Folhas

switch ($opcao_geral) {
    case 1:
        $sTabela = 'gerfsal';
        $sSigla = 'r14';

        $afastamentosDeletar = DBRegistry::get('afastamentosDeletar');
        if (!empty($afastamentosDeletar)) {
            CalculoFolha::deletaAfastamentos($afastamentosDeletar);
        }
        CalculoFolha::reverteAfastamento(DBRegistry::getInstance());

        $matriculasComProrrogacao = DBRegistry::get('matriculasAlteradas');
        if (!empty($matriculasComProrrogacao)) {
            CalculoFolha::proporcionalizaAfastamento(
                $matriculasComProrrogacao,
                InstituicaoRepository::getInstituicaoSessao(),
                DBRegistry::getInstance(),
                $sTabela,
                $sSigla
            );
        }
        CalculoFolha::reverteAfastamento(DBRegistry::getInstance(), true);
        break;
    case 3:

        $sTabela = 'gerffer';
        $sSigla = 'r31';
        break;
    case 4:

        $sTabela = 'gerfres';
        $sSigla = 'r20';
        break;

    case 5:

        $sTabela = 'gerfs13';
        $sSigla = 'r35';
        break;
    case 8:

        $sTabela = 'gerfcom';
        $sSigla = 'r48';
        break;
}

if (in_array($opcao_geral, $aFolhasNoCalculo)) { // Não executa para o cálculo de fixo e adiantamento e provisões

    $iInstituicao = db_getsession("DB_instit");
    $sSqlDeletaMolestia = "delete from {$sTabela} ";
    $sSqlDeletaMolestia .= "      where {$sSigla}_rubric in ('R913', 'R914', 'R915', 'R997', 'R999')";
    $sSqlDeletaMolestia .= "        and {$sSigla}_regist in ( select rh02_regist ";
    $sSqlDeletaMolestia .= "                                    from rhpessoalmov";
    $sSqlDeletaMolestia .= "                                   inner join  rhregime on rh30_codreg = rh02_codreg ";
    $sSqlDeletaMolestia .= "                                   where rh30_vinculo <> 'A'";
    $sSqlDeletaMolestia .= "                                     and rh02_anousu = {$anousu}";
    $sSqlDeletaMolestia .= "                                     and rh02_mesusu = {$mesusu}";
    $sSqlDeletaMolestia .= "                                     and rh02_instit = {$iInstituicao}";
    $sSqlDeletaMolestia .= "                                     and rh02_portadormolestia = true ";
    $sSqlDeletaMolestia .= "                                 )";
    $sSqlDeletaMolestia .= "        and {$sSigla}_anousu = $anousu";
    $sSqlDeletaMolestia .= "        and {$sSigla}_mesusu = $mesusu";
    $sSqlDeletaMolestia .= "        and {$sSigla}_instit = $iInstituicao";
    $rsDeletaMolestia = db_query($sSqlDeletaMolestia);
    if (pg_affected_rows($rsDeletaMolestia) > 0) {
        LogCalculoFolha::write("Removido " . pg_affected_rows($rsDeletaMolestia) . " Registros de IRRF.");
    }
    if (!$rsDeletaMolestia) {
        throw new DBException("Ocorreu um erro ao deletar as rubricas de IRRF para portadores de moléstia. =>" . pg_num_rows($rsDeletaMolestia) . " ");
    }
}

/* Aqui é realizado o agrupamento das Rubricas de Cálculo do Adiantamento do 13º Salário
    em uma Rubrica única somando todos os valores de proventos.

   Primeiro verficar se o cálculo é de 13º salario ou se é de adiantamento  
   verficando a sua competência, se ela for menor que 12 é cálculo de adiantamento.

   Depois verfica se está configurado a rubrica de agrupamento na rotna de Rubricas Especiais.

   No script trás todos os funcionários, agrupa os valores de proventos, deleta e insere novamente na rubrica configurada.
*/ 

if ($mesusu < '12' && $opcao_geral == 5 && $cfpess[0]["r11_rubagrupaadiantamento"] != '') {
    $rubricaAgrupamento = $cfpess[0]["r11_rubagrupaadiantamento"];

$sqlDecimoSomadas = " SELECT sum(r35_valor) AS valor,
                             r35_regist     AS matricula,
                             r35_lotac      AS lotacao
                      FROM gerfs13
                      INNER JOIN rhpessoalmov ON rh02_regist = r35_regist
                        AND rh02_anousu = r35_anousu
                        AND rh02_mesusu = r35_mesusu
                        AND r35_instit = $DB_instit
                      WHERE r35_anousu = $anousu
                        AND r35_mesusu = $mesusu 
                        AND r35_pd = 1
                      $where_regist_fim
                      GROUP BY r35_regist, 
                               r35_lotac";

$sqlResultSomadas = db_query($sqlDecimoSomadas);
$result = pg_fetch_all($sqlResultSomadas);

foreach ($result as $linhasRubricasAgrupadas) {
  $deleteRubricas = "delete from gerfs13 where r35_anousu = $anousu and r35_mesusu = $mesusu and r35_regist = {$linhasRubricasAgrupadas['matricula']} 
   and r35_instit = $DB_instit and r35_pd = 1";

   $result = db_query($deleteRubricas);

   if($result == false) {
     echo("Não foi possível efetuar a exclusão do cálculo");
   }

  $insereRubricaAgrupada = "insert into gerfs13 values ($anousu,$mesusu,{$linhasRubricasAgrupadas['matricula']},'$rubricaAgrupamento',
   {$linhasRubricasAgrupadas['valor']},'1','1',{$linhasRubricasAgrupadas['lotacao']},'0',$DB_instit)";
  
   $result = db_query($insereRubricaAgrupada);
   
   if($result == false) {
    echo("Não foi possível efetuar a inclusão do cálculo");
  }
}
} 

if ($mesusu < '12' && $opcao_geral == 5) {

$sqlRubricaAdiantamento =
  " WITH rubrica_adiantamento AS (
    SELECT
        rh27_rubric as rubrica_mae,
        rh262_rubrica_adiantamento AS rubrica_filha
    FROM 
        rhrubricas
        JOIN gerfs13 ON r35_rubric = rh27_rubric
        AND r35_anousu = $anousu
        AND r35_mesusu = $mesusu
        AND r35_instit = $DB_instit
        JOIN rhrubricasadiantamento ON rh262_rubrica_principal = rh27_rubric 
        and rh262_instituicao = rh27_instit 
        JOIN rhpessoalmov ON rh02_anousu = r35_anousu 
        AND rh02_mesusu = r35_mesusu
        AND rh02_instit = $DB_instit
        and rh02_regist  = r35_regist
        $where_regist_fim 
        and rh27_instit = $DB_instit
  )
  UPDATE
    gerfs13
  SET
    r35_rubric = rubrica_filha
  FROM 
    rubrica_adiantamento
  WHERE
    r35_rubric = rubrica_mae
    AND r35_anousu = $anousu
    AND r35_mesusu = $mesusu
    AND r35_instit = $DB_instit   
";

$sqlResult = db_query($sqlRubricaAdiantamento);

 if($sqlResult == false) {
   echo("Não foi possível efetuar o cálculo");
 }
}

if ($mesusu == '12' && $opcao_geral == 5) {

$sqlDescontoAdiantamento = "SELECT sum(r35_valor) AS valor,
                             r35_regist     AS matricula,
                             r35_lotac      AS lotacao
                      FROM gerfs13
                      INNER JOIN rhpessoalmov ON rh02_regist = r35_regist
                        AND rh02_anousu = r35_anousu
                        AND rh02_mesusu = r35_mesusu
                        AND r35_instit = $DB_instit
                      WHERE r35_anousu = $anousu
                        AND r35_mesusu <> 12 
                        AND r35_pd = 1
                      $where_regist_fim
                      GROUP BY r35_regist, 
                               r35_lotac
";

$sqlResult = db_query($sqlDescontoAdiantamento);
$result = pg_fetch_all($sqlResult);

foreach ($result as $linhasDescontoAdiantamento) {
  $insereRubricaDesconto = "insert into gerfs13 values ($anousu,$mesusu,{$linhasDescontoAdiantamento['matricula']},'R934',
   {$linhasDescontoAdiantamento['valor']},'2','1',{$linhasDescontoAdiantamento['lotacao']},'0',$DB_instit)";
  
   $result = db_query($insereRubricaDesconto);
   
   if($result == false) {
    echo("Não foi possível efetuar a inclusão do cálculo");
  }
}

}
