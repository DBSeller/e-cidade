<?php

namespace App\Domain\Financeiro\Tesouraria\Services;

use cl_saltes;
use Illuminate\Support\Facades\DB;

class ContaBancariaOutrosDadosService
{
    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function buscar($request)
    {
        $instit = $request['DB_instit'];
        $anousu = $request['DB_anousu'];
        $sql = 'select distinct on (k13_conta) *
                from saltes
                inner join conplanoreduz on conplanoreduz.c61_reduz = saltes.k13_reduz and c61_anousu = '.$anousu.'
                    inner join conplanoexe on conplanoexe.c62_reduz = conplanoreduz.c61_reduz and c61_anousu=c62_anousu
                    inner join conplano on conplanoreduz.c61_codcon = conplano.c60_codcon
                    inner join orctiporec on o15_codigo = c61_codigo
                        where c61_instit = '.$instit.' and c61_instit = '.$instit.' and c62_anousu = '.$anousu.'
                        order by k13_conta;';

        return DB::select($sql);
    }
    /**
     * @throws Exception
     */
    public function alterar($request)
    {

        $explode = explode(".", $request['changed']);
        $json = '{"'.$explode[0].'": "'.$explode[1].'"}';
        $conta = (int) $request['conta'];

        $result = DB::table('saltes')
            ->select('k13_outrosdados')
            ->where('k13_conta', $conta)
            ->get();

        $decode = (json_decode(json_decode($result->toJson())[0]->k13_outrosdados));

        if (isset(json_decode($json)->conta_ativa)) {
            $decode->conta_ativa = json_decode($json)->conta_ativa;
        }
        if (isset(json_decode($json)->enviada_sagres)) {
            $decode->enviada_sagres = json_decode($json)->enviada_sagres;
        }

        $dao = new cl_saltes;
        $dao->k13_conta = $conta;
        $dao->k13_outrosdados = json_encode($decode);
        return $dao->alterar($conta);
    }
}
