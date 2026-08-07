<?php

namespace App\Domain\Saude\Farmacia\Builders;

use Illuminate\Support\Facades\DB;
use ECidade\Patrimonial\Material\Helpers\Material;
use App\Domain\Patrimonial\Material\Models\MaterialEstoqueItem;
use App\Domain\Patrimonial\Material\Models\MaterialLocalizacao;

class RastreabilidadeMedicamentoBuilder
{
    const MEDICAMENTO = 1;
    const DEPOSITO = 2;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $estoques;

    /**
     * @var integer
     */
    private $agrupamento;

    /**
     * @param \Illuminate\Database\Eloquent\Collection $estoques
     * @return RastreabilidadeMedicamentoBuilder
     */
    public function setEstoques(\Illuminate\Database\Eloquent\Collection $estoques)
    {
        $this->estoques = $estoques;
        return $this;
    }

    /**
     * @param integer $agrupamento
     * @return RastreabilidadeMedicamentoBuilder
     */
    public function setAgrupamento($agrupamento)
    {
        $this->agrupamento = $agrupamento;
        return $this;
    }

    /**
     * @return array
     */
    public function build()
    {
        if ($this->agrupamento == self::MEDICAMENTO) {
            return $this->buildAgrupamentoMedicamento();
        }

        if ($this->agrupamento == self::DEPOSITO) {
            return $this->buildAgrupamentoUnidade();
        }

        return $this->buildSemAgrupamento();
    }

    private function buildSemAgrupamento()
    {
        $dados = [];

        foreach ($this->estoques as $estoqueItem) {
            $dados[] = $this->buildObjeto($estoqueItem);
        }

        return $dados;
    }

    private function buildAgrupamentoMedicamento()
    {
        $dados = [];

        foreach ($this->estoques as $estoqueItem) {
            $estoque = $estoqueItem->estoque;
            if (!array_key_exists($estoque->m70_codmatmater, $dados)) {
                $dados[$estoque->m70_codmatmater] = (object)[
                    'id' => $estoqueItem->id_medicamento,
                    'descricao' => $estoqueItem->m60_descr,
                    'estoques' => []
                ];
            }

            $dados[$estoque->m70_codmatmater]->estoques[] = $this->buildObjeto($estoqueItem);
        }

        return $dados;
    }

    private function buildAgrupamentoUnidade()
    {
        $dados = [];

        foreach ($this->estoques as $estoqueItem) {
            $estoque = $estoqueItem->estoque;
            if (!array_key_exists($estoque->m70_coddepto, $dados)) {
                $dados[$estoque->m70_coddepto] = (object)[
                    'id' => $estoque->m70_coddepto,
                    'descricao' => $estoque->departamento->descrdepto,
                    'estoques' => []
                ];
            }

            $dados[$estoque->m70_coddepto]->estoques[] = $this->buildObjeto($estoqueItem);
        }

        return $dados;
    }

    private function buildObjeto(MaterialEstoqueItem $estoqueItem)
    {
        $estoque = $estoqueItem->estoque;
        $localizacao = $this->getLocalizacao($estoque->m70_codmatmater, $estoque->departamento->deposito->m91_codigo);
        $quantidade = $estoqueItem->m71_quant - $estoqueItem->m71_quantatend;
        $precoMedio = $this->getPrecoMedio($estoque->m70_codmatmater, $estoque->m70_coddepto);

        return (object)[
            'idMedicamento' => $estoqueItem->id_medicamento,
            'medicamento' => $estoqueItem->m60_descr,
            'unidadeMedida' => $estoque->material->unidade->m61_descr,
            'idDepartamento' => $estoque->m70_coddepto,
            'departamento' => $estoque->departamento->descrdepto,
            'localizacao' => !empty($localizacao) ? $localizacao : 'N/A',
            'lote' => !empty($estoqueItem->lote) ? $estoqueItem->lote : 'N/A',
            'validade' => !empty($estoqueItem->data_validade) ? db_formatar($estoqueItem->data_validade, 'd') : 'N/A',
            'quantidade' => $quantidade,
            'valor' => str_replace('.', ',', Material::arredondarQuantidade($quantidade * (float)$precoMedio))
        ];
    }

    private function getLocalizacao($idMaterial, $idDeposito)
    {
        return MaterialLocalizacao::select('m64_localizacao')
            ->material($idMaterial)
            ->deposito($idDeposito)
            ->get()
            ->implode('m64_localizacao', ', ');
    }

    private function getPrecoMedio($idMaterial, $idDepartamento)
    {
        return DB::table('material.matmaterprecomedio')
            ->select('m85_precomedio')
            ->where('m85_matmater', $idMaterial)
            ->where('m85_coddepto', $idDepartamento)
            ->orderByDesc('m85_data', 'm85_hora')
            ->first()
            ->m85_precomedio;
    }
}
