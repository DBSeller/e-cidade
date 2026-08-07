<?php
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));

use ECidade\Financeiro\Contabilidade\Importacao\Siconfi\TipoRecursos as Importacao;
use ECidade\Financeiro\Contabilidade\Exportacao\Siconfi\TipoRecursos as Exportacao;


$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;

try {

    db_inicio_transacao();
    switch($oParam->exec) {

        case 'importarArquivo' :

            $oFiles = db_utils::postMemory($_FILES);
            if (strtolower(substr($oFiles->arquivo['name'], -4)) != '.csv') {
                throw new BusinessException("Arquivo importado com formato inválido! Arquivo deve ser do formato CSV.");
            }

            if (trim(file_get_contents($oFiles->arquivo['tmp_name'])) == "") {
                throw new BusinessException("Não é possível importar arquivo vazio.");
            }

            $ano = db_getsession("DB_anousu");
            $instituicao = InstituicaoRepository::getInstituicaoSessao();

            $aFile = file($oFiles->arquivo['tmp_name']);
            $aFile = array_slice($aFile, 1, count($aFile));
            $transacoesIncluidas = array();


            /*
             * @todo -
             *
             * incluir documento
             * incluir na contrans
             *
             *
             */

            

            foreach ($aFile as $linha) {

                $colunas = explode(";", $linha);

                $documento	          = $colunas[0];
                $documento_estorno	  = $colunas[1];
                $descricao_documento  = $colunas[2];

                $historico	          = $colunas[3];
                $ordem	              = $colunas[4];
                $descricao_lancamento = $colunas[5];
                $conta_debito	      = $colunas[6];
                $conta_credito	      = $colunas[7];
                $tipo_comparacao	  = $colunas[8];
                $valor_comparacao     = $colunas[9];

                if ( ! in_array($documento.$ordem, $transacoesIncluidas) )
                {
                    $oLancamento = new transacaoContabilLancamento();
                    $oLancamento->setHistorico($historico);

                    $oLancamento->setOrdem($ordem);
                    $oLancamento->setDescricao($descricao_lancamento);
                    $oLancamento->setObservacao("Incluido via importação externa");
                    $oLancamento->save();
                    $transacoesIncluidas[] = $documento.$ordem;
                }

                $oLancamento->salvarContaLancamento( $ano,
                                                     $conta_credito,
                                                     $conta_debito,
                                                     $instituicao,
                                                     0,
                                                     $valor_comparacao,
                                                     $tipo_comparacao );
            }

            db_fim_transacao(true);

            $oRetorno->sMessage = utf8_encode("Importação efetuada com sucesso!");

            if (!empty($erros)) {
                $oRetorno->sMessage = "Erro ao importar os codigos (".implode(",", $erros) .")";
            }

            break;
    }


} catch (Exception $eErro){
    db_fim_transacao(true);
    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = utf8_encode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->status;
echo $oJson->encode($oRetorno);
