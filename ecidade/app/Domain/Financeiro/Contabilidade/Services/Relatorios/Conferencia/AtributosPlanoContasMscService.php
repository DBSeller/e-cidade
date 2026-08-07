<?php

namespace App\Domain\Financeiro\Contabilidade\Services\Relatorios\Conferencia;

use App\Domain\Financeiro\Contabilidade\Relatorios\Conferencia\AtributosPlanoContasMscPdf;
use Illuminate\Support\Facades\DB;

class AtributosPlanoContasMscService
{
    /**
     * @var false|string[]
     */
    private $filtrarEstrutural;
    /**
     * @var integer
     */
    private $exercicio;
    /**
     * @var \Illuminate\Support\Collection
     */
    private $filtrarInstituicoes;
    /**
     * @var array
     */
    private $nomeInstituicoes = [];

    protected $contasSemAtributos = [];
    protected $contasComAtributos = [];

    /**
     * @param array $filtros
     * @return $this
     */
    public function setFiltros(array $filtros)
    {
        if (!empty($filtros['estrutural'])) {
            $this->filtrarEstrutural = explode(',', str_replace('.', '', $filtros['estrutural']));
        }

        if (!empty($filtros['instituicoes'])) {
            $instituicoes = str_replace('\"', '"', $filtros['instituicoes']);
            $instituicoes = \JSON::create()->parse($instituicoes);

            $this->filtrarInstituicoes = collect($instituicoes)->map(function ($instituicao) {
                $this->nomeInstituicoes[] = $instituicao->nome;
                return $instituicao->codigo;
            });
        }

        $this->exercicio = $filtros['DB_anousu'];
        return $this;
    }

    public function emitir()
    {
        $this->processar();
        $pdf = new AtributosPlanoContasMscPdf();
        $pdf->headers($this->exercicio, $this->nomeInstituicoes);
        $pdf->contasSemAtributos($this->contasSemAtributos);
        $pdf->contasComAtributos($this->contasComAtributos);
        return $pdf->imprimir();
    }

    protected function buscarDados()
    {
        $where = ["c60_anousu = {$this->exercicio}"];
        if (!empty($this->filtrarEstrutural)) {
            $estruturais = array_map(function ($estrutural) {
                return "(c60_estrut like '{$estrutural}%')";
            }, $this->filtrarEstrutural);

            $where[] = '(' . implode(' or ', $estruturais) . ')';
        }

        if (!$this->filtrarInstituicoes->isEmpty()) {
            $where[] = "c61_instit in (". $this->filtrarInstituicoes->implode(',') . ")";
        }

        $where = implode(' and ', $where);

        $sql = "
        select c60_estrut as estrutural,
               (select array_to_string(array_accum(distinct c121_sigla), ', ')
                  from contabilidade.conplanoatributos
                  join conplanoinfocomplementar
                      on conplanoinfocomplementar.c121_sequencial = conplanoatributos.c120_infocomplementar
                  where c120_conplanosistema = 1
                    and c120_conplano = c60_codcon
                    and c120_anousu =  c60_anousu
               ) as informacoes_complementares
          from conplanoreduz
          join conplano on (c60_codcon, c60_anousu) = (c61_codcon, c61_anousu)
          where {$where}
        order by 1
        ";

        return DB::select($sql);
    }

    private function processar()
    {
        $dados = $this->buscarDados();

        foreach ($dados as $dado) {
            if (empty($dado->informacoes_complementares)) {
                $this->contasSemAtributos[] = $dado;
            } else {
                $this->contasComAtributos[] = $dado;
            }
        }
    }
}
