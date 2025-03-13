<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Relatorios\NotificacaoRecebimentoRecursosPDF;
use DBDate;
use Illuminate\Support\Facades\DB;
use ParameterException;

class RelatorioNotificacaoRecebimentoRecursosService
{
    /**
     * @var DBDate
     */
    private $dataInicial;
    /**
     * @var DBDate
     */
    private $dataFinal;
    /**
     * @var integer
     */
    private $ano;

    /**
     * @var array
     */
    private $aDados;

    /**
     * RelatorioNotificacaoRecebimentoRecursosService constructor.
     * @param $parametros
     * @throws ParameterException
     */
    public function __construct($parametros)
    {
        $this->dataInicial = new DBDate($parametros['dataInicial']);
        $this->dataFinal = new DBDate($parametros['dataFinal']);
        $this->ano = $parametros['DB_anousu'];

        $this->buscarDados();
    }

    /**
     * @throws ParameterException
     */
    private function buscarDados()
    {
        $dados = DB::select(<<<sql
select c70_data as data_arrecadacao,
               'SECRETARIA DO TESOURO NACIONAL'            as orgao_concessor,
               o57_descr                                   as descricao_do_recurso,
               substr(o57_fonte, 2, 14)                    as codigo_da_receita,
               coalesce(sum(case when c53_tipo = 101 then round(c70_valor, 2) * -1 else round(c70_valor, 2) end),
                        0)                                    valor_recebido
        from conlancam
                 join conlancamrec on c70_codlan = c74_codlan
                 join conlancamdoc on c70_codlan = c71_codlan
                 join conhistdoc on c71_coddoc = c53_coddoc
                 join orcreceita on c74_anousu = o70_anousu and c74_codrec = o70_codrec
                 join orcfontes on o70_anousu = o57_anousu and o70_codfon = o57_codfon
        where o70_anousu = {$this->ano}
          and substr(o57_fonte, 2, 3) = '171'
          and c70_data between '{$this->dataInicial->getDate()}' and '{$this->dataFinal->getDate()}'
        group by 1, 2, 3, 4
        order by 1, 3;
sql
        );

        $this->organizarDados($dados);
    }

    /**
     * @param $recursos
     * @throws ParameterException
     */
    private function organizarDados($recursos)
    {
        $this->aDados = [];

        foreach ($recursos as $recurso) {
            $key = $recurso->data_arrecadacao;
            if (!array_key_exists($key, $this->aDados)) {
                $this->aDados[$key] = (object)[
                    "data" => new DBDate($key),
                    "recursos" => [],
                    "total_recebido" => 0
                ];
            }

            $this->aDados[$key]->recursos[] = $recurso;
            $this->aDados[$key]->total_recebido += $recurso->valor_recebido;
        }
    }

    public function emitirPdf()
    {
        $pdf = new NotificacaoRecebimentoRecursosPDF($this->dataInicial, $this->dataFinal);
        return $pdf->emitir($this->aDados);
    }
}
