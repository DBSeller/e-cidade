<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Domain\Financeiro\Contabilidade\Relatorios\RelatorioTcePDF;
use App\Domain\Financeiro\Contabilidade\Models\MovimentacaoAuditoria;
use App\Domain\Financeiro\Contabilidade\Repositories\LancamentosContabeisRepository;

class RelatorioTCEService
{

    public function __construct()
    {
        $this->repository = new LancamentosContabeisRepository();
    }

    /**
     * @param array $dados
     * @throws Exception
     */
    public function exportar($dados)
    {
        $mde = $this->repository->montaQueryMDE($dados);
        $relatorio = new RelatorioTcePDF($dados, $mde);
        return $relatorio->emitir();
    }

    public function salvarMovimentacaoMes($dados)
    {
        $movimentacaoAuditoria = $this->buscar($dados);
        if (sizeof($movimentacaoAuditoria) > 0) {
            $this->atualizar($dados);
        } else {
            $movimentacaoAuditoria = new MovimentacaoAuditoria();
            $movimentacaoAuditoria->c170_mes = $dados['mes'];
            $movimentacaoAuditoria->c170_anousu = $dados['anousu'];
            $movimentacaoAuditoria->c170_adicaoauditoria = $dados['adicaoAuditoria'];
            $movimentacaoAuditoria->c170_exclusaoauditoria = $dados['exclusaoAuditoria'];
            $movimentacaoAuditoria->c170_resto = $dados['resto'];
            $movimentacaoAuditoria->save();
        }
    }

    /**
     * @param $dados
     */
    private function atualizar($dados)
    {
        MovimentacaoAuditoria::where('c170_anousu', $dados['anousu'])
            ->where('c170_mes', $dados['mes'])
            ->update(['c170_adicaoauditoria' => $dados['adicaoAuditoria'],
                'c170_exclusaoauditoria' => $dados['exclusaoAuditoria'],
                'c170_resto' => $dados['resto']]);
    }

    public function buscar($dados)
    {
        $mes = (int) $dados['mes'];
        $anousu = (int) $dados['anousu'];
        return DB::table('movimentacoesauditoria')->where([
            ['c170_anousu','=',$anousu],['c170_mes','=',$mes]
        ])->get();
    }
}
