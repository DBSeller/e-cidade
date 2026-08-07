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

use ECidade\RecursosHumanos\ESocial\Repository\ESocialRubricasRepository;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

try {
    db_inicio_transacao();

    $retorno = new stdClass();
    $retorno->erro = false;

    $instituicao = new Instituicao(db_getsession('DB_instit'));
    $repository = new ESocialRubricasRepository();

    switch ($parametros->acao) {
        case 'buscar':
            $rubrica = new Rubrica($parametros->codigoRubrica);

            $retorno->rubrica = $repository->getByRubricaAndInstituicao($rubrica, $instituicao);
            $retorno->opcoesCodIncCP = array();
            $retorno->opcoesCodIncIRRF = array();
            $retorno->opcoesCodIncFGTS = array();
            $retorno->opcoesCodTetoRemun = [];
            $retorno->opcoesCodIncCPRP = [];

            $pergunta = new AvaliacaoPergunta(3000947);
            $opcoes = $pergunta->getOpcoes();

            foreach ($opcoes as $opcao) {
                $retorno->opcoesCodIncCP[] = array(
                    'value' => $opcao->getCodigo(),
                    'label' => $opcao->getDescricao()
                );
            }

            $pergunta = new AvaliacaoPergunta(3000948);
            $opcoes = $pergunta->getOpcoes();

            foreach ($opcoes as $opcao) {
                $retorno->opcoesCodIncIRRF[] = array(
                    'value' => $opcao->getCodigo(),
                    'label' => $opcao->getDescricao()
                );
            }

            $pergunta = new AvaliacaoPergunta(3000949);
            $opcoes = $pergunta->getOpcoes();

            foreach ($opcoes as $opcao) {
                $retorno->opcoesCodIncFGTS[] = array(
                    'value' => $opcao->getCodigo(),
                    'label' => $opcao->getDescricao()
                );
            }

            $pergunta = new AvaliacaoPergunta(4000297);
            $opcoes = $pergunta->getOpcoes();

            foreach ($opcoes as $opcao) {
                $retorno->opcoesCodIncCPRP[] = array(
                    'value' => $opcao->getCodigo(),
                    'label' => $opcao->getDescricao()
                );
            }

            $pergunta = new AvaliacaoPergunta(4000298);
            $opcoes = $pergunta->getOpcoes();
            foreach ($opcoes as $opcao) {
                $retorno->opcoesCodTetoRemun[] = array(
                    'value' => $opcao->getCodigo(),
                    'label' => $opcao->getDescricao()
                );
            }

            $sql = "select * from esocial.rubricasubgrupotce order by rh263_grupo asc, rh263_subgrupo asc";
            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException("Erro ao buscar os subgrupos das rubricas.");
            }

            $contador = pg_num_rows($rs);
            $retorno->subgruposrubricas = [];
            $dadosRetorno = [];
            for ($i = 0; $i < $contador; $i++) {
                $elemento = db_utils::fieldsMemory($rs, $i);
                if (empty($dadosRetorno[$elemento->rh263_grupo])) {
                    $dadosRetorno[$elemento->rh263_grupo] = [];
                }
                $dados = [
                    'value' => $elemento->rh263_subgrupo,
                    'label' => "{$elemento->rh263_subgrupo} - {$elemento->rh263_descricao}",
                ];
                $dadosRetorno[$elemento->rh263_grupo][] = $dados;
            }
            foreach ($dadosRetorno as $key => $value) {
                $dado = ["grupo" => $key, "dado" => $dadosRetorno[$key]];
                $retorno->subgruposrubricas[] = $dado;
            }


            $retorno->opcoesNatureza = JSON::create()->parse(file_get_contents('arquivos/esocial/tabelas/tabela03.json'));
            break;
        case 'salvar':
            $rubrica = JSON::create()->parse($parametros->rubrica);
            $retorno->rubrica = $repository->persist($rubrica);
            $retorno->mensagem = 'Informações salvas com sucesso!';
            break;
        case 'excluir':
            $rubrica = new Rubrica($parametros->codigoRubrica);
            $repository->delete($rubrica, $instituicao);
            $retorno->mensagem = 'Informações excluídas com sucesso!';
            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
