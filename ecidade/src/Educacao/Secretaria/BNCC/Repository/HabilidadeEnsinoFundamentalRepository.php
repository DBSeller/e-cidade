<?php


namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use cl_bnccensinofundamental;
use ECidade\Educacao\Escola\Model\HabilidadeDesenvolvida;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\BNCC\Model\Etapa;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadesEnsinoFundamental;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;
use Exception;

/**
 * Class HabilidadeEnsinoFundamentalRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class HabilidadeEnsinoFundamentalRepository extends Repository
{
    /**
     * @var integer
     */
    private $ano;

    /**
     * @var Etapa[]
     */
    private $etapas = [];

    /**
     * @return HabilidadesEnsinoFundamental[]
     * @throws Exception
     */
    public function get($campos = null, $order = null, $registroAula = null)
    {
        $campos = !is_null($campos) ? implode(', ', $campos) : '*';
        $order = !empty($order) ? implode(', ', $order) : 1;
        $configuracao = ParametrosGlobaisService::get();
    
        if (!isset($this->scopes['ano'])) {
            $this->scopeAno(date('Y'));
        }
        $dao = new cl_bnccensinofundamental();
        $sql = $dao->sql_query(null, $campos, $order, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Não foi possível buscar as habilidades do Ensino Fundamental.");
        }

        $habilidades = [];

        if (is_null($registroAula)) {
            while ($state = pg_fetch_array($rs)) {
                $habilidade = HabilidadesEnsinoFundamental::fromState($state);
                if ($configuracao->isReferencialCurricularEstadual()) {
                    $referencialRepository = new HabilidadeReferencialCurricularEstadualRepository();
                    $referencialRepository->scopeAno($this->ano)
                        ->scopeCodigoHabilidadeBNCC($habilidade->getCodigo());
                    if (!empty($this->etapas)) {
                        $referencialRepository->scopeEtapaBNCC($this->etapas);
                    }
    
                    $habilidade->setHabilidadesReferencialCurricular($referencialRepository->get());
                }
                $habilidades[] = $habilidade;
            }
        } else {
            while ($state = pg_fetch_array($rs)) {
                $habilidade = HabilidadesEnsinoFundamental::fromState($state);
                if ($configuracao->isReferencialCurricularEstadual()) {
                    $referencialRepository = new HabilidadeReferencialCurricularEstadualRepository();
                    $referencialRepository->scopeAno($this->ano)
                        ->scopeCodigoHabilidadeBNCC($habilidade->getCodigo());
                    if (!empty($this->etapas)) {
                        $referencialRepository->scopeEtapaBNCC($this->etapas);
                    }
    
                    $habilidade->setHabilidadesReferencialCurricular($referencialRepository
                        ->scopeObjetoConhecimento($habilidade->getObjetoConhecimento())
                        ->get());
                    if (!empty($habilidade->getHabilidadesReferencialCurricular())) {
                        $habilidades[] = $habilidade;
                    }
                } else {
                    $habilidades[] = $habilidade;
                }
            }
        }

        return $habilidades;
    }

    /**
     * @return HabilidadesEnsinoFundamental|null
     * @throws Exception
     */
    public function first()
    {
        $habilidades = $this->get();
        return array_shift($habilidades);
    }

    /**
     * @param HabilidadesEnsinoFundamental $habilidadeEnsinoFundamental
     * @return HabilidadesEnsinoFundamental
     * @throws Exception
     */
    public function salvar(HabilidadesEnsinoFundamental $habilidadeEnsinoFundamental)
    {
        $dao = new cl_bnccensinofundamental();
        $dao->ed148_sequencial = $habilidadeEnsinoFundamental->getId();
        $dao->ed148_disciplina = pg_escape_string($habilidadeEnsinoFundamental->getDisciplina());
        $dao->ed148_etapa = pg_escape_string($habilidadeEnsinoFundamental->getEtapa());
        $dao->ed148_codigo = pg_escape_string($habilidadeEnsinoFundamental->getCodigo());
        $dao->ed148_unidade_tematica = pg_escape_string($habilidadeEnsinoFundamental->getUnidadeTematica());
        $dao->ed148_objeto_conhecimento = pg_escape_string($habilidadeEnsinoFundamental->getObjetoConhecimento());
        $dao->ed148_habilidade = pg_escape_string($habilidadeEnsinoFundamental->getHabilidade());
        $dao->ed148_ano = pg_escape_string($habilidadeEnsinoFundamental->getAno());

        if (empty($dao->ed148_sequencial)) {
            $dao->incluir($dao->ed148_sequencial);
        } else {
            $dao->alterar($dao->ed148_sequencial);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar habilidade do ensino fundamental. {$dao->erro_msg}");
        }

        $habilidadeEnsinoFundamental->setId($dao->ed148_sequencial);
       
        return $habilidadeEnsinoFundamental;
    }

    /**
     * @param HabilidadesEnsinoFundamental $habilidadeEnsinoFundamental
     * @return bool
     * @throws Exception
     */
    public function excluir(HabilidadesEnsinoFundamental $habilidadeEnsinoFundamental)
    {
        $dao = new cl_bnccensinofundamental();
        $dao->ed148_sequencial = $habilidadeEnsinoFundamental->getId();
        $dao->excluir($dao->ed148_sequencial);
        if ($dao->erro_status === 0) {
            throw new Exception("Erro ao excluir Habilidade do Ensino Fundamental: ".$dao->erro_banco);
        }
        unset($habilidadeEnsinoFundamental);
        return true;
    }

        /**
     * @param HabilidadesEnsinoFundamental $habilidadeEnsinoFundamental
     * @return bool
     * @throws Exception
     */
    public function editarObjetoConhecimento($novoNome, $where)
    {
        $dao = new cl_bnccensinofundamental();
        $dao->ed148_objeto_conhecimento = pg_escape_string($novoNome);
        $dao->alterarObjetoConhecimento(null, $where);

        if ($dao->erro_status === 0) {
            throw new Exception("Erro ao editar Objeto de Conhecimento: ".$dao->erro_banco);
        }
        return true;
    }

