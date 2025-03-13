<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\CronogramaDesembolsoDespesa;
use App\Domain\Financeiro\Planejamento\Models\CronogramaDesembolsoReceita;
use App\Domain\Financeiro\Planejamento\Models\Valor;

class CronogramaDesembolsoService
{
    /**
     * @param $valor
     * @return float
     */
    public function getValorMensal($valor)
    {
        return round(($valor / 12), 2);
    }

    /**
     * @param $valorMensal
     * @param $valorTotal
     * @return float
     */
    public function getValorDezembro($valorMensal, $valorTotal)
    {
        $valorDezembro = $valorMensal;

        $validaDiferenca = $valorMensal * 12;
        if ($validaDiferenca > $valorTotal) {
            $valorDezembro -= ($validaDiferenca - $valorTotal);
        }

        if ($validaDiferenca < $valorTotal) {
            $valorDezembro += ($valorTotal - $validaDiferenca);
        }

        return $valorDezembro;
    }

    /**
     * @param CronogramaDesembolsoReceita|CronogramaDesembolsoDespesa $model
     * @param Valor $valor
     */
    public function updateRateioAutomatico($model, Valor $valor)
    {
        $this->salvar($model, $valor->pl10_valor, $valor->pl10_ano);
    }

    /**
     * @param CronogramaDesembolsoReceita|CronogramaDesembolsoDespesa $model
     * @param $valorTotal
     * @param $exercicio
     * @return mixed
     */
    protected function salvar($model, $valorTotal, $exercicio)
    {
        $valorMes = $this->getValorMensal($valorTotal);
        $valorDezembro = $this->getValorDezembro($valorMes, $valorTotal);

        $model->exercicio = $exercicio;
        $model->janeiro = $valorMes;
        $model->fevereiro = $valorMes;
        $model->marco = $valorMes;
        $model->abril = $valorMes;
        $model->maio = $valorMes;
        $model->junho = $valorMes;
        $model->julho = $valorMes;
        $model->agosto = $valorMes;
        $model->setembro = $valorMes;
        $model->outubro = $valorMes;
        $model->novembro = $valorMes;
        $model->dezembro = $valorDezembro;
        $model->save();
        return $model;
    }

    /**
     * @param CronogramaDesembolsoReceita|CronogramaDesembolsoDespesa $model
     * @param $dados
     */
    public function update($model, $dados)
    {
        $model->exercicio = $dados->exercicio;
        $model->janeiro = !empty($dados->janeiro) ? $dados->janeiro : 0;
        $model->fevereiro = !empty($dados->fevereiro) ? $dados->fevereiro : 0;
        $model->marco = !empty($dados->marco) ? $dados->marco : 0;
        $model->abril = !empty($dados->abril) ? $dados->abril : 0;
        $model->maio = !empty($dados->maio) ? $dados->maio : 0;
        $model->junho = !empty($dados->junho) ? $dados->junho : 0;
        $model->julho = !empty($dados->julho) ? $dados->julho : 0;
        $model->agosto = !empty($dados->agosto) ? $dados->agosto : 0;
        $model->setembro = !empty($dados->setembro) ? $dados->setembro : 0;
        $model->outubro = !empty($dados->outubro) ? $dados->outubro : 0;
        $model->novembro = !empty($dados->novembro) ? $dados->novembro : 0;
        $model->dezembro = !empty($dados->dezembro) ? $dados->dezembro : 0;
        $model->save();
    }

    /**
     * @param $model
     * @return mixed
     */
    protected function zeraValores($model)
    {
        $model->janeiro = 0;
        $model->fevereiro = 0;
        $model->marco = 0;
        $model->abril = 0;
        $model->maio = 0;
        $model->junho = 0;
        $model->julho = 0;
        $model->agosto = 0;
        $model->setembro = 0;
        $model->outubro = 0;
        $model->novembro = 0;
        $model->dezembro = 0;
        return $model;
    }
}
