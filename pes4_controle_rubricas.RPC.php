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
require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

use ECidade\RecursosHumanos\Pessoal\Repository\ControleRubricasParametrosRepository;
use ECidade\RecursosHumanos\Pessoal\Service\ControleRubricasParametrosService;

$parametros = JSON::requestParameters();
$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

try {
    db_inicio_transacao();
    $dao = new cl_controlehorasextras();
    $repository = new ControleRubricasParametrosRepository($dao);
    $service = new ControleRubricasParametrosService($repository);
    $instituicao = InstituicaoRepository::getInstituicaoSessao();
    $competencia = DBPessoal::getCompetenciaFolha();

    switch ($parametros->acao) {
        case 'salvarParametros':
            $controleHorasExtras = $service->salvar($parametros, $instituicao, $competencia);
            $retorno->controleHorasExtras = $controleHorasExtras->toArray();
            $retorno->mensagem = "Controle de horas extras salvo com sucesso.";
            break;
        case 'buscarPorInstituicaoECompetencia':
            $retorno->controleHorasExtras = null;
            $controleHorasExtras = $service->buscarPorInstituicaoECompetencia($instituicao, $competencia);
            if ($controleHorasExtras) {
                $retorno->controleHorasExtras = $controleHorasExtras->toArray();
            }
            break;
        case 'excluirParametros':
            $service->remover($parametros);
            $retorno->mensagem = "Controle de horas extras excluído com sucesso.";
            break;
    }

} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
