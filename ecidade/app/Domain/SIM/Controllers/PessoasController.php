<?php

namespace App\Domain\SIM\Controllers;

use App\Domain\Patrimonial\Protocolo\Model\Cgm;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PessoasController extends Controller
{
    private $queryCgm;

    public function __construct()
    {
        $this->queryCgm = Cgm::query()
            ->orderBy('z01_nomecomple')
            ->limit(200);
    }

    /**
     * @param $nome
     * @param int $tp_pesquisa
     * @return JsonResponse
     * @throws Exception
     */
    public function pesquisaPorNome($nome, $tp_pesquisa = 0)
    {
        $nome = utf8_decode($nome);

        if (strlen($nome) < 2) {
            return response()->json([
                "error" => true,
                "message" => utf8_encode("Nome deve ter no mínimo 2 caracteres"),
                "status" => 400,
            ], 400);
        }

        switch ($tp_pesquisa) {
            case 1:
                $this->queryCgm->whereRaw("to_ascii(z01_nomecomple) ilike to_ascii('{$nome}%')");
                break;
            case 2:
                $this->queryCgm->whereRaw("to_ascii(z01_nomecomple) ilike to_ascii('%{$nome}%')");
                break;
            default:
                $this->queryCgm->whereRaw("z01_nomecomple = '{$nome}'");
        }
        $this->queryCgm->whereRaw("length(z01_cgccpf) <= 11");

        $response = $this->makeResponse($this->queryCgm->get());

        return response()->json($response);
    }

    public function pesquisaPorCpf($cpf)
    {
        $this->queryCgm->whereRaw("z01_cgccpf = '{$cpf}'");
        $response = $this->makeResponse($this->queryCgm->get());

        return response()->json($response);
    }

    public function pesquisaPorRg($rg)
    {
        $this->queryCgm->whereRaw("z01_ident = '{$rg}'");
        $response = $this->makeResponse($this->queryCgm->get());

        return response()->json($response);
    }

    public function pesquisaEndereco($numcgm)
    {
        $response = (object)[
            "enderecospessoa" => [],
            "qtd_registro" => 0
        ];

        $cgm = Cgm::find($numcgm);
        $data_atualizacao = $cgm->z01_ultalt?date('Y-m-d H:i:s', strtotime($cgm->z01_ultalt)):date('Y-m-d H:i:s');
        $response->enderecospessoa[] = (object)[
            "data_endereco" => $data_atualizacao,
            "tp_endereco" => "PRINCIPAL",
            "txt_endereco" => sprintf(
                "%s %s %s %s %s %s %s",
                utf8_encode(trim($cgm->z01_ender)),
                utf8_encode(trim($cgm->z01_numero)),
                utf8_encode(trim($cgm->z01_compl)),
                utf8_encode(trim($cgm->z01_bairro)),
                utf8_encode(trim($cgm->z01_munic)),
                utf8_encode(trim($cgm->z01_uf)),
                utf8_encode(trim($cgm->z01_cep))
            )
        ];

        DB::table("proprietario")
            ->select('*')
            ->where('z01_numcgm', '=', $numcgm)
            ->limit(200)
            ->get()
            ->map(function ($endereco) use (&$response) {
                $response->enderecospessoa[] = (object)[
                    "data_endereco" => date('Y-m-d H:i:s'),
                    "tp_endereco" =>utf8_encode("Carnê IPTU"),
                    "txt_endereco" => sprintf(
                        "%s %s %s %s %s %s %s",
                        utf8_encode(trim($endereco->z01_ender)),
                        utf8_encode(trim($endereco->z01_numero)),
                        utf8_encode(trim($endereco->z01_compl)),
                        utf8_encode(trim($endereco->z01_bairro)),
                        utf8_encode(trim($endereco->z01_munic)),
                        utf8_encode(trim($endereco->z01_uf)),
                        utf8_encode(trim($endereco->z01_cep))
                    )
                ];
            });

        $response->qtd_registro = count($response->enderecospessoa);
        return response()->json($response);
    }

    /**
     * @param $pessoas
     * @return object
     */
    private function makeResponse($pessoas)
    {
        $response = (object)[
            "pessoas" => [],
            "qtd_registros" => 0
        ];

        $response->pessoas = $pessoas->map(function ($cgm) {
            $nro_rg = null;
            if (!empty(trim($cgm->z01_ident))) {
                $nro_rg = trim($cgm->z01_ident);
            }

            $nro_cpf = null;
            if ($cgm->z01_cgccpf != '99999999999' && $cgm->z01_cgccpf != '00000000000') {
                $nro_cpf = $cgm->z01_cgccpf;
            }

            $observacoes = [];
//            if (!empty(trim($cgm->z01_obs))) {
//                $observacoes[] = utf8_encode($cgm->z01_obs);
//            }
            if (!empty(trim($cgm->z01_telcel))) {
                $observacoes[] = trim($cgm->z01_telcel);
            }
            if (!empty(trim($cgm->z01_telef))) {
                $observacoes[] = trim($cgm->z01_telef);
            }

            return (object)[
                "cod_pessoa" => $cgm->z01_numcgm,
                "nro_cpf" => $nro_cpf,
                "nro_rg" => $nro_rg,
                "uf_rg" => $cgm->z01_identorgao,
                "nome_pessoa" => utf8_encode($cgm->z01_nomecomple),
                "dt_nascimento" => date('Y-m-d H:i:s', strtotime($cgm->z01_nasc)),
                "nome_mae" => utf8_encode($cgm->z01_mae),
                "nome_pai" => utf8_encode($cgm->z01_pai),
                "txt_observacao" => implode(" - ", $observacoes)
            ];
        });

        $response->qtd_registros = count($response->pessoas);
        return $response;
    }
}
