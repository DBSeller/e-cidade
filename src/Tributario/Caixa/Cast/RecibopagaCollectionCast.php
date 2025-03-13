<?php

namespace ECidade\Tributario\Caixa\Cast;

use ECidade\Tributario\Caixa\Collection\RecibopagaCollection;
use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;
use ECidade\Tributario\Caixa\Entity\Debito;
use ECidade\Tributario\Caixa\Entity\Parcela;
use ECidade\Tributario\Caixa\Entity\Receita;

final class RecibopagaCollectionCast
{
    public function toDebitoCollection(RecibopagaCollection $recibopagaCollection)
    {
        $receitas = array();
        $parcelas = array();
        $debitos = array();

        foreach ($recibopagaCollection as $recibopaga) {

            $receita = new Receita();
            $receita->setCodigo($recibopaga->getReceit());
            $receita->setValor($recibopaga->getValor());

            $receitas[$recibopaga->getNumpre()][$recibopaga->getNumpar()][] = $receita;

            if (empty($parcelas[$recibopaga->getNumpre()][$recibopaga->getNumpar()])) {

                $parcela = new Parcela();
                $parcela->setNumero($recibopaga->getNumpar());
                $parcela->setVencimento($recibopaga->getDtvenc());

                $parcelas[$recibopaga->getNumpre()][$recibopaga->getNumpar()] = $parcela;
            }

            if (empty($debitos[$recibopaga->getNumpre()])) {

                $debito = new Debito();
                $debito->setNumpre($recibopaga->getNumpre());

                $debitos[$recibopaga->getNumpre()] = $debito;
            }
        }

        $debitoCollection = new DebitoCollection();

        foreach ($debitos as $numpre => $debito) {

            foreach ($parcelas[$numpre] as $numpar => $parcela) {

                foreach ($receitas[$numpre][$numpar] as $receita) {
                    $parcela->addReceita($receita);
                }

                $debito->addParcela($parcela);
            }

            $debitoCollection->add($debito);
        }

        return $debitoCollection;
    }
}
