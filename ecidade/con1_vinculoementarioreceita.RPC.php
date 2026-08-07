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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\Financeiro\Contabilidade\PlanoDeContas\Orcamento\Atualizacao\Ementario;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Orcamento\Atualizacao\Ementario\Receita;

$oJson = JSON::create();
$oParam = $oJson->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMensagem = '';
$oRetorno->erro = false;

db_inicio_transacao();

try {

    switch ($oParam->exec) {

        case 'getPlanosContasOrcamentario':

            $where = implode(' and ', array(
                'c60_anousu = 2017',
                "substr(c60_estrut, 1, 1)::integer in (4,9)",
            )). " order by c60_estrut ";

            $campos = implode(', ', array(
                'conplanoorcamento.*',
                'c97_conplanoorcamento',
                '(case when 
                    (select true 
                       from orcreceita 
                      where o70_anousu = 2018 
                        and o70_codfon = c60_codcon limit 1) then true 
                    else false 
                 end) as possui_receita'
            ));
            $oConPlanoOrcamento = new \cl_conplanoorcamento();
            $sSql = $oConPlanoOrcamento->sql_query_orcamento_detalhe($campos, $where);

            $result = $oConPlanoOrcamento->sql_record($sSql);

            if ($oConPlanoOrcamento->numrows == 0) {
                throw new Exception('Nenhum plano orçamentário encontrado.');
            }

            for ($x = 0; $x < $oConPlanoOrcamento->numrows; $x++) {

                $stdOrcamento = db_utils::fieldsMemory($result, $x);

                if (empty($stdOrcamento)) {
                    continue;
                }

                $stdOrcamento->c60_estrut = db_formatar($stdOrcamento->c60_estrut, 'receita');
                $index = '0';
                if (!empty($stdOrcamento->c97_conplanoorcamento)) {
                    $index = '1';
                }
                $oRetorno->conPlanoOrcamento[$index][] = $stdOrcamento;
            }

            break;

        case 'getEmentarioReceita':


            $receita = new Receita();
            if (!$receita->possuiImportacaoRealizada()) {
                throw new BusinessException("Não existe importação de arquivo realizada. Utilize a rotina DB:FINANCEIRO > Contabilidade > Cadastros > Plano de Contas Orçamentário > Atualizar Ementário da Receita para importar um arquivo De/Para.");
            }

            $importacao = $receita->getImportacao();


            $sSql = ' SELECT * ';
            $sSql .= ' FROM planocontadetalhe';
            $sSql .= ' LEFT JOIN planocontadetalheconplanoorcamento ON c97_planocontadetalhe = c95_sequencial';
            $sSql .= ' WHERE';
            $sSql .= ' c95_modeloplanoconta = ' . $importacao->getModelo();
            $sSql .= ' ORDER BY c95_estrutural;';

            $result = db_query($sSql);
            $numRows = pg_num_rows($result);

            if ($numRows == 0) {
                throw new Exception('Nenhum ementário encontrado.');
            }

            for ($x = 0; $x < $numRows; $x++) {

                $oEmentario = db_utils::fieldsMemory($result, $x);

                if (empty($oEmentario)) {
                    continue;
                }

                $index = '0';

                if (!empty($oEmentario->c97_planocontadetalhe)) {
                    $index = '1';
                }

                $oRetorno->ementarioReceita[$index][] = $oEmentario;
            }

            break;

        case 'processar':

            if (empty($oParam->planocontadetalhe)) {
                throw new Exception('Deve ser informado o ementário da receita.');
            }

            if (empty($oParam->conplanoorcamento)) {
                throw new Exception('Deve ser informado o plano orçamentário.');
            }
            Receita::salvarVinculo($oParam->planocontadetalhe, $oParam->conplanoorcamento);
            $oRetorno->sMensagem = 'Vínculo efetuado com sucesso!';

            break;

        case 'criarEmentarioReceita':

            $receita    = new Receita();
            $importacao = $receita->getImportacao();

            $oPlanoContaDetalhe = new \cl_planocontadetalhe();
            $oPlanoContaDetalhe->c95_sequencial       = null;
            $oPlanoContaDetalhe->c95_modeloplanoconta = $importacao->getModelo();
            $oPlanoContaDetalhe->c95_estrutural       = DBEstrutura::mascararString('0.0.0.0.0.00.0.0.00.00.00', str_replace('.', '', $oParam->c95_estrutural));
            $oPlanoContaDetalhe->c95_titulo           = mb_strtoupper($oParam->c95_titulo);
            $oPlanoContaDetalhe->c95_funcao           = mb_strtoupper($oParam->c95_funcao);
            $oPlanoContaDetalhe->c95_naturezasaldo    = $oParam->c95_naturezasaldo;
            $oPlanoContaDetalhe->c95_analitica        = $oParam->c95_analitica;
            $oPlanoContaDetalhe->c95_sistema          = $oParam->c95_sistema;
            $oPlanoContaDetalhe->c95_indicadorsuperavit = 'N';
            $oPlanoContaDetalhe->c95_excluir            = false;

            $oPlanoContaDetalhe->incluir();

            if ($oPlanoContaDetalhe->erro_status === "0") {
                throw new Exception('Não foi possível cadastrar o Ementário da Receita');
            }

            $oRetorno->sMensagem = 'Registro cadastrado com sucesso!';

            break;

        case 'importarEmentario':

            if (count($oParam->contas) === 0) {
                throw new ParameterException("Selecione no mínimo uma conta do ementário de receita.");
            }

            foreach ($oParam->contas as $codigoDetalhe) {
                Receita::importarContaParaOrcamento($codigoDetalhe);
            }

            $oRetorno->sMensagem = 'Contas do ementário de receita importadas com sucesso.';
            break;

    }

    db_fim_transacao(false);

} catch (Exception $e) {

    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMensagem = $e->getMessage();
    $oRetorno->erro = true;
}

echo $oJson->stringify($oRetorno);