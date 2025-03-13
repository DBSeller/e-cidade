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

use ECidade\RecursosHumanos\Pessoal\Model\ControleAfastamento;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleAfastamentoRepository;
use ECidade\RecursosHumanos\Pessoal\Service\ControleAfastamentoService;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

$instituicao = InstituicaoRepository::getInstituicaoSessao();
$anoFolha = DBPessoal::getAnoFolha();
$mesFolha = DBPessoal::getMesFolha();

$dao = new cl_controleafastamento();
$repository = new ControleAfastamentoRepository($dao);
$service = new ControleAfastamentoService($repository);

try {
    db_inicio_transacao();

    switch ($parametros->acao) {
        case 'buscarFaixas':
            if (empty($parametros->codigoTabela)) {
                throw new Exception('É necessário informar o código da tabela de previdência.');
            }

            $retorno->faixas = $service->buscarFaixas($instituicao, $parametros->codigoTabela, $anoFolha, $mesFolha);
            break;

        case 'buscarRubricas':
            if (empty($parametros->codigoTabela)) {
                throw new Exception('É necessário informar o código da tabela de previdência.');
            }

            if (empty($parametros->tipoAfastamento)) {
                throw new Exception('É necessário informar o código do afastamento.');
            }
            $retorno->rubricas = array_map(function (Rubrica $rubrica) {
                return $rubrica->toArray();
            }, $service->buscaRubricasProporcionalizaveis(
                new cl_rhrubricas(),
                $instituicao,
                $parametros->codigoTabela,
                $parametros->tipoAfastamento,
                $anoFolha,
                $mesFolha
            ));

            $retorno->rubricasSelecionadas = array_map(function (ControleAfastamento $controleAfastamento) {
                return $controleAfastamento->toArray();
            }, $service->filtraRubricasPorAfastamentos(
                $instituicao,
                $parametros->tipoAfastamento,
                $parametros->codigoTabela,
                $anoFolha,
                $mesFolha
            ));

            break;

        case 'salvarVinculo':
            if (empty($parametros->afastamento)) {
                throw new Exception('É necessário informar o afastamento.');
            }

            if (empty($parametros->rubricas)) {
                throw new Exception('É necessário informar as rubricas.');
            }

            if (empty($parametros->codigoTabela)) {
                throw new Exception('É necessário informar o código da tabela de previdência.');
            }

            $retorno->sucesso = $service->vinculaRubricasAfastamento(
                $parametros->afastamento,
                JSON::create()->parse($parametros->rubricas),
                $parametros->codigoTabela,
                $instituicao,
                $anoFolha,
                $mesFolha
            );

            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
