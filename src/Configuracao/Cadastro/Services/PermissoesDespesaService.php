<?php

namespace ECidade\Configuracao\Cadastro\Services;

use App\Domain\Financeiro\Orcamento\Models\Funcao;
use App\Domain\Financeiro\Orcamento\Models\Orgao;
use App\Domain\Financeiro\Orcamento\Models\Programa;
use App\Domain\Financeiro\Orcamento\Models\ProjetoAtividade;
use App\Domain\Financeiro\Orcamento\Models\Recurso;
use App\Domain\Financeiro\Orcamento\Models\Subfuncao;
use cl_orcdotacao;
use Exception;

class PermissoesDespesaService
{
    /**
     * @var int
     */
    private $anousu;

    public function __construct()
    {
        $this->anousu = db_getsession('DB_anousu');
    }
    /**
     * @return array
     * @throws Exception
     */
    public function buscarClassificacaoProgramatica()
    {
        $anousu = db_getsession("DB_anousu");
        $orgaos = Orgao::select(['o40_orgao as codigo', 'o40_descr as descricao'])
            ->where('o40_anousu', '=', db_getsession("DB_anousu"))
            ->orderBy('o40_orgao')
            ->get();
        $funcoes = Funcao::select(['o52_funcao as codigo', 'o52_descr as descricao'])
            ->ano($anousu)
            ->orderBy('o52_funcao')
            ->distinct()
            ->get();
        $subfuncoes = Subfuncao::select(['o53_subfuncao as codigo', 'o53_descr as descricao'])
            ->orderBy('o53_subfuncao')
            ->get();
        $programas = Programa::select(['o54_programa as codigo', 'o54_descr as descricao'])
            ->where('o54_anousu', '=', $anousu)
            ->orderBy('o54_programa')
            ->get();
        $projetoAtividade = ProjetoAtividade::select(['o55_projativ as codigo', 'o55_descr as descricao'])
            ->where('o55_anousu', '=', $anousu)
            ->orderBy('o55_projativ')
            ->get();
        $recursos = Recurso::select(['o15_codigo as codigo', 'o15_descr as descricao'])
            ->orderBy('o15_codigo')
            ->get();

        $daoDotacao = new cl_orcdotacao();
        $sSqlElemento = $daoDotacao->sql_query(
            null,
            null,
            "distinct o56_codele, o56_elemento as codigo,o56_descr as descricao",
            "o56_elemento",
            "o58_anousu = " . $anousu
        );
        $rs = db_query($sSqlElemento);
        if (!$rs) {
            throw new Exception("Erro ao buscar elementos");
        }
        $elementos = [];
        while ($elemento = pg_fetch_assoc($rs)) {
            $elementos[] = $elemento;
        }

        return [
            'orgaos' => $orgaos->toArray(),
            'funcoes' => $funcoes->toArray(),
            'subfuncoes' => $subfuncoes->toArray(),
            'programas' => $programas->toArray(),
            'projetoAtividade' => $projetoAtividade->toArray(),
            'elementos' => $elementos,
            'recursos' => $recursos->toArray(),
        ];
    }

    public function buscarFuncoes()
    {
        $funcoes = Funcao::select(['o52_funcao as codigo', 'o52_descr as descricao'])
            ->orderBy('o52_funcao')
            ->get();
    }
}
