<?php

namespace ECidade\Tributario\Issqn\Inscricao\Service;

use ECidade\Tributario\Library\Service as BaseService;
use ECidade\Tributario\Issqn\Inscricao\Service\Procedure\CalculoIssqn;
use ECidade\Tributario\Library\Session;
use ECidade\Tributario\Issqn\Model\Issbase;
use \Empresa;

final class Calculo extends BaseService
{
    private $session;
    private $calculoIssqnProcedure;

    public function __construct(Session $session, CalculoIssqn $calculoIssqnProcedure)
    {
        $this->session = $session;
        $this->calculoIssqnProcedure = $calculoIssqnProcedure;
    }

    /**
     * @param Issbase $issbase
     * @return string
     * @throws \BusinessException
     * @throws \DBException
     */
    public function execute(Empresa $empresa)
    {
        if ($empresa->isParalisada()) {
            $erroMensagem = (object) array('iInscricao', $empresa->getInscricao());
            throw new \Exception(_M(Empresa::MENSAGENS . 'empresa_paralisada', $erroMensagem));
        }

        $ano = $this->session->get('DB_anousu');
        $instituicao = $this->session->get('DB_instit');
        $dataCalculo = $this->session->getData();

        $dataInicio = new \DateTime($empresa->getDataInicioAtividades()->getDate());

        if ((int)$dataInicio->format('Y') > (int)$ano) {
            $mensagem = "Empresa mais nova que ano do Calculo: \n Ano Inicio Empresa: ".$dataInicio->format('Y');
            throw new \BusinessException($mensagem);
        }

        return $this->calculoIssqnProcedure->execute($empresa, $dataCalculo, $instituicao, $ano);
    }
}