/**
 * @return bool
 * @throws Exception
 */
    public function excluirObjetoConhecimento($objeto)
    {
        $dao = new cl_bnccensinofundamental();
        $dao->ed148_objeto_conhecimento = pg_escape_string($objeto);
        $dao->excluirObjetoConhecimento($objeto);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Objeto de Conhecimento: ".$dao->erro_banco);
        }
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function temVinculoDiarioDeClasse($habilidades)
    {
        $dao = new cl_bnccensinofundamental();
        if ($dao->temVinculoDiarioDeClasse($habilidades)) {
            return true;
        }
        return false;
    }

    /**
     * @param Disciplina $disciplina
     * @return $this
     */
    public function scopeDisciplinaBNCC(Disciplina $disciplina)
    {
        $disciplina = trim(pg_escape_string($disciplina->getNome()));
        $this->scopes['disciplina'] = "trim(ed148_disciplina) = '{$disciplina}'";
        return $this;
    }

    /**
     * @param $disciplina
     * @return $this
     */
    public function scopeDisciplina($disciplina)
    {
        $disciplina = trim(pg_escape_string($disciplina));
        $this->scopes['disciplina'] = "trim(ed148_disciplina) = '{$disciplina}'";
        return $this;
    }

    /**
     * @param Etapa[] $etapas
     * @return $this
     */
    public function scopeEtapaBNCC(array $etapas)
    {
        $this->etapas = $etapas;
        $scopes = [];
        foreach ($etapas as $etapa) {
            $scopes[] = "trim(ed148_etapa) ilike '%{$etapa->getEtapa()}%'";
        }
        $this->scopes['etapa'] = '('.implode(' or ', $scopes) .')';
        return $this;
    }

    /**
     * @param HabilidadeDesenvolvida $habilidadeDesenvolvida
     * @return $this
     */
    public function scopeHabilidadeDesenvolvida(HabilidadeDesenvolvida $habilidadeDesenvolvida)
    {
        $habilidadeDesenvolvida = pg_escape_string($habilidadeDesenvolvida->getCodigoHabilidade());
        $this->scopes['habilidade'] = "trim(ed148_codigo) = trim('{$habilidadeDesenvolvida}')";
        return $this;
    }

    /**
     * @param integer $ano
     * @return $this
     */
    public function scopeAno($ano)
    {
        $this->ano = $ano;
        $this->scopes['ano'] = "ed148_ano = {$ano}";
        return $this;
    }

    /**
     * @param string $codigo
     * @return $this
     */
    public function scopeHabilidade($codigo)
    {
        $codigo = trim(pg_escape_string($codigo));
       
        $this->scopes['habilidade'] = "trim(ed148_codigo) = trim('{$codigo}')";
        return $this;
    }

    /**
     * @param string $codigo
     * @return $this
     */
    public function scopeObjetoConhecimento($obj)
    {
        $obj = trim(pg_escape_string($obj));
        $this->scopes['objeto_conhecimento'] = "trim(ed148_objeto_conhecimento) = trim('{$obj}')";
        return $this;
    }

     /**
     * @param $unidadeTematica
     * @return HabilidadeEnsinoFundamentalRepository
     */
    public function scopeUnidadeTematica($unidadeTematica)
    {
        $unidadeTematica = trim(pg_escape_string($unidadeTematica));
        $this->scopes['unidade_tematica'] = "ed148_unidade_tematica = '{$unidadeTematica}'";
        return $this;
    }

    /**
     * @return HabilidadeEnsinoFundamentalRepository|void
     */
    public function resetScopes()
    {
        parent::resetScopes();
        $this->ano = null;
        $this->etapas = [];
        return $this;
    }
}
