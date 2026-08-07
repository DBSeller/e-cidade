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

use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Services\AutorizacaoService;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Repository\AutorizacaoRepository;
use ECidade\Patrimonial\Compras\HistoricoEmpenho\Repository\HistoricoRepository;
use ECidade\Patrimonial\Compras\TipoPrestacaoEmpenho\Repository\TipoPrestacaoRepository;
use ECidade\Patrimonial\Compras\ProcessoAdministrativoEmpenho\Repository\ProcessoAdministrativoRepository;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('model/configuracao/InstituicaoRepository.model.php');

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';
$ano = db_getsession('DB_anousu');

try {
    db_inicio_transacao();

    $repository = new AutorizacaoRepository(new \cl_empautoriza());
    $service = new AutorizacaoService($repository);

    switch ($parametros->acao) {
        case 'validaDadosAutorizacao':
            $parametros->isInsersao = $parametros->isInsersao == "true" ? true : false;
            if (empty($parametros->fornecedor)) {
                throw new Exception('È necessário informar o fornecedor.');
            }
            $retorno->statusFornecedor = $service->validaDadosAutorizacao(
                new fornecedor($parametros->fornecedor),
                $parametros->isInsersao
            );

            break;

        case 'buscarPorCodigoAutorizacao':
            $result = $service->buscaAutorizacao($parametros->e54_autori);
            $retorno->autorizacao = $result->toArray();
            break;

        case 'buscaLicitacoesPorTipoCodigo':
            $retorno->camposLicitacao = $service->buscaLicitacoesPorTipoCompra(
                $parametros->e54_codcom,
                new cl_cflicita()
            );
            break;

        case 'salvar':
            $daoAndamentoAutorizacao = new cl_andamentoemppreautorizacao;
            if (!empty($parametros->e54_autori) &&
                $daoAndamentoAutorizacao->travaAutorizacaoAndamento($parametros->e54_autori)
            ) {
                $msg = "1 - Não é possivel alterar a autorização no andamento em que ele está.";
                $msg .= " Verique o status da autorização";
                throw new Exception($msg);
            }
            $parametros->o58_anousu = $ano;
            $autorizacao = $service->salvar($parametros, InstituicaoRepository::getInstituicaoSessao());
            $retorno->autorizacao = $autorizacao->toArray();
            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
