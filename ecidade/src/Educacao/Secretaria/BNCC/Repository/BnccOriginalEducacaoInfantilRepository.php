<?php


namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\BnccOriginalEducacaoInfantil;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeEducacaoInfantil;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;
use Exception;

/**
 * Class BnccOriginalEducacaoInfantilRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class BnccOriginalEducacaoInfantilRepository extends Repository
{
    /**
     * @param string[] $campos
     * @param array $order
     * @return BnccOriginalEducacaoInfantil[]
     * @throws Exception
     */
    public function get($campos = ['*'], $order = [])
    {
        $campos = implode(', ', $campos);
        $order = !empty($order) ? implode(', ', $order) : null;

        $dao = new \cl_bncceducacaoinfantiloriginal();
        $sql = $dao->sql_query(null, $campos, $order, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar dados da Educação Infantil da BNCC.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $dados[] = BnccOriginalEducacaoInfantil::fromState($state);
        }

        return $dados;
    }

    /**
     * @param $camposExperiencia
     * @return $this
     */
    public function scopeCampoExperiencia($camposExperiencia)
    {
        $this->scopes['disciplina'] = "ed167_disciplina = '{$camposExperiencia}'";
        return $this;
    }

    /**
     * @param $faixaEtaria
     * @return $this
     */
    public function scopeFaixaEtaria($faixaEtaria)
    {
        $this->scopes['faixaEtaria'] = "ed167_faixa_etaria = '{$faixaEtaria}'";
        return $this;
    }

    /**
     * @param integer $ano
     * @param string[] $campos
     * @param array $order
     * @return BnccOriginalEducacaoInfantil[]
     * @throws Exception
     */
    public function getCompleto($ano, $campos = ['*'], $order = [])
    {
        $campos = implode(', ', $campos);
        $order = !empty($order) ? implode(', ', $order) : null;

        $dao = new \cl_bncceducacaoinfantiloriginal();
        $sql = $dao->sql_query_completa(null, $campos, $order, implode(' and ', $this->scopes), $ano);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar dados da Educação Infantil da BNCC.");
        }

        $dados = [];
        $configuracao = ParametrosGlobaisService::get();

        while ($state = pg_fetch_array($rs)) {
            $bnccOriginalEducacaoInfantil = BnccOriginalEducacaoInfantil::fromState($state);
            if (!empty($state['ed147_sequencial'])) {
                $habilidade = HabilidadeEducacaoInfantil::fromState($state);
                if ($configuracao->isReferencialCurricularEstadual()) {
                    $referencialRepository = new HabilidadeReferencialCurricularEstadualRepository();
                    $referencialRepository->scopeAno($ano)
                        ->scopeCodigoHabilidadeBNCC($habilidade->getCodigo());

                    $habilidade->setHabilidadesReferencialCurricular($referencialRepository->get());
                }
                $bnccOriginalEducacaoInfantil->setHabilidadeComentada($habilidade);
            }
            $dados[] = $bnccOriginalEducacaoInfantil;
        }

        return $dados;
    }
}
