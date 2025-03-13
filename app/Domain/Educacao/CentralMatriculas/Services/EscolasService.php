<?php

namespace App\Domain\Educacao\CentralMatriculas\Services;

use App\Domain\Educacao\CentralMatriculas\Models\Escola;
use ECidade\Educacao\MatriculaOnline\Registry\ConfiguracaoRegistry;
use ECidade\Educacao\MatriculaOnline\Registry\FaseRegistry;
use ECidade\Educacao\MatriculaOnline\Repository\ConfiguracaoEscolaRepository;
use ECidade\Educacao\MatriculaOnline\Repository\InscricaoRepository;
use ECidade\Enum\Educacao\MatriculaOnline\SituacoesListaEsperaEnum;
use ECidade\Educacao\MatriculaOnline\Repository\LimiteInscricoesRepository;
use ECidade\Educacao\MatriculaOnline\Repository\ListaEsperaRepository;
use EtapaRepository;
use Exception;

class EscolasService
{
    /**
     * @param $codigoEtapa
     * @param $fase
     * @return Escola[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function buscarEscolasDisponiveis($codigoEtapa, $fase)
    {
        $etapa = EtapaRepository::getEtapaByCodigo($codigoEtapa);
        $configuracao = ConfiguracaoRegistry::get();
        $configuracaoEscolaRepository = new ConfiguracaoEscolaRepository();

        if ($configuracao->isApenasEscolaComVagas()) {
            $configuracaoEscolaRepository->scopeEscolasComVaga($fase, $etapa);
        }
        $configuracaoEscolas = $configuracaoEscolaRepository
            ->scopeEtapa($etapa)
            ->getEscolasDisponiveisPorFase($fase);

        if ($configuracao->isValidaLimiteInscricoes()) {
            foreach ($configuracaoEscolas as $key => $escolaDisponivel) {
                $configuracaoEscolas[$key]->ultrapassouLimite = false;
                $limiteInscricoesRepository = new LimiteInscricoesRepository();
                $limiteInscricoes = $limiteInscricoesRepository->scopeConfiguracao($escolaDisponivel)->get();
                $limiteInscricoes = array_shift($limiteInscricoes);
                if (is_null($limiteInscricoes)) {
                    continue;
                }
                if (is_null($limiteInscricoes->getLimite())) {
                    continue;
                }
                $escolaDisponivel->setLimiteInscricoes($limiteInscricoes);
                $listaEsperaRepository = new ListaEsperaRepository();
                $listaEspera = $listaEsperaRepository
                ->scopeInFase(FaseRegistry::get($fase))
                ->scopeEscola($escolaDisponivel->getEscola())
                ->scopeEtapa($etapa)
                ->scopeTurno($escolaDisponivel->getTurno())
                ->scopeNaoAlocado()
                ->scopeSituacao(new SituacoesListaEsperaEnum(SituacoesListaEsperaEnum::ATIVA))
                ->get();
                if (count($listaEspera) >= $limiteInscricoes->getLimite()) {
                    $configuracaoEscolas[$key]->ultrapassouLimite = true;
                }
            }
        }

        $escolas = [];
        foreach ($configuracaoEscolas as $escolaDisponivel) {
            $escolas[] = $escolaDisponivel->getEscola()->getCodigo();
        }

        $escolasDisponiveis = Escola::with('bairro')
            ->with('bairrosAtendidos')
            ->orderBy('mo53_nome')
            ->findMany($escolas);

        return $escolasDisponiveis->map(function (Escola $escola) use ($configuracaoEscolas) {
            $turnos = [];
            foreach ($configuracaoEscolas as $configuracaoEscola) {
                if ($configuracaoEscola->getEscola()->getCodigo() == $escola->getCodigo()) {
                    $ultrapassouLimite = isset($configuracaoEscola->ultrapassouLimite) ?
                        $configuracaoEscola->ultrapassouLimite : false;
                    $turnos[] = [
                        "codigo" => $configuracaoEscola->getTurno()->getCodigoTurno(),
                        "descricao" => $configuracaoEscola->getTurno()->getDescricao(),
                        "indisponivel" => $ultrapassouLimite
                    ];
                }
            }

            usort($turnos, function ($a, $b) {
                return $a['codigo'] > $b['codigo'];
            });

            return [
                "codigo" => $escola->getCodigo(),
                "nome" => $escola->getNome(),
                "bairro" => $escola->getBairro()->getDescricao(),
                "turnos" => $turnos,
                "bairros_atendidos" => $escola->bairrosAtendidos->toArray()
            ];
        });
    }
}
