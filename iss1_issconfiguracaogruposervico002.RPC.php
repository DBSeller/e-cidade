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

use ECidade\Tributario\Issqn\Model\IssGSCadAnexos;
use ECidade\Tributario\Issqn\Repository\IssGSCadAnexosRepository;
use ECidade\Tributario\Issqn\Model\IssGSAnexos;
use ECidade\Tributario\Issqn\Repository\IssGSAnexosRepository;
use ECidade\Tributario\Issqn\Model\IssCnaeAnexos;
use ECidade\Tributario\Issqn\Repository\IssCnaeAnexosRepository;

$post = db_utils::postMemory($_REQUEST);
$post->json = str_replace("\\", "", $post->json);
$parametro = JSON::create()->parse($post->json);
$retorno = (object)array('erro' => false, 'mensagem' => '');

try {
    db_inicio_transacao();

    switch ($parametro->executa) {
        case "lista":
            $IssGSCadAnexos = new IssGSCadAnexos();

            $IssGSCadAnexosRepository = IssGSCadAnexosRepository::getInstance();

            $dados = $IssGSCadAnexosRepository->getAnexos();

            $lista = array();

            foreach ($dados as $key => $dado) {
                $lista[] = array(
                    "q157_sequencial" => $dado->getSequencial(),
                    "q157_codigo" => $dado->getCodigo(),
                    "q157_descricao" => $dado->getDescricao()
                );
            }

            $retorno->lista = $lista;

            break;

        case "anexosGrupoServico":
            $IssGSAnexos = new IssGSAnexos();

            $IssGSAnexosRepository = IssGSAnexosRepository::getInstance();

            $dados = $IssGSAnexosRepository->getByAnexosGrupoServico($test);

            foreach ($dados as $key => $dado) {
                $lista[] = array(
                    "q162_sequencial" => $dado->getSequencial(),
                    "q162_issgruposervico" => $dado->getIssgruposervico(),
                    "q162_issgscadanexos" => $dado->getIssgscadanexos(),
                    "q162_data_fim" => $dado->getDataFim()
                );
            }

            break;

        case "excluir":
            $IssGSAnexos = new IssGSAnexos();

            $IssGSAnexos->setSequencial($parametro->q162_sequencial);

            $IssGSAnexosRepository = IssGSAnexosRepository::getInstance();

            $IssGSAnexosRepository->delete($IssGSAnexos);
            $retorno->mensagem = 'Vinculo removido com sucesso!';

            break;

        case "excluirVinculoCnae":
                $IssCnaeAnexos = new IssCnaeAnexos();
    
                $IssCnaeAnexos->setSequencial($parametro->q178_sequencial);
    
                $IssCnaeAnexosRepository = IssCnaeAnexosRepository::getInstance();
    
                $IssCnaeAnexosRepository->delete($IssCnaeAnexos);
                $retorno->mensagem = 'Vinculo removido com sucesso!';
    
            break;

        default:
            throw new Exception('Nenhuma ação encontrada.');
            break;
    }

    db_fim_transacao(false);
} catch (Exception $erro) {
    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $erro->getMessage();
}

echo JSON::create()->stringify($retorno);