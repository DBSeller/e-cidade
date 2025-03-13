<?php

namespace App\Domain\Client\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Client\Repositories\ClientRepository;

class ClientController extends Controller
{
    /**
     * Classe para autenticar um usuário na API do e-cidade com base no CPF / CNPJ
     * @param Request $request
     * @return DBJsonResponse
     */
    public function autenticacaoUsuario(Request $request)
    {
        try {
            $clientRepository = new ClientRepository;
            $aUser = $clientRepository->getUserEcidadeAuthByCpfCnpj($request->cpfcnpj);

            $aUser->aUser["access_token"] = $clientRepository->getAccessTokenUser($aUser->oUser);

            $clientRepository->updateSession($aUser->aUser["id_usuario"]);

            return new DBJsonResponse($aUser->aUser);
        } catch (\Exception $exception) {
            return new DBJsonResponse(
                null,
                $exception->getMessage(),
                ($exception->getCode() ? $exception->getCode() : 400)
            );
        }
    }
}
