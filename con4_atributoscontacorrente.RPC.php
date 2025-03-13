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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam             = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;

$anoSessao = db_getsession('DB_anousu');
$instituicaoSessao = db_getsession('DB_instit');

try {

    db_inicio_transacao();

    switch ($oParam->exec) {

        case 'carregarAtributos':

            if (empty($oParam->reduzido_conta)) {
                throw new ParameterException("Código da Conta não informado.");
            }

            $where = implode( ' and ', array(
                "conplanoreduz.c61_reduz  = {$oParam->reduzido_conta}",
                "conplanoreduz.c61_anousu = {$anoSessao}",
                "conplanoreduz.c61_instit = {$instituicaoSessao}",
                "conplanosistema.c122_tipo = 2",
            )) . ' order by conplanosistemaatributos.c129_ordem';

            $campos = array(
                'c121_sequencial as codigo',
                'c121_sigla as sigla',
                'c121_descricao as descricao',
                'c121_ajuda as ajuda',
            );


            if (!empty($oParam->codigo_lancamento)) {

                $sqlValorAtributo = "
                    select infocomplementarvalor.c123_valor
                      from conplanoatributolancamentos
                           join infocomplementarvalor on infocomplementarvalor.c123_conplanoatributolancamentos = conplanoatributolancamentos.c124_sequencial
                           join conplanoinfocomplementar valorinfo on valorinfo.c121_sequencial = infocomplementarvalor.c123_infocomplementar
                           join conplanosistema on conplanosistema.c122_sequencial = infocomplementarvalor.c123_conplanosistema
                     where conplanoatributolancamentos.c124_lancamento = {$oParam->codigo_lancamento}
                       and infocomplementarvalor.c123_reduzido = {$oParam->reduzido_conta}
                       and valorinfo.c121_sigla = conplanoinfocomplementar.c121_sigla
                 ";
                $campos[] = "({$sqlValorAtributo}) as valor";
            } else {
                $campos[] = "'' as valor";
            }

            $daoReduzidos = new cl_conplanoreduz();
            $buscaAtributos = $daoReduzidos->sql_query_infocomplementar_obrigatorias(implode(',', $campos), $where);
            $buscaAtributos = db_query($buscaAtributos);
            if (!$buscaAtributos) {
                throw new DBException("Ocorreu um erro ao consultar os atributos da conta contábil.");
            }

            $atributos = db_utils::makeCollectionFromRecord($buscaAtributos, function($stdLinha) {

                return (object)array(
                    'codigo' => $stdLinha->codigo,
                    'sigla' => $stdLinha->sigla,
                    'descricao' => $stdLinha->descricao,
                    'ajuda' => $stdLinha->ajuda,
                    'valor' => empty($stdLinha->valor) ? '' : $stdLinha->valor,
                );
            });
            $oRetorno->atributos = $atributos;
            break;

    }

    db_fim_transacao(false);

} catch (Exception $e) {

    db_fim_transacao(true);

    $oRetorno->mensagem = urlencode($e->getMessage());
    $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
