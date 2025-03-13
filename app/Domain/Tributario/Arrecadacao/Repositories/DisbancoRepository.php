<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Disbanco;

class DisbancoRepository
{
    /**
     * @var Disbanco
     */
    private $disbanco;

    public function __construct()
    {
        $this->disbanco = new Disbanco();
    }

    /**
     * Busca o debito baixado com base no numpre e numpar
     * @param $numpre
     * @param $numpar
     * @return \_db_fields|\stdClass
     * @throws \Exception
     */
    public function getBaixaByNumpreNumpar($numpre, $numpar)
    {
        $cl_disbanco = new \cl_disbanco();

        $rDisbanco = $cl_disbanco->sql_record(
            $cl_disbanco->sql_queryDadosBaixa(
                $numpre,
                $numpar
            )
        );

        if (!$rDisbanco) {
            throw new \Exception("Erro ao buscar os dados da baixa do débito.");
        }

        return \db_utils::fieldsMemory($rDisbanco, 0);
    }

    public function salvar(Disbanco $entity)
    {
        $cl_disbanco = new \cl_disbanco();

        $cl_disbanco->k00_numbco  = $entity->getNumbco();
        $cl_disbanco->k15_codbco = $entity->getCodbco();
        $cl_disbanco->k15_codage  = $entity->getCodage();
        $cl_disbanco->codret = $entity->getCodret();
        $cl_disbanco->dtarq  = $entity->getDtarq();
        $cl_disbanco->dtpago  = $entity->getDtpago();
        $cl_disbanco->vlrpago = $entity->getVlrpago();
        $cl_disbanco->vlrjuros = $entity->getVlrjuros();
        $cl_disbanco->vlrmulta = $entity->getVlrmulta();
        $cl_disbanco->vlracres = $entity->getVlracres();
        $cl_disbanco->vlrdesco = $entity->getVlrdesco();
        $cl_disbanco->vlrtot = $entity->getVlrtot();
        $cl_disbanco->cedente  = $entity->getCedente();
        $cl_disbanco->vlrcalc = $entity->getVlrcalc();
        $cl_disbanco->idret = $entity->getIdret();
        $cl_disbanco->classi = $entity->isClassi();
        $cl_disbanco->k00_numpre = $entity->getNumpre();
        $cl_disbanco->k00_numpar = $entity->getNumpar();
        $cl_disbanco->convenio  = $entity->getConvenio();
        $cl_disbanco->instit = $entity->getInstit();
        $cl_disbanco->dtcredito  = $entity->getDtcredito();
        $cl_disbanco->bancopagamento  = $entity->getBancopagamento();
        $cl_disbanco->agenciapagamento = $entity->getAgenciapagamento();

        $cl_disbanco->incluir(null);

        if ($cl_disbanco->erro_status == "0") {
            throw new \Exception("Erro ao inserir os dados na tabela disbanco.\\n{$cl_disbanco->erro_msg}");
        }
    }
}
