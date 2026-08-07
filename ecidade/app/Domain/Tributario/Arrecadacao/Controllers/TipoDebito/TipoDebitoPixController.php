<?php

namespace App\Domain\Tributario\Arrecadacao\Controllers\TipoDebito;

use App\Domain\Tributario\Arrecadacao\Models\Arretipo;
use App\Domain\Tributario\Arrecadacao\Services\ArretipopixbancogeracaoService;
use Exception;

use App\Http\Controllers\Controller;
use App\Domain\Tributario\Arrecadacao\Models\Arretipopix;
use App\Domain\Tributario\Arrecadacao\Models\Arretipopixasso;
use App\Domain\Tributario\Arrecadacao\Requests\TipoDebitoPixRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TipoDebitoPixController extends Controller
{
    private $rules;

    public function __construct()
    {
        $this->rule = new TipoDebitoPixRequest();
    }

    public function deletar($k00_tipo)
    {
        try {
            $arretipopix = Arretipopix::where([
                "k00_tipo" => $k00_tipo
            ])->first();

            if (!$arretipopix) {
                throw new Exception("Nenhum tipo de débito pix foi encontrado");
            }

            DB::beginTransaction();

            $deletar = $arretipopix->arretipopixasso()->get();

            foreach ($deletar as $arretipopixasso) {
                $arretipopixasso->delete();
            }

            if ($arretipopix->delete()) {
                DB::commit();

                return response()->json([
                    "status"  => "Success",
                    "message" => "Os dados do tipo de débito pix foram removidos"
                ]);
            }

            throw new Exception(
                "Ocorreu um erro na tentativa de remover os dados pix do tipo de débito"
            );
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "status"  => "Error",
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function pegarDadosTipoDebitoPix($k00_tipo)
    {
        try {
            $arretipopix = Arretipopix::where([
                "k00_tipo" => $k00_tipo
            ])->first();

            if (is_null($arretipopix)) {
                $arretipopix = Arretipo::where([
                    "k00_tipo" => $k00_tipo
                ])->count();

                if ($arretipopix <= 0) {
                    throw new Exception(
                        "Nenhum tipo de débito foi encontrado"
                    );
                }

                $arretipopix = Arretipopix::create([
                    "k00_tipo" => $k00_tipo
                ]);

                $arretipopix = Arretipopix::latest()->first();
            }

            $bancos      = [];
            $bancosAsso  = $arretipopix->arretipopixasso()->get();

            foreach ($bancosAsso as $asso) {
                $banco = $asso->getBanco();
                $banco->dadosBanco();

                array_push($bancos, $banco);
            }

            $arretipopix->bancos = $bancos;

            return response()->json([
                "status"  => "Success",
                "message" => $arretipopix
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status"  => "Error",
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function salvar(Request $request)
    {
        try {
            $resultValidationData = $this->validationData($request);

            if ($resultValidationData->getStatusCode() !== 200) {
                return $resultValidationData;
            }

            $data = $this->mountDataArretipopix($request);

            if ($arretipopix = Arretipopix::create($data)) {
                $this->mountAssocBan($data, $arretipopix);

                return response()->json([
                    "status"  => true,
                    "message" => "Tipo de débido salvo com sucesso"
                ]);
            }

            throw new Exception(
                "Ocorreu um erro na tentativa de cadastrar os dados" .
                "do tipo de débito pix"
            );
        } catch (Exception $e) {
            return response()->json([
                "status"  => "Error",
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function atualizar(Request $request, $k00_tipo)
    {
        try {
            $codtipopix = Arretipopix::where([
                "k00_tipo" => $k00_tipo
            ])->first();

            if (is_null($codtipopix)) {
                throw new Exception(
                    "Nenhum Tipo de débido PIX foi localizado " .
                    "referente ao código passado"
                );
            }

            $resultValidationData = $this->validationData(
                $request,
                $codtipopix->codtipopix
            );

            if ($resultValidationData->getStatusCode() !== 200) {
                return $resultValidationData;
            }

            $data             = (array) $this->mountDataArretipopix($request, true);
            $data["k00_tipo"] = $k00_tipo;

            $this->mountAssocBan($data, $codtipopix);

            if ($codtipopix->update($data)) {
                return response()->json([
                    "status"  => "Success",
                    "message" => utf8_encode("Os dados de tipo de débito PIX foram atualizados.")
                ]);
            }

            throw new Exception(
                "Ocorreu um erro na tentativa de " .
                "atualizar o tipo de débito PIX."
            );
        } catch (Exception $e) {
            return response()->json([
                "status"  => "Error",
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Atualiza as associações tipos de débitos e dados bancários pix
     *
     * @param array $data
     * @param Arretipopix $arretipopix
     *
     * @return void
     */
    public function mountAssocBan(&$data, Arretipopix &$arretipopi)
    {
        $arretipopixbancogeracaoService = new ArretipopixbancogeracaoService();

        if (isset($data["codbank"])) {
            if (is_array($data["codbank"]) and count($data["codbank"]) > 0) {
                $deletar = $arretipopi->arretipopixasso()->get();

                foreach ($deletar as $arretipopixasso) {
                    $index = array_search(
                        $arretipopixasso->db90_codban,
                        $data["codbank"]
                    );

                    if ($index !== false) {
                        unset($data["codbank"][$index]);
                    } else {
                        $arretipopixbancogeracaoService->delete($arretipopixasso);
                        $arretipopixasso->delete();
                    }
                }

                foreach ($data["codbank"] as $db90_codban) {
                    $createdArretipopixasso = Arretipopixasso::create([
                        "db90_codban" => $db90_codban,
                        "k00_tipo"    => $arretipopi->k00_tipo
                    ]);

                    $arretipopixbancogeracaoService->save($arretipopi, $createdArretipopixasso);
                }

                $arretipopixbancogeracaoService->reorder($arretipopi);
                $arretipopixbancogeracaoService->chooseBankToGeneratePix($arretipopi, false, true);
            }

            unset($data["codbank"]);

            return;
        }

        $deletar = $arretipopi->arretipopixasso()->get();

        foreach ($deletar as $arretipopixasso) {
            $arretipopixbancogeracaoService->delete($arretipopixasso);
            $arretipopixasso->delete();
        }
    }

    /**
     * Validaçao dos dados para cadastrar ou atualizar Tipo de débito Píx
     *
     * @param Request $request
     * @param int     $codtipopix Defailt null
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationData(Request $request, $codtipopix = null)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rule->rules($codtipopix)
        );

        if ($validator->fails()) {
            return response()->json([
                "status"  => "Error",
                "message" => $validator->errors()
            ], 422);
        }

        $successMessage = "Os dados são válidos para ";
        $successMessage .= ((!is_null($codtipopix)) ? "alteração" : "inclusão");
        $successMessage .= " do tipo de débido PIX.";
        return response()->json(["status"  => "Success", "message" => utf8_encode($successMessage)]);
    }

    /**
     * Retorna uma lista com todos os dados utilizados na rules
     *
     * @param Request $request
     * @param bool $update Se true Adiciona valores default
     * para os campos modsistema e moddbpref
     *
     * @return array;
     */
    private function mountDataArretipopix(Request $request, $update = false)
    {
        $data      = [];
        $data_keys = array_keys($this->rule->rules());

        foreach ($request->all() as $index => $value) {
            if (array_search($index, $data_keys) !== false) {
                $data[$index] = $value;
            }
        }

        $data["modsistema"] = ((isset($data["modsistema"]) ? true : false));
        $data["moddbpref"]  = ((isset($data["moddbpref"]) ? true : false));

        if ($update) {
            if (isset($data["k00_tipo"])) {
                unset($data["k00_tipo"]);
            }
        }

        if (isset($data["codbank"]) and
            (
                !is_array($data["codbank"]) or
                count($data["codbank"]) <= 1
            )
        ) {
            $data["qtdemissao"] = null;
        }

        return $data;
    }
}
