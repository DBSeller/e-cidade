<?php
    /**
     *     E-cidade Software Publico para Gestao Municipal
     *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

    use ECidade\Tributario\Juridico\Repository\UnificacaoIniciaisRepository;
    use ECidade\Tributario\Juridico\Service\UnificacaoIniciaisService;

    require_once(modification("libs/db_stdlib.php"));
    require_once(modification("libs/db_utils.php"));
    require_once(modification("libs/db_app.utils.php"));
    require_once(modification("libs/db_conecta.php"));
    require_once(modification("libs/db_sessoes.php"));
    require_once(modification("dbforms/db_funcoes.php"));

    $post = db_utils::postMemory($_REQUEST);
    $post->json = str_replace("\\", "", $post->json);
    $parametro = JSON::create()->parse($post->json);
    $retorno = (object)array('erro' => false, 'mensagem' => '');

try {
    $unificacaoIniciaisRepository = new UnificacaoIniciaisRepository(
        db_getsession("DB_instit"),
        date("Y-m-d", db_getsession("DB_datausu")),
        db_getsession("DB_anousu")
    );

    $unificacaoIniciaisService = new UnificacaoIniciaisService();

    db_inicio_transacao();

    switch ($parametro->executa) {
        case "getIniciais":
            $aIniciais = $unificacaoIniciaisRepository->setCodigo($parametro->codigo)
                                                      ->setOrigem($parametro->origem)
                                                      ->setAgrupamento($parametro->agrupamento)
                                                      ->montaFiltros()
                                                      ->getIniciaisByOrigem();

            if (count($aIniciais) == 0) {
                throw new \Exception("Não foi encontrado iniciais para o filtro.");
            }

            $retorno->iniciais = $unificacaoIniciaisService->agrupaIniciais($aIniciais);
            break;
        case "verificaOrigens":
            $aIniciais = $unificacaoIniciaisRepository->getByInicial($parametro->iniciaisSecundarias);

            $retorno->origens = $unificacaoIniciaisService->agrupaOrigens($aIniciais);
            break;
        case "unificaIniciais":
            $unificacaoIniciaisService->unificaIniciais(
                $unificacaoIniciaisRepository,
                $parametro->inicialPrimaria,
                $parametro->iniciaisSecundarias,
                $parametro->cdas,
                $parametro->somenteOrigemFiltro
            );

            $retorno->mensagem = "Iniciais unificadas com sucesso!";
            break;
        default:
            throw new Exception('Nenhuma ação encontrada.');
    }

    db_fim_transacao();
} catch (Exception $erro) {
    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $erro->getMessage();
}

echo JSON::create()->stringify($retorno);
