<?php
/**
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

use ECidade\Financeiro\Contabilidade\ContaCorrente\Services\Processamento;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
ini_set('memory_limit', '-1');

$parametros = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$retorno          = new stdClass();
$retorno->message = '';
$retorno->erro    = true;
define("MENSAGENS", "con4_matrizsaldocontabil.RPC.json");
db_putsession("DB_desativar_account", true);
$instituicao = InstituicaoRepository::getInstituicaoSessao();
try {
    db_inicio_transacao();

    switch ($parametros->exec) {
        case 'reprocessar':

            if (empty($parametros->competencia)) {
                throw  new ParameterException('A competência para processamento deve ser informada.');
            }
            if (empty($parametros->conta_corrente)) {
                throw  new ParameterException('A conta corrente para processamento deve ser informada.');
            }

            $competencia = DBCompetencia::createFromString($parametros->competencia);
            $processamento = new Processamento($instituicao, $competencia);
            $processamento->reprocessar($parametros->conta_corrente, $parametros->contas);

            break;

        case 'reprocessarSaldoInicial':
            
            if (empty($parametros->ano)) {
                throw  new ParameterException('O ano para processamento deve ser informado.');
            }
            if (empty($parametros->conta_corrente)) {
                throw  new ParameterException('A conta corrente para processamento deve ser informada.');
            }
            if (empty($parametros->contas)) {
                throw new ParameterException("É necessário informar ao menos uma conta contábil para reprocessamento.");
            }

            $processamento = new Processamento($instituicao, new DBCompetencia($parametros->ano, '01'));
            foreach ($parametros->contas as $codigoConta) {
                $processamento->reprocessarSaldoInicial($codigoConta, $parametros->conta_corrente);
            }

            break;
    }


    $retorno->message = "Os conta correntes com os filtros informados foram reprocessados com sucesso.";
    db_fim_transacao(false);
} catch (NotFoundException $oErro) {

    db_fim_transacao(false);
    $retorno->message = $oErro->getMessage();
} catch (Exception $oErro) {

    db_fim_transacao(true);
    $retorno->erro = true;
    $retorno->message = $oErro->getMessage();
}
unset($_SESSION["DB_desativar_account"]);
echo JSON::create()->stringify($retorno);