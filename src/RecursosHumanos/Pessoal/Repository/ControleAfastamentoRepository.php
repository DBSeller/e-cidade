<?php


namespace ECidade\RecursosHumanos\Pessoal\Repository;

use BusinessException;
use cl_controleafastamento;
use ECidade\RecursosHumanos\Pessoal\Model\ControleAfastamento;
use Exception;
use Instituicao;
use Rubrica;

class ControleAfastamentoRepository
{
    /**
     * @var cl_controleafastamento
     */
    private $dao;

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * ControleAfastamentoRepository constructor.
     * @param cl_controleafastamento $dao
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param int $tipoAfastamento
     * @param string $operator
     * @return $this
     */
    public function scopeTipoAfastamento($tipoAfastamento, $operator = '=')
    {
        $this->scopes['tipoAfastamento'] = "rh231_afastamento {$operator} {$tipoAfastamento}";
        return $this;
    }

    /**
     * @param Rubrica $rubrica
     * @param string $operator
     * @return $this
     */
    public function scopeRubrica(Rubrica $rubrica, $operator = '=')
    {
        $this->scopes['rubrica'] = "rh231_rubrica {$operator} {$rubrica->getCodigo()}";
        return $this;
    }

    /**
     * @param int $tabelaPrevidencia
     * @param string $operator
     * @return $this
     */
    public function scopeTabelaPrevidencia($tabelaPrevidencia, $operator = '=')
    {
        $this->scopes['tabelaPrevidencia'] = "rh231_tabelaprevidencia {$operator} {$tabelaPrevidencia}";
        return $this;
    }

    /**
     * @param Instituicao $instituicao
     * @param string $operator
     * @return $this
     */
    public function scopeInstituicao(Instituicao $instituicao, $operator = '=')
    {
        $this->scopes['instituicao'] = "rh231_instituicao {$operator} {$instituicao->getCodigo()}";
        return $this;
    }

    /**
     * @param $ano
     * @param string $operator
     * @return $this
     */
    public function scopeAno($ano, $operator = '=')
    {
        $this->scopes['ano'] = "rh231_ano {$operator} {$ano}";
        return $this;
    }

    /**
     * @param $mes
     * @param string $operator
     * @return $this
     */
    public function scopeMes($mes, $operator = '=')
    {
        $this->scopes['mes'] = "rh231_mes {$operator} {$mes}";
        return $this;
    }

    /**
     * @return $this
     */
    public function scopeRubricaAtiva()
    {
        $this->scopes['rubricaAtiva'] = "rh27_ativo = 't'";
        return $this;
    }

    /**
     * @return $this
     */
    public function scopeRubricaProporcionalizaAfastamento()
    {
        $this->scopes['rubricaProporcionaliza'] = "rh27_calcp = 't'";
        return $this;
    }

    /**
     * @param Instituicao $instituicaoRubrica
     * @param string $operator
     * @return $this
     */
    public function scopeInstituicaoRubrica(Instituicao $instituicaoRubrica, $operator = '=')
    {
        $this->scopes['rubricaInstituicao'] = "rh27_instit = {$instituicaoRubrica->getCodigo()}";
        return $this;
    }

    /**
     * @param array $columns
     * @return ControleAfastamento[]
     * @throws BusinessException
     */
    public function all($columns = array('*'))
    {
        $sql = $this->dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $controlesAfastamentos = array();

        if (pg_num_rows($rs) === 0) {
            return $controlesAfastamentos;
        }

        while ($controleAfastamento = pg_fetch_array($rs)) {
            $pensoesAlimenticias[] = ControleAfastamento::fromState($controleAfastamento);
        }

        return $controlesAfastamentos;
    }

    /**
     *
     */
    private function resetScopes()
    {
        $this->scopes = array();
    }

    /**
     * @return ControleAfastamento[]
     * @throws Exception
     */
    public function get()
    {
        $sql = $this->dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os vínculos dos afastamentos com as rúbricas.");
        }

        $controlesAfastamentos = array();

        if (pg_num_rows($rs) === 0) {
            return $controlesAfastamentos;
        }

        while ($controleAfastamento = pg_fetch_array($rs)) {
            $controlesAfastamentos[] = ControleAfastamento::fromState($controleAfastamento);
        }

        $this->resetScopes();

        return $controlesAfastamentos;
    }

    /**
     * @param ControleAfastamento $controleAfastamento
     * @return ControleAfastamento
     * @throws Exception
     */
    public function save(ControleAfastamento $controleAfastamento)
    {
        $this->dao->rh231_sequencial = $controleAfastamento->getSequencial();
        $this->dao->rh231_afastamento = $controleAfastamento->getAfastamento();
        $this->dao->rh231_rubrica = $controleAfastamento->getRubrica()->getCodigo();
        $this->dao->rh231_tabelaprevidencia = $controleAfastamento->getTabelaPrevidencia();
        $this->dao->rh231_instituicao = $controleAfastamento->getInstituicao()->getCodigo();
        $this->dao->rh231_ano = $controleAfastamento->getAno();
        $this->dao->rh231_mes = $controleAfastamento->getMes();

        if (is_null($this->dao->rh231_sequencial)) {
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($this->dao->rh231_sequencial);
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar os vinculos de afastamento e rubricas.");
        }

        $controleAfastamento->setSequencial($this->dao->rh231_sequencial);

        return $controleAfastamento;
    }

    /**
     * @param int $afastamento
     * @param int $codigoTabela
     * @param Instituicao $instituicao
     * @param int $ano
     * @param int $mes
     * @return bool
     * @throws Exception
     */
    public function removeVinculoAfastamento($afastamento, $codigoTabela, Instituicao $instituicao, $ano, $mes)
    {
        $where = array(
            "rh231_afastamento = {$afastamento}",
            "rh231_tabelaprevidencia = {$codigoTabela}",
            "rh231_instituicao = {$instituicao->getCodigo()}",
            "rh231_ano = {$ano}",
            "rh231_mes = {$mes}"
        );

        $this->dao->excluir(null, implode(' AND ', $where));

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível remover os vinculos de afastamentos e rubricas.");
        }

        return true;
    }

    /**
     * @param array $controleAfastamentosAtualizados
     * @return ControleAfastamento[]
     * @throws Exception
     */
    public function saveAll(array $controleAfastamentosAtualizados)
    {
        $controleAfastamentos = array();
        foreach ($controleAfastamentosAtualizados as $controleAfastamentosAtualizado) {
            $controleAfastamentos[] = $this->save($controleAfastamentosAtualizado);
        }

        return $controleAfastamentos;
    }

    /**
     * @param Instituicao $instituicao
     * @param $ano
     * @param $mes
     * @return bool
     * @throws Exception
     */
    public function excluirControleAfastamentoPorCompetencia(Instituicao $instituicao, $ano, $mes)
    {
        $where = array(
            "rh231_instituicao = {$instituicao->getCodigo()}",
            "rh231_ano = {$ano}",
            "rh231_mes = {$mes}"
        );

        $this->dao->excluir(null, implode(" AND ", $where));

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível remover os vinculos de afastamentos e rubricas.");
        }

        return true;
    }
}
