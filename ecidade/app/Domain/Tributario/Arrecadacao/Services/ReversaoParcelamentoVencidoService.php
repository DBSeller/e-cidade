<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use db_utils;
use ECidade\Tributario\Library\DataBase;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use ECidade\Tributario\Arrecadacao\Repository\Arrecad;
use ECidade\Tributario\Arrecadacao\Model\Arrecad as ModelArrecad;
use App\Domain\Tributario\Arrecadacao\Models\RegistroOriginalControleParcelamento;
use App\Domain\Tributario\Arrecadacao\Models\RegistroReversaoControleParcelamento;

class ReversaoParcelamentoVencidoService
{

    public function processar($numParcelamento)
    {
        $registrosOriginais = $this->buscarRegistrosOriginais($numParcelamento);

        if ($registrosOriginais->isEmpty()) {
            throw new \Exception('Não foram encontrados parcelamentos para este processamento.');
        }
      
        foreach ($registrosOriginais as $registroOriginal) {
            $daoArrecad = new \cl_arrecad;
            $where = [];
            $where[] = "k00_numpre = {$registroOriginal->ar51_numpre}";
            $where[] = "k00_numpar = {$registroOriginal->ar51_numpar}";
            $where[] = "k00_receit = {$registroOriginal->ar51_receit}";

            $where = implode(' AND ', $where);
            $sql = $daoArrecad->sql_query_file(null, '*', null, $where);
            $rs = $daoArrecad->sql_record($sql);

            $parcela = db_utils::fieldsMemory($rs, 0);
            $repository = new Arrecad(DataBase::getInstance(), new \cl_arrecad);
            $model = $repository->make($parcela);
        
            $this->salvarRollback($model);
            
            $model->setDataVencimento($registroOriginal->ar51_dtvenc);

            $repository->alterar($model, $where);
            $registroOriginal->delete();
        }
    }

    private function buscarRegistrosOriginais($numParcelamento)
    {
        $registroOriginal = RegistroOriginalControleParcelamento::select(['controleparc_registrosorig.*'])
            ->join('termo', 'v07_numpre', 'ar51_numpre')
            ->join('arrecad', function ($join) {
                $join->on('k00_numpre', 'ar51_numpre')
                        ->on('k00_numpar', 'ar51_numpar')
                        ->on('k00_receit', 'ar51_receit');
            })
            ->where('v07_parcel', $numParcelamento)
            ->get();

        return $registroOriginal;
    }

    private function salvarRollback(ModelArrecad $arrecad)
    {
        $registroRollback = new RegistroReversaoControleParcelamento;
        
        $registroRollback->ar52_dtrollback = date('Y-m-d');
        $registroRollback->ar52_numpre = $arrecad->getNumpre();
        $registroRollback->ar52_numpar = $arrecad->getNumpar();
        $registroRollback->ar52_receit = $arrecad->getReceita();
        $registroRollback->ar52_dtvenc = $arrecad->getDataVencimento();
        $registroRollback->ar52_usuario = db_getsession("DB_id_usuario");

        $registroRollback->save();
    }
}
