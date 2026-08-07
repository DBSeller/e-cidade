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

use ECidade\Financeiro\Contabilidade\ContaCorrente\Repository\Visao as VisaoRepository;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao as VisaoModel;

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$anoSessao = db_getsession('DB_anousu');
$parametros        = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$retorno           = new stdClass();
$retorno->mensagem = '';
$retorno->erro     = false;

try {
    db_inicio_transacao();

    switch ($parametros->exec) {

        case "salvar":

            $propriedadesInvalidas = array();
            if (empty($parametros->dados->nome)) {
                $propriedadesInvalidas[] = 'nome';
            }

            if ((empty($parametros->dados->id_item) || empty($parametros->dados->modulo)) && empty($parametros->dados->codigo)) {
                $propriedadesInvalidas[] = 'menu';
            }

            if (empty($parametros->dados->dadosJson)) {
                $propriedadesInvalidas[] = 'filtros';
            }

            if (count($propriedadesInvalidas) > 0) {
                throw new ParameterException("Os itens ".implode(', ', $propriedadesInvalidas)." são de preenchimento obrigatório.");
            }

            if ( ! empty($parametros->dados->codigo)) {
                excluirVisao($parametros);
                $parametros->dados->codigo = null;
            }
            $codigoMenu = criarMenu($parametros);

            /*
             * Inclui a nova visão com seus filtros na tabela e ajusta o programa a ser executado no item de menu
             */
            $visao = new VisaoModel();
            $visao->setCodigo($parametros->dados->codigo);
            $visao->setNome($parametros->dados->nome);
            $visao->setFiltrosJson(JSON::create()->stringify($parametros->dados->dadosJson));
            $visao->setCodigoItemMenu($codigoMenu);
            $visaoRepository = VisaoRepository::salvar($visao);

            $daoItensMenu = new cl_db_itensmenu();
            $daoItensMenu->id_item = $codigoMenu;
            $daoItensMenu->funcao = "con2_consultacontacorrente001.php?codigo_visao={$visaoRepository->getCodigo()}";
            $daoItensMenu->alterar($codigoMenu);
            if ($daoItensMenu->erro_status === '0') {
                throw new DBException("Não foi possível alterar os dados do menu criado.");
            }

            $retorno->mensagem = "Visão salva com sucesso.";

            break;

        case 'excluir':

            if (empty($parametros->dados->codigo)) {
                throw new ParameterException("Informe o código da visão para ser excluída.");
            }
            excluirVisao($parametros);
            $retorno->mensagem = "Visão excluída com sucesso.";

            break;


        case 'getVisaoPorCodigo':

            if (empty($parametros->dados->codigo)) {
                throw new ParameterException("Informe o código da visão.");
            }

            $visaoRepository = VisaoRepository::getPorCodigo($parametros->dados->codigo);
            if (empty($visaoRepository)) {
                throw new BusinessException("Não foi localizada uma visão com o código {$parametros->dados->codigo}.");
            }

            $menuEncontrado = getMenu($visaoRepository->getCodigoItemMenu());

            $filtros = $visaoRepository->getFiltros();
            if (!empty($filtros->contas->itens)) {
                foreach ($filtros->contas->itens as $indice => $stdContas) {
                    $contaPlano = ContaPlanoPCASPRepository::getContaPorReduzido($stdContas->codigo, $anoSessao);
                    $filtros->contas->itens[$indice]->estrutural = $contaPlano->getEstrutural();
                }
            }

            $retorno->dados = (object)array(
                'codigo' => $visaoRepository->getCodigo(),
                'nome' => $visaoRepository->getNome(),
                'menu' => $menuEncontrado,
                'dadosJson' => $filtros
            );

            break;

        case 'getVisoes':

            $todasVisoes = VisaoRepository::getTodos();
            $retorno->dados = array();
            foreach ( $todasVisoes as $visao ) {

                $menuEncontrado = getMenu($visao->getCodigoItemMenu());
                $retorno->dados[] = (object)array(
                    'codigo' => $visao->getCodigo(),
                    'nome' => $visao->getNome(),
                    'menu' => $menuEncontrado
                );
            }

            break;
        case 'getNomeMenu':

            $menuEncontrado = getMenu($parametros->dados->id_item, $parametros->dados->modulo);
            $retorno->dados = new stdClass();
            $retorno->dados->menu = $menuEncontrado;
            break;
    }

    db_fim_transacao(false);

} catch (Exception $eErro){

    db_fim_transacao(true);
    $retorno->erro = true;
    $retorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($retorno);

/**
 * @param $parametros
 * @throws DBException
 */
function excluirVisao($parametros)
{
    $visaoExcluir = VisaoRepository::getPorCodigo($parametros->dados->codigo);
    $codigoItemMenu = $visaoExcluir->getCodigoItemMenu();
    VisaoRepository::excluir($parametros->dados->codigo);
    $parametros->dados->codigo = null;
    DBMenu::excluir($codigoItemMenu);
}

/**
 * @param $parametros
 * @return int
 * @throws DBException
 */
function criarMenu($parametros)
{
    $buscaCodigoItemMenu = db_query("select nextval('db_itensmenu_id_item_seq') as id_item");
    $daoItensMenu = new cl_db_itensmenu();
    $daoItensMenu->id_item    = db_utils::fieldsMemory($buscaCodigoItemMenu, 0)->id_item;
    $daoItensMenu->descricao  = $parametros->dados->nome;
    $daoItensMenu->help       = $parametros->dados->nome;
    $daoItensMenu->funcao     = "con2_consultacontacorrente001.php?codigo_visao={$parametros->dados->codigo}";
    $daoItensMenu->itemativo  = '1';
    $daoItensMenu->manutencao = "1";
    $daoItensMenu->desctec    = $parametros->dados->nome;
    $daoItensMenu->libcliente = '1';
    $daoItensMenu->incluir($daoItensMenu->id_item);
    if ($daoItensMenu->erro_status === '0') {
        throw new DBException("Ocorreu um erro ao incluir o novo item de menu. ".pg_last_error());
    }

    $menuSequencia = db_query("select max(menusequencia)+1 as menusequencia from db_menu where modulo = {$parametros->dados->modulo} and id_item = {$parametros->dados->id_item};");
    $menuSequencia = db_utils::fieldsMemory($menuSequencia, 0)->menusequencia;

    $daoDbMenu = new cl_db_menu();
    $daoDbMenu->id_item       = $parametros->dados->id_item;
    $daoDbMenu->id_item_filho = $daoItensMenu->id_item;
    $daoDbMenu->modulo        = $parametros->dados->modulo;
    $daoDbMenu->menusequencia = $menuSequencia;
    $daoDbMenu->incluir();
    if ($daoDbMenu->erro_status === '0') {
        throw new DBException("Não foi possível vincular o novo menu ao módulo selecionado.");
    }

    return $daoItensMenu->id_item;
}

function getMenu( $iIdMenu, $idModulo=0 )
{

    $menu = db_query("select fc_montamenu( {$iIdMenu}) as localizacao_menu");
    $menuEncontrado = db_utils::fieldsMemory($menu, 0)->localizacao_menu;
    if ( ! $menu || pg_num_rows($menu) === 0 ) {
        return false;
    }

    if ( $idModulo <> 0 ) {

        $modulo = db_query("select nome_modulo from db_modulos where id_item = {$idModulo} ");
        if (!$modulo|| pg_num_rows($modulo) === 0) {
            return false;
        }
        $s = $menuEncontrado;
        $menu_sem_modulo = substr($s,strpos($s, " > "), strlen($s));
        $menuEncontrado  = db_utils::fieldsMemory($modulo, 0)->nome_modulo.$menu_sem_modulo;
        return $menuEncontrado;

    }

    return $menuEncontrado;

}
