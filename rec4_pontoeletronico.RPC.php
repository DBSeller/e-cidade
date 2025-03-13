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

use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa;
use ECidade\RecursosHumanos\RH\Assentamento\Model\LoteLancamento;
use ECidade\RecursosHumanos\RH\Assentamento\Repository\LoteLancamentoRepository;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Jornada as JornadaRepository;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Periodo as PeriodoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Importacao;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository\Importacao as ImportacaoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\Repository\EspelhoPontoCache;
use ECidade\V3\Extension\Registry;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oPost       = db_utils::postMemory($_REQUEST);
$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

$oDiaTrabalhoRepository = new DiaTrabalhoRepository();

ini_set('memory_limit', '-1');
error_reporting(E_ALL & ~E_DEPRECATED);
$timeZone = date_default_timezone_get();
date_default_timezone_set('UTC');

try {
    db_inicio_transacao();
    switch ($oParametro->exec) {
        case "importarArquivo":
            if (empty($_FILES['rh196_arquivo'])) {
                throw new ParameterException('Nenhum arquivo informado.');
            }

            if ($_FILES['rh196_arquivo']['error'] !== UPLOAD_ERR_OK) {
                throw new FileException('Ocorreu um erro ao fazer envio do arquivo.');
            }

            if(!isset($oParametro->periodo->dataInicio) || empty($oParametro->periodo->dataInicio)) {
                throw new ParameterException('Período inicio não informado.');
            }

            if(!isset($oParametro->periodo->dataFim) || empty($oParametro->periodo->dataFim)) {
                throw new ParameterException('Período fim não informado.');
            }

            $oParametro->arquivo = (object)$_FILES['rh196_arquivo'];
            $sNomeArquivo        = 'tmp/'.$oParametro->arquivo->name;

            move_uploaded_file($oParametro->arquivo->tmp_name, $sNomeArquivo);

            $oLayoutArquivo = new \DBLayoutReader(Importacao::CODIGO_LAYOUT_ARQUIVO, $sNomeArquivo, true, false);
            $oLayoutArquivo->processarArquivo(0, true, true);
            $aPisMatriculasProcessar = array();

            $oDataInicioParametro = new DBDate($oParametro->periodo->dataInicio);
            $oDataFinalParametro  =  new DBDate($oParametro->periodo->dataFim);

            foreach($oLayoutArquivo->getLines() as $oLinha) {

                switch($oLinha->TIPO_REGISTRO) {
                    case Importacao::REGISTRO_CABECALHO:

                        $oDataInicialArquivo = new DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_INICIAL));
                        $oDataFinalArquivo   = new DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_FINAL));

                        if (   ($oDataInicioParametro->getTimeStamp() < $oDataInicialArquivo->getTimeStamp())
                            || ($oDataFinalParametro->getTimeStamp() > $oDataFinalArquivo->getTimeStamp()) ) {
                            $sMensagemDataInconsistente  = "O período informado deve ser igual ou estar entre o período do arquivo.\n";
                            $sMensagemDataInconsistente .= "Período do arquivo: {$oDataInicialArquivo->getDate(DBDate::DATA_PTBR)} - {$oDataFinalArquivo->getDate(DBDate::DATA_PTBR)} \n";
                            $sMensagemDataInconsistente .= "Importe um arquivo com o período informado ou altere o período para o correspondente no arquivo.";
                            throw new BusinessException($sMensagemDataInconsistente);
                        }
                        break;
                    default:
                        if(isset($oLinha->PIS_EMPREGADO)) {
                            $aPisMatriculasProcessar[$oLinha->PIS_EMPREGADO] = substr($oLinha->PIS_EMPREGADO, 1);
                        }
                        continue 2;
                        break;
                }
            }

            $oPeriodoRepository = new PeriodoRepository(null, null, true);
            $aPeriodos = $oPeriodoRepository->getPeriodosEntreDatas($oDataInicioParametro, $oDataFinalParametro);
            $aMatriculasProcessar = ImportacaoRepository::matriculasParaProcessarPorPis($aPisMatriculasProcessar);

            $timeStamp = time();
            file_put_contents('tmp/log_importacao_arquivo_ponto.log', 'Quantidade de matriculas: '.count($aMatriculasProcessar).PHP_EOL);
            db_fim_transacao(false);

            foreach ($aPeriodos as $oPeriodo) {
                $_SESSION["DB_desativar_account"] = true;

                db_inicio_transacao();
                file_put_contents('tmp/log_importacao_arquivo_ponto.log', "Periodo: {$oPeriodo->getDataInicio()} ate {$oPeriodo->getDataFim()}" .PHP_EOL, FILE_APPEND);

                $oImportacao          = new Importacao($sNomeArquivo, $oPeriodo);
                $oImportacao->setSobrescreverMarcacao(!!$oParametro->sobrescrever);
                $aInconsistencias     = $oImportacao->persistirRegistros();
                $iCodigoArquivo       = $oImportacao->getCodigoArquivo();

                if(empty($iCodigoArquivo)) {
                    continue;
                }
            }

            unlink($sNomeArquivo);

            $oRetorno->mensagem  = "Arquivo importado";

            if(is_array($aInconsistencias) && !empty($aInconsistencias)) {
                $oRetorno->mensagem .= ".\nPorém, os seguintes PIS/PASEP não foram encontrados:\n\n". implode(", ", $aInconsistencias);
            } else {
                $oRetorno->mensagem .= " com sucesso.";
            }
            unset($_SESSION["DB_desativar_account"]);
            break;
        case 'buscaRegistrosPonto':
            if(!isset($oParametro->periodo->dataInicio) || empty($oParametro->periodo->dataInicio)) {
                throw new ParameterException('Data inicio não informada.');
            }

            if(!isset($oParametro->periodo->dataFim) || empty($oParametro->periodo->dataFim)) {
                throw new ParameterException('Data fim não informada.');
            }

            if(!isset($oParametro->matriculas) || empty($oParametro->matriculas)) {
                throw new ParameterException('Nenhuma matrícula informada.');
            }

            $oRetorno->aDados = array();
            $dataInicio = new DBDate($oParametro->periodo->dataInicio);
            $dataInicio =  new \DateTime($dataInicio->getDate());
            foreach($oParametro->matriculas as $matricula) {
                $oServidor          = ServidorRepository::getInstanciaByCodigo($matricula);

                if ($oServidor->isRescindido()) {
                    if ($oServidor->getDataRescisao()->getTimestamp() < $dataInicio->getTimestamp()) {
                        throw new \Exception("Servidor {$oServidor->getCgm()->getNome()} foi rescindo em {$oServidor->getDataRescisao()->format("d/m/Y")}.");
                    }
                }

                $oInstituicao       = InstituicaoRepository::getInstituicaoSessao();
                if ($oServidor->isRescindido()) {
                    $oParametro->periodo->dataFim = $oServidor->getDataRescisao()->format('Y-m-d');
                }
                $oPeriodoRepository = new PeriodoRepository(null, null, true);
                $aPeriodos          = $oPeriodoRepository->getPeriodosEntreDatas(
                    new DBDate($oParametro->periodo->dataInicio),
                    new DBDate($oParametro->periodo->dataFim)
                );

                $oEspelhoPonto      = new EspelhoPonto($oServidor, $aPeriodos, $oInstituicao);
                $oRetorno->aDados[] = $oEspelhoPonto->retornaDados();
            }
            break;
        case 'salvarRegistrosPonto':
            $oRetorno->label_log = null;
            $oRetorno->url       = null;

            $aDatasProcessar     = array();
            $iCodigoData         = null;
            $matriculasProcessar = array();

            foreach ($oParametro->aDados as $oDados) {
                if(empty($iCodigoData)) {
                    $iCodigoData = $oDados->codigo_data;
                }

                $oServidor = ServidorRepository::getInstanciaByCodigo($oDados->matricula);
                $aEscalas  = $oServidor->getEscalas();

                list($dia, $mes, $ano)                   = explode("/", $oDados->data);
                $sDatasProcessar                         = $ano .'-'. $mes .'-'. $dia;
                $aDatasProcessar[]                       = $sDatasProcessar;
                $matriculasProcessar[$oDados->matricula] = $oDados->matricula;
                $colecaoHistoricoRegistraPonto           = ServidorRepository::getRegistraPontoNoPeriodoPorMatricula($oDados->matricula, $oParametro->periodo->dataInicio, $oParametro->periodo->dataFim);

                if(!empty($colecaoHistoricoRegistraPonto)) {
                    $registraPonto = $colecaoHistoricoRegistraPonto->getPosicaoHistoricoPorData($sDatasProcessar);

                    if(!empty($registraPonto)) {
                        $oServidor->setDispensaLancamentoPonto($registraPonto->registraPontoEletronico());
                    }
                }

                $oData = new DBDate($sDatasProcessar);

                ProcessamentoPontoEletronico::criarMarcacoesNasDatas(
                    $oServidor->getMatricula(),
                    array((object)array('data' => $sDatasProcessar))
                );

                $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
                $oDiaTrabalhoRepository->setEscalaServidor(ProcessamentoPontoEletronico::getEscalaNaData($aEscalas, $oData));
                $oDiaTrabalhoModel      = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oData);
                $oDiaTrabalhoModel->setAfastamento(null);
                $oDiaTrabalhoRepository->persist($oDiaTrabalhoModel);

                $aAfastamentos          = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($oServidor, 'A', $oData);

                if(!empty($aAfastamentos)){
                    $oDiaTrabalhoModel->setAfastado(true);
                    $oDiaTrabalhoModel->setAfastamento($aAfastamentos[0]);
                    $oDiaTrabalhoRepository->persist($oDiaTrabalhoModel);
                }

                $oDiaTrabalhoModel = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oData);
                ProcessamentoPontoEletronico::salvarMarcacaoEVincularJustificativa($oServidor, $sDatasProcessar, $oDiaTrabalhoModel, $oDados->aMarcacoes);
            }

            $oPeriodoRepository = new PeriodoRepository(null, null, true);
            $aPeriodos          = $oPeriodoRepository->getPeriodosEntreDatas(
                new DBDate($oParametro->periodo->dataInicio),
                new DBDate($oParametro->periodo->dataFim)
            );

            Registry::get('app.container')->register('app.ponto_eletronico.debug', function() {
                $idUsuario      = db_getsession('DB_id_usuario');
                $usuarioSistema = UsuarioSistemaRepository::getPorCodigo($idUsuario);
                $nomeUsuario    = strtolower($usuarioSistema->getNome());

                $debugPonto = false;
                if(strpos($nomeUsuario, 'dbseller') !== false) {
                    $debugPonto = true;
                }

                return $debugPonto;
            });

            foreach($aPeriodos as $oPeriodo) {
                $oPeriodo = $oPeriodoRepository->getCodigoArquivoPorPeriodo($oPeriodo);
                ProcessamentoPontoEletronico::processarMatriculas($matriculasProcessar, $oPeriodo, $aDatasProcessar);
            }
            $oRetorno->mensagem  = "Salvo com sucesso.";

            if(Registry::get('app.container')->has('app.ponto_eletronico.debug') && Registry::get('app.container')->get('app.ponto_eletronico.debug')) {
                $path  = ECIDADE_PATH . 'tmp/.log/';
                if(!is_dir($path)) {
                    mkdir($path);
                }
                $path .= 'calculo_horas_ponto_eletronico_'. date('Ymd') .'.log';

                if(file_exists($path)) {
                    $oRetorno->label_log = preg_replace('/.*\/(.+\..+$)/', "$1", $path);
                    $oRetorno->url       = preg_replace('/.*\/(tmp.+\..+$)/', "$1", $path);
                }
            }
            break;
        case 'criarMarcacoesNasDatas':
            if(empty($oParametro->datas)) {
                throw new ParameterException("Não foram informadas as datas a processar.");
            }

            if(empty($oParametro->matricula)) {
                throw new ParameterException("Informe a matrícula do servidor.");
            }

            $aHorarios     = false;
            $lSobrescrever = false;

            if(isset($oParametro->aHorarios)) {
                $aHorarios = $oParametro->aHorarios;
            }

            if(isset($oParametro->bSobrescrever)) {
                $lSobrescrever = $oParametro->bSobrescrever == 't';
            }

            ProcessamentoPontoEletronico::criarMarcacoesNasDatas($oParametro->matricula, $oParametro->datas, $aHorarios, $lSobrescrever);
            break;
        case 'criarMarcacoesEmLote':
            // Para nao ficar replicando as informacoes a cada interação do loop
            $oServidorBase                = new STDClass();
            // data tem que ser uma propriedade data dentro de um array da propriedade datas
            $oData                        = new STDClass();
            $oData->data = $oParametro->datas[0];
            $oServidorBase->datas         = array($oData);
            $aErros                       = array();

            if(!empty($oParametro->selecao)) {
                // Busca os servidores pela selecao
                $aSelecao = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), $oParametro->selecao);

                // cria a propriedade matriculas
                $oParametro->matriculas = array();

                foreach ($aSelecao as $oSelecao) {
                    $oParametro->matriculas[] = $oSelecao->getMatricula();
                }
            }

            $lErroGeral                    = false;
            $oRetorno->lTemInconsistencias = false;
            $oRetorno->matriculas          = array();

            foreach ($oParametro->matriculas as $iMatricula) {
                try {
                    $oServidorBase->matricula = $iMatricula;
                    ProcessamentoPontoEletronico::criarMarcacoesNasDatas(
                        $oServidorBase->matricula,
                        $oServidorBase->datas,
                        $oParametro->horarios,
                        $oParametro->sobrescreverMarcacao == 't'
                    );
                    $oRetorno->matriculas[] = $iMatricula;
                } catch  (Exception $eErro) {
                    //Vamos fazer o tratamento das mensagens por código de erro
                    //Os codigos de 1 a 10 foram criados e retornam do ../Repository/DiaTrabalho.php
                    $oServidor = ServidorRepository::getInstanciaByCodigo($iMatricula);
                    switch ($eErro->getCode()) {
                        case 1 :
                            // Sempre adiciona ou readiciona, para evitar 1 if.
                            $aErros[1]['titulo'] = "Não há escalas para o servidor (RH > Cadastros > Efetividade > Escala de Trabalho).";
                            break;
                        case 2:
                            $aErros[2]['titulo'] = "Não há lotação configurada para o servidor (Pessoal > Cadastro > Servidores > aba Movimentações).";
                            break;
                        case 3:
                            $aErros[3]['titulo'] = "A lotação do servidor não está configurada (RH > Procedimentos > Ponto Eletrônico > Configurações > aba Lotação).";
                            break;
                        case 4:
                            $aErros[4]['titulo'] = "Servidor não possui escala (RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários).";
                            break;
                        case 5:
                            $aErros[5]['titulo'] = "Servidor não possui escala na data (RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários).";
                            break;
                        default:
                            db_fim_transacao(true);
                            $oRetorno->mensagem = $eErro->getMessage();
                            $lErroGeral         = true;
                            break 2;
                    }
                    $aErros[$eErro->getCode()]['matriculas'][] = array('matricula' => $iMatricula, 'nome' =>$oServidor->getCgm()->getNome());
                }
            }

            if(!$lErroGeral) {
                $oRetorno->mensagem = "Informações atualizadas com sucesso.";
                if(!empty($aErros)) {
                    $oRetorno->lTemInconsistencias  = true;
                    $oRetorno->mensagem             = "Informações atualizadas com sucesso. Porém, foram encontradas inconsistências";
                    $oRetorno->mensagem            .= " em alguns servidores. Deseja imprimí-las?";

                    file_put_contents('tmp/servidores_inconsistencia.json', json_encode(DBString::utf8_encode_all($aErros)));
                }
            }
            break;
        case 'criarAssentamentosJustificativas':
            $matriculasProcessar = $oParametro->matriculas;
            $tipoassentamento    = $oParametro->tipoassentamento;
            $dataInicio          = new DBDate($oParametro->dataInicio);
            $dataFim             = new DBDate($oParametro->dataFim);
            $lErroGeral          = false;
            $datasPeriodo        = DBDate::getDatasNoIntervalo($dataInicio, $dataFim);
            $oRetorno->lTemInconsistencias = false;

            if($oParametro->tipoFiltro == 1) { // Filtro de seleção
                if(!isset($oParametro->selecao) || empty($oParametro->selecao)) {
                    throw new \ParameterException("Informe corretamente uma seleção para fazer as justificativas em lote.");
                }

                $aServidoresPorSelecao = ServidorRepository::getServidoresBySelecao(
                    \DBPessoal::getAnoFolha(),
                    \DBPessoal::getMesFolha(),
                    $oParametro->selecao
                );

                $matriculasProcessar = array();
                foreach ($aServidoresPorSelecao as $servidorPorSelecao){
                    $matriculasProcessar[] = $servidorPorSelecao->getMatricula();
                }
            }

            if(empty($matriculasProcessar)) {
                throw new Exception("Não foi possível identificar os servidores a processar.");
            }

            $loteLancamento = null;
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            if (!empty($oParametro->porLote)) {
                $loteLancamento = new LoteLancamento();
                $loteLancamento->setData(new DateTime());
                $loteLancamento->setInstituicao($instituicao);
                $loteLancamento->setTipoAssentamento(TipoAssentamentoRepository::getInstanciaPorCodigo($tipoassentamento));
            }

            foreach ($matriculasProcessar as $matricula) {
                $lPersisteAssentamento = true;
                $servidor = ServidorRepository::getInstanciaByCodigo($matricula);
                $assentamento = new AssentamentoJustificativa();
                $assentamento->setMatricula($matricula);
                $assentamento->setServidor($servidor);
                $assentamento->setLoginUsuario(db_getsession("DB_id_usuario"));
                $assentamento->setTipoAssentamento($tipoassentamento);
                $assentamento->setDataConcessao($dataInicio);
                $assentamento->setDataTermino($dataFim);
                $assentamento->setDias(DBDate::calculaIntervaloEntreDatas($dataFim, $dataInicio, 'd') +1);
                $assentamento->setDataLancamento(new DBDate(date('Y-m-d')));

                $assentamento->setPeriodo1((int)(bool)$oParametro->periodoJustificativa1);
                $assentamento->setPeriodo2((int)(bool)$oParametro->periodoJustificativa2);
                $assentamento->setPeriodo3((int)(bool)$oParametro->periodoJustificativa3);

                foreach ($datasPeriodo as $dataPeriodo) {
                    $oEscalaServidor = $servidor->getEscalas($dataPeriodo);
                    if(empty($oEscalaServidor)){
                        $aErros[8]['titulo'] = "Não há escalas para o servidor no período\n(RH > Cadastros > Efetividade > Escala de Trabalho)";
                        $aErros[8]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                        $lPersisteAssentamento = false;
                    }

                    $oLotacao = LotacaoRepository::getInstanceByCodigo($servidor->getCodigoLotacao());
                    if(empty($oLotacao)){
                        $aErros[9]['titulo'] = "Não há lotação configurada para o servidor \n(Pessoal > Cadastro > Servidores > aba Movimentações)";
                        $aErros[9]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                        $lPersisteAssentamento = false;
                    } else {
                        $oConfiguracoesLotacao = ParametrosRepository::create()->getConfiguracoesLotacao($servidor->getCodigoLotacao());
                        if(empty($oConfiguracoesLotacao)){
                            $aErros[10]['titulo'] = "A lotação do servidor não está configurada \n(RH > Procedimentos > Ponto Eletrônico > Configurações > aba Lotação).";
                            $aErros[10]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                            $lPersisteAssentamento = false;
                        }
                    }

                    if($assentamento->validarExistenciaJustificativaNoPeriodo($dataPeriodo)) {
                        $inconsistencias[$matricula]['justificativas'][] = $dataPeriodo->getDate(DBDate::DATA_PTBR);
                    }

                    if($servidor->isAfastadoNoRH($dataPeriodo)) {
                        $inconsistencias[$matricula]['afastamentos'][] = $dataPeriodo->getDate(DBDate::DATA_PTBR);
                    }
                }

                if(!empty($inconsistencias[$matricula]['justificativas'])) {
                    $aErros[6]['titulo'] = "Existe justificativa nesta(s) data(s): ". implode(', ', $inconsistencias[$matricula]['justificativas']). "\nRH > Procedimentos > Manutenção de Assentamentos > Assentamentos de Efetividade.";
                    $aErros[6]['matriculas'][] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                }

                if(!empty($inconsistencias[$matricula]['afastamentos'])) {
                    $aErros[7]['titulo'] = "Existe afastamento do RH nesta(s) data(s): ". implode(', ', $inconsistencias[$matricula]['afastamentos']). "\nRH > Procedimentos > Manutenção de Assentamentos > Assentamentos de Efetividade.";
                    $aErros[7]['matriculas'][] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                }

                if($lPersisteAssentamento){
                    \AssentamentoRepository::persist($assentamento);
                    if (!empty($loteLancamento)) {
                        $loteLancamento->addAssentamento($assentamento);
                    }
                    $oRetorno->mensagem = "Informações atualizadas com sucesso. ";
                }
            }

            if (!empty($loteLancamento) && count($loteLancamento->getAssentamentos()) > 0) {
                $response = LoteLancamentoRepository::save($loteLancamento);
                if (!$response) {
                    throw new Exception('Não foi possível salvar o lote de assentamentos.');
                }
            }

            if(!empty($aErros)) {
                $oRetorno->lTemInconsistencias  = true;
                $oRetorno->mensagem .= "Foram encontradas inconsistências";
                $oRetorno->mensagem .= " em alguns servidores. Deseja imprimí-las?";

                file_put_contents('tmp/servidores_inconsistencia.json', json_encode(DBString::utf8_encode_all($aErros)));
            }
            break;
        case 'criarAssentamentosAutorizacaoHoraExtra':
            $matriculasProcessar = $oParametro->matriculas;
            $tipoassentamento    = $oParametro->tipoassentamento;
            $horasAutorizadas    = $oParametro->horasAutorizadas;
            $dataInicio          = new DBDate($oParametro->dataInicio);
            $dataFim             = new DBDate($oParametro->dataFim);
            $lErroGeral          = false;
            $aErros              = array();
            $datasPeriodo        = DBDate::getDatasNoIntervalo($dataInicio, $dataFim);
            $oRetorno->lTemInconsistencias = false;

            if($oParametro->tipoFiltro == 1) { // Filtro de seleção
                if(!isset($oParametro->selecao) || empty($oParametro->selecao)) {
                    throw new \ParameterException("Informe corretamente uma seleção para fazer as autorizações de horas extras em lote.");
                }

                $aServidoresPorSelecao = ServidorRepository::getServidoresBySelecao(
                    \DBPessoal::getAnoFolha(),
                    \DBPessoal::getMesFolha(),
                    $oParametro->selecao
                );

                $matriculasProcessar = array();
                foreach ($aServidoresPorSelecao as $servidorPorSelecao){
                    $matriculasProcessar[] = $servidorPorSelecao->getMatricula();
                }
            }

            $loteLancamento = null;
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            if (!empty($oParametro->porLote)) {
                $loteLancamento = new LoteLancamento();
                $loteLancamento->setData(new DateTime());
                $loteLancamento->setInstituicao($instituicao);
                $loteLancamento->setTipoAssentamento(TipoAssentamentoRepository::getInstanciaPorCodigo($tipoassentamento));
            }

            foreach ($matriculasProcessar as $matricula) {
                $lPersisteAssentamento = true;
                $servidor = ServidorRepository::getInstanciaByCodigo($matricula);
                $assentamento = new Assentamento();
                $assentamento->setMatricula($matricula);
                $assentamento->setServidor($servidor);
                $assentamento->setTipoAssentamento($tipoassentamento);
                $assentamento->setDataConcessao($dataInicio);
                $assentamento->setDataTermino($dataFim);
                $assentamento->setDias(DBDate::calculaIntervaloEntreDatas($dataFim, $dataInicio, 'd') +1);
                $assentamento->setDataLancamento(new DBDate(date('Y-m-d')));
                $assentamento->setHora($horasAutorizadas);

                foreach ($datasPeriodo as $dataPeriodo) {
                    $oEscalaServidor = $servidor->getEscalas($dataPeriodo);
                    if(empty($oEscalaServidor)){
                        $aErros[8]['titulo'] = "Não há escalas para o servidor no período\n(RH > Cadastros > Efetividade > Escala de Trabalho)";
                        $aErros[8]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                        $lPersisteAssentamento = false;
                    }

                    $oLotacao = LotacaoRepository::getInstanceByCodigo($servidor->getCodigoLotacao());
                    if(empty($oLotacao)){
                        $aErros[9]['titulo'] = "Não há lotação configurada para o servidor \n(Pessoal > Cadastro > Servidores > aba Movimentações)";
                        $aErros[9]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                        $lPersisteAssentamento = false;
                    } else {
                        $oConfiguracoesLotacao = ParametrosRepository::create()->getConfiguracoesLotacao($servidor->getCodigoLotacao());
                        if(empty($oConfiguracoesLotacao)){
                            $aErros[10]['titulo'] = "A lotação do servidor não está configurada \n(RH > Procedimentos > Ponto Eletrônico > Configurações > aba Lotação).";
                            $aErros[10]['matriculas'][$matricula] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                            $lPersisteAssentamento = false;
                        }
                    }

                    if(AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($servidor, 'S', $dataPeriodo, Assentamento::NATUREZA_AUTORIZA_HORA_EXTRA)) {
                        $inconsistencias[$matricula]['hora_extra_autorizada'][] = $dataPeriodo->getDate(DBDate::DATA_PTBR);
                    }

                    if($servidor->isAfastadoNoRH($dataPeriodo)) {
                        $inconsistencias[$matricula]['afastamentos'][] = $dataPeriodo->getDate(DBDate::DATA_PTBR);
                    }
                }

                if(!empty($inconsistencias[$matricula]['hora_extra_autorizada'])) {
                    $aErros[6]['titulo'] = "Existe autorização de hora extra nesta(s) data(s): ". implode(', ', $inconsistencias[$matricula]['hora_extra_autorizada']). "\nRH > Procedimentos > Manutenção de Assentamentos > Assentamentos de Efetividade.";
                    $aErros[6]['matriculas'][] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                }

                if(!empty($inconsistencias[$matricula]['afastamentos'])) {
                    $aErros[7]['titulo'] = "Existe afastamento do RH nesta(s) data(s): ". implode(', ', $inconsistencias[$matricula]['afastamentos']). "\nRH > Procedimentos > Manutenção de Assentamentos > Assentamentos de Efetividade.";
                    $aErros[7]['matriculas'][] = array('matricula' => $servidor->getMatricula(), 'nome' =>$servidor->getCgm()->getNome());
                }

                if($lPersisteAssentamento){
                    \AssentamentoRepository::persist($assentamento);
                    if (!empty($loteLancamento)) {
                        $loteLancamento->addAssentamento($assentamento);
                    }
                    $oRetorno->mensagem = "Informações atualizadas com sucesso. ";
                }
            }

            if (!empty($loteLancamento) && count($loteLancamento->getAssentamentos()) > 0) {
                $response = LoteLancamentoRepository::save($loteLancamento);
                if (!$response) {
                    throw new Exception('Não foi possível salvar o lote de assentamentos.');
                }
            }

            if(!empty($aErros)) {
                $oRetorno->lTemInconsistencias  = true;
                $oRetorno->mensagem .= "Foram encontradas inconsistências";
                $oRetorno->mensagem .= " em alguns servidores. Deseja imprimí-las?";

                file_put_contents('tmp/servidores_inconsistencia.json', json_encode(DBString::utf8_encode_all($aErros)));
            }
            break;
        case 'consultarMarcacoes':
            if (empty($oParametro->matricula)) {
                throw new ParameterException('Informe a matrícula para verificar as horas justificadas.');
            }

            if (empty($oParametro->data)) {
                throw new ParameterException('Informe a data para verificar as horas justificadas.');
            }

            if (empty($oParametro->horaInicio)) {
                throw new ParameterException('Informe a hora de início para verificar as horas justificadas.');
            }

            if (empty($oParametro->horaFim)) {
                throw new ParameterException('Informe a hora final para verificar as horas justificadas.');
            }

            list($diferenca, $mensagem) = AssentamentoAbonoFalta::getHorasDeAbono(
                ServidorRepository::getInstanciaByCodigo($oParametro->matricula),
                new DBDate($oParametro->data),
                $oParametro->horaInicio,
                $oParametro->horaFim
            );

            $oRetorno->mensagem = $mensagem;
            $oRetorno->diferenca = $diferenca;

            break;
        case 'criarAssentamentosAbonoFaltaLote':
            $horaInicio = $oParametro->horaInicio;
            $horaFim    = $oParametro->horaFim;

            if(empty($oParametro->dataInicio)) {
                throw new ParameterException('Data inicio não informada.');
            }

            if(empty($oParametro->dataFim)) {
                $oParametro->dataFim = $oParametro->dataInicio;
            }

            $dataInicio = $oParametro->dataInicio;
            $dataFim    = $oParametro->dataFim;

            if(!empty($oParametro->selecao)) {
                // Busca os servidores pela selecao
                $aSelecao = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), $oParametro->selecao);
                // cria a propriedade matriculas
                $oParametro->matriculas = array();
                foreach ($aSelecao as $oSelecao) {
                    $oParametro->matriculas[] = $oSelecao->getMatricula();
                }
            }
            $matriculasInconsistentes = array();

            $datasLancar = DBDate::getDatasNoIntervalo(new DBDate($dataInicio), new DBDate($dataFim));

            $loteLancamento = null;
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            if (!empty($oParametro->porLote)) {
                $loteLancamento = new LoteLancamento();
                $loteLancamento->setData(new DateTime());
                $loteLancamento->setInstituicao($instituicao);
                $loteLancamento->setTipoAssentamento(TipoAssentamentoRepository::getInstanciaPorCodigo($oParametro->tipoAssentamento));
            }

            foreach ($oParametro->matriculas as $matricula) {
                foreach ($datasLancar as $data) {
                    $oAssentamento = new AssentamentoAbonoFalta();
                    $oAssentamento->setMatricula($matricula);
                    $oAssentamento->setTipoAssentamento($oParametro->tipoAssentamento);
                    $oAssentamento->setDataConcessao($data);
                    $oAssentamento->setDataTermino($data);
                    $oAssentamento->setDias(1);
                    $oAssentamento->setLoginUsuario(db_getsession("DB_id_usuario"));
                    $oAssentamento->setDataLancamento(date("Y-m-d",db_getsession("DB_datausu")));
                    $oAssentamento->setConvertido("false");
                    $oAssentamento->setHoraInicio($horaInicio);
                    $oAssentamento->setHoraFim($horaFim);

                    try {
                        $oAssentamento->validarExistenciaAssentamento($data, $data, $matricula);

                    } catch (Exception $e) {
                        $matriculasInconsistentes[$matricula] = $e->getMessage();
                        continue;
                    }

                    $oAssentamento->retornarHorasAbonar();
                    $oAssentamento->persist();

                    if (!empty($loteLancamento)) {
                        $loteLancamento->addAssentamento($oAssentamento);
                    }
                }
            }

            $oRetorno->mensagem = "Lançamento concluído com sucesso.";

            if (!empty($loteLancamento) && count($loteLancamento->getAssentamentos()) > 0) {
                $response = LoteLancamentoRepository::save($loteLancamento);
                if (!$response) {
                    throw new Exception('Não foi possível salvar o lote de assentamentos.');
                }
            }

            if(!empty($matriculasInconsistentes)) {
                $oRetorno->mensagem  = "Processamento concluído. Porém, não foi possível lançar assentamentos para ";
                $oRetorno->mensagem .= " a(s) matrícula(s): " . implode(', ', array_keys($matriculasInconsistentes));
            }
            break;
        case 'buscaRegistrosPontoCache':
            if(!isset($oParametro->periodo->dataInicio) || empty($oParametro->periodo->dataInicio)) {
                throw new ParameterException('Data inicio não informada.');
            }

            if(!isset($oParametro->periodo->dataFim) || empty($oParametro->periodo->dataFim)) {
                throw new ParameterException('Data fim não informada.');
            }

            if(!isset($oParametro->matriculas) || empty($oParametro->matriculas)) {
                throw new ParameterException('Nenhuma matrícula informada.');
            }

            $dataInicio = new DBDate($oParametro->periodo->dataInicio);
            $dataFim = new DBDate($oParametro->periodo->dataFim);

            $matriculasCacheInvalido = EspelhoPontoCache::init()->getEspelhoPontoCacheValido($oParametro->matriculas, $dataInicio, $dataFim, false);

            if(!empty($matriculasCacheInvalido)) {
                ProcessamentoPontoEletronico::ajustarMatriculasParaCacheEspelhoPonto($oParametro->matriculas, $dataInicio, $dataFim);
            }

            $espelhosPontoServidores = EspelhoPontoCache::init()->getEspelhoPontoCacheValido($oParametro->matriculas, $dataInicio, $dataFim);

            $oRetorno->servidores = array();
            foreach ($espelhosPontoServidores as $matricula => $espelhoPontoData) {
                $servidor = new \stdClass();
                $servidor->matricula = $matricula;
                $servidor->aDados = array();

                foreach ($espelhoPontoData as $espelhoPonto) {
                    $servidor->aDados[] = $espelhoPonto;
                }

                $oRetorno->servidores[] = $servidor;
            }
            break;

        case 'buscaJornadaServidor':
            if(empty($oParametro->periodo->dataFim)) {
                $oParametro->periodo->dataFim = $oParametro->periodo->dataInicio;
            }

            $servidor = ServidorRepository::getInstanciaByCodigo($oParametro->matricula);
            $periodo  = new Periodo();
            $periodo->setDataInicio(new DBDate($oParametro->periodo->dataInicio));
            $periodo->setDataFim(new DBDate($oParametro->periodo->dataFim));

            $jornadas = JornadaRepository::getJornadasNoIntervalo($servidor, $periodo);
            $oRetorno->jornadas = array();

            if(!empty($jornadas)) {
                do {
                    $jornadaNaData = current($jornadas);
                    $jornada       = $jornadaNaData['jornada'];

                    $oRetorno->jornadas[] = (object)array(
                        'codigo'    => $jornada->getCodigo(),
                        'descricao' => $jornada->getDescricao(),
                        'data'      => $jornadaNaData['data']->getDate(DBDate::DATA_PTBR),
                        'horas'     => array_values($jornada->toArray())
                    );
                } while (next($jornadas));
            }

            break;
        case 'buscaLoteLancamento':
            $usuario = UsuarioSistemaRepository::getUsuarioSessao();
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            $departamento = DBDepartamentoRepository::getDepartamentoSessao();
            $codLotacoes = array();

            $loteLancamentoRepository = new LoteLancamentoRepository();

            if (!empty($oParametro->assentamentoFuncional)) {
                $lotacoes = LotacaoRepository::getLotacoesByUsuario($usuario);
                foreach ($lotacoes as $lotacao) {
                    $codLotacoes[] = $lotacao->getCodigoLotacao();
                }
                $codLotacoes = implode(", ", $codLotacoes);
                $loteLancamentoRepository->scopeInstituicao($instituicao);
                $lotes = LoteLancamentoRepository::getSequenciaisLotePorLotacao($codLotacoes);

                if (sizeof($lotes) == 0) {
                    throw new BusinessException("Nenhum lote encontrado.");
                }

                $sequenciais = implode(',', $lotes);
                $loteLancamentoRepository->scopeSequenciais($sequenciais);
            } else {
                $lotes = LoteLancamentoRepository::getSequenciaisEfetividade();

                if(sizeof($lotes) == 0) {
                    throw new BusinessException("Nenhum lote encontrado.");
                }
                $sequenciais = implode(',', $lotes);

            $loteLancamentoRepository->scopeInstituicao($instituicao)
                  ->scopeDepartamento($departamento)->scopeSequenciais($sequenciais);
            }
            $filtrosData = array();
            $dataInicial = $dataFinal = null;

            if (!empty($oParametro->dataInicial)) {
                $dataInicial = new DateTime($oParametro->dataInicial);
            }

            if (!empty($oParametro->dataFinal)) {
                $dataFinal = new DateTime($oParametro->dataFinal);
            }

            if (!empty($oParametro->tipoAssentamento)) {
                $tipoassentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oParametro->tipoAssentamento);
                $loteLancamentoRepository->scopeTipo($tipoassentamento);
            }

            if ($dataInicial && $dataFinal) {
                $loteLancamentoRepository->scopeData("'{$dataInicial->format('Y-m-d')}' AND '{$dataFinal->format('Y-m-d')}'", 'BETWEEN');
            } elseif ($dataInicial) {
                $loteLancamentoRepository->scopeData("'{$dataInicial->format('Y-m-d')}'", ">=");
            } elseif ($dataFinal) {
                $loteLancamentoRepository->scopeData("'{$dataInicial->format('Y-m-d')}'", "<=");
            }

            $lotes = $loteLancamentoRepository->get();
            $oRetorno->lotes = array();
            foreach ($lotes as $lote) {
                $oRetorno->lotes[] = $lote->toArray();
            }

            break;
        case 'excluiLoteLancamento':
            $assentamentoFuncional = false;
            if (empty($oParametro->codigo)) {
                throw new Exception('É necessário selecionar o lote que deseja excluir.');
            }

            if (!empty($oParametro->assentamentoFuncional)) {
                $assentamentoFuncional = true;
            }
            $dataInicial = $dataFinal = $tipoAssentamento = null;

            if (!empty($oParametro->dataInicial)) {
                $dataInicial = new DateTime($oParametro->dataInicial);
            }

            if (!empty($oParametro->dataFinal)) {
                $dataFinal = new DateTime($oParametro->dataFinal);
            }

            if (!empty($oParametro->tipoAssentamento)) {
                $tipoAssentamento = $oParametro->tipoAssentamento;
            }

            $loteLancamento = LoteLancamentoRepository::find($oParametro->codigo);
            $resultado = LoteLancamentoRepository::delete($loteLancamento);

            $oRetorno->excluiuTodosAssentamentos = true;
            $oRetorno->loteLancamento = $resultado->loteLancamento->toArray();

            if (count($resultado->erros) > 0) {
                $oRetorno->excluiuTodosAssentamentos = false;
                $oRetorno->mensagem = "Não foi possível excluir alguns assentamentos do lote.\nDeseja visualizar o relatório de inconsistências?";
                $erros = array();

                $pdf = new PDFTable();
                $pdf->setPercentWidth(true);
                $pdf->setHeaders(array('Matrícula', 'Data', 'Mensagem'));
                $pdf->setColumnsAlign(array(PDFDocument::ALIGN_CENTER, PDFDocument::ALIGN_CENTER, PDFDocument::ALIGN_LEFT));
                $pdf->setColumnsWidth(array('20', '20', '60'));
                $pdf->setMulticellColumns(array(2));

                foreach ($resultado->erros as $erro) {
                    $pdf->addLineInformation(
                        array(
                            $erro->assentamento->getMatricula(),
                            $resultado->loteLancamento->getData()->format('d/m/Y'),
                            $erro->mensagem
                        )
                    );
                }

                $pdfDocument = new PDFDocument();
                $pdfDocument->addHeaderDescription('Inconsistências de exclusão de lote');
                $pdfDocument->addHeaderDescription('');
                $pdfDocument->addHeaderDescription('Filtros utilizados:');
                $pdfDocument->addHeaderDescription('Data inicial: '. ($dataInicial ? $dataInicial->format('d/m/Y'): 'N/A'));
                $pdfDocument->addHeaderDescription('Data final: '. ($dataFinal ? $dataFinal->format('d/m/Y'): 'N/A'));
                $pdfDocument->addHeaderDescription('Tipos de assentamento: '. ($tipoAssentamento ? $tipoAssentamento : 'Todos'));
                $pdfDocument->SetFillColor(235);
                $pdfDocument->setFontSize(9);
                $pdfDocument->open();

                $pdf->printOut($pdfDocument, false);
                $oRetorno->arquivo = $pdfDocument->savePDF('lote_assentamentos_inconsistencias');
            }

            break;
        default:
            return;
    }
    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);

    $oRetorno->erro     = true;
    $oRetorno->mensagem = $eErro->getMessage();
}

unset($_SESSION["DB_desativar_account"]);
date_default_timezone_set($timeZone);
echo JSON::create()->stringify($oRetorno);
