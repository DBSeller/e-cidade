<?php /*
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

use ECidade\RecursosHumanos\Pessoal\Service\ServidorOutrosVinculosService;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorOutrosVinculos;

$parametros = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$retorno = new stdClass();
$retorno->erro = 1;
$retorno->mensagem = '';

try {
    db_inicio_transacao();

    switch ($parametros->exec) {
        case 'salvar':

            $service = new ServidorOutrosVinculosService();
            $service->salvar($parametros);
            $retorno->mensagem = 'Vínculo do servidor salvo com sucesso.';
            break;
        case 'excluir':

            $service = new ServidorOutrosVinculosService();
            $service->excluir($parametros);
            $retorno->mensagem = 'Vínculo do servidor excluído com sucesso.';
            break;
        case 'buscar':

            $service = new ServidorOutrosVinculosService();
            $servidorOutrosVinculos = $service->buscarOutrosVinculosPorMatriculaCompetencia($parametros);

            $retorno->servidorOutrosVinculos = array_map(function (ServidorOutrosVinculos $servidorOutrosVinculos) {
                return $servidorOutrosVinculos->toArray();
            }, $servidorOutrosVinculos);
            break;
        case 'buscarCategorias':

            $retorno->categorias = array();
            $avaliacao = AvaliacaoRepository::getAvaliacaoByCodigo(3000013);
            $avaliacao->setAvaliacaoGrupo(3000013);
            $respostasCategoria = $avaliacao->getRespostasDaPerguntaPoCodigo(3000827);

            foreach ($respostasCategoria as $respostaCategoria) {
                $stdResposta = new stdClass();
                $stdResposta->codigo = $respostaCategoria->valorresposta;
                $stdResposta->descricao = $respostaCategoria->descricaoresposta;
                $retorno->categorias[] = $stdResposta;
            }
            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $retorno->erro = 2;
    $retorno->mensagem = $eErro->getMessage();
}

$retorno->erro = $retorno->erro == 2;
echo JSON::create()->stringify($retorno);
