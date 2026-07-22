<?php

namespace ECidade\Tributario\Caixa\Service\Procedure;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\Procedure;
use ECidade\Tributario\Library\Session;
use ECidade\Tributario\Caixa\Entity\Recibo;

final class ReciboProcedure extends Procedure
{
    private $session;

    public function __construct(DataBase $dataBase, Session $session)
    {
        parent::__construct($dataBase);

        $this->session = $session;
    }

    public function execute(Recibo $recibo)
    {
        $numpre = $recibo->getNumpre();
        $vencimento = $recibo->getVencimento()->format('Y-m-d');
        $ano = $this->session->getAno();

        $sql = "select * from fc_recibo({$numpre}, '{$vencimento}'::date,'{$vencimento}'::date, {$ano})";

        return $this->dataBase->execute($sql);
    }
}
