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

use ECidade\RecursosHumanos\Pessoal\Model\ServidorOperadoraSaude;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorOperadoraSaudeDependente;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorOperadoraSaudeRepository;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorOperadoraSaudeDependenteService;
use ECidade\RecursosHumanos\Pessoal\Service\ServidorOperadoraSaudeService;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

try {
    db_inicio_transacao();

    $servidorOperadoraSaudeService = new ServidorOperadoraSaudeService();
    $servicoDependente = new ServidorOperadoraSaudeDependenteService();

    switch ($parametros->acao) {
        case 'salvarServidorOperadoraSaude':
            $instituicao = empty($parametros->instituicao) ? InstituicaoRepository::getInstituicaoSessao() : InstituicaoRepository::getInstituicaoByCodigo($parametros->instituicao);
            $servidorOperadoraSaude = $servidorOperadoraSaudeService->salvar($parametros, $instituicao,
                DBPessoal::getCompetenciaFolha());
            $retorno->servidorOperadoraSaude = $servidorOperadoraSaude->toArray();
            $retorno->mensagem = 'Plano de saúde do servidor salvo com sucesso!';
            break;
        case 'buscarTiposDependentes':
            $retorno->tiposDepentente = JSON::create()->parse(file_get_contents('arquivos/esocial/tabelas/tabela07.json'));
            break;
        case 'buscarServidorOperadoraSaude':
            $servidor = ServidorRepository::getInstanciaByCodigo($parametros->servidor);
            $competencia = DBPessoal::getCompetenciaFolha();

            $servidorOperadoraSaudeRepository = new ServidorOperadoraSaudeRepository();
            $servidorOperadoraSaude = $servidorOperadoraSaudeRepository->scopeServidor($servidor)
                ->scopeAno($competencia->getAno())
                ->scopeMes($competencia->getMes())
                ->get();

            $retorno->servidorOperadoraSaude = array_map(function (ServidorOperadoraSaude $servidorOperadoraSaude) {
                return $servidorOperadoraSaude->toArray();
            }, $servidorOperadoraSaude);
            break;
        case 'buscarCompetencia':
            $competencia = DBPessoal::getCompetenciaFolha();

            $retorno->competencia = $competencia->toArray();
            break;
        case 'salvarDependente':
            $servicoDependente = new ServidorOperadoraSaudeDependenteService();
            $dependente = $servicoDependente->salvar($parametros);

            $retorno->dependente = $dependente->toArray();
            $retorno->mensagem = 'Plano de saúde do dependente salvo com sucesso!';
            break;
        case 'excluirServidorOperadoraSaude':
            $servidorOperadoraSaudeService->excluir($parametros);
            $retorno->mensagem = 'Vínculo do servidor com o plano de saúde excluído com sucesso!';
            break;
        case 'excluirServidorOperadoraSaudeDependente':
            $servicoDependente->excluir($parametros);
            $retorno->mensagem = 'Vínculo do dependente com o plano de saúde excluído com sucesso!';
            break;
        case 'buscarDependentes':
            $servidorService = new ServidorOperadoraSaudeService();
            $dependentes = $servidorService->dependentes(ServidorOperadoraSaudeRepository::find($parametros->codigoPlanoSaudeServidor));

            $retorno->dependentes = array_map(function (ServidorOperadoraSaudeDependente $dependente) {
                return $dependente->toArray();
            }, $dependentes);
            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
