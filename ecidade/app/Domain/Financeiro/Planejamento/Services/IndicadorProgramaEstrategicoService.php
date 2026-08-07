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

use App\Domain\Financeiro\Planejamento\Models\IndicadorProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Models\ProgramaEstrategico;
//phpcs:disable
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarIndicadorProgramaEstrategicoRequest;
//phpcs:enable
use Exception;

/**
 * Class ObjetivoProgramaEstrategico
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class IndicadorProgramaEstrategicoService
{


    public function __construct()
    {
        $this->serviceValores = new ValoresService();
    }

    /**
     * @param SalvarIndicadorProgramaEstrategicoRequest $request
     * @return IndicadorProgramaEstrategico|mixed
     * @throws Exception
     */
    public function salvarFromRequest(SalvarIndicadorProgramaEstrategicoRequest $request)
    {
        $codigo = $request->get('pl22_codigo');
        $codigoPrograma = $request->get('pl9_codigo');
        $codigoIndicador = $request->get('pl22_orcindica');
        $ano = $request->get('pl22_ano');

        $indicador = new IndicadorProgramaEstrategico();
        if (!empty($codigo)) {
            $indicador = IndicadorProgramaEstrategico::find($codigo);
        }

        $indicador->programaEstrategico()->associate(ProgramaEstrategico::with('indicadores')->find($codigoPrograma));

        $indicador->pl22_ano = $ano;
        $indicador->pl22_indice = $request->get('pl22_indice');
        $indicador->pl22_orcindica = $codigoIndicador;

        $indicador->save();
        
        return $this->find($indicador->pl22_codigo);
    }
    public function find($id)
    {
        $indicador = IndicadorProgramaEstrategico::with('indicador')->find($id);
        return $indicador;
    }
    /**
     * @param integer $id
     * @throws Exception
     */
    public function remover($id)
    {
        $indicador = IndicadorProgramaEstrategico::find($id);

        $indicador->delete();
    }
}
