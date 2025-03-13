<?php
namespace ECidade\Patrimonial\Compras\ProcessoAdministrativoEmpenho\Model;

class ProcessoAdministrativo
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var object
     */
    private $codigoAutorizacao;

    /**
     * @var int
     */
    private $numeroProcesso;

    public function __construct($codigoAutorizacao = null)
    {
        if ($codigoAutorizacao) {
            $dao = new \cl_empautorizaprocesso();
            $sql = $dao->sql_query_file(null, null, null, "e150_empautoriza = {$codigoAutorizacao}");
            $rs = $dao->sql_record($sql);

            if ($rs) {
                $this->sequencial = pg_fetch_result($rs, 0, 'e150_sequencial');
                $this->codigoAutorizacao = pg_fetch_result($rs, 0, 'e150_empautoriza');
                $this->numeroProcesso = pg_fetch_result($rs, 0, 'e150_numeroprocesso');
            }
        }
    }


    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return int
     */
    public function getCodigoAutorizacao()
    {
        return $this->codigoAutorizacao;
    }

    /**
     * @param int $codigoAutorizacao
     */
    public function setCodigoAutorizacao($codigoAutorizacao)
    {
        $this->codigoAutorizacao = $codigoAutorizacao;
    }

    /**
     * @return int
     */
    public function getNumeroProcesso()
    {
        return $this->numeroProcesso;
    }

    /**
     * @param int $numeroProcesso
     */
    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }

    public static function fromState(array $state)
    {
        $processoAdministrativo = new self();

        if (array_key_exists('e150_sequencial', $state)) {
            $processoAdministrativo->setSequencial((int) $state['e150_sequencial']);
        }

        if (array_key_exists('e150_empautoriza', $state)) {
            $processoAdministrativo->setCodigoAutorizacao((int) $state['e150_empautoriza']);
        }

        if (array_key_exists('e150_numeroprocesso', $state)) {
            $processoAdministrativo->setNumeroProcesso($state['e150_numeroprocesso']);
        }

        return $processoAdministrativo;
    }

    public function toArray()
    {
        $retorno = array(
            'e150_sequencial' => $this->getSequencial(),
            'e150_empautoriza' => $this->getCodigoAutorizacao(),
            'e150_numeroprocesso' => $this->getNumeroProcesso()
        );

        return $retorno;
    }
}
