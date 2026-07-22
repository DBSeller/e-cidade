<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Models\ConplanoAtributos;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoSistema;
use Illuminate\Support\Facades\DB;

class ContaCorrentePcaspService
{
    public function buscarContaCorrenteVinculadas($codcon, $exercicio)
    {
        $tipo = ConplanoSistema::TIPO_CONTA_CORRENTE;
        return DB::select("
            SELECT distinct conplanosistema.*,
                   EXISTS(
                    SELECT 1
                      FROM conplanoatributolancamentos
                      JOIN infocomplementarvalor ON c123_conplanoatributolancamentos = c124_sequencial
                      JOIN conplanoreduz ON c61_reduz = c123_reduzido
                           AND c61_anousu = c120_anousu
                           AND c61_codcon = c120_conplano
                      JOIN conlancam ON c70_codlan = c124_lancamento
                           AND c70_anousu = c120_anousu
                     WHERE conplanoatributolancamentos.c124_conplanosistema = c120_conplanosistema
                   ) AS conta_usada
              FROM contabilidade.conplanoatributos
              JOIN conplanosistema ON c122_sequencial = c120_conplanosistema
             WHERE c120_anousu = {$exercicio}
               AND c120_conplano = {$codcon}
               AND c122_tipo = {$tipo}
             order by c122_sequencial
        ");
    }

    public function adicionarContaCorrente($codcon, $exercicio, $contaCorrente)
    {
        $sistema = ConplanoSistema::find($contaCorrente);
        $atributos = $sistema->atributos;

        $salvar = [];
        foreach ($atributos as $atributo) {
            $salvar[] = [
                "c120_anousu" => $exercicio,
                "c120_conplano" => $codcon,
                "c120_infocomplementar" => $atributo->c129_conplanoinfocomplementar,
                "c120_conplanosistema" => $contaCorrente,
            ];
        }

        (new ConplanoAtributos())->insert($salvar);
    }

    /**
     * Remove o vínculo da conta corrente com o plano pcasp
     * @param $exercicio
     * @param $codcon
     * @param $contaCorrente
     * @throws \Exception
     */
    public function removerContaCorrente($codcon, $exercicio, $contaCorrente = null)
    {
        $contas = $this->buscarContaCorrenteVinculadas($codcon, $exercicio);

        foreach ($contas as $conta) {
            if ($conta->c122_sequencial === $contaCorrente && $conta->conta_usada) {
                throw new \Exception("Você não pode excluir uma conta corrente que já teve execução.");
            }
        }

        ConplanoAtributos::where('c120_anousu', $exercicio)
            ->where('c120_conplano', $codcon)
            ->when(!empty($contaCorrente), function ($query) use ($contaCorrente) {
                $query->where('c120_conplanosistema', $contaCorrente);
            })
            ->delete();
    }
}
