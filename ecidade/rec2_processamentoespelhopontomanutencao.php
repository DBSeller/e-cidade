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
require_once(modification("fpdf151/pdf.php"));

use \ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\Repository\EspelhoPontoCache;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;

$oParametros = \db_utils::postMemory(array_merge($_GET, $_POST));
$aMatriculas = explode(',', $oParametros->aMatriculas);
$lMostraObservacoes = $oParametros->lMostraObservacoes == 'S';
$lEmiteTodosAfastamentos = !empty($oParametros->iEmiteTodosAfastamentos) && $oParametros->iEmiteTodosAfastamentos == 1;
$timeZone = date_default_timezone_get();
date_default_timezone_set('UTC');

try {
    
    $dadosRelatorio = new stdClass();
    $dadosRelatorio->mostraObservacoes = $lMostraObservacoes;
    $dadosRelatorio->emiteTodosAfastamentos = $lEmiteTodosAfastamentos;
    $dadosRelatorio->dataInicio = implode('/', array_reverse(explode('-', $oParametros->periodoInicio)));
    $dadosRelatorio->dataFim = implode('/', array_reverse(explode('-', $oParametros->periodoFim)));

    $id          = uniqid();
    $fileName    = "tmp/dados_espelho_ponto_{$id}.txt";
    file_put_contents($fileName, serialize($dadosRelatorio) . PHP_EOL);

    $dataInicial = new \DBDate($oParametros->periodoInicio);
    $dataFinal = new \DBDate($oParametros->periodoFim);

    $matriculasCacheIncalido = EspelhoPontoCache::init()->getEspelhoPontoCacheValido($aMatriculas, $dataInicial, $dataFinal, false);

    if(!empty($matriculasCacheInvalido)) {
        $matriculas = array_keys($matriculasCacheInvalido);
        $retornoAjuste = ProcessamentoPontoEletronico::ajustarMatriculasParaCacheEspelhoPonto($matriculas, $dataInicial, $dataFinal);
    }

    $aServidores = EspelhoPontoCache::init()->getEspelhoPontoCacheValido($aMatriculas, $dataInicial, $dataFinal, true);
    $servidorAdicionado = false;

    foreach ($aServidores as $matricula) {

        $datasServidor = $matricula;
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
      'matriculasComErro' => array()
    );

    if(!empty($retornoAjuste)) {
        $matriculasComErro = array(
          'matriculasComErro' => $retornoAjuste->matriculasComErro
        );
    }

    file_put_contents($fileName, serialize($matriculasComErro) . PHP_EOL, FILE_APPEND);

    if(!$servidorAdicionado && empty($retornoAjuste->matriculasComErro)) {
        throw new BusinessException('Nenhum servidor a ser impresso.');
    }

    db_redireciona("rec2_espelhoponto002.php?id={$id}");
} catch (Exception $e) {
    db_msgbox($e->getMessage());
}

date_default_timezone_set($timeZone);
