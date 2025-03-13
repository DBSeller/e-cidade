<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2022  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use App\Domain\Financeiro\Empenho\Models\AquisicaoProducaoRuralProcessos;
use App\Domain\Financeiro\Empenho\Models\RetencaoReceitasProdutorRural;
use App\Domain\Integracoes\EFDReinf\Services\ConfiguracaoService;
use CgmRepository;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use stdClass;

class EFDAquisicaoProducaoRural extends ProcessamentoAbstract implements ProcessamentoInterface
{

    private $cgm;
    private $instituicao;
    private $ano;
    private $mes;
    private $config;

    public function __construct($cgm = null, $instituicao = null, $ano = null, $mes = null)
    {
        $this->cgm = $cgm;
        $this->instituicao = $instituicao;
        $this->ano = $ano;
        $this->mes = $mes;
        $this->config = ConfiguracaoService::getInstance($instituicao);
    }

    public function processar()
    {
        if (empty($this->ano) && empty($this->mes)) {
            throw new \Exception("Você deve informar a competência");
        }

        $alteracaoDados = false;
        $dados = $this->preProcessar();
        $validaMd5 = true;

        if ($this->envioForcado) {
            $validaMd5 = false;
        }

        foreach ($dados as $dadosEvento) {
            $eventoFila = new Evento(Tipo::R2055, $this->cgm, $dadosEvento->referencia, $dadosEvento);
            if ($eventoFila->adicionarFila(false, $validaMd5)) {
                $alteracaoDados = true;
            }
        }

        return $alteracaoDados;
    }

    public function preProcessar()
    {
        // notas
        $retencao = new RetencaoReceitasProdutorRural;
        $filtroOrgaoUnidade = false;
        $unidadeCnpjBase = null;

        if ($this->config->filtraOrgaoUnidade()) {
            $filtroOrgaoUnidade = true;
            $unidade = CgmRepository::getByCodigo($this->cgm);
            $unidadeCnpjBase = substr($unidade->getCnpj(), 0, 8);
        }

        $notas = $retencao->notas($this->instituicao, $this->ano, $this->mes, $filtroOrgaoUnidade, $unidadeCnpjBase);

        // dados finais
        $dados = [];

        foreach ($notas as $nota) {
            $cgm       = $nota->cgm;
            $aquisicao = new stdClass;

            if (!array_key_exists($cgm, $dados)) {
                $contribuinte = ($this->config->filtraOrgaoUnidade()) ? $nota->unidade : $nota->contribuinte;

                $infoAquisProd = new stdClass;
                $infoAquisProd->inscricao_contribuinte = $contribuinte;
                $infoAquisProd->referencia = "{$this->ano}-{$this->mes} $contribuinte-$nota->nrinscProd";
                $infoAquisProd->perApur = "{$this->ano}-{$this->mes}";

                $infoAquisProd->ideEstabAdquir->tpinscadq = "1";
                $infoAquisProd->ideEstabAdquir->nrinscadq = $contribuinte;
                $infoAquisProd->ideEstabAdquir->ideprodutor->tpinscprod = strlen($nota->nrinscProd) >= 14 ? 1 : 2;
                $infoAquisProd->ideEstabAdquir->ideprodutor->nrinscprod = $nota->nrinscProd;
                $infoAquisProd->ideEstabAdquir->ideprodutor->detaquis = [];

                $dados[$cgm] = $infoAquisProd;
            }

            $aquisicao->indaquis     = $nota->indAquis;
            $aquisicao->vlrbruto     = floatval($nota->vlrbruto);
            $aquisicao->vlrcpdescpr  = floatval($nota->vlrcpdescpr);
            $aquisicao->vlrratdescpr = floatval($nota->vlrratdescpr);
            $aquisicao->vlrsenardesc = floatval($nota->vlrsenardesc);

            // verificar se existe processo judicial
            $nota->ids = substr($nota->ids, 1, -1);
            $ids = explode(',', $nota->ids);
            $processos = AquisicaoProducaoRuralProcessos::whereIn(
                'e157_retencaoreceitasprodutorrural',
                $ids
            )->get();

            if (count($processos) > 0) {
                foreach ($processos as $item) {
                    $proc = new stdClass;
                    $proc->nrprocjud    = $item->e157_nrprocjud;
                    $proc->vlrcpnret    = floatval($item->e157_vlrcpnret);
                    $proc->vlrratnret   = floatval($item->e157_vlrratnret);
                    $proc->vlrsenarnret = floatval($item->e157_vlrsenarnret);
                    $aquisicao->infoprocjud[] = $proc;
                }
            }

            $dados[$cgm]->ideEstabAdquir->ideprodutor->detaquis[] = $aquisicao;
        }

        return $dados;
    }
}
