<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2020  DBSeller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$get = (array)filter_input_array(INPUT_GET);
$post = (array)filter_input_array(INPUT_POST);
$parametros = (object)array_merge($get, $post);
$retorno    = (object)array( 'erro' => false, 'mensagem'=> '');

try {
    db_inicio_transacao();
    switch ($parametros->exec) {

        case 'salvarCampo':

            $retorno->campo = (object)[
                'idCampoDinamico' => $parametros->idCampoDinamico ? $parametros->idCampoDinamico : substr(time(), -4),
                'idTipoProcesso'  => (int) $parametros->idTipoProcesso,
                'codcam'          => (int) $parametros->codcam,
                'nomecam'         => $parametros->nomecam,
                'obrigatorio'     => (int) $parametros->obrigatorio,
            ];
            $retorno->mensagem = "Salvo com sucesso";

            break;

        case 'excluirCampo':

            $parametros->idCampoDinamico;
            $parametros->idTipoProcesso;
            
            // $retorno->erro = true;
            // $retorno->mensagem = "Teste de erro";

            break;

        case 'getCampos':

            $retorno->campos = [];

            for ($i = 1; $i <= 10; $i++) {

                $nomecam = [];

                for ($ii = 1; $ii <= 15; $ii++) {
                    $nomecam[] = chr(rand(97, 122));
                }
                $nomecam = implode('', $nomecam);
                
                $campo = (object)[
                    'idCampoDinamico' => substr((time() + $i), -4),
                    'idTipoProcesso'  => (int) $parametros->idTipoProcesso,
                    'codcam'          => (int) $i + 49,
                    'nomecam'         => $nomecam,
                    'obrigatorio'     => rand(0,1),
                ];
    
                $retorno->campos[] = $campo;
            }

            break;

        default:
            return;
    }
    db_fim_transacao(false);

} catch (Exception $eErro) {

    db_fim_transacao(true);

    $retorno->erro     = true;
    $retorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($retorno);
