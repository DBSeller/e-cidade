<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbinome;

final class ItbinomeRepository
{
    /**
     * @var Itbinome
     */
    private $itbinome;

    public function __construct()
    {
        $this->itbinome = new Itbinome();
    }

    /**
     * Busca os dados com base no número da guia
     * @param $guia
     * @param false $principal
     * @return Itbinome[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getByGuia($guia, $principal = false, $campos = ["*"])
    {
        $oQuery = $this->itbinome->leftJoin(
            "itbinomecgm",
            "itbinomecgm.it21_itbinome",
            "=",
            "itbinome.it03_seq"
        )->leftJoin(
            "cgm",
            "cgm.z01_numcgm",
            "=",
            "itbinomecgm.it21_numcgm"
        )->where(
            "it03_guia",
            "=",
            $guia
        );

        if ($principal) {
            $oQuery->where(
                "it03_princ",
                "=",
                "t"
            );
        }

        $oItbinome = $oQuery->select($campos)->get();

        if (empty($oItbinome)) {
            throw new \Exception("Erro ao buscar o(s) transmitente(s) e o(s) adquirente(s)");
        }

        return $oItbinome;
    }

    public function salvar(Itbinome $entity)
    {
        $clitbinome = new \cl_itbinome();

        $clitbinome->it03_seq = $entity->getSeq();
        $clitbinome->it03_guia = $entity->getGuia();
        $clitbinome->it03_tipo = $entity->getTipo();
        $clitbinome->it03_princ = $entity->getPrinc();
        $clitbinome->it03_nome = $entity->getNome();
        $clitbinome->it03_sexo = $entity->getSexo();
        $clitbinome->it03_cpfcnpj = $entity->getCpfcnpj();
        $clitbinome->it03_endereco = $entity->getEndereco();
        $clitbinome->it03_numero = $entity->getNumero();
        $clitbinome->it03_compl = $entity->getCompl();
        $clitbinome->it03_cxpostal = $entity->getCxpostal();
        $clitbinome->it03_bairro = $entity->getBairro();
        $clitbinome->it03_munic = $entity->getMunic();
        $clitbinome->it03_uf = $entity->getUf();
        $clitbinome->it03_cep = $entity->getCep();
        $clitbinome->it03_mail = $entity->getMail();

        if (!empty($clitbinome->it03_seq)) {
            $clitbinome->alterar($clitbinome->it03_seq);
        } else {
            $clitbinome->incluir(null);
        }

        if ($clitbinome->erro_status == "0") {
            throw new \Exception($clitbinome->erro_msg);
        }

        return $clitbinome->it03_seq;
    }
}
