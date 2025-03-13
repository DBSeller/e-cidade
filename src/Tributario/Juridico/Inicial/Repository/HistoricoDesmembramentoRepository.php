<?php
namespace ECidade\Tributario\Juridico\Inicial\Repository;

use ECidade\Tributario\Juridico\Inicial\HistoricoDesmembramento;
use Exception;

/**
 * Class HistoricoDesmembramentoRepositorio
 */
class HistoricoDesmembramentoRepository extends AbstractQuery
{
    /**
     * @var string
     */
    protected $table = 'desmembramentoinicialhistorico';

    /**
     * @param HistoricoDesmembramento $historicoDesmembramento
     * @return bool
     * @throws Exception
     */
    public function inserir(HistoricoDesmembramento $historicoDesmembramento)
    {
        return $this->insert($historicoDesmembramento->toArray());
    }

    /**
     * @param $certidao
     * @return bool
     * @throws Exception
     */
    public function hasHistory($certidao)
    {
        $hasHistory = $this
            ->where('v37_cda', '=', $certidao)
            ->get();

        return $hasHistory ? true : false;
    }

    /**
     * @param $initial
     * @return \stdClass|bool
     * @throws Exception
     */
    public function getHistoryByInitial($initial)
    {
        return $this
            ->where('v37_inicial', '=', $initial)
            ->orWhere('v37_inicial_old', '=', $initial)
            ->first();
    }
}
