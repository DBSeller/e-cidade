<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Configuracao\Instituicao\Model\DBConfig;
use App\Domain\Configuracao\Usuario\Models\Usuario;
use App\Domain\Financeiro\Planejamento\Models\FatorCorrecaoDespesa;
use App\Domain\Financeiro\Planejamento\Models\Iniciativa;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Projecao\Despesa\CalculaProjecaoRequest;
use ECidade\Enum\Financeiro\Planejamento\TipoEnum;
use Exception;
use Illuminate\Support\Facades\DB;
use JSON;
use stdClass;

// phpcs:disable
include(modification("libs/db_liborcamento.php"));
// phpcs:enable
/**
 * Class ProjecaoDespesaService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class ProjecaoDespesaService extends ProjecaoService
{

    /**
     * @throws Exception
     */
    public function calcular()
    {
        ProgramaEstrategico::where('pl9_planejamento', '=', $this->planejamento->pl2_codigo)->delete();

        $valoresDotacao = $this->buscarValores();
        $valoresDotacao = $this->calculaProjecao($valoresDotacao);

        $valoresAgrupados = $this->agrupaValores($valoresDotacao);
        $this->persisteCalculo($valoresAgrupados);
    }


    /**
     * @throws Exception
     */
    public function getProjecao()
    {
        $projecoesPrograma = ProgramaEstrategico::where('pl9_planejamento', '=', $this->planejamento->pl2_codigo)
            ->when(!$this->instituicao->prefeitura, function ($query) {
                $query->where('o58_instit', '=', $this->instituicao->codigo);
            })
            ->get();

        if ($projecoesPrograma->count() === 0) {
            $this->calcular();
        }
        /**
         * @todo esta fixo para trabalhar apenas PPA e LDO
         */
        $filtros = ["pl9_planejamento = {$this->planejamento->pl2_codigo}"];
        if (!$this->instituicao->prefeitura) {
            $filtros[] = " pl9_instituicao = {$this->instituicao->codigo}";
        }

        $where = implode(' and ', $filtros);

        $sql = "
            select codigo as id_instituicao,
                   nomeinst as instituicao,
                   pl9_codigo as id_programa,
                   pl9_planejamento as planejamento,
                   pl9_orcprograma as orcprograma,
                   pl9_anoorcamento as anoorcamento,
                   pl12_codigo as id_iniciativa,
                   pl12_orcprojativ as orcprojativ,
                   pl12_valorbase as valorbase,
                   lpad(o54_programa, 4, 0) ||'.'|| lpad(o55_projativ, 4, 0) as estrutural,
                   o55_descr as descricao_iniciativa,
                   o54_descr as descricao_programa,

                   ( select json_agg(
                              json_build_object(
                                'ano', x.pl10_ano,
                                'valor', x.pl10_valor
                              )
                            ) as valores
                       from (select valores.pl10_ano,
                                    valores.pl10_valor
                               from planejamento.valores
                              where pl10_origem = 'INICIATIVA'
                                and pl10_chave = pl12_codigo
                              order by pl10_ano
                             ) as x
                   ) as valores
              from planejamento.programaestrategico
              join planejamento.iniciativaprojativ
                  on iniciativaprojativ.pl12_programaestrategico = programaestrategico.pl9_codigo
              join db_config on db_config.codigo = programaestrategico.pl9_instituicao
              join orcamento.orcprograma on (o54_anousu, o54_programa) = (pl9_anoorcamento, pl9_orcprograma)
              join orcamento.orcprojativ on (o55_anousu, o55_projativ) = (pl12_anoorcamento, pl12_orcprojativ)
            where {$where}
            order by id_instituicao, id_programa, id_iniciativa
        ";

        return \db_utils::makeCollectionFromRecord(db_query($sql), function ($dado) {
             $dado->valores = JSON::create()->parse($dado->valores);
            return $dado;
        });
    }

    /**
     * Busca os orgaos que o usuário possui permissão no ano informado
     * @return array
     * @throws Exception
     */
    private function getOrgaosLiberadoUsuario()
    {
        return Usuario::getOrgaosLiberadoUsuario($this->idUsuario, $this->anoSessao);
    }

    /**
     * Busca os valores das dotações agrupando por programa e ação(iniciativa)
     * @return array
     */
    private function buscarValores()
    {

        if ($this->planejamento->pl2_tipo === TipoEnum::LOA) {
            return $this->calulaValoresDetalhado();
        }

        return $this->calulaValoresSintetico();
//
//        $orgaos = implode(',', $orgaosUsuario);
//        return DB::table('orcdotacao')
//            /* se o plano for uma LOA */
//            ->when($this->planejamento->pl2_tipo === TipoEnum::LOA, function ($query) {
//                $campos = [
//                    'fc_estruturaldotacao(o58_anousu, o58_coddot) as estrutural',
//                    'o58_instit',
//                    'o58_orgao',
//                    'o58_unidade',
//                    'o58_subfuncao',
//                    'o58_projativ',
//                    'o58_codigo',
//                    'o58_funcao',
//                    'o58_programa',
//                    'o58_codele',
//                    'o58_valor as valor',
//                    'o58_localizadorgastos',
//                    'o58_concarpeculiar',
//                    'o58_esferaorcamentaria',
//                    'o54_descr',
//                    'o55_descr',
//                    'o40_descr',
//                    'o41_descr',
//                    'o56_descr',
//                    'o15_descr',
//                    'c58_descr',
//                    'o52_descr',
//                    'o53_descr',
//                    'o11_descricao',
//                    'nomeinst',
//                ];
//                $query->select($campos);
//            })
//            /* se o plano for PPA ou LDO */
//            ->when($this->planejamento->pl2_tipo !== TipoEnum::LOA, function ($query) {
//                $outrosCampos = [
//                    DB::raw("o58_programa  ||'.'|| o58_projativ as estrutural, sum(o58_valor) as valor")
//                ];
//                $campos = [
//                    'o58_programa',
//                    'o58_projativ',
//                    'o58_codele',
//                    'o58_instit',
//                    'o54_descr',
//                    'o55_descr',
//                    'nomeinst',
//                ];
//                $query->select(
//                    array_merge($campos, $outrosCampos)
//                )->groupBy($campos);
//            })
//            ->whereIn('o58_orgao', $orgaosUsuario)
//            ->where('o58_anousu', $this->anoSessao)
//            ->when(!$this->instituicao->prefeitura, function ($query) {
//                $query->where('o58_instit', $this->instituicao->codigo);
//            })
//            ->join('orcprograma', function ($join) {
//                $join->on('orcprograma.o54_programa', '=', 'orcdotacao.o58_programa')
//                    ->on('orcprograma.o54_anousu', '=', 'orcdotacao.o58_anousu');
//            })
//            ->join('orcprojativ', function ($join) {
//                $join->on('orcprojativ.o55_projativ', '=', 'orcdotacao.o58_projativ')
//                    ->on('orcprojativ.o55_anousu', '=', 'orcdotacao.o58_anousu');
//            })
//            ->join('orcorgao', function ($join) {
//                $join->on('orcorgao.o40_orgao', '=', 'orcdotacao.o58_orgao')
//                    ->on('orcorgao.o40_anousu', '=', 'orcdotacao.o58_anousu');
//            })
//            ->join('orcunidade', function ($join) {
//                $join->on('orcunidade.o41_orgao', '=', 'orcdotacao.o58_orgao')
//                    ->on('orcunidade.o41_unidade', '=', 'orcdotacao.o58_unidade')
//                    ->on('orcunidade.o41_anousu', '=', 'orcdotacao.o58_anousu');
//            })
//            ->join('orcelemento', function ($join) {
//                $join->on('orcelemento.o56_codele', '=', 'orcdotacao.o58_codele')
//                    ->on('orcelemento.o56_anousu', '=', 'orcdotacao.o58_anousu');
//            })
//            ->join('orctiporec', 'o15_codigo', '=', 'o58_codigo')
//            ->join('concarpeculiar', 'c58_sequencial', '=', 'o58_concarpeculiar')
//            ->join('orcfuncao', 'o52_funcao', '=', 'o58_funcao')
//            ->join('db_config', 'codigo', '=', 'o58_instit')
//            ->join('ppasubtitulolocalizadorgasto', 'o11_sequencial', '=', 'o58_localizadorgastos')
//            ->join('orcsubfuncao', 'o53_subfuncao', '=', 'o58_subfuncao')
//            ->orderBy('o58_programa', 'o58_projativ')
//            ->get()->toArray();
    }

    private function agrupaValores(array $dadosDotacao)
    {
        $valoresAgrupados = [];
        foreach ($dadosDotacao as $dadoDotacao) {
            $idPrograma = "{$dadoDotacao->instituicao}#{$dadoDotacao->programa}";
            if (!array_key_exists($idPrograma, $valoresAgrupados)) {
                $valoresAgrupados[$idPrograma] = $this->createObjectPrograma($dadoDotacao);
            }

            $idAcao = $dadoDotacao->acao;
            if (!array_key_exists($idAcao, $valoresAgrupados[$idPrograma]->iniciativas)) {
                $valoresAgrupados[$idPrograma]->iniciativas[$idAcao] = $this->createObjectAcao($dadoDotacao);
            }

            $valoresAgrupados[$idPrograma]->valorBase += $dadoDotacao->valor;
            $valoresAgrupados[$idPrograma]->iniciativas[$idAcao]->valorBase += $dadoDotacao->valor;

            $acao = $valoresAgrupados[$idPrograma]->iniciativas[$idAcao];

            foreach ($dadoDotacao->valoresPrevisto as $ano => $valorPrevisto) {
                if (!array_key_exists($ano, $acao->valoresPrevisto)) {
                    $acao->valoresPrevisto[$ano] = 0;
                }
                $acao->valoresPrevisto[$ano] += $valorPrevisto;
            }
        }

        foreach ($valoresAgrupados as $programa) {
            foreach ($programa->iniciativas as $iniciativas) {
                foreach ($iniciativas->valoresPrevisto as $ano => $valorPrevisto) {
                    if (!array_key_exists($ano, $programa->valoresPrevisto)) {
                        $programa->valoresPrevisto[$ano] = 0;
                    }
                    $programa->valoresPrevisto[$ano] += $valorPrevisto;
                }
            }
        }

        return $valoresAgrupados;
    }


    private function createObjectPrograma(StdClass $valor)
    {
        return (object)[
            'codigo' => $valor->programa,
            'anoExercicio' => $this->anoSessao,
            'instituicao' => $valor->instituicao,
            'valorBase' => 0,
            'iniciativas' => [],
            'valoresPrevisto' => []
        ];
    }

    private function createObjectAcao($valor)
    {
        return (object)[
            'codigo' => $valor->acao,
            'anoExercicio' => $this->anoSessao,
            'valorBase' => 0,
            'valoresPrevisto' => [],
        ];
    }

    private function calulaValoresSintetico()
    {
        $sqlDotacao = $this->executaDotacaoSaldo();
        $formulaValor = $this->getFormulaCalculaValor();

        $sql = "
            select dotacao.o58_programa as programa,
                   dotacao.o58_projativ as acao,
                   orcelemento.o56_codele as elemento,
                   orcdotacao.o58_instit as instituicao,
                   sum($formulaValor) as valor
                from ({$sqlDotacao}) dotacao
                join orcelemento on orcelemento.o56_elemento = dotacao.o58_elemento
                                and orcelemento.o56_anousu = {$this->anoSessao}
                join orcdotacao on orcdotacao.o58_coddot = dotacao.o58_coddot
                               and orcdotacao.o58_anousu = {$this->anoSessao}
            group by 1, 2, 3, 4
            limit 10
        ";

        return \db_utils::getCollectionByRecord(db_query($sql));
    }

    private function executaDotacaoSaldo()
    {
        $orgaosUsuario = $this->getOrgaosLiberadoUsuario();
        $orgaos = implode(',', $orgaosUsuario);
        $filtros = ["o58_orgao in ($orgaos)"];

        if (!$this->instituicao->prefeitura) {
            $filtros[] = "o58_instit = {$this->instituicao->codigo}";
        }

        return db_dotacaosaldo(
            8,
            2,
            2,
            true,
            implode(' and ', $filtros),
            $this->anoSessao,
            "{$this->anoSessao}-01-01",
            $this->dataUsuario,
            8,
            0,
            true
        );
    }

    private function getFormulaCalculaValor()
    {
        if ($this->planejamento->pl2_base_calculo === 1) {
            return "dot_ini + suplementado_acumulado - reduzido_acumulado";
        } else {
            switch ($this->planejamento->pl2_base_despesa) {
                case 1: // empenho
                    return "empenhado_acumulado - anulado_acumulado";
                    break;
                case 2: // liquidado
                    return "liquidado_acumulado";
                    break;
                case 3: // pago
                    return "pago_acumulado";
                    break;
            }
        }
    }

    /**
     * @param array $valoresDotacao
     * @return array
     */
    private function calculaProjecao(array $valoresDotacao)
    {
        $exerciciosPlanejamento = $this->planejamento->execiciosPlanejamento();
        $fatores = FatorCorrecaoDespesa::where('pl7_planejamento', '=', $this->planejamento->pl2_codigo)
            ->get();

        // aplica correção por elemento
        foreach ($valoresDotacao as $valorDotacao) {
            $elemento = $valorDotacao->elemento;
            $valor = $valorDotacao->valor;

            foreach ($exerciciosPlanejamento as $exercicio) {
                $fator = $fatores->filter(function ($fator) use ($exercicio, $elemento) {
                    if ($fator->pl7_orcelemento == $elemento && $fator->pl7_exercicio == $exercicio) {
                        return $fator;
                    }
                })->shift();

                if (isset($fator->pl7_percentual)) {
                    $valor = round($valor * (1 + ($fator->pl7_percentual / 100)), $this->precisaoRound);
                }

                $valorDotacao->valoresPrevisto[$exercicio] = $valor;
            }
        }

        return $valoresDotacao;
    }



    private function persisteCalculo($valoresAgrupados)
    {
        foreach ($valoresAgrupados as $programa) {
            $programaestrategico = new ProgramaEstrategico();
            $programaestrategico->pl9_anoorcamento = $programa->anoExercicio;
            $programaestrategico->pl9_orcprograma = $programa->codigo;
            $programaestrategico->pl9_instituicao = $programa->instituicao;
            $programaestrategico->pl9_valorbase = $programa->valorBase;
            $programaestrategico->planejamento()->associate($this->planejamento);
            $programaestrategico->save();

            $this->persisteValores(
                Valor::ORIGEM_PROGRAMA,
                $programaestrategico->pl9_codigo,
                $programa->valoresPrevisto
            );

            // persiste as iniciativas
            foreach ($programa->iniciativas as $dadosIniciativa) {
                $iniciativa = new Iniciativa();
                $iniciativa->pl12_orcprojativ = $dadosIniciativa->codigo;
                $iniciativa->pl12_anoorcamento = $dadosIniciativa->anoExercicio;
                $iniciativa->programaEstrategico()->associate($programaestrategico);
                $iniciativa->pl12_valorbase = $dadosIniciativa->valorBase;
                $iniciativa->save();

                $this->persisteValores(
                    Valor::ORIGEM_INICIATIVA,
                    $iniciativa->pl12_codigo,
                    $dadosIniciativa->valoresPrevisto
                );

                /**
                 *
                 * @todo implementar para persistir o detalhamento
                 * $iniciativa->detalhamento
                 */
            }
        }
    }

    /**
     * Salva o valor do plano
     * @param string $origem
     * @param integer $chave
     * @param array $valores
     */
    private function persisteValores($origem, $chave, array $valores)
    {
        foreach ($valores as $ano => $valorPrevisto) {
            $valor = new Valor();
            $valor->pl10_origem = $origem;
            $valor->pl10_chave = $chave;
            $valor->pl10_ano = $ano;
            $valor->pl10_valor = $valorPrevisto;
            $valor->save();
        }
    }

    private function log($tipo, $msg)
    {
        $date = new \DateTime();

        echo sprintf("%s - [%s] %s <br>", $date->format('Y-m-d H:i:s u'), $tipo, $msg);
    }
}
