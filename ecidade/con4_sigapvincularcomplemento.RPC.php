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


$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->message = 1;

try {

    db_inicio_transacao();
    switch ($oParam->exec) {

        case 'importarArquivo' :

            $oFiles = db_utils::postMemory($_FILES);
            if (strtolower(substr($oFiles->arquivo['name'], -4)) != '.csv') {
                throw new BusinessException("Arquivo importado com formato inválido! Arquivo deve ser do formato CSV.");
            }

            if (trim(file_get_contents($oFiles->arquivo['tmp_name'])) == "") {
                throw new BusinessException("Não é possível importar arquivo vazio.");
            }
            /**
             * validamos as linhas do arquivo
             */
            $linhas = file ($oFiles->arquivo['tmp_name']);
            if (empty($linhas)) {
                throw new BusinessException("Não é possível importar arquivo vazio.");
            }
            $erros = [];
            if (str_replace(array(" ", "\n"), '', $linhas[0]) != 'dotacao;complemento') {
                throw new BusinessException("O arquivo informado não é um arquivo valido para essa importação.");
            }
            foreach ($linhas as $i => $linha) {
                if ($i == 0 || trim($linha) == '') {
                    continue;
                }
                $linha = str_replace("\n", "", $linha);
                $colunas = explode(";", $linha);
                $numeroLinha = $i+1;
                if (count($colunas) != 2) {
                    $erros[] = " - Linha {$numeroLinha} deve possuir duas colunas";
                }

                if (strlen(trim($colunas[1])) !== 4) {
                    $erros[] = " - Complemento da linha {$numeroLinha} deve ter 4(quatro) caracteres";
                }
            }
            if (count($erros) > 0) {
                $mensagem = "Não foi possível inmportar arquivo. Inconsistências encontradas:\n";
                $mensagem .= implode("\n", $erros);
                throw new BusinessException($mensagem);
            }
            $nomeChave = "sigap_complemento_recurso_{$oParam->anoSelecionado}";
            $dados = file_get_contents($oFiles->arquivo['tmp_name']);
            $opcao = \ECidade\Configuracao\Opcao\Opcao::salvar($nomeChave, $dados, $oParam->anoSelecionado);
            $oRetorno->sMessage = utf8_encode("Importação efetuada com sucesso!");
            break;
        case "downloadArquivo":
            $nomeChave = "sigap_complemento_recurso_{$oParam->anoSelecionado}";
            $conteudo = \ECidade\Configuracao\Opcao\Opcao::get($nomeChave, $oParam->anoSelecionado);
            $nomeArquivo = "complemento_recurso_dotacao_{$oParam->anoSelecionado}.csv";
            file_put_contents('tmp/' . $nomeArquivo, $conteudo);
            $oRetorno->sNomeArquivo = urlencode('tmp/'.$nomeArquivo);
            $oRetorno->sNome = urlencode($nomeArquivo);
            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = utf8_encode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->status;
echo $oJson->encode($oRetorno);
