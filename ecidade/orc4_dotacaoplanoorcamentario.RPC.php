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

use ECidade\Financeiro\Orcamento\Dotacao\Model\LinhaDePacto as LinhaDePactoModel;
use ECidade\Financeiro\Orcamento\Dotacao\Repository\LinhaDePacto as LinhaDePactoRepository;
use ECidade\Financeiro\Orcamento\Dotacao\Repository\PlanoOrcamentario as PlanoOrcamentarioRepository;
use ECidade\Financeiro\Orcamento\Dotacao\Model\PlanoOrcamentario as PlanoOrcamentarioModel;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$parametros = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';


$anoSessao = db_getsession('DB_anousu');
$instituicaoSessao = db_getsession('DB_instit');
try {

    db_inicio_transacao();

    switch ($parametros->exec) {

        case 'salvarPlano':

            $dotacao = DotacaoRepository::getDotacaoPorCodigoAno($parametros->po_dotacao, $anoSessao);
            $valorTotalPlano  = PlanoOrcamentarioRepository::getValorTotalLinhasDaDotacao($dotacao);
            if ($valorTotalPlano + (float)$parametros->po_valor > $dotacao->getValor() && $parametros->po_valor > 0) {
                $mensagem = "O valor total das linhas de pacto não deve ultrapassar o valor inicial da dotação.";
                throw new Exception($mensagem);
            }
            $plano = new PlanoOrcamentarioModel();
            $plano->setCodigo($parametros->po_codigo);
            $plano->setTitulo($parametros->po_descricao);
            $plano->setValor((float)$parametros->po_valor);
            $plano->setDotacao($dotacao);
            PlanoOrcamentarioRepository::persist($plano);
            $retorno->mensagem = 'Plano orçamentário salvo com sucesso.';

            break;

        case 'excluirPlano':

            $plano = new PlanoOrcamentarioModel();
            $plano->setCodigo($parametros->po_codigo);
            PlanoOrcamentarioRepository::excluir($plano);
            $retorno->mensagem = "Plano orçamentário excluído com sucesso.";
            break;

        case 'salvarLinha':

            $planoLinha = new LinhaDePactoModel();
            $planoLinha->setCodigo(null);
            $planoLinha->setCodigoLinha($parametros->linha);
            $planoLinha->setValor($parametros->valor);

            $plano = PlanoOrcamentarioRepository::getPorCodigo($parametros->po_codigo);
            $plano->adicionarLinha($planoLinha);
            PlanoOrcamentarioRepository::persist($plano);
            $retorno->mensagem = "Linha de pacto salva com sucesso.";

            break;

        case 'excluirLinha':

            $linhaPacto = new LinhaDePactoModel();
            $linhaPacto->setCodigo($parametros->codigo);
            LinhaDePactoRepository::excluir($linhaPacto);
            $retorno->mensagem = "Linha de pacto excluída com sucesso.";
            break;

        case 'getPlanos':

            $dotacao = DotacaoRepository::getDotacaoPorCodigoAno($parametros->po_dotacao, $anoSessao);
            $planos = PlanoOrcamentarioRepository::getPorDotacao($dotacao);

            $retorno->planos = array();
            foreach ($planos as $plano) {

                $retorno->planos[] = (object)array(
                    'codigo' => $plano->getCodigo(),
                    'titulo' => $plano->getTitulo(),
                    'valor'  => $plano->getValor()
                );
            }

            break;

        case 'getLinhas':

            $plano = new PlanoOrcamentarioModel();
            $plano->setCodigo($parametros->po_codigo);
            $linhas = LinhaDePactoRepository::getPorPlano($plano);
            $retorno->linhas = array();
            foreach ($linhas as $linha) {

                $retorno->linhas[] = (object)array(
                    'codigo' => $linha->getCodigo(),
                    'codigoLinha' => $linha->getCodigoLinha(),
                    'descricao' => $linha->getDescricao(),
                    'valor' => $linha->getValor(),
                );
            }

            break;

        case 'remanejarValores':


            if (empty((int)$parametros->linha_pacto_origem)) {
                throw new \Exception("A linha de pacto de origem deve ser informada");
            }
            if (empty((int)$parametros->linha_pacto_destino)) {
                throw new \Exception("A linha de pacto de origem deve ser informada");
            }
            if (empty((float)$parametros->valor)) {
                throw new \Exception("o Valor deve ser informado");
            }

            $saldoPo = getSaldoPactoLinhaOrcamento((int)$parametros->linha_pacto_origem);
            if ($saldoPo - (float)$parametros->valor < 0) {
                throw new Exception("A linha de pacto de origem não possui saldo para realizar essa operação.");
            }
            $data = date("Y-m-d", db_getsession('DB_datausu'));
            $sqlInsertOrigem = "select fc_atualiza_saldo_po({$parametros->linha_pacto_origem}, 1, '{$data}', {$parametros->valor})";
            $rsInclusaoOrigem = db_query($sqlInsertOrigem);
            if (!$rsInclusaoOrigem) {
                throw new Exception("Não foi possível incluir redução para a Linha de pacto {$parametros->linha_pacto_origem}");
            }

            $sqlInsertDestino = "select fc_atualiza_saldo_po({$parametros->linha_pacto_destino}, 2, '{$data}', {$parametros->valor})";
            $rsInclusaoOrigem = db_query($sqlInsertDestino);
            if (!$sqlInsertDestino) {
                throw new Exception("Não foi possível incluir suplementação para a Linha de pacto {$parametros->linha_pacto_destino}");
            }
            $retorno->mensagem = 'O Remanejamento foi realizado com sucesso.';
            break;

        case 'incluirValorManual':


            if (empty((int)$parametros->linha_pacto_origem)) {
                throw new \Exception("A linha de pacto de deve ser informada");
            }
            if (empty((float)$parametros->valor)) {
                throw new \Exception("o Valor deve ser informado");
            }

            $data = date("Y-m-d", db_getsession('DB_datausu'));
            $sqlInsertOrigem = "select fc_atualiza_saldo_po({$parametros->linha_pacto_origem}, 1, '{$data}', {$parametros->valor})";
            $rsInclusaoOrigem = db_query($sqlInsertOrigem);
            if (!$rsInclusaoOrigem) {
                throw new Exception("Não foi possível incluir redução para a Linha de pacto {$parametros->linha_pacto_origem}");
            }

            $retorno->mensagem = 'O Remanejamento foi realizado com sucesso.';
            break;
    }

    db_fim_transacao(false);
} catch (Exception $e) {

    $retorno->erro = true;
    $retorno->mensagem = $e->getMessage();
    db_fim_transacao(true);
}

echo JSON::create()->stringify($retorno);

