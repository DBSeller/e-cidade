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
use App\Domain\Financeiro\Planejamento\Models\MetasIniciativa;
use App\Domain\Financeiro\Planejamento\Models\OrgaoPrograma;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use App\Domain\Financeiro\Planejamento\Relatorios\ProjecaoDespesaAgrupadaSinteticaPdf;
use Exception;

/**
 * Class RelatorioProjecaoDespesaAgrupadaSinteticoService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class RelatorioProjecaoDespesaAgrupadaSinteticoService extends RelatorioProjecaoDespesaService
{
    protected $carregarExercicioAnterior = false;

    /**
     * @var integer
     */
    private $idPrograma;
    /**
     * @var integer
     */
    private $idOrgao;

    public function __construct(array $filtros)
    {
        $this->processar($filtros);
    }

    public function emitirPdf()
    {
        $pdf = new ProjecaoDespesaAgrupadaSinteticaPdf();
        $pdf->setDados($this->dados);

        return $pdf->emitir();
    }

    /**
     * @param array $filtros
     * @throws Exception
     */
    protected function processar(array $filtros)
    {
        parent::processar($filtros);
        if (!empty($filtros['orcprograma_id'])) {
            $this->idPrograma = (int)$filtros['orcprograma_id'];
        }

        if (!empty($filtros['orcorgao_id'])) {
            $this->idOrgao = (int)$filtros['orcorgao_id'];
        }

        $this->organizaPlanejamento();
        $this->fitroAgruparPlanejamento();
        $this->totaliza();
    }

    public function buscarProgramasAplicandoFiltros()
    {
        return $this->planejamento->programas->filter(function (ProgramaEstrategico $programaEstrategico) {
            return empty($this->idPrograma) || $programaEstrategico->pl9_orcprograma === $this->idPrograma;
        })->filter(function (ProgramaEstrategico $programaEstrategico) {
            if (empty($this->idOrgao)) {
                return true;
            } else {
                $orgaosPrograma = $programaEstrategico->orgaos->map(function (OrgaoPrograma $orgaoPrograma) {
                    return $orgaoPrograma->pl27_orcorgao;
                })->toArray();
                return in_array($this->idOrgao, $orgaosPrograma);
            }
        });
    }

    protected function buscarProgramas()
    {
        $this->dados['dados'] = $this->buscarProgramasAplicandoFiltros()->map(
            function (ProgramaEstrategico $programaEstrategico) {
                return $this->montaStdPrograma($programaEstrategico);
            }
        )->toArray();

        if (empty($this->dados['dados'])) {
            throw new Exception('Sem registros para o filtro informado.');
        }
    }

    private function montaStdPrograma(ProgramaEstrategico $programaEstrategico)
    {
        $programa = $programaEstrategico->getProgramaOrcamento();

        $id = $programaEstrategico->pl9_codigo;
        $objeto = $this->objetoBaseAgrupador($id, $programa->formataCodigo(), $programa->o54_descr);
        $programaEstrategico->getValores()->each(function (Valor $valor) use ($objeto) {
            $objeto->valores[$valor->pl10_ano] = $valor->pl10_valor;
        });

        return $objeto;
    }

    protected function buscarIniciativas()
    {
        $this->dados['dados'] = $this->buscarProgramasAplicandoFiltros()->map(
            function (ProgramaEstrategico $programaEstrategico) {
                $programa = $this->montaStdPrograma($programaEstrategico);
                $programa->valoresIniciativa = $this->criaArrayValores($this->execiciosPlanejamento);

                $programaEstrategico->iniciativas->each(function (Iniciativa $iniciativa) use ($programa) {
                    $projeto = $iniciativa->getIniciativaOrcamento();
                    $objeto = $this->objetoBaseAgrupador(
                        $projeto->o55_projativ,
                        $projeto->formataCodigo(),
                        $projeto->o55_descr
                    );
                    $iniciativa->metas->each(function (MetasIniciativa $meta) use ($objeto, $programa) {
                        $objeto->valores[$meta->exercicio] = $meta->meta_financeira;
                        $programa->valoresIniciativa[$meta->exercicio] += $meta->meta_financeira;
                    });
                    $programa->iniciativas[] = $objeto;
                });

                return $programa;
            }
        )->toArray();
    }

    /**
     * Cria um objeto base para o agrupador
     * @param $codigo
     * @param $formatado
     * @param $descricao
     * @return object
     */
    private function objetoBaseAgrupador($codigo, $formatado, $descricao)
    {
        return (object)[
            'codigo' => $codigo,
            'formatado' => $formatado,
            'descricao' => $descricao,
            'valores' => $this->criaArrayValores($this->planejamento->execiciosPlanejamento()),
        ];
    }

    private function totaliza()
    {
        $this->dados['totalizador'] = $this->criaArrayValores($this->execiciosPlanejamento);

        foreach ($this->dados['dados'] as $dado) {
            foreach ($dado->valores as $ano => $valor) {
                $this->dados['totalizador'][$ano] += $valor;
            }
        }
    }
}
