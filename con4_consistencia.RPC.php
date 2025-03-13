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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$parametros            = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$retorno               = new stdClass();
$retorno->mensagem     = '';
$retorno->erro         = false;

use ECidade\Configuracao\Consistencia\Repository\Consistencia as ConsistenciaRepository;

try {
    db_inicio_transacao();

    switch ($parametros->exec) {
        case "getArquivosConsistenciaPorTipo":
            $consistencia = ConsistenciaRepository::getInstance();

            $retorno->consistencia = $consistencia->getArquivosConsistenciaPorTipo($parametros->tipo);
            break;
        case "executarConsistencia":
            $consistencia = ConsistenciaRepository::getInstance();
            $retorno->registrosConsistencia = $consistencia->executarConsistencia(
                $parametros->id,
                $parametros->filtros
            );
            if (empty($retorno->registrosConsistencia)) {
                throw new DBException("Não foram encontrados registros inconsistentes para correção.");
            }
            $retorno->possuiCorrecao = $consistencia->possuiCorrecao($parametros->id);
            $retorno->ajuda = $consistencia->getAjuda($parametros->id);
            break;
        case "executarCorrecao":
            $consistencia = ConsistenciaRepository::getInstance();

            if ($consistencia->executarCorrecao($parametros->id, $parametros->registros)) {
                $retorno->mensagem = "Correção realizada com sucesso.";
            } else {
                throw new \Exception("Falha ao realizar correção.");
            }
            break;
        case "getOpcoesSelectPorConsultaSql":
            $consistencia = ConsistenciaRepository::getInstance();

            $retorno->opcoes = $consistencia->getOpcoesSelectPorConsultaSql(
                $parametros->id,
                $parametros->propriedadeCampo
            );
            break;

        case "exportarParaCsv":

            $delimitador = ',';
            $nomeArquivo = "consistencia_".date('Y_m_d').".csv";
            $caminhoArquivo = "tmp/{$nomeArquivo}";

            $consistencia = ConsistenciaRepository::getInstance();
            $colunas = $consistencia->getColunas($parametros->id);

            $registros = $consistencia->executarConsistencia($parametros->id, $parametros->filtros);
            if (empty($registros)) {
                throw new DBException("Não foram encontrados registros inconsistentes para correção.");
            }
            $arquivoCsv = array();
            $primeiraLinha = array();
            foreach ($colunas as $coluna) {
                $primeiraLinha[] = $coluna->nome;
            }

            $arquivoCsv[] = implode($delimitador, $primeiraLinha);
            foreach ($registros as $stdRegistro) {

                $linhaRegistro = array();
                foreach ($colunas as $coluna) {
                    $linhaRegistro[] = $stdRegistro->{$coluna->propriedade};
                }
                $arquivoCsv[] = implode($delimitador, $linhaRegistro);
            }
            file_put_contents($caminhoArquivo, implode("\n", $arquivoCsv));
            $retorno->arquivo = $caminhoArquivo;
            $retorno->nome_arquivo = $nomeArquivo;

            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro){

    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($retorno);
