<?php

namespace ECidade\Api\V1\Controllers\Auth;

use cl_db_usuarios;
use ECidade\Api\V1\Controllers\GenericApplicationController;
use ECidade\Api\V1\ResourceInterface;
use ECidade\Configuracao\Api\Generator\HashGenerator;
use ECidade\Configuracao\Api\Models\ApiCliente;
use Encriptacao;
use Exception;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Login extends GenericApplicationController implements ResourceInterface
{

    /**
     * @return string
     * @throws Exception
     */
    public function login()
    {
        $request = $this->request->request;
        $app = $this->application();

        if (!isset($app['authClient'])) {
            throw new AccessDeniedException('Aplicação não autenticada.', 401);
        }

        /**
         * @var ApiCliente $client
         */
        $client = $app['authClient'];

        if (!$request->has('user') || !$request->has('password')) {
            throw new NotFoundResourceException('Usuário ou Senha não informados.');
        }

        $user = $request->get('user');
        $password = Encriptacao::encriptaSenha($request->get('password'));

        $dao = new cl_db_usuarios();
        $where = " usuarioativo = 1 AND login = '{$user}' AND senha = '{$password}' ";
        $sql = $dao->sql_query_file(null, "id_usuario", null, $where);

        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) == 0) {
            throw new AccessDeniedException('Usuário não encontrado com os parâmetros informados.');
        }

        $validatedUser = pg_fetch_object($rs);

        $data = array(
            'idUsuario' => $validatedUser->id_usuario
        );

        return HashGenerator::encrypt($data, $client->getChave());
    }
}
