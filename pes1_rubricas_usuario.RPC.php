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

use ECidade\RecursosHumanos\Pessoal\Model\RubricasUsuario;
use ECidade\RecursosHumanos\Pessoal\Repository\RubricasUsuarioRepository;
use ECidade\RecursosHumanos\Pessoal\Service\RubricasUsuarioService;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->erro = false;

try {
    db_inicio_transacao();

    $servico = new RubricasUsuarioService();

    switch ($parametros->acao) {
        case 'instituicao':
            $instituicao = InstituicaoRepository::getInstituicaoSessao();

            $retorno->instituicao = array(
                'codigo' => $instituicao->getCodigo(),
                'descricao' => $instituicao->getDescricao()
            );
            break;
        case 'buscarRubricasUsuario':
            $rubricasUsuario = $servico->buscarRubricasUsuario(
                UsuarioSistemaRepository::getPorCodigo($parametros->usuario),
                InstituicaoRepository::getInstituicaoByCodigo($parametros->instituicao)
            );
            $retorno->rubricasUsuario = $servico->toArray($rubricasUsuario);
            break;
        case 'salvarRubricasUsuario':
            $usuario = UsuarioSistemaRepository::getPorCodigo($parametros->usuario);
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo($parametros->instituicao);

            $repositorio = new RubricasUsuarioRepository();
            $repositorio->scopeUsuario($usuario)->scopeInstituicao($instituicao)->deleteFromScope();

            if (isset($parametros->rubricas)) {
                foreach ($parametros->rubricas as $codigo) {
                    $rubrica = RubricaRepository::getInstanciaByCodigo($codigo, $instituicao->getCodigo());
                    $rubricasUsuario = new RubricasUsuario();
                    $rubricasUsuario->setInstituicao($instituicao);
                    $rubricasUsuario->setUsuario($usuario);
                    $rubricasUsuario->setRubrica($rubrica);

                    $repositorio->save($rubricasUsuario);
                }
            }

            $retorno->mensagem = 'Configuração efetuada com sucesso.';
            break;

    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
