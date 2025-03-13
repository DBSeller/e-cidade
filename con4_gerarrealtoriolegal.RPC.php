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

use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Servico\DuplicarRelatorio;
use ECidade\Configuracao\RelatorioLegal\Servico\Exportar;
use ECidade\Configuracao\RelatorioLegal\Servico\ExportarJson;
use ECidade\Configuracao\RelatorioLegal\Servico\ExportarSql;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

db_inicio_transacao();
$resposta = new stdClass();
$resposta->mensagem = '';
$resposta->erro = false;
try {
    switch ($parametros->acao) {
        case 'duplicarRelatorio':
            $relatorioAntigo = RelatorioRegistry::get($parametros->relatorio);
            $duplicar = new DuplicarRelatorio($relatorioAntigo, $parametros->nomeNovo);
            $duplicar->duplicar();

            $resposta->mensagem = "Relatório duplicado com sucesso.\nCódigo: {$duplicar->getCodigoNovoRelatorio()} - {$parametros->nomeNovo}";
            break;
        case 'exportarRelatorio':
            $relatorio = RelatorioRegistry::get($parametros->codigoRelarotio);
            if ($parametros->formatoExportacao === Exportar::FORMATO_JSON) {
                $exportacao = new ExportarJson();
            } else {
                $exportacao = new ExportarSql();
                $exportacao->setCodigoRelatorio($parametros->codigoRelarotio);
            }

            $exportacao->setRelatorio($relatorio)
                ->exportarRelatorio($parametros->exportarRelatorio === "true")
                ->exportarPeriodos($parametros->exportarPeriodos === "true")
                ->exportarColunas($parametros->exportarColunas === "true")
                ->exportarColunas($parametros->exportarColunas === "true")
                ->formato($parametros->formatoExportacao)
                ->exportar();

            $resposta->filePath = $exportacao->getCaminhoArquivo();
            break;
    }
} catch (Exception $exception) {
    $resposta->erro = true;
    $resposta->mensagem = $exception->getMessage();
}

db_fim_transacao($resposta->erro);

echo JSON::create()->stringify($resposta);
