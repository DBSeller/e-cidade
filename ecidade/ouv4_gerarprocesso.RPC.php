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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/JSON.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('model/processoOuvidoria.model.php');

use ECidade\Patrimonial\Ouvidoria\Externa\Repository\PreProcesso as PreProcessoRepository;
use ECidade\Patrimonial\Ouvidoria\Externa\Service\PreProcesso as PreProcessoService;
use cl_ouvidoriaatendimento;
use cl_ouvidoriaparametro;
use cl_ouvidoriaatendimentocidadao;
use cl_processoouvidoria;
use db_utils;
use db_query;
use CidadaoRepository;
use Cidadao;

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

db_inicio_transacao();

try {
    $parametrosRequest = JSON::requestParameters();
    $parametros = JSON::create()->parse($parametrosRequest->json);

    switch ($parametros->executa) {
        case 'buscarPreProcessos':
            $instituicao = InstituicaoRepository::getInstituicaoSessao();

            $preProcessoRepository = PreProcessoRepository::getInstancia();
            $preProcessos = $preProcessoRepository->buscarPreProcessos($instituicao);

            if ($preProcessos === null) {
                throw new Exception('Nenhuma Ouvidoria Externa encontrada.');
            }

            $retorno->preProcessos = array();

            foreach ($preProcessos->getAll() as $preProcesso) {
                $dadosPreProcesso = new stdClass();
                $dadosPreProcesso->sequencial = $preProcesso->getSequencial();
                $dadosPreProcesso->tipoProcesso = $preProcesso->getTipoProcesso()->getDescricao();

                $retorno->preProcessos[] = $dadosPreProcesso;
            }

            if ($preProcessos === null) {
                throw new Exception('Nenhuma Ouvidoria Externa encontrada.');
            }

            break;

        case 'buscarDadosPreProcesso':
            if (empty($parametros->sequencial)) {
                throw new Exception('Pré Processo não informado.');
            }

            $preProcessoRepository = PreProcessoRepository::getInstancia();
            $preProcessoModel = $preProcessoRepository->getPreProcessoByCodigo($parametros->sequencial);

            $retorno->sequencial = $preProcessoModel->getSequencial();
            $retorno->data = $preProcessoModel->getData()->getDate(DBDate::DATA_PTBR);
            $retorno->hora = $preProcessoModel->getHora();
            $retorno->requerente = $preProcessoModel->getRequerente();
            $retorno->tipoProcesso = $preProcessoModel->getTipoProcesso()->getDescricao();
            $retorno->maisInformacoes = $preProcessoModel->getObservacao();

            break;

        case 'criarProcesso':
            if (empty($parametros->codigosPreProcesso)) {
                throw new Exception('Nenhuma ouvidoria informada para criação de processo.');
            }

            $preProcessoRepository = PreProcessoRepository::getInstancia();

            foreach ($parametros->codigosPreProcesso as $codigoPreProcesso) {
                $preProcessoModel = $preProcessoRepository->getPreProcessoByCodigo($codigoPreProcesso);
                $tipoProcesso = $preProcessoModel->getTipoProcesso();


                $protocoloProcesso = new processoProtocolo();
                $protocoloProcesso->setTipoProcesso($tipoProcesso->getCodigo());
                $protocoloProcesso->setDataProcesso($preProcessoModel->getData()->getDate());
                $protocoloProcesso->setUsuario($preProcessoModel->getUsuario());
                $protocoloProcesso->setCgm($preProcessoModel->getCgm()->getCodigo());
                $protocoloProcesso->setRequerente($preProcessoModel->getRequerente());
                $protocoloProcesso->setDepartamento($preProcessoModel->getDepartamento());
                $protocoloProcesso->setObservacao($preProcessoModel->getObservacao());
                $protocoloProcesso->setDespacho($preProcessoModel->getDespacho());
                $protocoloProcesso->setHora($preProcessoModel->getHora());
                $protocoloProcesso->setInterno($preProcessoModel->isInterno() ? 'true' : 'false');
                $protocoloProcesso->setPublico($preProcessoModel->isPublico() ? 'true' : 'false');
                $protocoloProcesso->setInstituicao($preProcessoModel->getInstituicao());
                $protocoloProcesso->setAnoProcesso($preProcessoModel->getAno());
                $protocoloProcesso->salvar();

                $preProcessoRepository->vincularProcesso($preProcessoModel, $protocoloProcesso->getCodProcesso());

                // Cria atendimento de ouvidoria e vicula o processo criado
                if ($tipoProcesso->getCodigoGrupo() == $tipoProcesso::GRUPO_OUVIDORIA) {

                    $requerenteCpf = $preProcessoModel->getDespacho();
                    $cidadao = null;

                    if (!empty($requerenteCpf)) {
                        $cidadaos = cidadaoRepository::getCidadaoPorNomeCpf($preProcessoModel->getRequerente(), $requerenteCpf);

                        if (!empty($cidadaos)) {
                            $cidadao = $cidadaos[0];
                        } else {
                            $cidadao = new Cidadao();
                            $cidadao->setNome(addslashes($preProcessoModel->getRequerente()));
                            $cidadao->setCpfCnpj($requerenteCpf);
                            $cidadao->setSituacaoCidadao(2);
                            $cidadao->salvar();
                        }
                    }

                    $ouvidoriaAtendimento = new cl_ouvidoriaatendimento();
                    $ouvidoriaAtendimento->ov01_tipoprocesso      = $tipoProcesso->getCodigo();
                    $ouvidoriaAtendimento->ov01_formareclamacao   = 3;
                    $ouvidoriaAtendimento->ov01_tipoidentificacao = !empty($requerenteCpf) ? 2 : 1;
                    $ouvidoriaAtendimento->ov01_usuario           = $preProcessoModel->getUsuario()->getCodigo();
                    $ouvidoriaAtendimento->ov01_depart            = $preProcessoModel->getDepartamento()->getCodigo();
                    $ouvidoriaAtendimento->ov01_instit            = $preProcessoModel->getInstituicao()->getCodigo();
                    $ouvidoriaAtendimento->ov01_anousu            = $preProcessoModel->getAno();
                    $ouvidoriaAtendimento->ov01_dataatend         = $preProcessoModel->getData()->getDate();  
                    $ouvidoriaAtendimento->ov01_horaatend         = $preProcessoModel->getHora();
                    $ouvidoriaAtendimento->ov01_requerente        = $preProcessoModel->getRequerente(); 
                    $ouvidoriaAtendimento->ov01_solicitacao       = $preProcessoModel->getObservacao();
                    $ouvidoriaAtendimento->ov01_executado         = ''; 
                    $ouvidoriaAtendimento->ov01_situacaoouvidoriaatendimento = 1;
                    $ouvidoriaAtendimento->ov01_sequencial        = null;


                    // Lógica para pegar o numero do atendimento

                    $oOuvidoriaParam = new cl_ouvidoriaparametro();
                    $sSqlParametro   = $oOuvidoriaParam->sql_query_file($preProcessoModel->getInstituicao()->getCodigo(), date('Y'), "ov06_tiponumprocesso");
                    $rsParametro     = $oOuvidoriaParam->sql_record($sSqlParametro);

                    $iTipoControleNumeracao = 1;
                    if ($rsParametro && $oOuvidoriaParam->numrows == 1) {
                      $iTipoControleNumeracao = 2;
                    }
                    
                    $numeroAtendimento = 1;

                    if ($iTipoControleNumeracao == 1) { // Sequencial infinito
                        $rsNumeroAtendimento = db_query("select max(ov01_numero) + 1 as seq from ouvidoriaatendimento");
                        if ( $rsNumeroAtendimento ) {
                          $numeroAtendimento = db_utils::fieldsMemory($rsNumeroAtendimento, 0)->seq;
                        }
                    } else {
                      
                      $rsAnoAtendimento = db_query("select 1 from ouvidoriaatendimento where ov01_anousu = " . date('Y'));
                      
                      if ($rsAnoAtendimento && pg_num_rows($rsAnoAtendimento) > 0) { //Sequencial por ano
                        $rsProximoNumero    = db_query("select max(ov01_numero) + 1 as seq from ouvidoriaatendimento where ov01_anousu = " . date('Y')); 
                        $oNumeroAtendimento = db_utils::fieldsMemory($rsProximoNumero, 0)->seq;
                      }
                    }
                    
                    $ouvidoriaAtendimento->ov01_numero = $numeroAtendimento;
                    $ouvidoriaAtendimento->incluir(null);

                    // Inclui vinculo com cidadao quando existir
                    if ($cidadao) {
                        $ouvidoriaCidadao = new cl_ouvidoriaatendimentocidadao();
                        $ouvidoriaCidadao->ov10_ouvidoriaatendimento = $ouvidoriaAtendimento->ov01_sequencial;
                        $ouvidoriaCidadao->ov10_cidadao              = $cidadao->getCodigo(); 
                        $ouvidoriaCidadao->ov10_seq                  = $cidadao->getSequencialInterno();
                        $ouvidoriaCidadao->ov10_sequencial           = null;
                        $ouvidoriaCidadao->incluir(null);

                        if ($ouvidoriaCidadao->erro_status == 0) {
                            throw new Exception($ouvidoriaCidadao->erro_msg);
                        }
                    }

                    if ($ouvidoriaAtendimento->erro_status === '0') {
                        throw new Exception('Erro ao gerar atendimento de ouvidoria.');
                    }

                    // Vincula atendimento de ouvidoria com o processo
                    $processoOuvidoriaVinculo = new cl_processoouvidoria();
                    $processoOuvidoriaVinculo->ov09_ouvidoriaatendimento = $ouvidoriaAtendimento->ov01_sequencial; 
                    $processoOuvidoriaVinculo->ov09_protprocesso         = $protocoloProcesso->getCodProcesso();
                    $processoOuvidoriaVinculo->ov09_principal            = 'true';
                    $processoOuvidoriaVinculo->incluir(null);
                    
                    if ($processoOuvidoriaVinculo->erro_status === '0') {
                        throw new Exception('Erro ao gerar atendimento de ouvidoria.');
                    }

                    $processoOuvidoria = new processoOuvidoria();
                    $processoOuvidoria->incluirDespachoInterno($protocoloProcesso->getCodProcesso(), $ouvidoriaAtendimento->ov01_solicitacao);
                }

                $preProcessoService = new PreProcessoService($preProcessoModel);
                $arquivosCollection = $preProcessoService->getArquivos();

                foreach ($arquivosCollection->getAll() as $arquivo) {
                    $oProcessoDocumento = new ProcessoDocumento();
                    $oProcessoDocumento->setDescricao(db_stdClass::normalizeStringJsonEscapeString($arquivo->getNome()));
                    $oProcessoDocumento->setProcessoProtocolo($protocoloProcesso);
                    $oProcessoDocumento->setUsuario($preProcessoModel->getUsuario());
                    $oProcessoDocumento->setProcandamint(0);
                    $oProcessoDocumento->setCaminhoArquivo($arquivo->getCaminho());
                    $oProcessoDocumento->salvar();
                }
            }

            $retorno->mensagem = 'Processo(s) criado(s) com sucesso.';

            break;
    }
} catch (Exception $exception) {
    $retorno->erro = true;
    $retorno->mensagem = $exception->getMessage();
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
