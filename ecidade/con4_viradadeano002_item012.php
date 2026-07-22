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

// Para garantir que nao houve erros em outros itens
if ($sqlerro == false) {

    //RECEITAS ORÇAMENTÁRIAS";
    include(modification("classes/db_taborc_classe.php"));


    try {
        /**
         * Ajusta as receitas que estão na taborc e nao foram orcadas no exercicio
         *
         */
        $sqlAjusteReceitasTesouraria = "
        SELECT DISTINCT orcreceita.*,
               orcfontes.o57_codfon
          FROM orcamento.orcreceita
               LEFT JOIN orcfontes 
                      ON orcfontes.o57_codfon = orcreceita.o70_codfon
                     AND orcfontes.o57_anousu = {$anodestino}
               LEFT JOIN orcreceita receita_proximo 
                      ON receita_proximo.o70_anousu = {$anodestino}
                     AND orcreceita.o70_codfon = receita_proximo.o70_codfon
                     AND orcreceita.o70_instit = receita_proximo.o70_instit
                     AND orcreceita.o70_concarpeculiar = receita_proximo.o70_concarpeculiar
        WHERE receita_proximo.o70_codrec is null
          AND orcreceita.o70_anousu = {$anoorigem};";

        $daoOrcreceita = new cl_orcreceita();
        $rsAjustesReceita = db_query($sqlAjusteReceitasTesouraria);
        $totalRegistros = pg_num_rows($rsAjustesReceita);
        if ($totalRegistros > 0) {
            for ($i = 0; $i < $totalRegistros; $i++) {
                $receita = \db_utils::fieldsMemory($rsAjustesReceita, $i);

                if (empty($receita->o57_codfon)) {
                    $cldb_viradaitemlog->c35_log = "A receita ($receita->o70_codrec) não possui cadastro no plano orçamentário em {$anodestino}.";
                    $cldb_viradaitemlog->c35_codarq = 78;
                    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
                    $cldb_viradaitemlog->c35_data = date("Y-m-d");
                    $cldb_viradaitemlog->c35_hora = date("H:i");
                    $cldb_viradaitemlog->incluir(null);
                    if ($cldb_viradaitemlog->erro_status == 0) {
                        $sqlerro = true;
                        $erro_msg .= $cldb_viradaitemlog->erro_msg;
                    }
                    continue;
                }

                $daoOrcreceita->o70_codrec = $receita->o70_codrec;
                $daoOrcreceita->o70_anousu = $anodestino;
                $daoOrcreceita->o70_codfon = $receita->o70_codfon;
                $daoOrcreceita->o70_concarpeculiar = $receita->o70_concarpeculiar;
                $daoOrcreceita->o70_datacriacao = $receita->o70_datacriacao;
                $daoOrcreceita->o70_esferaorcamentaria = $receita->o70_esferaorcamentaria;
                $daoOrcreceita->o70_instit = $receita->o70_instit;
                $daoOrcreceita->o70_codigo = $receita->o70_codigo;
                $daoOrcreceita->o70_orcorgao = $receita->o70_orcorgao;
                $daoOrcreceita->o70_orcunidade = $receita->o70_orcunidade;
                $daoOrcreceita->o70_reclan = $receita->o70_reclan == 't' ? 'true' : "false";
                $daoOrcreceita->o70_valor = "0";
                $daoOrcreceita->incluir($anodestino, $receita->o70_codrec);
                if ($daoOrcreceita->erro_status == 0) {
                    throw new \Exception("Erro ao ajustar receitas.\n" . $daoOrcreceita->erro_msg);
                }
            }
        }
        $cl_taborc = new cl_taborc;
        $sqltaborc = "select * from taborc where k02_anousu = {$anoorigem} ";
        $resulttaborc = db_query($sqltaborc);
        $linhastaborc = pg_num_rows($resulttaborc);
        if ($linhastaborc > 0) {
            $fonte = "";
            $vir = "";

            for ($o = 0; $o < $linhastaborc; $o++) {
                db_fieldsmemory($resulttaborc, $o);
                db_atutermometro($o, $linhastaborc, 'termometroitem', 1, $sMensagemTermometroItem);

                $sql1 = "select * from taborc where k02_anousu = {$anodestino} and k02_codigo = {$k02_codigo}";
                $result1 = db_query($sql1);
                $linhas1 = pg_num_rows($result1);
                if ($linhas1 == 0) {
                    $sql2 = "select * ";
                    $sql2 .= "  from taborc ";
                    $sql2 .= "       inner join orcreceita  on o70_codrec = k02_codrec ";
                    $sql2 .= "                             and o70_anousu = k02_anousu ";
                    $sql2 .= "       inner join orcfontes   on o57_codfon = o70_codfon ";
                    $sql2 .= "                             and o57_anousu = o70_anousu ";
                    $sql2 .= " where k02_anousu = {$anoorigem} ";
                    $sql2 .= "   and k02_codigo = {$k02_codigo}";
                    $result2 = db_query($sql2);
                    $linhas2 = pg_num_rows($result2);
                    if ($linhas2 > 0) {
                        db_fieldsmemory($result2, 0);
                        $sql3 = "select * ";
                        $sql3 .= "  from orcfontes ";
                        $sql3 .= "       inner join orcreceita  on o70_codfon = o57_codfon ";
                        $sql3 .= "                             and o70_anousu = o57_anousu ";
                        $sql3 .= " where orcreceita.o70_codfon = {$o70_codfon}" ;
                        $sql3 .= "   and o57_anousu         = {$anodestino} ";
                        $sql3 .= "   and o70_concarpeculiar = lpad('{$o70_concarpeculiar}',3,'0') ";
                        $sql3 .= "   and o70_instit         = {$o70_instit} ";
                        $result3 = db_query($sql3);
                        $linhas3 = pg_num_rows($result3);
                        if ($linhas3 > 0) {
                            db_fieldsmemory($result3, 0);
                            // inclui na taborc
                            $cl_taborc->k02_codigo = $k02_codigo;
                            $cl_taborc->k02_anousu = $anodestino;
                            $cl_taborc->k02_estorc = $o57_fonte;
                            $cl_taborc->k02_codrec = $o70_codrec;
                            $cl_taborc->incluir($anodestino, $k02_codigo);
                            if ($cl_taborc->erro_status == 0) {
                                $sqlerro = true;
                                $erro_msg .= $cl_taborc->erro_msg;
                                break;
                            }

                        } else {
                            $fonte .= $vir . $o57_fonte;
                            $vir = ", ";

                        }
                    }
                }
            }

            if ($sqlerro == false) {
                if ($fonte != "") {
                    $cldb_viradaitemlog->c35_log = "As fontes de receita ($fonte) não possuem previsão para o exercício de $anodestino ou não constam no cadastro.";
                    $cldb_viradaitemlog->c35_codarq = 78;
                    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
                    $cldb_viradaitemlog->c35_data = date("Y-m-d");
                    $cldb_viradaitemlog->c35_hora = date("H:i");
                    $cldb_viradaitemlog->incluir(null);
                    if ($cldb_viradaitemlog->erro_status == 0) {
                        $sqlerro = true;
                        $erro_msg .= $cldb_viradaitemlog->erro_msg;
                    }
                }
            }

        } else {
            if ($linhasorigem == 0) {
                $cldb_viradaitemlog->c35_log = "Não existem dados (taborc) para o exercicio $anoorigem";
                $cldb_viradaitemlog->c35_codarq = 78;
                $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
                $cldb_viradaitemlog->c35_data = date("Y-m-d");
                $cldb_viradaitemlog->c35_hora = date("H:i");
                $cldb_viradaitemlog->incluir(null);
                if ($cldb_viradaitemlog->erro_status == 0) {
                    $sqlerro = true;
                    $erro_msg .= $cldb_viradaitemlog->erro_msg;
                }
            }
        }
    } catch (\Exception $e) {
        $erro_msg .= $e->getMessage();
    }
}
?>
