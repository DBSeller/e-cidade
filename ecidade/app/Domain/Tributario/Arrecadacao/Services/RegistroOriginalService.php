<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use ECidade\Tributario\Arrecadacao\Model\Arrecad;
use App\Domain\Tributario\Arrecadacao\Models\RegistroOriginalControleParcelamento;

class RegistroOriginalService
{
    public static function salvar(Arrecad $arrecad, $novaDataVencimento, $idAgendamento)
    {
        $registroOriginal = new RegistroOriginalControleParcelamento;
        
        if ($registroOriginal->where('ar51_numpre', $arrecad->getNumpre())
            ->where('ar51_numpar', $arrecad->getNumpar())
            ->where('ar51_receit', $arrecad->getReceita())
            ->get()
            ->isNotEmpty()
        ) {
            return false;
        }

        $registroOriginal->ar51_numpre = $arrecad->getNumpre();
        $registroOriginal->ar51_numpar = $arrecad->getNumpar();
        $registroOriginal->ar51_receit = $arrecad->getReceita();
        $registroOriginal->ar51_dtvenc = $arrecad->getDataVencimento();
        $registroOriginal->ar51_novadtvenc = $novaDataVencimento;
        $registroOriginal->ar51_id_agendamento = $idAgendamento;
        $registroOriginal->ar51_dtproc = date('Y-m-d');
        $registroOriginal->ar51_novadtvenc = $novaDataVencimento;

        $registroOriginal->save();

        return true;
    }
}
