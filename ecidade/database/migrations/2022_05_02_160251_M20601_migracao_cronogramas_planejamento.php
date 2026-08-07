<?php

use App\Domain\Financeiro\Planejamento\Models\DetalhamentoDespesa;
use App\Domain\Financeiro\Planejamento\Models\EstimativaReceita;
use App\Domain\Financeiro\Planejamento\Models\Iniciativa;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Services\CronogramaDesembolsoService;
use ECidade\Financeiro\Orcamento\Model\Dotacao;
use ECidade\Financeiro\Orcamento\Model\Receita;
use Illuminate\Database\Migrations\Migration;

class M20601MigracaoCronogramasPlanejamento extends Migration
{
    protected $exercicio = 2022;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->processarDespena();
        $this->processarReceita();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from orcamento.acompanhamentocronogramadespesa where exercicio = $this->exercicio;
delete from orcamento.acompanhamentocronogramareceita where exercicio = $this->exercicio;
SQL
        );
    }

    /**
     * @return boolean
     */
    private function processarDespena()
    {
        $dotacoes = $this->buscarDotacoes();
        if (empty($dotacoes)) {
            return false;
        }
        foreach ($dotacoes as $dotacao) {
            $this->criarCronogramaDesembolsoDespesa($dotacao);
        }
        return true;
    }

    /**
     * @return array|false
     */
    private function buscarDotacoes()
    {
        $where = [
            "o58_anousu = {$this->exercicio}",
        ];

        $sql = sprintf(
            'select * from orcamento.orcdotacao where %s order by o58_anousu, o58_coddot',
            implode(' and ', $where)
        );

        $results = DB::select($sql);

        if (empty($results)) {
            return false;
        }
        $dotacoes = [];
        foreach ($results as $data) {
            $dotacoes[] = Dotacao::fromState((array) $data);
        }

        return $dotacoes;
    }

    private function criarCronogramaDesembolsoDespesa(Dotacao $dotacao)
    {
        $cronograma = new CronogramaDesembolsoService();
        $valorMes = $cronograma->getValorMensal($dotacao->getValor());
        $valorDezembro = $cronograma->getValorDezembro($valorMes, $dotacao->getValor());

        DB::table('acompanhamentocronogramadespesa')->insert(
            [
                "exercicio" => $dotacao->getAno(),
                "dotacao_id" => $dotacao->getCodigoDotacao(),
                "base_calculo" => 1,
                "janeiro" => $valorMes,
                "fevereiro" => $valorMes,
                "marco" => $valorMes,
                "abril" => $valorMes,
                "maio" => $valorMes,
                "junho" => $valorMes,
                "julho" => $valorMes,
                "agosto" => $valorMes,
                "setembro" => $valorMes,
                "outubro" => $valorMes,
                "novembro" => $valorMes,
                "dezembro" => $valorDezembro,
            ]
        );
    }

    /**
     * @return bool
     */
    private function processarReceita()
    {
        $receitas = $this->getReceitas();
        if (empty($receitas)) {
            return false;
        }
        foreach ($receitas as $receita) {
            $this->criarCronogramaDesembolsoReceita($receita);
        }
        return true;
    }

    private function getReceitas()
    {
        $where = [
            "o70_anousu = {$this->exercicio}",
        ];

        $sql = sprintf(
            'select * from orcamento.orcreceita where %s order by o70_anousu, o70_codrec',
            implode(' and ', $where)
        );

        $results = DB::select($sql);

        if (empty($results)) {
            return false;
        }
        $receitas = [];
        foreach ($results as $data) {
            $receitas[] = Receita::fromState((array) $data);
        }

        return $receitas;
    }

    private function criarCronogramaDesembolsoReceita(Receita $receita)
    {
        $cronograma = new CronogramaDesembolsoService();
        $valorMes = $cronograma->getValorMensal($receita->getValor());
        $valorDezembro = $cronograma->getValorDezembro($valorMes, $receita->getValor());

        DB::table('acompanhamentocronogramareceita')->insert(
            [
                "exercicio" => $receita->getAno(),
                "receita_id" => $receita->getReduzido(),
                "base_calculo" => 1,
                "janeiro" => $valorMes,
                "fevereiro" => $valorMes,
                "marco" => $valorMes,
                "abril" => $valorMes,
                "maio" => $valorMes,
                "junho" => $valorMes,
                "julho" => $valorMes,
                "agosto" => $valorMes,
                "setembro" => $valorMes,
                "outubro" => $valorMes,
                "novembro" => $valorMes,
                "dezembro" => $valorDezembro,
            ]
        );
    }
}
