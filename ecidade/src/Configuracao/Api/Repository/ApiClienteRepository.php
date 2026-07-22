<?php


namespace ECidade\Configuracao\Api\Repository;

use cl_api_clients;
use ECidade\Configuracao\Api\Models\ApiCliente;
use Exception;

class ApiClienteRepository
{
    /**
     * @var ApiClienteRepository
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
     * @return ApiClienteRepository
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @param string $nome
     * @return ApiClienteRepository
     */
    public function scopeNome($nome)
    {
        $this->scopes['nome'] = " db172_nome = '{$nome}' ";
        return $this;
    }

    /**
     * @return ApiCliente[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_api_clients();
        $sql = $dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar os os clientes cadastrados na API');
        }

        $registros = array();

        if (pg_num_rows($rs) === 0) {
            return $registros;
        }

        while ($registro = pg_fetch_array($rs)) {
            $registros[] = ApiCliente::fromState($registro);
        }

        return $registros;
    }

    /**
     * @param  $id
     * @return bool|ApiCliente
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_api_clients();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar o cliente cadastrado na API.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ApiCliente::fromState($resultado);
    }

    /**
     * @param ApiCliente $cliente
     * @return ApiCliente
     * @throws Exception
     */
    public static function save(ApiCliente $cliente)
    {
        $dao = new cl_api_clients();

        $dao->db172_sequencial = $cliente->getSequencial();
        $dao->db172_nome = $cliente->getNome();
        $dao->db172_descricao = $cliente->getDescricao();
        $dao->db172_chave = $cliente->getChave();
        $dao->db172_ultima_utilizacao = !is_null($cliente->getUltimaUtilizacao()) ?
            $cliente->getUltimaUtilizacao()->format('Y-m-d H:i:s')
            : null;

        empty($dao->db172_sequencial) ? $dao->incluir(null) : $dao->alterar($cliente->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception('Não foi possível salvar o o cliente.');
        }

        $cliente->setSequencial($dao->db172_sequencial);

        return $cliente;
    }
}
