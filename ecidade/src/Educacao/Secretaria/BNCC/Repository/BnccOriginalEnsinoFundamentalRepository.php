<?php

namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use cl_bnccensinofundamentaloriginal;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\BnccOriginalEnsinoFundamental;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadesEnsinoFundamental;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;
use Exception;

/**
 * Class BnccOriginalEnsinoFundamentalRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class BnccOriginalEnsinoFundamentalRepository extends Repository
{
    /**
     * @param string[] $campos
     * @param array $order
     * @return BnccOriginalEnsinoFundamental[]
     * @throws Exception
     */
    public function get($campos = ['*'], $order = [])
    {
        $campos = implode(', ', $campos);
        $order = !empty($order) ? implode(', ', $order) : null;

        $dao = new cl_bnccensinofundamentaloriginal();
        $sql = $dao->sql_query(null, $campos, $order, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar dados do Ensino Fundamental da BNCC.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $dados[] = BnccOriginalEnsinoFundamental::fromState($state);
        }

        return $dados;
    }

    /**
     * @param $disciplina
     * @return BnccOriginalEnsinoFundamentalRepository
     */
    public function scopeDisciplina($disciplina)
    {
        $this->scopes['disciplina'] = "ed166_disciplina = '{$disciplina}'";
        return $this;
    }

    /**
     * @param $unidadeTematica
     * @return BnccOriginalEnsinoFundamentalRepository
     */
    public function scopeUnidadeTematica($unidadeTematica)
    {
        $this->scopes['unidade_tematica'] = "ed166_unidade_tematica = '{$unidadeTematica}'";
        return $this;
    }

    /**
     * @param $objetoConhecimento
     * @return BnccOriginalEnsinoFundamentalRepository
     */
    public function scopeObjetoConhecimento($objetoConhecimento)
    {
        $this->scopes['objeto_conhecimento'] = "ed166_objeto_conhecimento = '{$objetoConhecimento}'";
        return $this;
    }

    /**
     * @param integer $ano
     * @param string[] $campos
     * @param array $order
     * @return BnccOriginalEnsinoFundamental[]
     * @throws Exception,
     */
    public function getCompleto($ano, $campos = ['*'], $order = [])
    {
        $campos = implode(', ', $campos);
        $order = !empty($order) ? implode(', ', $order) : null;

        $dao = new cl_bnccensinofundamentaloriginal();
        $sql = $dao->sql_query_completa(null, $campos, $order, implode(' and ', $this->scopes), $ano);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar dados do Ensino Fundamental da BNCC.");
        }

        $dados = [];
        $configuracao = ParametrosGlobaisService::get();
        while ($state = pg_fetch_array($rs)) {
            $bnccOriginalEnsinoFundamental = BnccOriginalEnsinoFundamental::fromState($state);

            if (!empty($state['ed148_sequencial'])) {
                $habilidade = HabilidadesEnsinoFundamental::fromState($state);
                if ($configuracao->isReferencialCurricularEstadual()) {
                    $referencialRepository = new HabilidadeReferencialCurricularEstadualRepository();
                    $referencialRepository->scopeAno($ano)
                        ->scopeCodigoHabilidadeBNCC($habilidade->getCodigo());

                    $habilidade->setHabilidadesReferencialCurricular($referencialRepository->get());
                }

                $bnccOriginalEnsinoFundamental->setHabilidadeComentada($habilidade);
            }
            $dados[] = $bnccOriginalEnsinoFundamental;
        }

        return $dados;
    }
}
