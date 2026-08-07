<?php

namespace ECidade\Financeiro\Empenho\Mapper;

use CgmJuridico;

class TiposNotasParaiba
{
    private $tiposNotas = [
        [
            "id" => 0,
            "label" => "Sem Nota Fiscal",
            "chave" => 0,
            "numero" => 0,
            "serie" => 0,
            "data" => 0,
            "valor" => 0
        ],
        [
            "id" => 1,
            "label" => "A Nota Fiscal Avulsa Eletrônica - Estadual (NFA-e)",
            "chave" => 1,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 2,
            "label" => "Nota Fiscal - Eletrônica - Estadual (NF-e)",
            "chave" => 1,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 3,
            "label" => "Nota Fiscal de Prestação de Serviços - Eletrônica",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 6,
            "label" => " Nota Fiscal de Prestação de Serviços - Papel",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 7,
            "label" => "Nota Fiscal Avulsa de Prestação de Serviços - Papel",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 8,
            "label" => "Nota Fiscal Avulsa de Prestação de Serviços - Eletrônica",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 13,
            "label" => "Bilhete de Passagem",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 15,
            "label" => "Conhecimento de Transporte de Cargas Eletrônico (Rodoviário, Ferroviário, Aquaviário ou Aéreo)
        CT-e",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 16,
            "label" => "Nota Fiscal/Conta de Energia Elétrica",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ],
        [
            "id" => 17,
            "label" => "Nota Fiscal/Conta de Comunicações ou de Telecomunicações",
            "chave" => 0,
            "numero" => 1,
            "serie" => 2,
            "data" => 1,
            "valor" => 1
        ]
    ];

    public function getTiposNotasSegundoRegras($cgnFisico, $elemento = null, $subelemento = null)
    {
        if (in_array($elemento, [30, 52])) {
            if ($elemento == 30 and in_array($subelemento, [98, 13]) && $cgnFisico) {
                return $this->getNotas([2, 3, 6, 7, 8]);
            } elseif ($elemento == 30 and in_array($subelemento, [98, 13]) && !$cgnFisico) {
                return $this->getNotas([1, 2, 3, 6, 7, 8]);
            }
            return $this->getNotas([0, 1, 2]);
        }

        if ($cgnFisico) {
            return $this->getNotas([0, 2, 3, 6, 7, 8, 13, 15, 16, 17]);
        }
        return $this->tiposNotas;
    }

    private function getNotas(array $array)
    {
        $tipos = [];
        foreach ($array as $codigo) {
            foreach ($this->tiposNotas as $tiposNota) {
                if ($tiposNota['id'] == $codigo) {
                    array_push($tipos, $tiposNota);
                }
            }
        }

        return $tipos;
    }

    public function getTipoByID($id)
    {
        foreach ($this->tiposNotas as $tiposNota) {
            if ($tiposNota['id'] == $id) {
                return $tiposNota;
            }
        }

        return false;
    }

    public function getTiposNotasCompativelComEmenho(\EmpenhoFinanceiro $empenho)
    {
        $cgm = $empenho->getCgm();

        $cgnFisico = true;
        if ($cgm instanceof CgmJuridico) {
            $cgnFisico = false;
        }

        $desdobramento = $empenho->getDesdobramento();
        $elemento = '';
        $subelemento = '';
        if ($desdobramento) {
            $elemento = substr($desdobramento->o56_elemento, 5, 2);
            $subelemento = substr($desdobramento->o56_elemento, 7, 2);
        }

        return $this->getTiposNotasSegundoRegras($cgnFisico, $elemento, $subelemento);
    }
}
