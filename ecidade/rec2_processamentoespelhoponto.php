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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification("fpdf151/pdf.php"));

use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\Repository\EspelhoPontoCache;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;
use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Logger;

Registry::get('app.eventManager')->register('app.error', function ($event) {

    $params = $event->getParams();
    $entity = $params[0];
    $config = Registry::get('app.config');

    $mask = $config->get('app.error.log.mask');
    $traceMask = $config->get('app.error.log.mask.trace');
    $traces = '';

    $trace = $entity->getTrace();

    if ($trace) {

        foreach ($trace->getSanitizedData() as $index => $trace) {

            $args = array();
            if (!empty($trace['args'])) {
                foreach ($trace['args'] as $arg) {
                    if (!is_scalar($arg)) $arg = print_r($arg, true);
                    $args[] = $arg;
                }
            }

            $args = implode(', ', $args);

            $trace = strtr($traceMask, array(
              '{index}' => $index + 1,
              '{file}' => isset($trace['file']) ? $trace['file'] : '',
              '{line}' => isset($trace['line']) ? $trace['line'] : '',
              '{class}' => isset($trace['class']) ? $trace['class'] : '',
              '{function}' => isset($trace['function']) ? $trace['function'] : '',
              '{type}' => isset($trace['type']) ? $trace['type'] : '',
              '{args}' => $args,
            ));
            $traces .= $trace;
        }
    }

    $output = strtr($mask, array(
      '{type}' => $entity->getTypeAsString(),
      '{message}' => $entity->getMessage(),
      '{file}' => $entity->getFile(),
      '{line}' => $entity->getLine(),
      '{trace}' => $traces,
    ));

    $path   = $config->get('app.error.log.path.ponto', 'extension/log/error_ponto_eletronico.log');
    $logger = new Logger($path, Logger::ERROR);
    $logger->error($output);
});

?>
<html>
<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/ProgressBar.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body >
<div class="container">
    <fieldset style="width: 700px; padding: 2px">
      <progress id="barra-progresso" value="0" style="width: 100%; height: 25px;">Processando</progress>
    </fieldset>
    <fieldset style="width: 700px; padding: 1px 2px">
      <div id="log-processamento"></div>
    </fieldset>
</div>
</body>

<script type="text/javascript">
    var bar = $('barra-progresso');
    var logs = $('log-processamento');
    var progress = new ProgressBar(bar, logs);
</script>

</html>
<?php

$oParametros = \db_utils::postMemory(array_merge($_GET, $_POST));
$aMatriculas = explode(',', $oParametros->aMatriculas);
$aLocalTrabalho = explode(',', $oParametros->aLocalTrabalho);
$iCodigoSelecao = !empty($oParametros->iCodigoSelecao) ? $oParametros->iCodigoSelecao : null;
$lMostraObservacoes = $oParametros->lMostraObservacoes == 'S';
$lEmiteTodosAfastamentos = !empty($oParametros->iEmiteTodosAfastamentos) && $oParametros->iEmiteTodosAfastamentos == 1;
$timeZone = date_default_timezone_get();
date_default_timezone_set('UTC');


