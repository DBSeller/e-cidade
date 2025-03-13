<?php


namespace ECidade\Financeiro\Orcamento\Service;

use Dotacao;
use ECidade\Financeiro\Orcamento\Recurso\Origem;
use Exception;

/**
 * Class LancarComlementoRecursoService
 * @package ECidade\Financeiro\Orcamento\Service
 */
class LancarComlementoService extends Origem
{
    /**
     * @param Dotacao $dotacao
     * @param $complemento
     * @return \ECidade\Financeiro\Orcamento\Recurso\Recurso
     * @throws Exception
     */
    public function identificaRecurso(Dotacao $dotacao, $complemento)
    {
        return RecursoService::identificaRecursoComplemento(
            $dotacao->getCodigo(),
            $dotacao->getAno(),
            $complemento
        );
    }

    /**
     * @param Dotacao $dotacao
     * @param integer $codigoAutorizacao
     * @param integer $complemento
     * @throws Exception
     */
    public function complementoAutorizacao(Dotacao $dotacao, $codigoAutorizacao, $complemento)
    {
        $recurso = $this->identificaRecurso($dotacao, $complemento);
        Origem::setAutorizacao($codigoAutorizacao, $recurso->getCodigo(), $recurso->getComplemento());
    }

    /**
     * @param Dotacao $dotacao
     * @param integer $codigoEmpenho
     * @param integer $complemento
     * @throws Exception
     */
    public function complementoEmpenho(Dotacao $dotacao, $codigoEmpenho, $complemento)
    {
        $recurso = $this->identificaRecurso($dotacao, $complemento);
        Origem::setEmpenho(
            $codigoEmpenho,
            $recurso->getCodigo(),
            $recurso->getComplemento(),
            db_getsession('DB_anousu')
        );
    }
}
