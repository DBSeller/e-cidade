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

namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\IdObjetivoProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\RemoverObjetivoProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarObjetivoProgramaEstrategico;
use App\Domain\Financeiro\Planejamento\Services\ObjetivoProgramaEstrategicoService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

/**
 * Class ObjetivoProgramaEstrategioController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class ObjetivoProgramaEstrategioController extends Controller
{
    /**
     * @var ObjetivoProgramaEstrategicoService
     */
    private $service;

    public function __construct(ObjetivoProgramaEstrategicoService $service)
    {
        $this->service = $service;
    }

    public function buscar(Request $request)
    {
        $objetivos = $this->service->buscar($request->all());
        return new DBJsonResponse($objetivos, "Objetivos encontrados.");
    }

    /**
     * @param SalvarObjetivoProgramaEstrategico $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function salvar(SalvarObjetivoProgramaEstrategico $request)
    {
        return new DBJsonResponse($this->service->salvarFromRequest($request), 'Objetivo salvo com sucesso.');
    }

    /**
     * @param RemoverObjetivoProgramaEstrategico $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function delete(RemoverObjetivoProgramaEstrategico $request)
    {
        $this->service->remover($request->get('pl11_codigo'));
        return new DBJsonResponse([], 'Objetivo removido com sucesso.');
    }

    /**
     * Calcula o saldo do objetivo estratégico com base nas iniciativas vinculadas a ele
     * @param IdObjetivoProgramaEstrategico $request
     * @return DBJsonResponse
     */
    public function calculaSaldoIniciativa(IdObjetivoProgramaEstrategico $request)
    {
        $idIniciativa = $request->has('pl12_codigo') ? $request->get('pl12_codigo') : null;
        $saldos = $this->service->calcularSaldoIniciativa($request->get('pl11_codigo'), $idIniciativa);
        return new DBJsonResponse($saldos, 'Saldo do objetivo.');
    }
}
