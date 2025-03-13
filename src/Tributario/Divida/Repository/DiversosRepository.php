<?php


namespace ECidade\Tributario\Divida\Repository;

use cl_diversos;
use DateTime;
use ECidade\Tributario\Divida\Interfaces\TermoRepositoryInterface;
use ECidade\Tributario\Divida\Model\Diversos;
use ECidade\Tributario\Divida\Termo\Termo;
use Exception;

class DiversosRepository implements TermoRepositoryInterface
{

    /**
     * @var DiversosRepository
     */
    protected static $instance;

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * DiversosRepository constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return DiversosRepository
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @param  $numpre
     * @param  string $operador
     * @return $this
     */
    public function scopeNumpre($numpre, $operador = '=')
    {
        $this->scopes['numpre'] = "dv05_numpre {$operador} {$numpre}";
        return $this;
    }

    /**
     * @return Diversos[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_diversos();
        $sql = $dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os débitos.");
        }

        $diversos = array();

        if (pg_num_rows($rs) === 0) {
            return $diversos;
        }

        while ($debito = pg_fetch_array($rs)) {
            $diversos[] = Diversos::fromState($debito);
        }

        return $diversos;
    }

    /**
     * @param  $codigo
     * @return bool|Diversos
     * @throws Exception
     */
    public static function find($codigo)
    {
        $dao = new cl_diversos();
        $sql = $dao->sql_query_file($codigo);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar o lote solicitado.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Diversos::fromState($resultado);
    }

    /**
     * @param  Diversos $diversos
     * @return bool
     * @throws Exception
     */
    public static function save(Diversos $diversos)
    {
        $dao = new cl_diversos();
        $dao->dv05_coddiver = $diversos->getCodigo();
        $dao->dv05_numcgm = $diversos->getCgm()->getCodigo();
        $dao->dv05_dtinsc = $diversos->getDataInscricao() ? $diversos->getDataInscricao()->format('Y-m-d') : null;
        $dao->dv05_exerc = $diversos->getExercicio();
        $dao->dv05_numpre = $diversos->getNumpre();
        $dao->dv05_vlrhis = $diversos->getValorHistorico();
        $dao->dv05_procdiver = $diversos->getProcesso();
        $dao->dv05_numtot = $diversos->getNumeroParcelas();

        $dao->dv05_privenc = $diversos->getPrimeiroVencimento() ?
                             $diversos->getPrimeiroVencimento()->format('Y-m-d') :
                             null;

        $dao->dv05_provenc = $diversos->getProximoVencimento() ?
                             $diversos->getProximoVencimento()->format('Y-m-d') :
                             null;

        $dao->dv05_diaprox = $diversos->getDiaProximoVencimento();
        $dao->dv05_oper = $diversos->getDataOperacao() ? $diversos->getDataOperacao()->format('Y-m-d') : null;
        $dao->dv05_valor = $diversos->getValorCorrigidoDebito();
        $dao->dv05_obs = $diversos->getObservacao();
        $dao->dv05_instit = $diversos->getInstituicao()->getCodigo();

        empty($dao->dv05_coddiver) ? $dao->incluir(null) : $dao->alterar($diversos->getCodigo());

        if ($dao->erro_status === '0') {
            throw new Exception('Não foi possível salvar o débito.');
        }

        $diversos->setCodigo($dao->dv05_coddiver);

        return true;
    }

    /**
     * @param  array $numpres
     * @param  Termo $parcelamento
     * @return mixed
     * @throws Exception
     */
    public function atualizarObservacaoOrigemPorNumpreAoAnular(array $numpres, Termo $parcelamento)
    {
        $this->scopeNumpre("(".implode(',', $numpres).")", 'in');
        $diversos = $this->get();

        $dataAtual = new DateTime();

        $observacoes = "Este débito fez parte do parcelamento {$parcelamento->getCodigo()},";
        $observacoes .= " anulado em {$dataAtual->format('d/m/Y')}";

        $dataUltimoPagamento = $parcelamento->getDataUltimoPagamento();
        if (!empty($dataUltimoPagamento)) {
            $observacoes .= ", com último pagamento em {$dataUltimoPagamento->format('d/m/Y')}.";
        } else {
            $observacoes .= '. Não houve parcelas pagas.';
        }

        foreach ($diversos as $debito) {
            $observacaoAtual = $debito->getObservacao();
            $debito->setObservacao("{$observacaoAtual}\n{$observacoes}");
            self::save($debito);
        }

        return true;
    }
}
