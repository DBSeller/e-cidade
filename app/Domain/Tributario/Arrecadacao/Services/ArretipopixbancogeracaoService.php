<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use App\Domain\Tributario\Arrecadacao\Models\Arretipopix;
use App\Domain\Tributario\Arrecadacao\Models\Arretipopixasso;
use App\Domain\Tributario\Arrecadacao\Models\Arretipopixbancogeracao;

class ArretipopixbancogeracaoService
{
    public function save(Arretipopix $arretipopix, Arretipopixasso $arretipopixasso)
    {
        $this->validateSave($arretipopix, $arretipopixasso);

        $arretipopixbancogeracao = new Arretipopixbancogeracao();
        $arretipopixbancogeracao->k213_arretipopix = $arretipopix->codtipopix;
        $arretipopixbancogeracao->k213_arretipopixasso = $arretipopixasso->sequencial;
        $arretipopixbancogeracao->save();

        return $arretipopixbancogeracao;
    }

    public function delete(Arretipopixasso $arretipopixasso)
    {
        $arretipopixbancogeracao = Arretipopixbancogeracao::query()
                                                              ->where(
                                                                  "k213_arretipopixasso",
                                                                  $arretipopixasso->sequencial
                                                              )->first();

        $arretipopixbancogeracao->delete();
    }

    public function reorder(Arretipopix $arretipopix)
    {
        $arretipopixbancogeracaoList = Arretipopixbancogeracao::query()
                                                              ->where(
                                                                  "k213_arretipopix",
                                                                  $arretipopix->codtipopix
                                                              )
                                                              ->orderBy("k213_sequencial")
                                                              ->orderBy("k213_ordem_processamento")
                                                              ->get(["k213_sequencial", "k213_processando"]);

        foreach ($arretipopixbancogeracaoList as $key => $arretipopixbancogeracao) {
            $arretipopixbancogeracao->k213_ordem_processamento = $key + 1;
            $arretipopixbancogeracao->save();
        }
    }

    public function chooseBankToGeneratePix(Arretipopix $arretipopix, $incrementProcessedItem, $forceUseCurrentBank)
    {
        $quantityToProcess = 0;
        if ($arretipopix->qtdemissao) {
            $quantityToProcess = $arretipopix->qtdemissao;
        }

        $queryBuilder = Arretipopixbancogeracao::query();
        $queryBuilder->where("k213_arretipopix", $arretipopix->codtipopix);
        $queryBuilder->where("k213_processando", true);
        $currentBank = $queryBuilder->first();

        if ($currentBank) {
            if (!$forceUseCurrentBank) {
                if ($quantityToProcess > 0) {
                    if ($currentBank->k213_quantidade_processados >= $quantityToProcess) {
                        $currentBank = $this->chooseNextBank($arretipopix);
                    }
                } else {
                    $currentBank = $this->chooseNextBank($arretipopix);
                }
            }
        } else {
            $currentBank = $this->chooseNextBank($arretipopix);
        }

        if ($incrementProcessedItem) {
            $currentBank->k213_quantidade_processados++;
            $currentBank->save();
        }

        $arretipopixasso = Arretipopixasso::query()
                                          ->where("sequencial", $currentBank->k213_arretipopixasso)
                                          ->first();

        return $arretipopixasso->db90_codban;
    }

    private function chooseNextBank(Arretipopix $arretipopix)
    {
        $arretipopixbancogeracaoList = Arretipopixbancogeracao::query()
                                                   ->where("k213_arretipopix", $arretipopix->codtipopix)
                                                   ->orderBy("k213_ordem_processamento")
                                                   ->get([
                                                       "k213_sequencial",
                                                       "k213_processando",
                                                       "k213_ordem_processamento"
                                                   ])->toArray();

        $bankProcessingList = array_filter($arretipopixbancogeracaoList, function ($arretipopixbancogeracao) {
            return $arretipopixbancogeracao["k213_processando"];
        });

        if (count($bankProcessingList) > 0) {
            $bankProcessing = array_values($bankProcessingList)[0];

            $nextBankToProcessList = array_filter(
                $arretipopixbancogeracaoList,
                function ($arretipopixbancogeracao) use ($bankProcessing) {
                    return $arretipopixbancogeracao["k213_ordem_processamento"]
                                >
                            $bankProcessing["k213_ordem_processamento"];
                }
            );

            if (count($nextBankToProcessList) > 0) {
                $nextBankToProcess = array_values($nextBankToProcessList)[0];
            } else {
                $nextBankToProcess = $arretipopixbancogeracaoList[0];
            }
        } else {
            $nextBankToProcess = $arretipopixbancogeracaoList[0];
        }

        if (!$nextBankToProcess) {
            throw new \BusinessException("Nenhum banco encontrado para ser o próximo a processar.");
        }

        Arretipopixbancogeracao::query()
                               ->where("k213_arretipopix", $arretipopix->codtipopix)
                               ->update(["k213_processando" => false, "k213_quantidade_processados" => 0]);

        Arretipopixbancogeracao::query()
                               ->where("k213_sequencial", $nextBankToProcess["k213_sequencial"])
                               ->update(["k213_processando" => true]);

        return Arretipopixbancogeracao::query()
                                      ->where("k213_sequencial", $nextBankToProcess["k213_sequencial"])
                                      ->first();
    }

    private function validateSave(Arretipopix $arretipopix, Arretipopixasso $arretipopixasso)
    {
        if (!$arretipopix) {
            throw new \BusinessException("Informe o tipo de débito do PIX");
        }

        if (!$arretipopixasso) {
            throw new \BusinessException("Informe o banco associado ao tipo de débito");
        }
    }
}
