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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil;

$parametros = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$retorno = new stdClass();
$retorno->status = 1;
$retorno->message = '';
$ano = db_getsession("DB_anousu");
$instituicao = InstituicaoRepository::getInstituicaoSessao();
try {
    db_inicio_transacao();

    switch ($parametros->exec) {
        case "getContas":
            $buscaContas = db_query("
                select c60_codcon, c60_estrut, c60_descr
                  from conplano
                 where c60_anousu = {$ano}
                   and c60_estrut ilike '{$parametros->estrutural}%'
                   and exists (select 1 from conplanoreduz where c61_codcon = c60_codcon and c61_anousu = c60_anousu)
                 order by c60_estrut");
            if (!$buscaContas) {
                throw new DBException("Ocorreu um erro ao consultar o plano de contas.");
            }

            $contasRetorno = array();
            $totalRegistros = pg_num_rows($buscaContas);
            for ($row = 0; $row < $totalRegistros; $row++) {
                $stdDados = db_utils::fieldsMemory($buscaContas, $row);

                $stdConta = new stdClass();
                $stdConta->codigo = $stdDados->c60_codcon;
                $stdConta->estrutural = $stdDados->c60_estrut;
                $stdConta->descricao = $stdDados->c60_descr;
                $contasRetorno[] = $stdConta;
            }

            if (count($contasRetorno) == 0) {
                throw new BusinessException("Sem contas cadastradas para o estrutural {$parametros->estrutural}.");
            }

            $retorno->contas = $contasRetorno;
            break;

        case "getInformacoesComplementares":
            $daoInfoComplementar = new cl_conplanoinfocomplementar();
            $listaAtributos = implode(",", MatrizSaldoContabil::getAtributos(db_getsession('DB_anousu')));
            $where = "c121_sequencial in ({$listaAtributos})";
            $sqlInformacoes = $daoInfoComplementar->sql_query_file(null, "*", 'c121_sigla', $where);
            $rs = db_query($sqlInformacoes);
            $retorno->informacoes_complementares = db_utils::makeCollectionFromRecord($rs, function ($dados) {
                $informacaoComplementar = new stdClass();
                $informacaoComplementar->codigo = $dados->c121_sequencial;
                $informacaoComplementar->sigla = $dados->c121_sigla;
                $informacaoComplementar->descricao = $dados->c121_descricao;
                return $informacaoComplementar;
            });

            break;

        case 'salvar':
            if (empty($parametros->contas) || !is_array($parametros->contas)) {
                throw new ParameterException("Contas não informadas para processamento.");
            }
            $totalAtributos = 6;
            $mensagem = "É permitido a seleção de no máximo 6(seis) informações complementares";
            if ($ano >= 2020) {
                $totalAtributos = 7;
                $mensagem = "É permitido a seleção de no máximo 7(sete) informações complementares";
            }
            if (count($parametros->informacoes_complementares) > $totalAtributos) {

                throw new ParameterException($mensagem);
            }
            foreach ($parametros->contas as $conta) {
                $contaPlano = ContaPlanoPCASPRepository::getContaByCodigo((int)$conta, $ano);
                if (empty($contaPlano)) {
                    continue;
                }
                $contaPlano->removerInformacoesComplementares(1);
                if (!empty($parametros->informacoes_complementares)) {
                    $contaPlano->adicionarInformacoesComplementares(1, $parametros->informacoes_complementares);
                }
            }
            $retorno->message = "Informações complementares vinculadas com sucesso.";
    }

    db_fim_transacao(false);
} catch (Exception $oErro) {
    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->message = $oErro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);
