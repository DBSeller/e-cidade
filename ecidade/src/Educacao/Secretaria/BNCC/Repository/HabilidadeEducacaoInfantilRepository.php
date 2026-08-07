<?php


namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use cl_bncceducacaoinfantil;
use ECidade\Educacao\Escola\Model\HabilidadeDesenvolvida;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeEducacaoInfantil;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;
use ECidade\Enum\Educacao\BNCC\TipoBaseCurricularEnum;
use Exception;

/**
 * Class HabilidadeEducacaoInfantilRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class HabilidadeEducacaoInfantilRepository extends Repository
{
    /**
     * @var integer
     */
    private $ano;

    /**
     * @return HabilidadeEducacaoInfantil[]
     * @throws Exception
     */
    public function get($campos = ['*'])
    {
        $configuracao = ParametrosGlobaisService::get();

        if (!isset($this->scopes['ano'])) {
            $this->scopeAno(date('Y'));
        }

        $campos = implode(', ', $campos);
        $dao = new cl_bncceducacaoinfantil();
        $sql = $dao->sql_query(null, $campos, null, implode(' and ', $this->scopes));

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Não foi possível buscar as habilidades da Educação Infantil.");
        }

        $habilidades = [];
        while ($state = pg_fetch_array($rs)) {
            $habilidadeEducacaoInfantil = HabilidadeEducacaoInfantil::fromState($state);
            if ($configuracao->isReferencialCurricularEstadual()) {
                $referencialRepository = new HabilidadeReferencialCurricularEstadualRepository();
                $referencialRepository->scopeAno($this->ano)
                    ->scopeCodigoHabilidadeBNCC($habilidadeEducacaoInfantil->getCodigo());

                $habilidadeEducacaoInfantil->setHabilidadesReferencialCurricular($referencialRepository->get());
            }
            $habilidades[] = $habilidadeEducacaoInfantil;
        }

        return $habilidades;
    }

    /**
     * @param Disciplina $disciplina
     * @return $this
     */
    public function scopeDisciplinaBNCC(Disciplina $disciplina)
    {
        $this->scopes['disciplina'] = "trim(ed147_disciplina) = trim('{$disciplina->getNome()}')";
        return $this;
    }

    /**
     * @param HabilidadeDesenvolvida $habilidadeDesenvolvida
     * @return $this
     */
    public function scopeHabilidadeDesenvolvida(HabilidadeDesenvolvida $habilidadeDesenvolvida)
    {
        $this->scopes['habilidade'] = "trim(ed147_codigo) = trim('{$habilidadeDesenvolvida->getCodigoHabilidade()}')";
        return $this;
    }

    /**
     * @param $codigoHabilidade
     * @return $this
     */
    public function scopeHabilidade($codigoHabilidade)
    {
        $this->scopes['habilidade'] = "trim(ed147_codigo) = trim('{$codigoHabilidade}')";
        return $this;
    }

    /**
     * @param $ano
     * @return $this
     */
    public function scopeAno($ano)
    {
        $this->ano = $ano;
        $this->scopes['ano'] = "ed147_ano = {$ano}";
        return $this;
    }

    /**
     * @param HabilidadeEducacaoInfantil $habilidadeEducacaoInfantil
     * @throws Exception
     */
    public function salvar(HabilidadeEducacaoInfantil $habilidadeEducacaoInfantil)
    {
        $dao = new cl_bncceducacaoinfantil();
        $dao->ed147_sequencial = $habilidadeEducacaoInfantil->getId();
        $dao->ed147_disciplina = $habilidadeEducacaoInfantil->getDisciplina();
        $dao->ed147_faixa_etaria = $habilidadeEducacaoInfantil->getFaixaEtaria();
        $dao->ed147_codigo = $habilidadeEducacaoInfantil->getCodigo();
        $dao->ed147_habilidade = $habilidadeEducacaoInfantil->getHabilidade();
        $dao->ed147_ano = $habilidadeEducacaoInfantil->getAno();

        if (!empty($dao->ed147_sequencial)) {
            $dao->alterar($dao->ed147_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Habilidade Educação Infantil: ".$dao->erro_msg);
        }
    }

    /**
     * @param HabilidadeEducacaoInfantil $habilidadeEducacaoInfantil
     * @return bool
     * @throws Exception
     */
    public function excluir(HabilidadeEducacaoInfantil $habilidadeEducacaoInfantil)
    {
        $dao = new cl_bncceducacaoinfantil();
        $dao->ed147_sequencial = $habilidadeEducacaoInfantil->getId();
        $dao->excluir($dao->ed147_sequencial);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Habilidade Educação Infantil: ".$dao->erro_msg);
        }

        unset($habilidadeEducacaoInfantil);
        return true;
    }
}
