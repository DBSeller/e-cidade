<?php
/**
 * Created by PhpStorm.
 * User: robson
 * Date: 2020-02-05
 * Time: 16:36
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

/**
 * Interface In22
 * @package ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22
 */
interface In22
{

    /**
     * @return mixed
     */
    public function processar();

    public function setPeriodo($codigoPeriodo);

    public function setAno($ano);

    public function setInstituicoes(array $instituicoes);

    public function setDataEmissao(\DBDate $dataEmissao);
}
