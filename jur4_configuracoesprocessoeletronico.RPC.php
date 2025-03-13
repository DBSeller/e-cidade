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

use ECidade\Tributario\Juridico\ProcessoEletronico\Configuracao;
use ECidade\Tributario\Juridico\ProcessoEletronico\Processamento;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\Configuracao as ConfiguracaoRepository;

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));

$post = db_utils::postMemory($_POST);
$json = JSON::create();
$parametros = $json->parse(db_stdClass::db_stripTagsJson(str_replace("\\","",$post->json)));

$retorno = new stdClass();
$retorno->erro     = false;
$retorno->mensagem = '';
$dataSistema = new \DateTime(date("Y-m-d", db_getsession("DB_datausu")));
$instituicao = InstituicaoRepository::getInstituicaoSessao();
try {

    db_inicio_transacao();
    switch ($parametros->exec) {

        case 'salvarConfiguracao' :

            if (empty($parametros->senha)) {
                throw new ParameterException('Campo Senha deve ser informado.');
            }
            if (empty($parametros->localidade)) {
                throw new ParameterException('Campo Código da Localidade deve ser informado.');
            }
            if (empty($parametros->usuario)) {
                throw new ParameterException('Campo Usuário deve ser informado.');
            }

            $configuracao = ConfiguracaoRepository::getPorInstituicao($instituicao->getCodigo());
            if (empty($configuracao)) {
                $configuracao = new Configuracao();
            }
            $configuracao->setCodigo((int)$parametros->codigo);
            $configuracao->setUsuario($parametros->usuario);
            $configuracao->setSenha($parametros->senha);
            $configuracao->setLocalidade($parametros->localidade);
            ConfiguracaoRepository::persist($configuracao, $instituicao);
            $retorno->codigo = $configuracao->getCodigo();
            $retorno->mensagem = 'As configurações para a instiuição foram salvas com sucesso.';
            break;

        case 'getConfiguracao':

            $configuracao = ConfiguracaoRepository::getPorInstituicao($instituicao->getCodigo());
            if (!empty($configuracao)) {
                $retorno->codigo     = $configuracao->getCodigo();
                $retorno->senha      = $configuracao->getSenha();
                $retorno->usuario    = $configuracao->getUsuario();
                $retorno->localidade = $configuracao->getLocalidade();
            }
            break;
    }
    db_fim_transacao(false);
} catch (Exception $e) {

    $retorno->erro     = true;
    $retorno->mensagem = $e->getMessage();
    db_fim_transacao(true);
}
echo $json->stringify($retorno);