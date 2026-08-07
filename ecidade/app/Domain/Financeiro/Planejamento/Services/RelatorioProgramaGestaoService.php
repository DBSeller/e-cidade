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

use App\Domain\Financeiro\Planejamento\Models\Iniciativa;
use App\Domain\Financeiro\Planejamento\Models\MetasObjetivo;
use App\Domain\Financeiro\Planejamento\Models\ObjetivoProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\OrgaoPrograma;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\Comissao;
use App\Domain\Financeiro\Planejamento\Relatorios\ProgramaGestaoXls;
use App\Domain\Financeiro\Planejamento\Relatorios\ProgramaGestaoPdf;
use Exception;
use Illuminate\Support\Collection;

/**
 * Class RelatorioProgramaGestaoService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class RelatorioProgramaGestaoService
{
    /**
     * @var array
     */
    private $filtros;

    /**
     * @var Planejamento
     */
    private $planejamento;
    /**
     * @var Collection
     */
    private $programas;

    /**
     * @var array
     */
    private $dados = [];

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
        $this->processar();
    }

    /**
     * @return array
     */
    public function emitirPdf()
    {
        $relatorio = new ProgramaGestaoPdf();
        $relatorio->setFiltros($this->dados['filtros']);
        $relatorio->setDados($this->dados);
        if ($this->planejamento->pl2_tipo !== 'PPA') {
            $relatorio->setPeriodo($this->planejamento['pl2_ano_inicial']);
        }
        return $relatorio->emitir();
    }


    /**
     * @return array
     */
    public function emitirPlanilha()
    {
        $relarorio = new ProgramaGestaoXls();
        $relarorio->setDados($this->dados);
        return $relarorio->emitir();
    }

    private function processar()
    {
        $this->planejamento = Planejamento::find($this->filtros['planejamento_id']);

        $service = new ProgramaEstrategicoService();
        $filtros = ['planejamento' => $this->planejamento->pl2_codigo, 'apenasProgramaGestao' => true];
        if ($this->filtros['orcprograma_id'] !== '') {
            $filtros['orcprograma_id'] = $this->filtros['orcprograma_id'];
        }
        if ($this->filtros['orcorgao_id'] !== '') {
            $filtros['orcorgao_id'] = $this->filtros['orcorgao_id'];
        }
        $programas = $service->buscar($filtros);
        if ($programas->count() === 0) {
            throw new Exception("Não foi encontrado nenhum programa de gestão cadastrado para o planejamento.", 403);
        }

        $apresentaIdentidadeOrganizacional = $this->filtros['apresentaIdentidadeOrganizacional'] == 1 ? true : false;
        $this->dados['filtros'] = [
            'apresentaRegionalizacao' => $this->filtros['apresentaRegionalizacao'],
            'apresentaProduto' => $this->filtros['apresentaProduto'],
            'apresentaValoresMetaFisicas' => $this->filtros['apresentaValoresMetaFisicas'],
            'isPPA' => ($this->planejamento->pl2_tipo === 'PPA'),
            'apresentaIdentidadeOrganizacional' => $apresentaIdentidadeOrganizacional,
            'apresentaMetasObjetivoPrograma' => $this->filtros['apresentaMetasObjetivoPrograma'] == 1 ? true : false,
        ];

        $this->programas = $programas;
        $this->organizaDados();
    }
    private function organizaDados()
    {
        $this->organizaPlanejamento();
        $this->organizaProgramas();
        $this->organizaComissao();
    }
    private function organizaPlanejamento()
    {
        $this->dados['planejamento'] = $this->planejamento->toArray();
        $this->dados['planejamento']['exercicios'] = $this->planejamento->execiciosPlanejamento();
        $this->dados['planejamento']['missao'] =  $this->planejamento->pl2_missao;
        $this->dados['planejamento']['visao'] = $this->planejamento->pl2_visao;
        $this->dados['planejamento']['valores'] = $this->planejamento->pl2_valores;
    }
    private function organizaComissao()
    {
        $cgms = $this->planejamento->comissoes->map(function (Comissao $comissao) {
            return $comissao->cgm->z01_nome;
        })->toArray();
        $this->dados['planejamento']['comissao'] = $cgms;
    }
    private function organizaProgramas()
    {
        $this->programas->each(function (ProgramaEstrategico $programaEstrategico) {
            $programaOrcamento = $programaEstrategico->getProgramaOrcamento();
            $programa = $programaOrcamento->toArray();
            $programa['formatado'] = $programaOrcamento->formataCodigo();
            $programa['valores'] = $programaEstrategico->getValores()->toArray();
            $programa['orgaos'] = $this->organizaOrgaos($programaEstrategico->orgaos);
            $programa['objetivos'] = $this->organizaObjetivos($programaEstrategico->objetivos);

            $programa['iniciativas'] = $this->organizaIniciativas($programaEstrategico->iniciativas);

            if ($this->planejamento->pl2_composicao === 2 && $programaEstrategico->areasResultado->count()) {
                $area = $programaEstrategico->areasResultado->first();
                $programa['areasResultado'] = $area->toArray();
            }

            $this->dados['programas'][] = $programa;
        });
    }

    /**
     * @param Collection $iniciativas
     * @return Collection
     */
    private function organizaIniciativas(Collection $iniciativas)
    {
        $iniciativas = $iniciativas->map(function (Iniciativa $iniciativa) {
            $iniciativa->metas;
            $iniciativa->origem;
            $iniciativa->periodo;
            $iniciativa->regionalizacoes;
            $iniciativa->abrangencias;

            return $iniciativa->toArray();
        });

        return $iniciativas;
    }
    /**
     * @param \Illuminate\Database\Eloquent\Collection $orgaos
     * @return array
     */
    private function organizaOrgaos(Collection $orgaos)
    {
        return $orgaos->map(function (OrgaoPrograma $orgaoPrograma) {
            $orgao = $orgaoPrograma->getOrgaoOrcamento();
            return [
                'formatado' => $orgao->formataCodigo(),
                'descricao' => $orgao->o40_descr
            ];
        })->toArray();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $objetivos
     * @return array
     */
    private function organizaObjetivos(Collection $objetivos)
    {
        return $objetivos->map(function (ObjetivoProgramaEstrategico $objetivoProgramaEstrategico) {
            $objetivoProgramaEstrategico->iniciativas->each(function (Iniciativa $iniciativa) {
                $iniciativa->metas;
                $iniciativa->origem;
                $iniciativa->periodo;
                $iniciativa->regionalizacoes;
                $iniciativa->abrangencias;
            });

            $metas = $objetivoProgramaEstrategico->metas->map(function (MetasObjetivo $meta) {
                $array = $meta->toArray();
                $array['valores'] = $meta->getValores()->toArray();
                return $array;
            })->toArray();

            $objetivo = $objetivoProgramaEstrategico->toArray();
            $objetivo['metas'] = $metas;
            return $objetivo;
        })->toArray();
    }
}
