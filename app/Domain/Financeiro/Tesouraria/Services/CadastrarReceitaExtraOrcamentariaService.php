<?php

namespace App\Domain\Financeiro\Tesouraria\Services;

use App\Domain\Financeiro\Contabilidade\Models\ConplanoReduzido;
use App\Domain\Financeiro\Tesouraria\Models\Tabplan;
use App\Domain\Financeiro\Tesouraria\Models\Tabrec;
use App\Domain\Financeiro\Tesouraria\Models\Tabrecregrasjm;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CadastrarReceitaExtraOrcamentariaService
{
    const TIPO_PRINCIPAL = 1;

    private $codigoJurosMulta;
    private $codigoReceitaJuros;
    private $codigoReceitaMulta;

    /**
     * @param Collection|ConplanoReduzido $reduzidos
     * @param integer $idInstituicao
     * @param string $descricao
     * @param string $estrutural
     * @return boolean
     */
    public function cadastrar(Collection $reduzidos, $idInstituicao, $descricao, $estrutural)
    {
        foreach ($reduzidos as $reduzido) {
            $existeReceitaTesouraria = count(DB::select("
                select 1 from caixa.tabplan
                 where k02_reduz = $reduzido->c61_reduz and k02_anousu = $reduzido->c61_anousu
            ")) > 0;
            if ($existeReceitaTesouraria) {
                continue;
            }

            $this->carregaDadosDefault($reduzido->c61_anousu, $idInstituicao);
            $this->criar($reduzido, $descricao, $estrutural);
        }
        return true;
    }

    /**
     * @param ConplanoReduzido $reduzido
     * @param string $descricao
     * @param string $estrutural
     * @return void
     */
    private function criar(ConplanoReduzido $reduzido, $descricao, $estrutural)
    {
        $codigo = $this->nextCodigoSequence('tabrec_k02_codigo_seq');

        DB::table('caixa.tabrec')->insert([
            "k02_codigo" => $codigo,
            "k02_tipo" => 'E',
            "k02_descr" => substr($descricao, 0, 15),
            "k02_drecei" => substr($descricao, 0, 40),
            "k02_codjm" => $this->codigoJurosMulta,
            "k02_recjur" => $this->codigoReceitaJuros,
            "k02_recmul" => $this->codigoReceitaMulta,
            "k02_limite" => null,
            "k02_tabrectipo" => self::TIPO_PRINCIPAL,
            "k02_reccredito" => null
        ]);

        $codigoTabrecregrasjm = $this->nextCodigoSequence('tabrecregrasjm_k04_sequencial_seq');
        DB::table('caixa.tabrecregrasjm')->insert([
            "k04_sequencial" => $codigoTabrecregrasjm,
            "k04_receit" => $codigo,
            "k04_codjm" => $this->codigoJurosMulta,
            "k04_dtini" => '1900-01-01',
            "k04_dtfim" => '2099-12-31'
        ]);

        DB::table('caixa.tabplan')->insert([
            "k02_codigo" => $codigo,
            "k02_anousu" => $reduzido->c61_anousu,
            "k02_reduz" => $reduzido->c61_reduz,
            "k02_estpla" => $estrutural
        ]);
    }

    private function carregaDadosDefault($exercicio, $idInstituicao)
    {
        if (empty($this->codigoReceitaJuros) && empty($this->codigoReceitaMulta)) {
            $dados = DB::select("
               select numpref.k03_recjur as k02_recjur,
                      numpref.k03_recmul as k02_recmul
                 from numpref
                      left join tabrec mul on mul.k02_codigo  = k03_recmul
                      left join tabrec jur on jur.k02_codigo  = k03_recjur
                      left join tabrec credito on credito.k02_codigo  = k03_receitapadraocredito
                where numpref.k03_anousu = {$exercicio}
                  and numpref.k03_instit = {$idInstituicao}
            ");

            $this->codigoReceitaJuros = $dados[0]->k02_recjur;
            $this->codigoReceitaMulta = $dados[0]->k02_recmul;
        }

        if (empty($this->codigoJurosMulta)) {
            $dados = DB::select("
                select k02_codjm from caixa.tabrecjm where k02_instit = $idInstituicao order by k02_codjm limit 1
            ");
            $this->codigoJurosMulta = $dados[0]->k02_codjm;
        }
    }

    private static function nextCodigoSequence($sequence)
    {
        return DB::select("select nextval('{$sequence}')")[0]->nextval;
    }
}
