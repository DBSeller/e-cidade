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

use App\Domain\Financeiro\Planejamento\Models\DetalhamentoDespesa;
use App\Domain\Financeiro\Planejamento\Models\EstimativaReceita;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoIII;
use Illuminate\Support\Facades\DB;
use Instituicao;

class RelatorioAnexoIIIService extends AnexosLDOService
{
    protected $anosAnteriores = [];
    protected $fatorCorrecaoAnosAnteriores = [];

    protected $propriedadesAnoCorrente = [];
    protected $propriedadesAnoConstante = [];

    protected $estimativasReceitaAnteriores = [];
    protected $estimativasDespesaAnteriores = [];
    /**
     * @var Instituicao
     */
    private $prefeitura;
    /**
     * Layout para impressão
     * @var XlsAnexoIII
     */
    private $parser;

    public function __construct(array $filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = new XlsAnexoIII();
    }

    protected function processar()
    {
        parent::processar();
        $this->anosAnteriores[] = $this->plano->pl2_ano_inicial - 3;
        $this->anosAnteriores[] = $this->plano->pl2_ano_inicial - 2;
        $this->anosAnteriores[] = $this->plano->pl2_ano_inicial - 1;

        $this->indexaVarivaveis();

        $this->prefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();

        // carrega o fator de correção dos anos anteriores
        $this->fatorCorrecaoAnosAnteriores();
        // carrega o fator de correção dos anos da estimativa
        $this->fatorCorrecaoDespesa();
        $this->fatorCorrecaoReceita();

        $this->buscarProjecaoReceitaAnterior();
        $this->buscarProjecaoDespesaAnterior();
        $this->buscarProjecaoReceita();
        $this->buscarProjecaoDespesa();
        $this->processaLinhas();
    }

