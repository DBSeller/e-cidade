<?php


namespace ECidade\Educacao\Escola\Repository;

use cl_diario_classe_bncc_habilidade;
use ECidade\Educacao\Escola\Model\ConteudoDesenvolvido;
use ECidade\Educacao\Escola\Model\HabilidadeDesenvolvida;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;
use ECidade\Enum\Educacao\BNCC\TipoBaseCurricularEnum;
use Exception;

/**
 * Class HabilidadeDesenvolvidaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class HabilidadeDesenvolvidaRepository extends Repository
{
    /**
     * @return HabilidadeDesenvolvida[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_diario_classe_bncc_habilidade;
        $sql = $dao->sql_query(null, '*', 'ed156_habilidade', implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Habilidades Desenvolvidas.");
        }

        $habilidadeReferencialRepository = new HabilidadeDesenvolvidaReferencialRepository();
        $habilidades = [];
        while ($state = pg_fetch_array($rs)) {
            $habilidadeDesenvolvida = HabilidadeDesenvolvida::fromState($state);
            $configuracao = ParametrosGlobaisService::get();
            if ($configuracao->isReferencialCurricularEstadual()) {
                $habilidadesReferencial = $habilidadeReferencialRepository
                    ->scopeHabilidadeDesenvolvida($habilidadeDesenvolvida)
                    ->get();
                foreach ($habilidadesReferencial as $habilidadeReferencial) {
                    $habilidadeDesenvolvida->addHabilidadeReferencial($habilidadeReferencial);
                }
            }

            $habilidades[] = $habilidadeDesenvolvida;
        }

        return $habilidades;
    }

    /**
     * @param null $id
     * @return HabilidadeDesenvolvida
     * @throws Exception
     */
    public function find($id = null)
    {
        $dao = new cl_diario_classe_bncc_habilidade;
        $sql = $dao->sql_query($id, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Habilidades Desenvolvidas.");
        }

        return HabilidadeDesenvolvida::fromState(pg_fetch_array($rs));
    }

    /**
     * @param HabilidadeDesenvolvida $habilidade
     * @return HabilidadeDesenvolvida
     * @throws Exception
     */
    public function salvar(HabilidadeDesenvolvida $habilidade)
    {
        $dao = new cl_diario_classe_bncc_habilidade();
        $dao->ed156_diario_classe_bncc = $habilidade->getConteudoDesenvolvido()->getCodigo();
        $dao->ed156_bnccdisciplinas = $habilidade->getDisciplina()->getCodigo();
        $dao->ed156_habilidade = $habilidade->getCodigoHabilidade();

        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar habilidade desenvolvida.");
        }

        $habilidade->setCodigo($dao->ed156_codigo);
        return $habilidade;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function excluirByScope()
    {
        $dao = new cl_diario_classe_bncc_habilidade();
        $dao->excluir(null, implode(' and ', $this->scopes));
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir habilidade desenvolvida.");
        }

        $this->resetScopes();
        return true;
    }
    /**
     * @param Disciplina $disciplina
     * @return $this
     */
    public function scopeDisciplina(Disciplina $disciplina)
    {
        $this->scopes['disciplina'] = "ed156_bnccdisciplinas = {$disciplina->getCodigo()}";
        return $this;
    }

    /**
     * @param ConteudoDesenvolvido $conteudoDesenvolvido
     * @return HabilidadeDesenvolvidaRepository
     */
    public function scopeConteudoDesenvolvido(ConteudoDesenvolvido $conteudoDesenvolvido)
    {
        $this->scopes['conteudo'] = "ed156_diario_classe_bncc = {$conteudoDesenvolvido->getCodigo()}";
        return $this;
    }

    /**
     * @return HabilidadeDesenvolvidaRepository
     */
    public function scopeCodigoHabilidade($codigoHabilidade)
    {
        $this->scopes['conteudo'] = "ed156_habilidade = '{$codigoHabilidade}'";
        return $this;
    }
}
