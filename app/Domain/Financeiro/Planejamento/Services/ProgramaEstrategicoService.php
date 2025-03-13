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

use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use stdClass;

/**
 * Class ProgramaEstrategicoService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class ProgramaEstrategicoService
{
    /**
     * @var ValoresService
     */
    private $serviceValores;

    public function __construct()
    {
        $this->serviceValores = new ValoresService();
    }

    /**
     * Remove um programa e todos seus vínculos
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function remover($id)
    {
        $programa = ProgramaEstrategico::find($id);

        $this->serviceValores->remover($programa->getValores());
        $programa->delete();
        return true;
    }

    /**
     * @param $id
     * @return array
     */
    public function find($id)
    {
        $programa = ProgramaEstrategico::with('planejamento')
            ->with('orgaos')
            ->with('objetivos')
            ->with('indicadores')
            ->find($id);
        $programa->getValores();
        return $programa->toArray();
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function saveFromReques(Request $request)
    {
        $codigo = $request->get("pl9_codigo");
        $programaEstrategico = new ProgramaEstrategico();
        $planejamento = Planejamento::find($request->get("pl9_planejamento"));
        $codigoPrograma = (int)$request->get("pl9_orcprograma");
        if (!empty($codigo)) {
            $programaEstrategico = ProgramaEstrategico::find($codigo);
        } else {
            if ($this->programaJaCadastrado($planejamento, $codigoPrograma)) {
                throw new Exception('Programa já cadastrado para o planejamento.', 403);
            }
        }

        $programaEstrategico->planejamento()->associate($planejamento);
        $programaEstrategico->pl9_orcprograma = $codigoPrograma;
        $programaEstrategico->pl9_anoorcamento = $planejamento->pl2_ano_inicial;
        $programaEstrategico->pl9_valorbase = 0;

        $programaEstrategico->save();

        $valores = str_replace('\"', '"', $request->get('valores'));
        $valores = \JSON::create()->parse($valores);


        $valor = new ValoresService();
        $valor->salvarColecao($valores, Valor::ORIGEM_PROGRAMA, $programaEstrategico->pl9_codigo);

        return $this->find($programaEstrategico->pl9_codigo);
    }


    private function programaJaCadastrado(Planejamento $planejamento, $codigoPrograma)
    {
        $existe = ProgramaEstrategico::where('pl9_planejamento', '=', $planejamento->pl2_codigo)
            ->where('pl9_orcprograma', '=', $codigoPrograma)
            ->first();

        if (!is_null($existe)) {
            return true;
        }

        return false;
    }

    /**
     * Implementado buscar por filtros
     * @param array $filtros
     * @return mixed
     * @throws Exception
     */
    public function buscar(array $filtros)
    {
        return ProgramaEstrategico::orderBy('pl9_orcprograma')
            ->when(!empty($filtros['planejamento']), function ($query) use ($filtros) {
                $query->where('pl9_planejamento', '=', $filtros['planejamento']);
            })->when(isset($filtros['filtrarPermissao']), function ($query) use ($filtros) {
                $query->validaPermissaoUsuario($filtros['DB_id_usuario'], $filtros['DB_anousu']);
            })->when(isset($filtros['apenasProgramaTematico']), function ($query) {
                $query->apenasProgramasTematicos();
            })->when(isset($filtros['apenasProgramaGestao']), function ($query) {
                $query->apenasProgramasGestao();
            })->when(
                isset($filtros['orcprograma_id']) && $filtros['orcprograma_id'] !== '',
                function ($query) use ($filtros) {
                    $query->where('pl9_orcprograma', '=', $filtros['orcprograma_id']);
                }
            )->when(!empty($filtros['orcorgao_id']), function ($query) use ($filtros) {
                $query->possuiOrgao($filtros['orcorgao_id']);
            })
            ->get();
    }

    public function calcularSaldoIniciativa($id, $idIniciativa = null)
    {
        $programaEstrategico = ProgramaEstrategico::find($id);
        $valores = $programaEstrategico->getValores();

        $valoresAno = IniciativaService::totalizarValoresPorAno($programaEstrategico->iniciativas, $idIniciativa);

        if ($valoresAno) {
            foreach ($valores as $valor) {
                $valor->pl10_valor -= $valoresAno[$valor->pl10_ano];
            }
        }

        return $valores;
    }

    /**
     * Retorna os programas estratégicos usando os filtros do orçamento (aba nova com datagrid)
     *
     * @param Planejamento $planejamento
     * @param array $instituicoes com os códigos das instituições
     * @param stdClass $filtros conforme retorno da função filtrosDespesaToPlanejamento
     * @return Collection coleção de ProgramaEstrategico
     */
    public function getByFiltroOrcamento(Planejamento $planejamento, array $instituicoes, stdClass $filtros)
    {
        $programas = ProgramaEstrategico::query()
            ->where('pl9_planejamento', '=', $planejamento->pl2_codigo)
            ->when(!empty($filtros->programa->valores), function (Builder $query) use ($filtros) {
                if ($filtros->programa->contem) {
                    $query->whereIn('pl9_orcprograma', $filtros->programa->valores);
                } else {
                    $query->whereNotIn('pl9_orcprograma', $filtros->programa->valores);
                }
            })->when(!empty($filtros->orgao->valores), function (Builder $query) use ($filtros) {
                if ($filtros->orgao->contem) {
                    $query->possuiOrgaos($filtros->orgao->valores);
                } else {
                    $query->naoPossuiOrgaos($filtros->orgao->valores);
                }
            })
            ->get()
            ->filter(function (ProgramaEstrategico $programaEstrategico) use ($instituicoes, $filtros) {
                return $programaEstrategico
                        ->iniciativas()
                        ->when(!empty($filtros->iniciativa->valores), function (Builder $query) use ($filtros) {
                            if ($filtros->iniciativa->contem) {
                                $query->whereIn('pl12_orcprojativ', $filtros->iniciativa->valores);
                            } else {
                                $query->whereNotIn('pl12_orcprojativ', $filtros->iniciativa->valores);
                            }
                        })
                        ->when(!empty($filtros->unidade->valores), function (Builder $query) use ($filtros) {
                            $query->filtrarOrgaoUnidade($filtros->unidade->valores, $filtros->unidade->contem);
                        })
                        ->when(!empty($filtros->funcao->valores), function (Builder $query) use ($filtros) {
                            $query->filtrarFuncao($filtros->funcao->valores, $filtros->funcao->contem);
                        })
                        ->when(!empty($filtros->subfuncao->valores), function (Builder $query) use ($filtros) {
                            $query->filtrarSubFuncao($filtros->subfuncao->valores, $filtros->subfuncao->contem);
                        })
                        ->when(!empty($filtros->elemento->valores), function (Builder $query) use ($filtros) {
                            $query->filtrarElemento($filtros->elemento->valores, $filtros->elemento->contem);
                        })
                        ->when(!empty($filtros->recurso->valores), function (Builder $query) use ($filtros) {
                            $query->filtrarRecurso($filtros->recurso->valores, $filtros->recurso->contem);
                        })
                        ->filtrarInstituicoes($instituicoes)
                        ->get()
                        ->count() > 0;
            });

        return $programas;
    }
}