    public function emitir()
    {
        $this->parser->setDados($this->getLinhas());
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setNotaExplicativa($this->getNotaExplicativa());

        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    protected function processaReceita($linha)
    {
        foreach ($this->estimativasReceitaAnteriores as $ano => $estimativasOrcamento) {
            $estimativas = $this->estimativasCompativeis($linha->parametros->contas, $estimativasOrcamento);
            $estimativas->each(function ($estimativa) use ($linha, $ano) {
                if ($linha->ordem > 8) {
                    $propriedade = $this->propriedadesAnoConstante[$ano];
                    $fator = $this->fatorCorrecaoAnosAnteriores[$ano];
                    $linha->{$propriedade} += ($estimativa->previsao_inicial / $fator);
                } else {
                    $propriedade = $this->propriedadesAnoCorrente[$ano];
                    $linha->{$propriedade} += $estimativa->previsao_inicial;
                }
            });
        }

        $estimativas = $this->estimativasPlanejamentoCompativeisReceita($linha->parametros->contas);
        $estimativas->each(function (EstimativaReceita $estimativaReceita) use ($linha) {
            $fatores = $this->getFatoresReceita($estimativaReceita);

            $valores = $estimativaReceita->getValores();

            foreach ($valores as $valor) {
                if ($linha->ordem > 8) {
                    $propriedade = $this->propriedadesAnoConstante[$valor->pl10_ano];
                    $linha->{$propriedade} += $this->calculaValorConstanteReceita($valor, $fatores);
                } else {
                    $propriedade = $this->propriedadesAnoCorrente[$valor->pl10_ano];
                    $linha->{$propriedade} += $valor->pl10_valor;
                }
            }
        });
    }


    /**
     * @param \stdClass $linha
     */
    protected function processaDespesa($linha)
    {
        foreach ($this->estimativasDespesaAnteriores as $ano => $estimativasOrcamento) {
            $estimativas = $this->estimativasCompativeis($linha->parametros->contas, $estimativasOrcamento);
            $estimativas->each(function ($estimativa) use ($linha, $ano) {
                if ($linha->ordem > 8) {
                    $propriedade = $this->propriedadesAnoConstante[$ano];
                    $fator = $this->fatorCorrecaoAnosAnteriores[$ano];
                    $linha->{$propriedade} += ($estimativa->previsao / $fator);
                } else {
                    $propriedade = $this->propriedadesAnoCorrente[$ano];
                    $linha->{$propriedade} += $estimativa->previsao;
                }
            });
        }

        $estimativas = $this->estimativasPlanejamentoCompativeisDespesa($linha->parametros->contas);
        $estimativas->each(function (DetalhamentoDespesa $detalhamentoDespesa) use ($linha) {
            $fatores = $this->getFatoresDespesa($detalhamentoDespesa);

            $valores = $detalhamentoDespesa->getValores();
            foreach ($valores as $valor) {
                if ($linha->ordem > 8) {
                    $propriedade = $this->propriedadesAnoConstante[$valor->pl10_ano];
                    $linha->{$propriedade} += $this->calculaValorConstanteDespesa($valor, $fatores);
                } else {
                    $propriedade = $this->propriedadesAnoCorrente[$valor->pl10_ano];
                    $linha->{$propriedade} += $valor->pl10_valor;
                }
            }
        });
    }

    private function calculaValorConstanteReceita(Valor $valor, $fatores)
    {
        $valorConstante = $valor->pl10_valor;
        $fatorCorrecao = $this->filtraFatorReceitaUtilizadoNoAno($fatores, $valor->pl10_ano);
        if (!is_null($fatorCorrecao)) {
            if ($fatorCorrecao->deflator) {
                $fatorCorrecao->percentual *= -1;
            }

            $percentual = 1 + ($fatorCorrecao->percentual / 100);
            $valorConstante = $valor->pl10_valor / $percentual;
        }

        return $valorConstante;
    }

    private function calculaValorConstanteDespesa(Valor $valor, $fatores)
    {
        $valorConstante = $valor->pl10_valor;
        $fatorCorrecao = $this->filtraFatorDespesaUtilizadoNoAno($fatores, $valor->pl10_ano);
        if (!is_null($fatorCorrecao)) {
            if ($fatorCorrecao->deflator) {
                $fatorCorrecao->pl7_percentual *= -1;
            }

            $percentual = 1 + ($fatorCorrecao->pl7_percentual / 100);
            $valorConstante = $valor->pl10_valor / $percentual;
        }

        return $valorConstante;
    }

    /**
     * Esse metodo busca o fator dos anos anteriores de forma. Para anos futuros temos que alterar essa lógica pois
     * os inflatores estaram no planejamento e não no orcamento.
     */
    protected function fatorCorrecaoAnosAnteriores()
    {
        $inicio = $this->plano->pl2_ano_inicial - 3;
        $final = $this->plano->pl2_ano_inicial - 1;
        $idInstituicao = $this->prefeitura->getCodigo();
        $sql = "
        select o03_anoreferencia, sum(o03_valorparam) as valor
          from orccenarioeconomicoparam
          join orccenarioeconomico
              on orccenarioeconomico.o02_sequencial = orccenarioeconomicoparam.o03_orccenarioeconomico
         where o02_orccenarioeconomicogrupo = 2
           and o03_anoreferencia between $inicio and $final
           and o03_instit = {$idInstituicao}
         group by o03_anoreferencia
        order by o03_anoreferencia";

        $fatores = DB::select($sql);
        foreach ($fatores as $fator) {
            $this->fatorCorrecaoAnosAnteriores[$fator->o03_anoreferencia] = 1 + ($fator->valor / 100);
        }
    }

    private function buscarProjecaoReceitaAnterior()
    {
        foreach ($this->anosAnteriores as $anoAnterior) {
            $this->estimativasReceitaAnteriores[$anoAnterior] = $this->estimativaReceitaOrcamento(
                $anoAnterior,
                $this->codigosInstituicoes
            );
        }
    }

    private function buscarProjecaoDespesaAnterior()
    {
        foreach ($this->anosAnteriores as $anoAnterior) {
            $this->estimativasDespesaAnteriores[$anoAnterior] = $this->estimativaDespesaOrcamento(
                $anoAnterior,
                $this->codigosInstituicoes
            );
        }
    }

    private function indexaVarivaveis()
    {
        $ano = $this->plano->pl2_ano_inicial - 3;
        $this->propriedadesAnoCorrente[$ano] = "vlr_corrente_ano_menos_tres";
        $this->propriedadesAnoConstante[$ano] = "vlr_constante_ano_menos_tres";
        $ano = $this->plano->pl2_ano_inicial - 2;
        $this->propriedadesAnoCorrente[$ano] = "vlr_corrente_ano_menos_dois";
        $this->propriedadesAnoConstante[$ano] = "vlr_constante_ano_menos_dois";

        $ano = $this->plano->pl2_ano_inicial - 1;
        $this->propriedadesAnoCorrente[$ano] = "vlr_corrente_ano_menos_um";
        $this->propriedadesAnoConstante[$ano] = "vlr_constante_ano_menos_um";

        $ano = $this->plano->pl2_ano_inicial;
        $this->propriedadesAnoCorrente[$ano] = "vlr_corrente_ano_referencia";
        $this->propriedadesAnoConstante[$ano] = "vlr_constante_ano_referencia";

        $ano = $this->plano->pl2_ano_inicial + 1;
        $this->propriedadesAnoCorrente[$ano] = "vlr_corrente_ano_mais_um";
        $this->propriedadesAnoConstante[$ano] = "vlr_constante_ano_mais_um";

        $ano = $this->plano->pl2_ano_inicial + 2;
        $this->propriedadesAnoCorrente[$ano] = "vlr_corrente_ano_mais_dois";
        $this->propriedadesAnoConstante[$ano] = "vlr_constante_ano_mais_dois";
    }
}
