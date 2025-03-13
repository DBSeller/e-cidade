<?php

namespace App\Domain\Tributario\Arrecadacao\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Tributario\Arrecadacao\Services\ArrecadacaoPixService;
use ECidade\Tributario\Caixa\Repository\RecibopagaRepository;
use ECidade\Lib\Session\DefaultSession;

class RecibobarpixController extends Controller
{
    private $defaultSession;

    public function __construct()
    {
        $this->defaultSession = DefaultSession::getInstance();
    }

    public function gerarPix(Request $request)
    {
        $servicePixArrecadao = new ArrecadacaoPixService();

        $servicePixArrecadao->setCodigoArrecadacao($request->codigo_arrecadacao);
        $servicePixArrecadao->setConvenio($request->convenio);
        $servicePixArrecadao->setModelo($request->modelo);
        $servicePixArrecadao->setParcelaInicio($request->parcelainicio);
        $servicePixArrecadao->setParcelaFim($request->parcelafim);
        $servicePixArrecadao->setTipoDebito($request->tipo_debito);
        $servicePixArrecadao->setVencimento($request->vencimento);

        $servicePixArrecadao->seInstit($this->defaultSession->get(DefaultSession::DB_INSTIT));
        $servicePixArrecadao->setIp($this->defaultSession->get(DefaultSession::DB_IP));
        $servicePixArrecadao->setDatausu($this->defaultSession->get(DefaultSession::DB_DATAUSU));

        $servicePixArrecadao->gerarPix();
        
        return new DBJsonResponse([], 'Sucesso');
    }
}
