<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ManutencaoPlanoContasService
{
    private $exercicio;

    public function getContasSemUso($estrutural, $exercicio)
    {
        $sql = "
        select distinct c60_codigo, c60_codcon, c60_anousu, c61_reduz, c60_estrut, c60_descr
          from conplano
          join conplanoreduz on (c61_codcon, c61_anousu) = (c60_codcon, c60_anousu)
         where c60_anousu = $exercicio
           and c60_estrut like '$estrutural%'
           and not exists (
              select 1 from pcaspconplano
                join pcasp on pcasp.id = pcaspconplano.pcasp_id
               where conplano_codigo = conplano.c60_codigo
                 and uniao is false
           )
           and not exists(
               select 1
               from conlancamval
               where (   c69_debito = conplanoreduz.c61_reduz
                      or c69_credito = conplanoreduz.c61_reduz
                   )
                 and c69_anousu = conplanoreduz.c61_anousu
           )
           and not exists(
               select 1
               from conplanoexe
               where c62_reduz = conplanoreduz.c61_reduz
                 and c62_anousu = conplanoreduz.c61_anousu
                 and (c62_vlrcre > 0 or c62_vlrdeb > 0)
           )
         order by c60_estrut;
        ";
        return DB::select($sql);
    }

    public function excluirContas(array $contas)
    {
        $this->exercicio = $contas[0]->c60_anousu;

        try {
            foreach ($contas as $conta) {
                $this->excluiReduzido($conta);
                $this->excluirConta($conta);
            }
        } catch (QueryException $exception) {
            throw new Exception("Erro ao excluir contas selecionadas. Entre em contato com o suporte para análise.");
        }
    }

    private function excluirConta($conta)
    {
        $this->excluiVinculoConta($conta);
        DB::table('contabilidade.conplano')
            ->where('c60_codcon', $conta->c60_codcon)
            ->where('c60_anousu', '>=', $this->exercicio)
            ->delete();
    }

    /**
     * @param $conta
     * @return void
     */
    public function excluiReduzido($conta)
    {
        DB::table('contabilidade.conplanoconta')
            ->where('c63_reduz', $conta->c61_reduz)
            ->where('c63_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanocontabancaria')
            ->where('c56_reduz', $conta->c61_reduz)
            ->where('c56_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoexerecurso')
            ->where('c89_reduz', $conta->c61_reduz)
            ->where('c89_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoextra')
            ->where('c33_reduz', $conta->c61_reduz)
            ->where('c33_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoreduzcgm')
            ->where('c22_reduz', $conta->c61_reduz)
            ->where('c22_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.contacorrentedetalhe')
            ->where('c19_reduz', $conta->c61_reduz)
            ->where('c19_conplanoreduzanousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoreduz')
            ->where('c61_reduz', $conta->c61_reduz)
            ->where('c61_anousu', '>=', $this->exercicio)
            ->delete();
    }

    private function excluiVinculoConta($conta)
    {
        DB::table('contabilidade.clabensconplano')
            ->where('t86_conplano', $conta->c60_codcon)
            ->where('t86_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.clabensconplano')
            ->where('t86_conplanodepreciacao', $conta->c60_codcon)
            ->where('t86_anousudepreciacao', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoatributos')
            ->where('c120_conplano', $conta->c60_codcon)
            ->where('c120_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoconplanoorcamento')
            ->where('c72_conplano', $conta->c60_codcon)
            ->where('c72_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoconsaldo')
            ->where('c59_codcon', $conta->c60_codcon)
            ->where('c59_anoexe', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanoconta')
            ->where('c63_codcon', $conta->c60_codcon)
            ->where('c63_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanocontabancaria')
            ->where('c56_codcon', $conta->c60_codcon)
            ->where('c56_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanocontacorrente')
            ->where('c18_codcon', $conta->c60_codcon)
            ->where('c18_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('contabilidade.conplanogrupo')
            ->where('c21_codcon', $conta->c60_codcon)
            ->where('c21_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('material.materialestoquegrupoconta')
            ->where('m66_codcon', $conta->c60_codcon)
            ->where('m66_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('material.materialestoquegrupoconta')
            ->where('m66_codconvpd', $conta->c60_codcon)
            ->where('m66_anousu', '>=', $this->exercicio)
            ->delete();

        DB::table('orcamento.orccenarioeconomicoconplano')
            ->where('o04_conplano', $conta->c60_codcon)
            ->where('o04_anousu', '>=', $this->exercicio)
            ->delete();
    }
}
