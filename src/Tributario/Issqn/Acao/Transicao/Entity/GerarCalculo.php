<?php

namespace ECidade\Tributario\Issqn\Acao\Transicao\Entity;

use ECidade\Configuracao\Workflow\Interfaces\Acao as AcaoInterface;
use ECidade\Tributario\Issqn\Repository\IssbaseRepository;
use ECidade\Tributario\Issqn\Inscricao\Service\Calculo as CalculoService;

final class GerarCalculo extends AcaoBase implements AcaoInterface
{
    private $calculoService;
    private $empresa;

    /**
     * GerarCalculo constructor.
     * @param $processo
     * @param IssbaseRepository $issbaseRepository
     * @param CalculoService $calculo
     */
    public function __construct($processo, IssbaseRepository $issbaseRepository, CalculoService $calculo)
    {
        parent::__construct($processo, $issbaseRepository);
        $this->calculoService = $calculo;
    }

    /**
     * @return bool
     * @throws \BusinessException
     * @throws \DBException
     */
    public function validate()
    {
        $issbase = $this->getIssbase();
        $empresa = new \Empresa($issbase->getInscr());
        $atividades = $empresa->getAtividades();

        if (empty($atividades)) {
            throw new \BusinessException("Não há atividades vinculadas a inscrição");
        }

        $this->empresa = $empresa;
        return true;
    }

    /**
     * @return string
     * @throws \BusinessException
     * @throws \DBException
     */
    public function run()
    {
        return $this->calculoService->execute($this->empresa);
    }
}
