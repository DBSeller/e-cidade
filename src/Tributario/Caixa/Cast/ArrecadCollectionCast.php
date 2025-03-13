<?php

namespace ECidade\Tributario\Caixa\Cast;

use ECidade\Tributario\Caixa\Collection\ArrecadCollection;
use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;
use ECidade\Tributario\Caixa\Entity\Debito;
use ECidade\Tributario\Caixa\Entity\Parcela;
use ECidade\Tributario\Caixa\Entity\Receita;

final class ArrecadCollectionCast
{
    public function toDebitoCollection(ArrecadCollection $arrecadCollection)
    {
        $receitas = array();
        $parcelas = array();
        $debitos = array();

        foreach ($arrecadCollection as $arrecad) {
            $receita = new Receita();
            $receita->setCodigo($arrecad->getReceit());
            $receita->setValor($arrecad->getValor());

            $receitas[$arrecad->getNumpre()][$arrecad->getNumpar()][] = $receita;

            if (empty($parcelas[$arrecad->getNumpre()][$arrecad->getNumpar()])) {
                $parcela = new Parcela();
                $parcela->setNumero($arrecad->getNumpar());
                $parcela->setVencimento($arrecad->getDtvenc());

                $parcelas[$arrecad->getNumpre()][$arrecad->getNumpar()] = $parcela;
            }

            if (empty($debitos[$arrecad->getNumpre()])) {
                $debito = new Debito();
                $debito->setNumpre($arrecad->getNumpre());
                $debito->setTipo($arrecad->getTipo());

                $debitos[$arrecad->getNumpre()] = $debito;
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
