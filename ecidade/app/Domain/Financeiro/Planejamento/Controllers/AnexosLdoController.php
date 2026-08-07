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
use App\Domain\Financeiro\Planejamento\Requests\Relatorios\AnexosRequest;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoIIIService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoIIService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoIService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoIVService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoVIIIService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoVIIService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoVIService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioAnexoVService;
use App\Http\Controllers\Controller;

/**
 * Class AnexosLdoController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class AnexosLdoController extends Controller
{
    public function anexoUm(AnexosRequest $request)
    {
        $service = new RelatorioAnexoIService($request->all());
        $files = $service->emitir();

        return new DBJsonResponse($files, 'Anexo I - Metas Anuais');
    }

    public function anexoDois(AnexosRequest $request)
    {
        $service = new RelatorioAnexoIIService($request->all());
        $files = $service->emitir();

        $msg = 'Anexo II - Avaliação do cumprimento das metas fiscais do exercício anterior';
        return new DBJsonResponse($files, $msg);
    }

    public function anexoTres(AnexosRequest $request)
    {
        $service = new RelatorioAnexoIIIService($request->all());
        $files = $service->emitir();

        $msg = 'Anexo III - Metas fiscais atuais comparadas com as fixadas nos três exercícios anteriores';
        return new DBJsonResponse($files, $msg);
    }

    public function anexoQuatro(AnexosRequest $request)
    {
        $service = new RelatorioAnexoIVService($request->all());
        $files = $service->emitir();

        $msg = 'Anexo IV - Evolução do Patrimônio Líquido';
        return new DBJsonResponse($files, $msg);
    }

    public function anexoCinco(AnexosRequest $request)
    {
        $service = new RelatorioAnexoVService($request->all());
        $files = $service->emitir();

        $msg = 'Anexo V - Origem e Aplicação dos Recursos Obtidos com a Alienação de Ativos';
        return new DBJsonResponse($files, $msg);
    }

    public function anexoSeis(AnexosRequest $request)
    {
        $service = new RelatorioAnexoVIService($request->all());
        $files = $service->emitir();

        $msg = 'Anexo VI - Avaliação da Situação Financeira e Atuarial do RPPS';
        return new DBJsonResponse($files, $msg);
    }

    public function anexoSete(AnexosRequest $request)
    {
        $service = new RelatorioAnexoVIIService($request->all());
        $files = $service->emitir();

        $msg = 'Anexo VII - Estimativa e Compensação da Renúncia de Receita';
        return new DBJsonResponse($files, $msg);
    }

    public function anexoOito(AnexosRequest $request)
    {
        $service = new RelatorioAnexoVIIIService($request->all());
        $files = $service->emitir();

        $msg = 'Anexo VIII - Margem de Expansão das Despesas Obrigatórias de Caráter Continuado';
        return new DBJsonResponse($files, $msg);
    }
}
