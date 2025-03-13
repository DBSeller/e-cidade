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

use App\Domain\Financeiro\Planejamento\Models\ObjetivoProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\Ods;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarObjetivoProgramaEstrategico;
use Exception;

/**
 * Class ObjetivoProgramaEstrategico
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class ObjetivoProgramaEstrategicoService
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
     * @param SalvarObjetivoProgramaEstrategico $request
     * @return ObjetivoProgramaEstrategico|mixed
     * @throws Exception
     */
    public function salvarFromRequest(SalvarObjetivoProgramaEstrategico $request)
    {
        $codigo = $request->get('pl11_codigo');
        $codigoPrograma = $request->get('pl9_codigo');
        $ods = $request->get('pl11_ods');
        $objetivo = new ObjetivoProgramaEstrategico();
        if (!empty($codigo)) {
            $objetivo = ObjetivoProgramaEstrategico::find($codigo);
        }
        $numero = $request->get('pl11_numero');

        if (!is_null($objetivo->numeroJaCadastrado($numero, $codigoPrograma, $codigo)->first())) {
            throw new Exception(sprintf('Já existe um objetivo cadastrado com o número %s.', $numero), 403);
        }

        $objetivo->programaEstrategico()->associate(ProgramaEstrategico::find($codigoPrograma));

        if (!empty($ods)) {
            $objetivo->ods()->associate(Ods::find($ods));
        }

        $objetivo->pl11_numero = $numero;
        $objetivo->pl11_descricao = $request->get('pl11_descricao');
        $objetivo->save();

        $valores = str_replace('\"', '"', $request->get('valores'));
        $valores = \JSON::create()->parse($valores);

        $objetivo->setValores(
            $this->serviceValores->salvarColecao($valores, Valor::ORIGEM_OBJETIVOS, $objetivo->pl11_codigo)
        );
        $objetivo->metas;
        return $objetivo;
    }

    /**
     * @param integer $id
     * @throws Exception
     */
    public function remover($id)
    {
        $objetivo = ObjetivoProgramaEstrategico::find($id);

        $this->serviceValores->remover($objetivo->getValores());
        $objetivo->delete();
    }

    /**
     * Busca os objetivos dos programas estratégicos aplicando os filtros informados
     * @param array $filtros
     * @return mixed
     */
    public function buscar(array $filtros)
    {
        return ObjetivoProgramaEstrategico::orderBy('pl11_numero')
            ->with('ods')
            ->when(!empty($filtros['programa']), function ($query) use ($filtros) {
                $query->where('pl11_programaestrategico', '=', $filtros['programa']);
            })
            ->get();
    }

    /**
     * Calcula o saldo do objetivo estratégico com base nas iniciativas vinculadas a ele
     * @param $id
     * @param null $idIniciativa
     * @return \Illuminate\Support\Collection
     */
    public function calcularSaldoIniciativa($id, $idIniciativa = null)
    {
        $objetivo = ObjetivoProgramaEstrategico::find($id);
        $valores = $objetivo->getValores();
        $valoresAno = IniciativaService::totalizarValoresPorAno($objetivo->iniciativas, $idIniciativa);

        if ($valoresAno) {
            $valores->each(function (Valor $valor) use ($valoresAno) {
                $valor->pl10_valor -= $valoresAno[$valor->pl10_ano];
            });
        }

        return $valores;
    }
}
