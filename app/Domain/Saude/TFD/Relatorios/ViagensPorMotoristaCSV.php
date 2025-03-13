<?php

namespace App\Domain\Saude\TFD\Relatorios;

use ECidade\File\Csv\Dumper\Dumper;
use App\Domain\Saude\TFD\Contracts\ViagensPorMotorista;

/**
 * Classe responsável por montar um relatório, em CSV, com os dados agrupados por motorista
 * @package App\Domain\Saude\TFD\Relatorios
 */
class ViagensPorMotoristaCSV extends Dumper implements ViagensPorMotorista
{
    /**
     * @var array $dados
     */
    private $dados;

    public function __construct(array $dados)
    {
        $this->setCsvControl(';', '"');
        $this->dados = $dados;
    }

    public function emitir($ordem)
    {
        $dadosImprimir = [$this->cabecalho($ordem)];

        foreach ($this->dados as $motorista) {
            foreach ($motorista->viagens as $viagem) {
                $dadosImprimir[] = $this->preparaDados($motorista, $viagem, $ordem);
            }
        }

        return $this->imprimir($dadosImprimir);
    }

    private function cabecalho($ordem)
    {
        switch ($ordem) {
            case self::ORDEM_DATA:
                return [
                    'id',
                    'nome',
                    'data',
                    'destino',
                    'veiculo',
                    'placa',
                    'passageiros',
                    'km'
                ];
                break;
            case self::ORDEM_VEICULO:
                return [
                    'id',
                    'nome',
                    'veiculo',
                    'destino',
                    'data',
                    'placa',
                    'passageiros',
                    'km'
                ];
                break;
            default:
                return [
                    'id',
                    'nome',
                    'destino',
                    'data',
                    'veiculo',
                    'placa',
                    'passageiros',
                    'km'
                ];
                break;
        }
    }

    private function preparaDados($motorista, $viagem, $ordem)
    {
        switch ($ordem) {
            case self::ORDEM_DATA:
                return [
                    $motorista->id,
                    $motorista->nome,
                    $viagem->data,
                    $viagem->destino,
                    $viagem->veiculo,
                    $viagem->placa,
                    $viagem->passageiros,
                    $viagem->km
                ];
                break;
            case self::ORDEM_VEICULO:
                return [
                    $motorista->id,
                    $motorista->nome,
                    $viagem->veiculo,
                    $viagem->destino,
                    $viagem->data,
                    $viagem->placa,
                    $viagem->passageiros,
                    $viagem->km
                ];
                break;
            default:
                return [
                    $motorista->id,
                    $motorista->nome,
                    $viagem->destino,
                    $viagem->data,
                    $viagem->veiculo,
                    $viagem->placa,
                    $viagem->passageiros,
                    $viagem->km
                ];
                break;
        }
    }

    private function imprimir($dados)
    {
        $fileName = 'tmp/viagens_por_motorista' . time() . '.csv';
        $this->dumpToFile($dados, $fileName);
        
        return [
            "name" => "Relatório de Viagens por Motorista",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }
}
