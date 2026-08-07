<?php


namespace ECidade\Educacao\Secretaria\BNCC\Service;

use ECidade\Educacao\Escola\Model\HabilidadeDesenvolvida;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\BNCC\Model\Etapa;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadesEnsinoFundamental;
use ECidade\Educacao\Secretaria\BNCC\Repository\HabilidadeEnsinoFundamentalRepository;
use ECidade\Educacao\Secretaria\Models\ParametrosGlobais;
use Exception;

class HabilidadeEnsinoFundamentalService
{
    /**
     * @var ParametrosGlobais
     */
    private $configuracao;

    /**
     * @var integer
     */
    private $ano;

    /**
     * HabilidadeEnsinoFundamentalService constructor.
     * @param ParametrosGlobais $configuracao
     * @param null $ano
     */
    public function __construct(ParametrosGlobais $configuracao, $ano = null)
    {
        $this->ano = $ano;
        if (is_null($ano)) {
            $this->ano = date('Y');
        }

        $this->configuracao = $configuracao;
    }

    /**
     * @param Disciplina $disciplina
     * @param Etapa[] $etapa
     * @return HabilidadesEnsinoFundamental[]
     * @throws Exception
     */
    public function buscarHabilidades(Disciplina $disciplina, array $etapa, $registroAula = null)
    {
        $repository = new HabilidadeEnsinoFundamentalRepository();

        return $repository->scopeDisciplinaBNCC($disciplina)
            ->scopeEtapaBNCC($etapa)
            ->scopeAno($this->ano)
            ->get(null, null, $registroAula);
    }


    /**
     * @param HabilidadeDesenvolvida $habilidadeDesenvolvida
     * @return HabilidadesEnsinoFundamental[]
     * @throws Exception
     */
    public function getHabilidade(HabilidadeDesenvolvida $habilidadeDesenvolvida)
    {
        $repository = new HabilidadeEnsinoFundamentalRepository();
        $habilidades = $repository->scopeHabilidadeDesenvolvida($habilidadeDesenvolvida)
            ->scopeAno($this->ano)
            ->get();

        return array_shift($habilidades);
    }
}
