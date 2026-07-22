<?php

namespace App\Domain\Tributario\Cadastro\Services;

use App\Domain\Tributario\Cadastro\Models\Bairro;
use App\Domain\Tributario\Cadastro\Repositories\IptubaseRepository;
use App\Domain\Tributario\Cadastro\Repositories\LocalidaderuralRepository;
use App\Domain\Tributario\Cadastro\Repositories\SetorregimovelRepository;
use App\Domain\Tributario\Cadastro\Traits\Logradouro;

final class CadastroService
{
    use Logradouro;

    public function getDadosRegImovByMatric($matricula, $matriculaAtiva = true)
    {
        $iptubaseRepository = new IptubaseRepository();

        $oDadosMatric = $iptubaseRepository->getDadosRegImovByMatric($matricula);

        if ($matriculaAtiva && !empty($oDadosMatric->j01_baixa)) {
            throw new \Exception("A matrícula {$matricula} está baixada.");
        }

        $nLados = ($oDadosMatric->j36_testad) ? ($oDadosMatric->j34_area / $oDadosMatric->j36_testad) : 0;

        return [
            "setorBairro" => $oDadosMatric->j34_setor,
            "logradouro" => $oDadosMatric->j14_nome,
            "numero" => $oDadosMatric->j39_numero,
            "quadra" => $oDadosMatric->j34_quadra,
            "complemento" => $oDadosMatric->j39_compl,
            "lote" => $oDadosMatric->j34_lote,
            "areaTotal" => $oDadosMatric->j34_area,
            "frente" => $oDadosMatric->j36_testad,
            "fundos" => $oDadosMatric->j36_testad,
            "ladoDireito" => round($nLados, 4),
            "ladoEsquerdo" => round($nLados, 4),
            "setorRi" => intval($oDadosMatric->j04_setorregimovel),
            "quadraRi" => $oDadosMatric->j04_quadraregimo,
            "loteRi" => $oDadosMatric->j04_loteregimo,
            "matriculaRi" => $oDadosMatric->j04_matricregimo
        ];
    }

    public function getSetorRegImoveis()
    {
        $setorregimovelRepository = new SetorregimovelRepository();
        $aSetor = $setorregimovelRepository->get()->toArray();

        return array_map(function ($aSetor) {
            return (object) [
                "codigo" => $aSetor["j69_sequencial"],
                "descricao" => $aSetor["j69_descr"]
            ];
        }, $aSetor);
    }

    public function getLocalidadeRural()
    {
        $localidaderuralRepository = new LocalidaderuralRepository();
        $aLocalidade = $localidaderuralRepository->getAll()->toArray();

        return array_map(function ($aLocalidade) {
            return [
                "codigo" => $aLocalidade["j137_sequencial"],
                "descricao" => $aLocalidade["j137_descricao"]
            ];
        }, $aLocalidade);
    }

    public function getBairros()
    {
        $aCampos = [
            "j13_codi as codigo",
            "j13_descr as descricao",
            "j13_codant as codigoAnterior",
            "j13_rural as rural"
        ];

        return Bairro::all($aCampos)->toArray();
    }

    public function getLogradouros($iCep = null, $iBairro = null)
    {
        $aCampos = [
            "j14_codigo as codigo_logradouro",
            \DB::raw("j88_descricao||' '||j14_nome as logradouro"),
            "j13_codi as codigo_bairro",
            "j13_descr as bairro",
            "j29_cep as cep"
        ];

        $oQuery = $this->getLogradouro();

        if (!empty($iCep)) {
            return $oQuery->where("j29_cep", $iCep)->first($aCampos)->toArray();
        }

        if (!empty($iBairro)) {
            $oQuery->where("j13_codi", "=", $iBairro);
        }

        return $oQuery->get($aCampos)->toArray();
    }
}
