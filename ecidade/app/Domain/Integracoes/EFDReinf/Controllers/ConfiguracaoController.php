<?php

namespace App\Domain\Integracoes\EFDReinf\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Integracoes\EFDReinf\Services\ConfiguracaoService;
use App\Http\Controllers\Controller;
use ECidade\Lib\Session\DefaultSession;
use Exception;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = DefaultSession::getInstance();
    }

    public function getConfig()
    {
        try {
            $instit  = $this->session->get(DefaultSession::DB_INSTIT);
            $service = ConfiguracaoService::getInstance($instit);
            $config  = $service->getConfig();

            return new DBJsonResponse($config);
        } catch (Exception $e) {
            return new DBJsonResponse('', $e->getMessage(), 500);
        }
    }

    public function saveConfig(Request $request)
    {
        try {
            $this->validate($request, [
                'efd07_filtraorgaounidade' => 'required'
            ]);

            $instit  = $this->session->get(DefaultSession::DB_INSTIT);
            $data    = (object) $request->all();
            $service = ConfiguracaoService::getInstance($instit);

            $service->save($data);

            return new DBJsonResponse('', 'Configurações Salvas com sucesso.');
        } catch (Exception $e) {
            return new DBJsonResponse('', $e->getMessage(), 500);
        }
    }
}
