<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$parametro = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$retorno = new stdClass();
$retorno->mensagem = '';
$retorno->erro = false;

try {
    
    db_inicio_transacao();
    
    switch ($parametro->exec) {
        
        case 'salvar':
            
            $data = trim(str_replace('/', '', $parametro->data->s2230->data_inicio));
            if (empty($data)) {
                throw new ParameterException("A data para o arquivo S2230 é de preenchimento obrigatório.");
            }
            
            $s2230 = new \ECidade\RecursosHumanos\ESocial\Configuracao\S2230();
            $s2230->setDataEnvio(new DBDate($parametro->data->s2230->data_inicio));
            $s2230->salvar();
            
            $s2229 = new \ECidade\RecursosHumanos\ESocial\Configuracao\S2229();
            $s2229->setDataEnvio(new DBDate($parametro->data->s2229->data_inicio));
            $s2229->salvar();
            $retorno->mensagem = "Configuração salva com sucesso.";
            break;
            
        case 'getConfiguracao':
            
            $s2230 = new \ECidade\RecursosHumanos\ESocial\Configuracao\S2230();
            $retorno->arquivo = new stdClass();
            $retorno->arquivo->s2230 = $s2230->get();
            
            $s2229 = new \ECidade\RecursosHumanos\ESocial\Configuracao\S2229();
            $retorno->arquivo->s2229 = $s2229->get();
            
            break;
    }
    
    db_fim_transacao(false);
    
} catch (Exception $e) {
    
    db_fim_transacao(true);
    
    $retorno->mensagem = $e->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);