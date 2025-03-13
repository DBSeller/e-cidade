<?php

namespace ECidade\Tributario\Issqn\Acao\Transicao\Entity;

use ECidade\Tributario\Issqn\Repository\IssbaseRepository;

abstract class AcaoBase
{
    protected $processo;

    protected $issbaseRepository;

    public function __construct($processo, IssbaseRepository $issbaseRepository)
    {
        $this->processo = $processo;
        $this->issbaseRepository = $issbaseRepository;
    }

    /**
     * @return \ECidade\Tributario\Issqn\Model\Issbase|null
     * @throws \BusinessException
     */
    protected function getIssbase()
    {
        $issbase = $this->issbaseRepository->findByProcesso($this->processo);

        if (empty($issbase)) {
            throw new \BusinessException("Não há inscrição criada para o processo " . $this->processo);
        }

        return $issbase;
    }
}
