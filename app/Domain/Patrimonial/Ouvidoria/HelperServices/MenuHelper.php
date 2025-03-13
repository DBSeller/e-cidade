<?php

namespace App\Domain\Patrimonial\Ouvidoria\HelperServices;

class MenuHelper
{

    public static function converterMenusParaObjeto($menusResult)
    {

        $instituicoes = collect($menusResult)->groupBy(function ($menu) {
            return $menu->instituicao_codigo;
        })->toArray();

        return collect($instituicoes)->flatMap(function ($instituicao, $index) {

            $itens_menu = array_map(function ($item) {

                return (object)[
                    'codigo' => $item->orgao_codigo
                    , 'nome' => utf8_encode(urldecode($item->orgao_nome))
                    , 'estrutura' => 'secretaria'
                    , 'tipoprocesso_codigo' => $item->tipoprocesso_codigo
                    , 'tipoprocesso_descricao' => utf8_encode(urldecode($item->tipoprocesso_descricao))
                    , 'tipoprocesso_depto_id' => utf8_encode(urldecode($item->tipoprocesso_depto_id))
                    , 'tipoprocesso_depto_descricao' => utf8_encode(urldecode($item->tipoprocesso_depto_descricao))
                    , 'tipoprocesso_formareclamacao' => utf8_encode(urldecode($item->tipoprocesso_formareclamacao))
                    , 'linksaibamais' => utf8_encode(urldecode($item->linksaibamais))
                    , 'item_menu' => utf8_encode(urldecode($item->item_menu))
                    , 'rota' => utf8_encode(urldecode($item->rota))
                    , 'identificado' => $item->identificado == 't' ? 'sim' : 'nao'
                ];
            }, $instituicao);

            $itens_menu = collect($itens_menu)->groupBy(function ($item_menu) {
                return $item_menu->item_menu;
            })->toArray();

            $items_menu_com_filhos = collect($itens_menu)->flatMap(function ($item_menu, $index) {

                $tiposProcessos = array_map(function ($item) {

                    return (object)[
                        'id' => $item->tipoprocesso_codigo
                        , 'descricao' => $item->tipoprocesso_descricao
                        , 'nome' => $item->tipoprocesso_descricao
                        , 'depto_id' => $item->tipoprocesso_depto_id
                        , 'depto_descricao' => $item->tipoprocesso_depto_descricao
                        , 'formareclamacao' => $item->tipoprocesso_formareclamacao
                        , 'linksaibamais' => $item->linksaibamais
                        , 'rota' => utf8_encode(urldecode($item->rota))
                        , 'identificado' => $item->identificado
                    ];
                }, $item_menu);


                if ($index == "") {
                    return $tiposProcessos;
                }
                return [
                    (object)[
                        'id' => $index
                        , 'codigo' => $item_menu[0]->codigo
                        , 'nome' => $item_menu[0]->item_menu
                        , 'estrutura' => 'item_menu'
                        , 'children' => $tiposProcessos
                    ]
                ];
            });

            return [
                $index => (object)[
                    'id' => $index
                    , 'codigo' => utf8_encode(urldecode($instituicao[0]->instituicao_codigo))
                    , 'nome' => utf8_encode(urldecode($instituicao[0]->instituicao_nome))
                    , 'estrutura' => 'instituicao'
                    , 'children' => $items_menu_com_filhos
                ]
            ];
        })->values();
    }

    public static function coverterMenuPrimeiroAcesso($menusResult)
    {
        $instituicoes = collect($menusResult)->groupBy(function ($grupo) {
            return $grupo->instituicao_codigo;
        })->toArray();

        return collect($instituicoes)->flatMap(function ($instituicao, $index) {

            $secretarias = array_map(function ($item) {

                return (object)[
                    'codigo' => $item->orgao_codigo
                    , 'nome' => utf8_encode(urldecode($item->orgao_nome))
                    , 'estrutura' => 'secretaria'
                    , 'tipoprocesso_codigo' => $item->tipoprocesso_codigo
                    , 'tipoprocesso_descricao' => utf8_encode(urldecode($item->tipoprocesso_descricao))
                    , 'tipoprocesso_depto_id' => utf8_encode(urldecode($item->tipoprocesso_depto_id))
                    , 'tipoprocesso_depto_descricao' => utf8_encode(urldecode($item->tipoprocesso_depto_descricao))
                    , 'tipoprocesso_formareclamacao' => utf8_encode(urldecode($item->tipoprocesso_formareclamacao))
                    , 'linksaibamais' => utf8_encode(urldecode($item->linksaibamais))
                    , 'rota' => utf8_encode(urldecode($item->rota))
                ];
            }, $instituicao);

            $secretarias = collect($secretarias)->groupBy(function ($secreataria) {
                return $secreataria->codigo;
            })->toArray();


            $secretariasComTiposDeProcessos = collect($secretarias)->flatMap(function ($secretaria, $index) {
                $tiposProcessos = array_map(function ($item) {
                    return (object)[
                        'id' => $item->tipoprocesso_codigo
                        , 'descricao' => $item->tipoprocesso_descricao
                        , 'nome' => $item->tipoprocesso_descricao
                        , 'depto_id' => $item->tipoprocesso_depto_id
                        , 'depto_descricao' => $item->tipoprocesso_depto_descricao
                        , 'formareclamacao' => $item->tipoprocesso_formareclamacao
                        , 'linksaibamais' => $item->linksaibamais
                        , 'rota' => utf8_encode(urldecode($item->rota))
                    ];
                }, $secretaria);

                return [
                    $index => (object)[
                        'id' => $index
                        , 'codigo' => $secretaria[0]->codigo
                        , 'nome' => $secretaria[0]->nome
                        , 'estrutura' => 'orgao'
                        , 'children' => $tiposProcessos
                    ]
                ];
            });

            return [
                $index => (object)[
                    'id' => $index
                    , 'codigo' => utf8_encode(urldecode($instituicao[0]->instituicao_codigo))
                    , 'nome' => utf8_encode(urldecode($instituicao[0]->instituicao_nome))
                    , 'estrutura' => 'instituicao'
                    , 'children' => $secretariasComTiposDeProcessos
                ]
            ];
        })->values();
    }
}
