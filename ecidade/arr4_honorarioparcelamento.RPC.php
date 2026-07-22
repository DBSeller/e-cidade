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

use ECidade\Tributario\Juridico\Inicial\Inicial;//MODEL
use ECidade\Tributario\Juridico\ProcessoForo\ProcessoForo;//MODEL
use ECidade\Tributario\Arrecadacao\Model\HonorarioParcelamento;//MODEL
use ECidade\Tributario\Arrecadacao\Repository\HonorarioParcelamentoRepository;//REPOSITORY

$post = db_utils::postMemory($_REQUEST);
$post->json = str_replace("\\", "", $post->json);
$parametro = JSON::create()->parse($post->json);
$retorno = (object)array('erro' => false, 'mensagem' => '');

try {
    db_inicio_transacao();

    switch ($parametro->executa) {
        case "salvar":
            $honorarioParcelamento = new HonorarioParcelamento();
            $honorarioParcelamento->setSequencial($parametro->sequencial);

            //PROCESSO
            if (!empty($parametro->processoForo)) {
                $processoForo = new ProcessoForo;
                $processoForo->setCodigo($parametro->processoForo);

                $honorarioParcelamento->setProcessoForo($processoForo);
            }

            //INICIAL
            if (!empty($parametro->inicial)) {
                $inicial = new Inicial;
                $inicial->setCodigo($parametro->inicial);

                $honorarioParcelamento->setInicial($inicial);
            }

            $honorarioParcelamento->setNumeroParcelas($parametro->numeroParcelas);

            $honorarioParcelamentoRepository = new HonorarioParcelamentoRepository();
            $honorarioParcelamentoRepository->persist($honorarioParcelamento);

            $retorno->mensagem = 'Número de parcelas salva com sucesso.';

            break;

        case "excluir":
            $honorarioParcelamento = new HonorarioParcelamento();
            $honorarioParcelamento->setSequencial($parametro->sequencial);

            $honorarioParcelamentoRepository = new HonorarioParcelamentoRepository;
            $honorarioParcelamentoRepository->delete($honorarioParcelamento);

            $retorno->mensagem = 'Parcelamento removido com sucesso.';

            break;

        case "buscar":
            $honorarioParcelamentoRepository = new HonorarioParcelamentoRepository;

            if (!empty($parametro->processoForo)) {
                $processoForo = new ProcessoForo;
                $processoForo->setCodigo($parametro->processoForo);

                $validaProcessoForoParcelamento = $honorarioParcelamentoRepository->hasParcelamentoProcessoForo($processoForo);
                $validaProcessoForoParcelamento = true;
                if (!$validaProcessoForoParcelamento) {
                    $retorno->erro              = true;
                    $retorno->validado          = true;
                    $retorno->mensagemValidacao = "Esse processo do foro já possui parcelamento.";

                    $validaProcessoForo = $honorarioParcelamentoRepository->hasPartilhaProcessoForo($processoForo);

                    if ($validaProcessoForo) {
                        $retorno->mensagemValidacao = "Já existe honorário pago ou isento para este processo do foro.";
                    } else {
                        if($retorno->validado == ""){
                            $retorno->validado = false;
                        }
                    }
                } else {
                    $retorno->validado = false;
                }

                $honorarioParcelamento = $honorarioParcelamentoRepository->getByProcessoForo($processoForo);
            } else {
                if (!empty($parametro->inicial)) {
                    $inicial = new Inicial;
                    $inicial->setCodigo($parametro->inicial);

                    $validaInicial = $honorarioParcelamentoRepository->getValidaInicial($inicial);

                    if (!$validaInicial->liberaConsulta) {
                        $retorno->erro              = true;
                        $retorno->validado          = true;
                        $retorno->mensagemValidacao = $validaInicial->mensagem;
                    } else {
                        $retorno->validado = false;
                    }

                    $honorarioParcelamento = $honorarioParcelamentoRepository->getByInicial($inicial);
                } else {
                    throw new Exception('Informe o processo do foro ou a inicial.');
                    break;
                }
            }

            if (!empty($honorarioParcelamento)) {
                $retorno->sequencial     = $honorarioParcelamento->getSequencial();
                $retorno->numeroParcelas = $honorarioParcelamento->getNumeroParcelas();
            }

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