try {
    if (empty($oParametros->periodoInicio)) {
        throw new ParameterException("Informe a data início.");
    }

    if (empty($oParametros->periodoFim)) {
        throw new ParameterException("Informe a data fim.");
    }

    if (empty($aLocalTrabalho)) {
        if (empty($iCodigoSelecao)) {
            if (empty($iCodigoSelecao)) {
                throw new ParameterException("Informe uma seleção, uma ou mais matrículas ou um ou mais locais de trabalho para emissão do espelho ponto.");
            }
        }
    }

    if (!empty($iCodigoSelecao)) {
        $aMatriculas = array_keys(\ServidorRepository::getServidoresBySelecao(
          DBPessoal::getAnoFolha(),
          DBPessoal::getMesFolha(),
          $iCodigoSelecao
        ));

        if (empty($aMatriculas)) {
            throw new BusinessException("Não há servidores para esta seleção.");
        }
    }

    if (!empty($aLocalTrabalho) && !empty($aLocalTrabalho[0])) {
        if (!isset($aMatriculas) || empty($aMatriculas) || !is_array($aMatriculas)) {
            $aMatriculas = array();
        }

        foreach ($aLocalTrabalho as $codigoLocalTrabalho) {
            $aMatriculas = array_merge(
              $aMatriculas,
              array_keys(
                \ServidorRepository::getServidoresByLocalTrabalho(
                  DBPessoal::getAnoFolha(),
                  DBPessoal::getMesFolha(),
                  $codigoLocalTrabalho
                )
              )
            );
        }

        if (empty($aMatriculas)) {
            throw new BusinessException("Não há servidores para o local de trabalho informado.");
        }
    }

    if (empty($aMatriculas)) {
        throw new BusinessException('Nenhum servidor a ser impresso.');
    }

    $dadosRelatorio = new stdClass();
    $dadosRelatorio->mostraObservacoes      = $lMostraObservacoes;
    $dadosRelatorio->emiteTodosAfastamentos = $lEmiteTodosAfastamentos;
    $dadosRelatorio->dataInicio             = implode('/', array_reverse(explode('-', $oParametros->periodoInicio)));
    $dadosRelatorio->dataFim                = implode('/', array_reverse(explode('-', $oParametros->periodoFim)));
    
    $id          = uniqid();
    $fileName    = "tmp/dados_espelho_ponto_{$id}.txt";
    file_put_contents($fileName, serialize($dadosRelatorio) . PHP_EOL);

    $progressBar = new ProgressBar('progress');
    $progressBar->flush();

    $dataInicial = new \DBDate($oParametros->periodoInicio);
    $dataFinal   = new \DBDate($oParametros->periodoFim);

    $qntMatriculas = count($aMatriculas);
    $progressBar->updateMaxProgress($qntMatriculas);
    $progressBar->setMessageLog("Processando o ponto dos servidores...");
    $progresso = 0;

    $matriculasCacheInvalido = EspelhoPontoCache::init()->getEspelhoPontoCacheValido($aMatriculas, $dataInicial, $dataFinal, false);

    if(!empty($matriculasCacheInvalido)) {
        $matriculas = array_keys($matriculasCacheInvalido);

        $retornoAjuste = ProcessamentoPontoEletronico::ajustarMatriculasParaCacheEspelhoPonto($matriculas, $dataInicial, $dataFinal);
        $progresso = count($retornoAjuste->matriculasMarcacoesAjustadas);
        $progressBar->updateMaxProgress($qntMatriculas + $progresso);
        $progressBar->updatePercentual($progresso);        
    }

    $aServidores   = EspelhoPontoCache::init()->getEspelhoPontoCacheValido($aMatriculas, $dataInicial, $dataFinal, true);
    $servidorAdicionado = false;

    foreach ($aServidores as $matricula) {
            
        $datasServidor = $matricula;
        $progresso += 1;
        $progressBar->updatePercentual($progresso);

        if(empty($datasServidor)) {
            continue;
        }
        
        $dataServidor  = (object)current($datasServidor);
        $dadosServidor = array(
            'dados'                                   => $dataServidor->dados,
            'datas'                                   => array(),
            'aHorasJornada'                           => array(),
            'observacoes'                             => array(),
        );

        foreach ($datasServidor as $dataServidor) {

            $dataServidor                                                      = (object)$dataServidor;
            $dadosServidor['datas'][]                                          = $dataServidor->datas[0];
            $dadosServidor['nTotalHorasNormais'][]                             = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasNormais);
            $dadosServidor['nTotalHorasFaltas'][]                              = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasFaltas);
            $dadosServidor['nTotalHorasFaltasNoturna'][]                       = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasFaltasNoturna);
            $dadosServidor['nTotalHorasExt50diurnas'][]                        = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt50diurnas);
            $dadosServidor['nTotalHorasExt50noturnas'][]                       = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt50noturnas);
            $dadosServidor['nTotalHorasExt75diurnas'][]                        = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt75diurnas);
            $dadosServidor['nTotalHorasExt75noturnas'][]                       = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt75noturnas);
            $dadosServidor['nTotalHorasExt100diurnas'][]                       = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt100diurnas);
            $dadosServidor['nTotalHorasExt100noturnas'][]                      = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt100noturnas);
            $dadosServidor['nTotalHorasExt50NaoAutorizadasdiurnas'][]          = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt50NaoAutorizadasdiurnas);
            $dadosServidor['nTotalHorasExt50NaoAutorizadasnoturnas'][]         = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt50NaoAutorizadasnoturnas);
            $dadosServidor['nTotalHorasExt75NaoAutorizadasdiurnas'][]          = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt75NaoAutorizadasdiurnas);
            $dadosServidor['nTotalHorasExt75NaoAutorizadasnoturnas'][]         = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt75NaoAutorizadasnoturnas);
            $dadosServidor['nTotalHorasExt100NaoAutorizadasdiurnas'][]         = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt100NaoAutorizadasdiurnas);
            $dadosServidor['nTotalHorasExt100NaoAutorizadasnoturnas'][]        = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExt100NaoAutorizadasnoturnas);
            $dadosServidor['nTotalHorasExtNaoAutorizadasnoturnas'][]           = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExtNaoAutorizadasnoturnas);
            $dadosServidor['nTotalHorasExtNaoAutorizadasdiurnas'][]            = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasExtNaoAutorizadasdiurnas);
            $dadosServidor['nTotalHorasAdicional'][]                           = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasAdicional);
            $dadosServidor['nTotalHorasAtraso'][]                              = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasAtraso);
            $dadosServidor['nTotalHorasAtrasoDesmembrado'][]                   = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasAtrasoDesmembrado);
            $dadosServidor['nTotalHorasAtrasoNoturno'][]                       = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasAtrasoNoturno);
            $dadosServidor['nTotalHorasSaidaAtencipada'][]                     = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasSaidaAtencipada);
            $dadosServidor['nTotalHorasSaidaAtencipadaNoturna'][]              = EspelhoPonto::somarTotalizador($dataServidor->nTotalHorasSaidaAtencipadaNoturna);
            $dadosServidor['aHorasJornada'][key($dataServidor->aHorasJornada)] = current($dataServidor->aHorasJornada);
            $dadosServidor['observacoes'][]                                    = current($dataServidor->observacoes);
        }

            $servidorAdicionado = true;
            file_put_contents($fileName, serialize($dadosServidor) . PHP_EOL, FILE_APPEND);
        }

    $matriculasComErro = array(
        'matriculasComErro' => $retornoAjuste->matriculasComErro
    );
    file_put_contents($fileName, serialize($matriculasComErro) . PHP_EOL, FILE_APPEND);

    if(!$servidorAdicionado && empty($retornoAjuste->matriculasComErro)) {
        throw new BusinessException('Nenhum servidor a ser impresso.');
    }

    db_redireciona("rec2_espelhoponto001.php?imprime=true&id={$id}");
} catch (Exception $e) {
    db_msgbox($e->getMessage());
    db_redireciona('rec2_espelhoponto001.php');
}

date_default_timezone_set($timeZone);

function keepSessionAlive()
{
    session_write_close();
    @session_start();
}
