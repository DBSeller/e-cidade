<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->status = 1;
$retorno->mensagem = '';

$daoIssbase = new cl_issbase();

try {
    db_inicio_transacao();

    switch ($parametros->executa) {

        case 'buscaIsencoes':

            if (empty($parametros->matricula)) {
                throw new Exception("Matrícula não informada.");
            }

            $retorno->isencoes = array();

            $oDaoIsencao = new cl_iptuisen();
            $rsIptuisen = db_query($oDaoIsencao->sql_query_file("", "*", "", "j46_matric = $parametros->matricula"));

            if (!$rsIptuisen) {
                throw new Exception("Erro ao buscar isenções.");
            }

            $linhaIsencao = pg_num_rows($rsIptuisen);

            for ($i = 0; $i < $linhaIsencao; $i++) {
                $retorno->isencoes[] = $oDadosIsencao = db_utils::fieldsMemory($rsIptuisen, $i);
            }

            break;

        case 'buscarOutrosPropri':

            if (empty($parametros->matricula)) {
                throw new Exception("Matrícula não informada.");
            }

            $retorno->outrosPropri = array();

            $oDaoPercPosseRural = new cl_percposserural();
            $campos = "j166_sequencial, j166_numcgm, z01_nome, j166_percentual";
            $rsPercPosseRural = db_query($oDaoPercPosseRural->sql_outros_propri($campos, "j166_matric = $parametros->matricula"));
            
            if (!$rsPercPosseRural) {
                throw new Exception("Erro ao buscar percentuais de posse de outros proprietários.");
            }

            $linhaPercPosseRural = pg_num_rows($rsPercPosseRural);

            for ($i = 0; $i < $linhaPercPosseRural; $i++) {
                $retorno->outrosPropri[] = $oDadosIsencao = db_utils::fieldsMemory($rsPercPosseRural, $i);
            }

            break;

        default:
            throw new Exception('Nenhuma ao encontrada.');
            break;
    }

    db_fim_transacao(false);

} catch (Exception $erro){

    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->mensagem = $erro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);
