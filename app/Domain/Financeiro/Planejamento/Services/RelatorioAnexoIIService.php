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

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoII;
use Illuminate\Support\Facades\DB;

/**
 * Class RelatorioAnexoIIService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class RelatorioAnexoIIService extends AnexosLDOService
{

    /**
     * @var XlsAnexoII
     */
    private $parser;
    /**
     * ano base do relatório
     * @var int
     */
    private $anoBase;
    /**
     * Essa collection é a projeção da receita de dois anos atrás do planejamento atual
     * @var array
     */
    private $estimativasReceita;
    /**
     *  Essa collection é a projeção da despesa de dois anos atrás do planejamento atual
     * @var array
     */
    private $estimativasDespesa;
    /**
     * @var float
     */
    private $valorPib;

    public function __construct(array $filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = new XlsAnexoII();
    }

    protected function processar()
    {
        parent::processar();
        $this->anoBase = $this->plano->pl2_ano_inicial - 2;
        $this->valorPib = $this->getPibAno($this->anoBase);
        $this->estimativaReceita();
        $this->estimativaDespesa();
        $this->processaLinhas();
        $this->calculaPibLinhasManuais();
    }

    public function emitir()
    {
        $this->parser->setDados($this->getLinhas());
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setNotaExplicativa($this->getNotaExplicativa());
        $this->parser->setAno($this->anoBase);
        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }


    protected function processaReceita($linha)
    {
        $estimativas = $this->estimativasCompativeis($linha->parametros->contas, $this->estimativasReceita);
        $estimativas->each(function ($estimativa) use ($linha) {
            $linha->vlr_previsto_ano_menos_dois += $estimativa->previsao_inicial;
            $linha->vlr_realizado_ano_menos_dois += $estimativa->arrecadado_acumulado;
        });

        $linha->previsto_pib_ano_menos_dois = ($linha->vlr_previsto_ano_menos_dois / $this->valorPib);
        $linha->realizado_pib_ano_menos_dois = ($linha->vlr_realizado_ano_menos_dois / $this->valorPib);
    }

    protected function processaDespesa($linha)
    {
        $estimativas = $this->estimativasCompativeis($linha->parametros->contas, $this->estimativasDespesa);
        $estimativas->each(function ($estimativa) use ($linha) {
            $linha->vlr_previsto_ano_menos_dois += $estimativa->previsao;
            $linha->vlr_realizado_ano_menos_dois += $estimativa->liquidado_acumulado;
        });
        $linha->previsto_pib_ano_menos_dois = ($linha->vlr_previsto_ano_menos_dois / $this->valorPib);
        $linha->realizado_pib_ano_menos_dois = ($linha->vlr_realizado_ano_menos_dois / $this->valorPib);
    }

    /**
     *
     */
    private function estimativaReceita()
    {
        $this->estimativasReceita = $this->estimativaReceitaOrcamento($this->anoBase, $this->codigosInstituicoes);
    }

    private function estimativaDespesa()
    {
        $filtros = [
            "o58_anousu = {$this->anoBase}",
            sprintf("o58_instit in (%s)", implode(',', $this->codigosInstituicoes))
        ];

        $dataInicio = "$this->anoBase-01-01";
        $dataFim = "$this->anoBase-12-31";
        $where = implode(' and ', $filtros);
        $sql = "
            with dotacoes as (
                select fc_dotacaosaldo(o58_anousu,o58_coddot, 3, '{$dataInicio}', '{$dataFim}') as valores_despesa,
                       o56_elemento as natureza
                from orcdotacao
                join orcelemento on (o56_codele, o56_anousu) = (o58_codele, o58_anousu)
                where {$where}
            ), valores as (
                select substr(valores_despesa,3,12)::float8 as dot_ini,
                       substr(valores_despesa,198,12)::float8 as liquidado_acumulado,
                       natureza
                from dotacoes
            )
            select sum(dot_ini) as previsao,
                   sum(liquidado_acumulado) as liquidado_acumulado,
                   natureza
            from valores
            group by natureza
        ";

        $this->estimativasDespesa = DB::select($sql);
    }

    private function calculaPibLinhasManuais()
    {
        $linhas = $this->getLinhas();
        $linhas[6]->previsto_pib_ano_menos_dois = ($linhas[6]->vlr_previsto_ano_menos_dois / $this->valorPib);
        $linhas[6]->realizado_pib_ano_menos_dois = ($linhas[6]->vlr_realizado_ano_menos_dois / $this->valorPib);

        $linhas[7]->previsto_pib_ano_menos_dois = ($linhas[7]->vlr_previsto_ano_menos_dois / $this->valorPib);
        $linhas[7]->realizado_pib_ano_menos_dois = ($linhas[7]->vlr_realizado_ano_menos_dois / $this->valorPib);

        $linhas[8]->previsto_pib_ano_menos_dois = ($linhas[8]->vlr_previsto_ano_menos_dois / $this->valorPib);
        $linhas[8]->realizado_pib_ano_menos_dois = ($linhas[8]->vlr_realizado_ano_menos_dois / $this->valorPib);
    }
}
