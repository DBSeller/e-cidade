<?php

namespace App\Domain\Saude\Farmacia\Services;

use DateTime;
use Exception;
use stdClass;

class MedicamentoContinuadoService
{
    /**
     * Retorna um objeto contendo as informações de saldo, próxima retirada e margem do controlado, respectivamente.
     * @param integer $idCgs
     * @param integer $idMedicamento
     * @param DateTime $data
     * @param boolean $acumular indica se deve acumular o saldo contabilizando desde o inicio do periodo do continuado
     * @param boolean $isPeriodoFixo indica se data da próxima retirada deve ser fixa(à partir da data de inicio do
     * continuado) ou dinâmica(à partir da data da retirada).
     * @return stdClass
     * @throws Exception
     */
    public static function getInfoContinuado($idCgs, $idMedicamento, DateTime $data, $acumular, $isPeriodoFixo)
    {
        $infoContinuado = (object) [
            'saldo' => 0,
            'proximaRetirada' => null,
            'margem' => null
        ];

        $controlado = static::getContinuado($idCgs, $idMedicamento, $data);
        // Retorno um objeto default, caso não encontre registro do continuado
        if ($controlado === null) {
            return $infoContinuado;
        }
        $diasPrazo = $controlado->frequencia - $controlado->margem;
        $proximaRetirada = new DateTime($controlado->dataInicio->format('Y-m-d'));
        $proximaRetirada->modify("+{$diasPrazo} days");

        $infoContinuado->saldo = $controlado->quantidade;
        $infoContinuado->margem = $controlado->margem;

        $retiradas = static::getRetiradas($idCgs, $idMedicamento, $data, $controlado->dataInicio);
        // caso não possua retiradas, a data da próxima retirada é calculada em cima da informada no parâmetro
        if ($retiradas === null) {
            $infoContinuado->proximaRetirada = $data->modify("+{$diasPrazo} days");
            return $infoContinuado;
        }
        $saldoAcumulado = $controlado->quantidade;
        $quantidadeRetirada = 0;
        foreach ($retiradas as $retirada) {
            $saldoAcumulado -= $retirada->quantidade;
            if ($retirada->data->getTimestamp() >= $proximaRetirada->getTimestamp()) {
                $saldoAcumulado += $controlado->quantidade;
                $quantidadeRetirada = 0;
            }

            $proximaRetirada = $isPeriodoFixo ? $proximaRetirada : $retirada->data;
            $proximaRetirada->modify("+{$diasPrazo} days");
            $quantidadeRetirada += $retirada->quantidade;
        }

        if ($acumular) {
            $saldo = $saldoAcumulado;
        } else {
            $saldo = $controlado->quantidade - $quantidadeRetirada;
        }
        $saldo = $saldo > 0 ? $saldo : 0;

        if ($data->getTimestamp() >= $proximaRetirada->getTimestamp()) {
            if ($acumular) {
                $saldo += $controlado->quantidade;
            } else {
                $saldo = $controlado->quantidade;
            }
        }

        /**
         * caso a data de fim do controlado for menor que o prazo maximo da próxima retirada
         * então a data da próxima retirada deve ser nula
         */
        $prazoMax = $controlado->margem * 2;
        $dataMargem = new DateTime($proximaRetirada->format('Y-m-d'));
        $dataMargem->modify("+{$prazoMax} days");
        if ($controlado->dataFim !== null && $controlado->dataFim->getTimestamp() < $dataMargem->getTimestamp()) {
            $proximaRetirada = null;
        }

        $infoContinuado->saldo = $saldo;
        $infoContinuado->proximaRetirada = $proximaRetirada;
        return $infoContinuado;
    }

    /**
     * @param $idCgs
     * @param $idMedicamento
     * @param DateTime $data
     * @return object|null
     * @throws Exception
     */
    private static function getContinuado($idCgs, $idMedicamento, DateTime $data)
    {
        $dao = new \cl_far_controlemed();

        $campos = 'fa10_i_quantidade, fa10_i_margem, fa10_i_prazo, fa10_d_dataini, fa10_d_datafim';

        $where = [];
        $where[] = "fa11_i_cgsund = {$idCgs}";
        $where[] = "fa10_i_medicamento = {$idMedicamento}";
        $where[] = "fa10_d_dataini <= '{$data->format('Y-m-d')}'";
        $where[] = "(fa10_d_datafim >= '{$data->format('Y-m-d')}' or fa10_d_datafim is null)";
        $where = implode(' AND ', $where);

        $sql = $dao->sql_query('', $campos, '', $where);
        $rs = $dao->sql_record($sql);
        if (!$rs) {
            return null;
        }
        $dados = \db_utils::fieldsMemory($rs, 0);
        return (object)[
            'quantidade' => $dados->fa10_i_quantidade,
            'margem' => $dados->fa10_i_margem,
            'frequencia' => $dados->fa10_i_prazo,
            'dataInicio' => empty($dados->fa10_d_dataini) ? null : new DateTime($dados->fa10_d_dataini),
            'dataFim' => empty($dados->fa10_d_datafim) ? null : new DateTime($dados->fa10_d_datafim)
        ];
    }

    /**
     * @param $idCgs
     * @param $idMedicamento
     * @param DateTime $data
     * @param DateTime $dataInicioContinuado
     * @return array|null
     */
    private static function getRetiradas($idCgs, $idMedicamento, DateTime $data, DateTime $dataInicioContinuado)
    {
        $dao = new \cl_far_retiradaitens();
        $where = [];
        $where[] = "fa04_i_cgsund = {$idCgs}";
        $where[] = "fa06_i_matersaude = {$idMedicamento}";
        $where[] = "fa04_d_data <= '{$data->format('Y-m-d')}'";
        $where[] = "fa04_d_data >= '{$dataInicioContinuado->format('Y-m-d')}'";
        $where[] = 'not exists (
            select * from far_devolucaomed where fa23_i_retiradaitens = fa06_i_codigo and fa23_i_cancelamento = 1
        )';
        $where = implode(' AND ', $where);
        $sql = $dao->sql_query('', 'fa06_f_quant, fa04_d_data', 'fa04_d_data', $where);
        $rs = $dao->sql_record($sql);
        if (!$rs) {
            return null;
        }

        return \db_utils::makeCollectionFromRecord($rs, function ($retirada) {
            return (object)[
                'data' => new DateTime($retirada->fa04_d_data),
                'quantidade' => $retirada->fa06_f_quant
            ];
        });
    }
}
