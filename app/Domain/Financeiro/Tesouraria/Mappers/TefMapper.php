<?php


namespace App\Domain\Financeiro\Tesouraria\Mappers;

class TefMapper
{
    private $keyCollumn = 10;

    private $colluns = [
        2 => 'Vencimento',
        7 => 'Parcela',
        8 => 'Total Parcela',
        9 => 'Cartão',
        10 => 'Autorização',
        11 => 'Número cv',
        13 => 'Data da venda',
        14 => 'Valor original',
        15 => 'Valor bruto',
        16 => 'Descontos',
        17 => 'Líquido',
    ];

    private $fromTo = [
        10 => 'numero_autorizacao',
        11 => 'numero_cv',
         9 => 'cartao',
        13 => 'data_venda',
         2 => 'data_vencimento',
         7 => 'parcela',
         8 => 'total_parcelas',
        14 => 'valor_original',
        15 => 'valor_bruto',
        16 => 'valor_descontos',
        17 => 'valor_liquido',
    ];

    public function remapAuto($data)
    {
        $colunas = [
            'numero_autorizacao' => "AUTORIZAÇÃO",
            'numero_cv' => "NÚMERO CV",
            'cartao' => "CARTÃO",
            'data_venda' => "DATA DA VENDA",
            'data_vencimento' => "VENCIMENTO",
            'parcela' => "PARCELA",
            'total_parcelas' => "TOTAL DE PARCELAS",
            'valor_original' => "VALOR ORIGINAL",
            'valor_bruto' => "VALOR BRUTO",
            'valor_descontos' => "DESCONTOS",
            'valor_liquido' => "LÍQUIDO"
        ];

        $aAuxiliar = array_flip($colunas);

        $this->fromTo = array();

        foreach ($data as $indice => $dado) {
            if (in_array($dado, $colunas)) {
                $this->fromTo[$indice] = $aAuxiliar[$dado];
            }
        }
        //se não encontrou o nome da coluna no arquivo comparado com o array de colunas
        if (count($this->fromTo) != count($colunas)) {
            throw new \Exception("Numero de colunas do arquivo difere do e-cidade.");
        }
    }

    public function parse($data, array $cabecalho)
    {
        $parseLine = [];
        $this->remapAuto($cabecalho);

        foreach ($this->fromTo as $key => $collumn) {
            $value = $data[$key];

            if (in_array($key, [2, 13])) {
               //removi a formatacao de data daqui e coloquei no LinhaTefRepository,
               //antes de dar ->incluir ele formata as datas
               //$value = \DBDate::format($value, \DBDate::DATA_EN);
            }

            $parseLine[$collumn] = $value;
        }

        return $parseLine;
    }

    public function getCollumnKey()
    {
        return $this->keyCollumn;
    }
}
