<?php


namespace App\Domain\Financeiro\Tesouraria\Relatorios\Tef;

use ECidade\File\Csv\Dumper\Dumper;

class ListaMovimentosOperacoesCsv
{
    /**
     * @var array
     */
    private $data = [];

    public function setData(array $data)
    {
        $this->data = $data;
    }


    public function emitir()
    {
        $imprimir[] = [
            'Autorização',
            'NSU',
            'Cartão',
            'Data Venda',
            'Data Vencimento',
            'Parcela',
            'Total Parcela',
            'Valor Bruto',
            'Valor Desconto',
            'Valor Líquido'
        ];

        foreach ($this->data as $datum) {
            $imprimir[] = [
                $datum->numero_autorizacao,
                $datum->numero_cv,
                $datum->cartao,
                db_formatar($datum->data_venda, 'd'),
                db_formatar($datum->data_vencimento, 'd'),
                $datum->parcela,
                $datum->total_parcelas,
                $datum->valor_bruto,
                $datum->valor_descontos,
                $datum->valor_liquido,
            ];
        }

        $filename = sprintf('tmp/lista-movimentos-%s.csv', time());
        $dump = new Dumper();
        $dump->dumpToFile($imprimir, $filename);
        return [
            'csv' => $filename,
            'csvLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }
}
