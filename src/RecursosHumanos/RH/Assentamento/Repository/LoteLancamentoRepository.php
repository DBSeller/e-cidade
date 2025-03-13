<?php


namespace ECidade\RecursosHumanos\RH\Assentamento\Repository;

use AssentamentoRepository;
use cl_loteassentamento;
use cl_lotelancamento;
use DBDepartamento;
use ECidade\RecursosHumanos\RH\Assentamento\Model\LoteLancamento;
use Exception;
use TipoAssentamento;

class LoteLancamentoRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param $sequencial
     * @param string $operador
     * @return $this
     */
    public function scopeSequencial($sequencial, $operador = '=')
    {
        $this->scopes['sequencial'] = "h23_sequencial {$operador} {$sequencial}";
        return $this;
    }

    /**
     * @param string $stringData
     * @param string $operador
     * @return $this
     */
    public function scopeData($stringData, $operador = '=')
    {
        $this->scopes['data'] = "h23_data {$operador} {$stringData}";
        return $this;
    }

    /**
     * @param \Instituicao $instituicao
     * @param string $operador
     * @return $this
     */
    public function scopeInstituicao(\Instituicao $instituicao, $operador = '=')
    {
        $this->scopes['instituicao'] = "h23_instituicao {$operador} {$instituicao->getCodigo()}";
        return $this;
    }

    /**
     * @param DBDepartamento $departamento
     * @param string $operador
     * @return $this
     */
    public function scopeDepartamento(DBDepartamento $departamento, $operador = '=')
    {
        $this->scopes['departamento'] = "rh184_db_depart {$operador} {$departamento->getCodigo()}";
        return $this;
    }

    /**
     * @param TipoAssentamento
     * @param string $operador
     * @return $this
     */
    public function scopeTipo(TipoAssentamento $tipo, $operador = '=')
    {
        $this->scopes['tipo'] = "h23_tipoassentamento {$operador} {$tipo->getSequencial()}";
        return $this;
    }

    /**
     * @param LoteLancamento $loteLancamento
     * @return \stdClass
     * @throws Exception
     */
    public static function delete(LoteLancamento $loteLancamento, $assentamentoFuncional = false)
    {
        $dao = new cl_lotelancamento();
        $erros = array();
        foreach ($loteLancamento->getAssentamentos() as $indice => $assentamento) {
            try {
                if (!$assentamentoFuncional) {
                    AssentamentoRepository::excluiAssentamentoEfetividade($assentamento);
                } else {
                    AssentamentoRepository::excluir($assentamento);
                }
                $loteLancamento->unsetAssentamentoPorIndice($indice);
            } catch (Exception $exception) {
                $erros[$assentamento->getCodigo()] = (object) array (
                    'mensagem' => $exception->getMessage(),
                    'assentamento' => $assentamento
                );
            }
        }
        if (count($erros) === 0) {
            $dao->excluir($loteLancamento->getSequencial());
            if ($dao->erro_status === '0') {
                throw new Exception("Não foi possível excluir o lote de assentamentos.");
            }
        }

        return (object) array(
            'erros' => $erros,
            'loteLancamento' => $loteLancamento
        );
    }

    /**
     * @return LoteLancamento[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_lotelancamento();
        $sql = $dao->sql_query_lotes(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os lotes de assentamentos.");
        }

        $lotes = array();

        if (pg_num_rows($rs) === 0) {
            return $lotes;
        }

        while ($lote = pg_fetch_array($rs)) {
            $lotes[] = LoteLancamento::fromState($lote)->withAssentamentos();
        }

        return $lotes;
    }

    /**
     * @param $codigo
     * @return LoteLancamento
     * @throws Exception
     */
    public static function find($codigo)
    {
        $dao = new \cl_lotelancamento();
        $sql = $dao->sql_query_file($codigo);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar o lote solicitado.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return LoteLancamento::fromState($resultado)->withAssentamentos();
    }

    /**
     * @param \Assentamento $assentamento
     * @return LoteLancamento|null
     * @throws Exception
     */
    public static function getLotePorAssentamento(\Assentamento $assentamento)
    {
        $dao = new cl_lotelancamento();
        $sql = $dao->sql_query_lote_por_assentamento($assentamento->getCodigo());
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar os lote do assentamento.');
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        $codigo = pg_fetch_object($rs)->codigo;
        $loteLancamento = static::find($codigo);

        if (empty($loteLancamento)) {
            throw new Exception('Não foi possível buscar os lote do assentamento.');
        }

        return $loteLancamento;
    }

    public static function getAssentamentosPorLote(LoteLancamento $loteLancamento)
    {
        $dao = new cl_loteassentamento();
        $sql = $dao->sql_query_file(null, "*", null, "h24_lotelancamento = {$loteLancamento->getSequencial()}");
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar os assentamentos do lote.');
        }

        $assentamentos = array();

        if (pg_num_rows($rs) === 0) {
            return $assentamentos;
        }

        while ($assentamento = pg_fetch_object($rs)) {
            $assentamentos[] = \AssentamentoRepository::getInstanceByCodigo($assentamento->h24_assenta);
        }

        return $assentamentos;
    }

    public static function save(LoteLancamento $loteLancamento)
    {
        $dao = new \cl_lotelancamento();
        $dao->h23_data = $loteLancamento->getData()->format('Y-m-d');
        $dao->h23_sequencial = $loteLancamento->getSequencial();
        $dao->h23_instituicao = $loteLancamento->getInstituicao()->getCodigo();
        $dao->h23_tipoassentamento = $loteLancamento->getTipoAssentamento()->getSequencial();

        empty($dao->h23_sequencial) ? $dao->incluir(null) : $dao->alterar($loteLancamento->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception('Não foi possível salvar o lote de assentamentos.');
        }

        $loteLancamento->setSequencial($dao->h23_sequencial);

        $daoVinculo = new cl_loteassentamento();
        $assentamentos = $loteLancamento->getAssentamentos();

        $daoVinculo->excluir(null, "h24_lotelancamento = {$loteLancamento->getSequencial()}");

        if ($daoVinculo->erro_status === '0') {
            throw new Exception('Não foi possível salvar o assentamento dentro do lote.');
        }

        foreach ($assentamentos as $assentamento) {
            $daoVinculo->h24_assenta = $assentamento->getCodigo();
            $daoVinculo->h24_lotelancamento = $loteLancamento->getSequencial();
            $daoVinculo->h24_sequencial = null;
            $daoVinculo->incluir(null);
            if ($daoVinculo->erro_status === '0') {
                throw new Exception('Não foi possível salvar o assentamento dentro do lote.');
            }
        }

        return true;
    }

    public static function getSequenciaisLotePorLotacao($lotacaoCodigos)
    {
        $dao = new cl_lotelancamento();
        $sql = $dao->sql_query_lote_por_lotacao($lotacaoCodigos);
        $rs = db_query($sql);


        if (!$rs) {
            throw new Exception('Não foi possível buscar as lotações do usuário.');
        }

        $lotacao = array();

        if (pg_num_rows($rs) === 0) {
            return $lotacao;
        }

        for ($i = 0; $i < pg_num_rows($rs); $i++) {
            $sequenciais = \db_utils::fieldsMemory($rs, $i);
            $lotacao[] = $sequenciais->h23_sequencial;
        }
        return $lotacao;
    }

    public static function getSequenciaisEfetividade()
    {
        $dao = new cl_lotelancamento();
        $sql = $dao->sql_query_lote_assentamento_efetividade();
        $rs = db_query($sql);


        if (!$rs) {
            throw new Exception('Não foi possível buscar as lotações do usuário.');
        }

        $lotacao = array();

        if (pg_num_rows($rs) === 0) {
            return $lotacao;
        }

        for ($i = 0; $i < pg_num_rows($rs); $i++) {
            $sequenciais = \db_utils::fieldsMemory($rs, $i);
            $lotacao[] = $sequenciais->h23_sequencial;
        }

        return $lotacao;
    }

    public function scopeSequenciais($sequencial)
    {
        $this->scopes['sequencial'] = "h23_sequencial in ({$sequencial})";
        return $this;
    }
}
