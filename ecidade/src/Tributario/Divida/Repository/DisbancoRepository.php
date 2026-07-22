<?php

namespace ECidade\Tributario\Divida\Repository;

use ECidade\Tributario\Caixa\Model\Arrepaga;
use ECidade\Tributario\Divida\Model\Disbanco;
use cl_disbanco;
use ECidade\Tributario\Divida\Termo\Termo;
use Exception;

class DisbancoRepository
{

    /**
     * @var DisbancoRepository
     */
    private static $instance;

    /**
     * @var array
     */
    private $scopes;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return Disbanco[]
     */
    public function get()
    {
        $dao = new cl_disbanco();
        $where = implode(' AND ', $this->scopes);
        $disbancos = array();

        $sql = $dao->sql_query_file(null, '*', null, $where);

        $rs = db_query($sql);
        $resultados = pg_fetch_all($rs);

        foreach ($resultados as $disbanco) {
            $disbancos[] = Disbanco::fromState($disbanco);
        }

        return $disbancos;
    }

    private function scope($id, $campo, $operacao, $valor)
    {
        $this->scopes[$id] = "{$campo} {$operacao} {$valor}";
        return $this;
    }

    /**
     * Filtra por 'idret'
     *
     * @param mixed $idRet
     * @param string $operacao
     * @return DisbancoRepository
     */
    public function scopeIdRet($idRet, $operacao = '=')
    {
        return $this->scope('idret', 'idret', $operacao, $idRet);
    }

    /**
     * Fltra por 'k00_numpre'
     *
     * @param mixed $numpre
     * @param string $operacao
     * @return DisbancoRepository
     */
    public function scopeNumpre($numpre, $operacao)
    {
        return $this->scope('k00_numpre', 'k00_numpre', $operacao, $numpre);
    }

    /**
     * @param Arrepaga $arrepaga
     * @return Disbanco|null
     * @throws Exception
     */
    public function disbancoPorArrepaga(Arrepaga $arrepaga)
    {
        $dao = new cl_disbanco();
        $sql = $dao->sql_query_disbanco_por_arrepaga($arrepaga->getNumpre(), $arrepaga->getNumeroParcela());

        $rs = db_query($sql);

        if (!$rs) {
            $mensagem = "Não foi possível buscar as informações de pagamento do pagamento de" .
                        "numpre '{$arrepaga->getNumpre()}', parcela {$arrepaga->getNumeroParcela()}";
            throw new Exception($mensagem);
        }

        if (pg_num_rows($rs) <= 0) {
            return null;
        }

        $disbanco = pg_fetch_array($rs);
        return Disbanco::fromState($disbanco);
    }


    public function disbancoMaisRecentePorParcelamento(Termo $parcelamento)
    {
        $dao = new cl_disbanco();

        $sql = $dao->sql_query_disbanco_mais_recente_por_parcelamento($parcelamento->getCodigo());
        $res = db_query($sql);

        if (!$res) {
            throw new Exception(
                "Não foi possível buscar o pagamento mais recente do parcelamento {$parcelamento->getCodigo()}."
            );
        }

        if (pg_num_rows($res) === 0) {
            return null;
        }

        $disbanco = pg_fetch_array($res);
        return Disbanco::fromState($disbanco);
    }
}
