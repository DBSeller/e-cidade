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

    use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\CartorioExtra;
    use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\CartorioExtraTipo;
    use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\TiposCartorioExtra;
    use ECidade\Tributario\Juridico\CartorioExtrajudicial\Repository\CartorioExtraRepository;
    use ECidade\Tributario\Juridico\CartorioExtrajudicial\Repository\CartorioExtraTipoRepository;
    use ECidade\Tributario\Juridico\CartorioExtrajudicial\Repository\TiposCartorioExtraRepository;

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
        db_inicio_transacao();

        switch ($parametro->executa) {
            case "salvar":
                db_inicio_transacao();

                $cartorioExtraRepository = CartorioExtraRepository::getInstance();
                $cartorioExtra = new CartorioExtra;

                $cartorioExtra->setSequencial($parametro->j167_sequencial);
                $cartorioExtra->setDescricao($parametro->j167_descricao);
                $cartorioExtra->setNumcgm($parametro->j167_numcgm);
                $cartorioExtra->setObservacao($parametro->j167_observacao);

                $j167_sequencial = $cartorioExtraRepository->persist($cartorioExtra);

                $cartorioextratipoRepository = CartorioExtraTipoRepository::getInstance();
                $cartorioextratipo = new CartorioExtraTipo;

                $cartorioextratipo->setSequencial(null);
                $cartorioextratipo->setCartorioextra($j167_sequencial);

                $cartorioextratipoRepository->setDefaultCondition($cartorioextratipo)->delete();

                $aTipos = explode(",", $parametro->sTipos);

                foreach ($aTipos as $iTipo) {
                    $cartorioextratipo->setTiposcartorioextra($iTipo);

                    $cartorioextratipoRepository->persist($cartorioextratipo);
                }

                db_fim_transacao();

                $retorno->mensagem = "Cartório salvo com sucesso.";

                break;
            case "buscarDadosCartorio":
                $cartorioExtraRepository = CartorioExtraRepository::getInstance();
                $cartorioExtra = new CartorioExtra;

                $cartorioExtra->setSequencial($parametro->j167_sequencial);

                $retorno->oCartorio = $cartorioExtraRepository->setDefaultCondition($cartorioExtra)->get();

                $cartorioextratipoRepository = CartorioExtraTipoRepository::getInstance();
                $cartorioextratipo = new CartorioExtraTipo;

                $cartorioextratipo->setCartorioextra($parametro->j167_sequencial);

                $retorno->aTipos = $cartorioextratipoRepository->setDefaultCondition($cartorioextratipo)->get();
                break;
            case "listarTipos":
                $tiposCartorioExtraRepository = TiposCartorioExtraRepository::getInstance();

                $aTipos = $tiposCartorioExtraRepository->get();

                $retorno->aTipos = $aTipos;
                break;
            default:
                throw new Exception('Nenhuma ação encontrada.');
                break;
        }

        db_fim_transacao(false);
    } catch (Exception $erro) {
        db_fim_transacao(true);

        $retorno->erro = true;
        $retorno->mensagem = $erro->getMessage();
    }

    echo JSON::create()->stringify($retorno);
