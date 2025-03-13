<?php
namespace App\Domain\RecursosHumanos\Pessoal\Services\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Comissao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoFuncao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoServidor;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Sessao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\SessaoServidor;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\TipoSessao;
use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use Exception;

class SessaoService
{
    /**
     * @param $request
     * @return array
     * @throws Exception
     * Funcao responsavel por efetuar os lancamentos em lote
     * os lancamentos sao de inclusao e alteracao
     * na alteracao nao e permitido alterar desde que a sessao nao esteja processada
     *
     * Tambem e validada as quantidade de sessoes dentro da competencia
     * Assim como a quantidade de lancamentos da funcao do servidor dentro da competencia
     */
    public static function lancamentoLote($request)
    {
        $dados = json_decode(str_replace('\\', '', $request->get('dados')), true);
        $comissaoRequest = json_decode(str_replace('\\', '', $request->get('comissao')), true);
        $competenciaRequest = json_decode(
            str_replace('\\', '', $request->get('competencia')),
            true
        );
        $aErros = [];
        $competencia = CompetenciaHelper::get();
        if (!empty($competenciaRequest)) {
            $competencia = CompetenciaHelper::get($competenciaRequest["ano"], $competenciaRequest["mes"]);
        }

        $comissao = Comissao::with('tipoSessao')->find($comissaoRequest);
        $configuracao = [];

        /**
         * Buscamos as configuracao dentro da comissao
         * nela temos a quantidade limite e a que ja foi usada dentro da competencia
         */
        foreach ($comissao->tipoSessao as $tipoSessao) {
            $sessoesLancadas = Sessao::select(["rh247_sequencial"])
                ->where("rh247_mes", "=", $competencia->getMes())
                ->where("rh247_ano", "=", $competencia->getAno())
                ->where("rh247_tiposessao", "=", $tipoSessao->rh249_tiposessao)
                ->where("rh247_comissao", "=", $comissaoRequest)
                ->get();
            $configuracao[TipoSessao::getDescricaoByTipo($tipoSessao->rh249_tiposessao)] = [
                "limite" => $tipoSessao->rh249_quantidade,
                "usado" => $sessoesLancadas->count()
            ];
        }

        foreach ($dados["sessoes"] as $sessao) {
            /**
             * Primeira coisa que faremos é, validar se a sessao poderá ser lançada
             * Em seguida verificamos as regras do servidor
             */
            $sessao["tiposessao"] = TipoSessao::getTipoByDescricao($sessao["tipo"]);
            // Validamos se o campo data veio preenchido do front
            if (empty($sessao["data"])) {
                $sessao["data"] = null;
            }

            /**
             * Caso possua id, é alteracao
             */
            if (!empty($sessao["id"])) {
                $sessaoSalvar = Sessao::with("servidores")->find($sessao["id"]);
                /**
                 * Validamos se a sessao ja foi processada, caso sim, nao fazemos nada
                 */
                if ($sessaoSalvar->rh247_processada == true) {
                    continue;
                }
                /**
                 * caso alteramos o tipo de sessao, validamos a disponibilidade do novo tipo
                 */
                if ($sessaoSalvar->rh247_tiposessao != $sessao["tiposessao"]) {
                    if ($configuracao[$sessao["tipo"]]["limite"] <= ($configuracao[$sessao["tipo"]]["usado"] + 1)) {
                        $mensagem = "Limite de sessões da competência "
                            . "{$competencia->getMes()}/{$competencia->getAno()} para o tipo de sessão "
                            . "'{$sessao['tipo']}' excedido. Por favor revise as informações corretamente.";
                        throw new Exception($mensagem);
                    }
                    /**
                     * Caso seja permitido a alterção, vamos alterar os valores da configuracao
                     */
                    $configuracao[$sessao["tipo"]]["usado"] += 1;
                    $configuracao[TipoSessao::getDescricaoByTipo($sessaoSalvar->rh247_tiposessao)]["usado"] -= 1;
                }
                /**
                 * Deletamos os servidores da sessao que será alterada
                 */
                $sessaoSalvar->servidores()->delete();

                /**
                 * Vamos salvar as alteracoes da sessao
                 */
                $sessaoSalvar->rh247_data = $sessao["data"];
                $sessaoSalvar->rh247_tiposessao = $sessao["tiposessao"];
                if (!$sessaoSalvar->update()) {
                    throw new \Exception("Ocorreu um erro ao salvar a Sessão {$sessaoSalvar->rh247_sequencial}.");
                }
            } else {
                /**
                 * Criamos uma nova sessao
                 */
                $sessaoSalvar = new Sessao();
                $sessaoSalvar->setComissao($comissaoRequest);
                $sessaoSalvar->setData($sessao['data']);
                $sessaoSalvar->setProcessada(false);
                $sessaoSalvar->setTiposessao($sessao["tiposessao"]);
                $sessaoSalvar->setMes($competencia->getMes());
                $sessaoSalvar->setAno($competencia->getAno());
                $sessaoSalvar->save();
            }
            /**
             * Apos incluir ou alterar a sessao, vamos adicionar os servidores
             */
            foreach ($sessao["matriculas"] as $matricula) {
                $servidor = ComissaoServidor::select('*')
                    ->where("rh245_anofim", ">=", $competencia->getAno())
                    ->where("rh245_matricula", "=", $matricula)
                    ->where("rh245_comissao", "=", $comissaoRequest)
                    ->where("rh245_ativo", "=", "true")->get()->first();
                /**
                 * Caso o servidor seja encontrado
                 */
                if (!empty($servidor)) {
                    /**
                     * validamos se o servidor esta no ultimo ano dentro da comissao
                     */
                    if ($servidor->rh245_anofim == $competencia->getAno()) {
                        $servidor->rh245_mesfim = 7;
                        // Verifica se a competencia é maior que a configuracao do servidor
                        if ($competencia->getMes() > $servidor->rh245_mesfim) {
                            $mensagem = "Servidor com periodo de vigência na comissão inferior a competência atual";
                            $aErros[$matricula][] = utf8_encode($mensagem);
                            continue;
                        }
                    }

                    /**
                     * Precisamos da Model para pegar a configuracao de limite maximo da funcao dentro da competencia
                     */
                    $comissaoFuncaoModel = ComissaoFuncao::getComissaoFuncaoByComissaoFuncao(
                        $comissaoRequest,
                        $servidor->rh245_funcao
                    );

                    /**
                     * Buscamos todas as sessoes do servidor dentro da competencia
                     */
                    $sessoesDoServidor = Sessao::getSessoesDoServidorPorCompetenciaComissaoFuncao(
                        $servidor->rh245_sequencial,
                        $competencia->getAno(),
                        $competencia->getMes()
                    );
                    /**
                     * Validacao de quantidades na competencia pela funcao do servidor
                     */
                    $quantidadeServidor = 0;
                    foreach ($sessoesDoServidor as $sessaoDoServidor) {
                        $quantidadeServidor += $sessaoDoServidor->quantidade;
                    }
                    if (($quantidadeServidor+1) > $comissaoFuncaoModel->getQuantidade()) {
                        $mensagem = "A matricula {$matricula}, ultrapassou a quantidade da função na competencia";
                        $aErros[$matricula][] = utf8_encode($mensagem);
                        continue;
                    }
                    $sessaoServidor = new SessaoServidor();
                    $sessaoServidor->setServidor($servidor->rh245_sequencial);
                    $sessaoSalvar->servidores()->save($sessaoServidor);
                }
            }
        }
        return $aErros;
    }
}
