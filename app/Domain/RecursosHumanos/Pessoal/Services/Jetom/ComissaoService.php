<?php
namespace App\Domain\RecursosHumanos\Pessoal\Services\Jetom;

use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Comissao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoFuncao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Sessao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\TipoSessao;
use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use Illuminate\Support\Facades\DB;

class ComissaoService
{

    public static function lancamentosServidorByComissao($request)
    {
        $competencia = CompetenciaHelper::get();
        $ano = $competencia->getAno();
        $mes = $competencia->getMes();
        if ($request->ano) {
            $ano = $request->ano;
        }
        if ($request->mes) {
            $mes = $request->mes;
        }

        $retorno = Comissao::with(
            ['servidores',
            'tipoSessao',
            'sessao' => function ($query) use ($ano, $mes) {
                return $query->where('rh247_ano', $ano)->where('rh247_mes', $mes);
            }
            ]
        )->find($request->id);

        // Adicionada regra de validacao de funcao
        if (!empty($request->funcao)) {
            foreach ($retorno->servidores as $key => $elemento) {
                $comissaoFuncaoModel = ComissaoFuncao::getComissaoFuncaoByComissaoFuncao(
                    $request->id,
                    $elemento->rh245_funcao
                );
                $sessoesDoServidor = Sessao::getSessoesDoServidorPorCompetenciaComissaoFuncao(
                    $elemento->rh245_sequencial,
                    $ano,
                    $mes
                );
                $retorno->servidores[$key]["limite"] = $comissaoFuncaoModel->getQuantidade();
                $lancamentos = [
                    "normal" => ["processado" => 0, "uso" => 0],
                    "extraordinaria" => ["processado" => 0, "uso" => 0],
                    "urgente" => ["processado" => 0, "uso" => 0],
                ];

                foreach ($sessoesDoServidor as $sessao) {
                    switch ($sessao->rh247_tiposessao) {
                        case TipoSessao::NORMAL:
                            if ($sessao->rh247_processada == true) {
                                $lancamentos["normal"]["processado"] = $sessao->quantidade;
                            } else {
                                $lancamentos["normal"]["uso"] = $sessao->quantidade;
                            }
                            break;
                        case TipoSessao::EXTRAORDINARIA:
                            if ($sessao->rh247_processada == true) {
                                $lancamentos["extraordinaria"]["processado"] = $sessao->quantidade;
                            } else {
                                $lancamentos["extraordinaria"]["uso"] = $sessao->quantidade;
                            }
                            break;
                        case TipoSessao::URGENTE:
                            if ($sessao->rh247_processada == true) {
                                $lancamentos["urgente"]["processado"] = $sessao->quantidade;
                            } else {
                                $lancamentos["urgente"]["uso"] = $sessao->quantidade;
                            }
                            break;
                    }
                }
                $retorno->servidores[$key]["lancamentos"] = $lancamentos;
            }
        }

        return $retorno;
    }
}
