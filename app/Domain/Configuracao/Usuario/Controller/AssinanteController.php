<?php

namespace App\Domain\Configuracao\Usuario\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
// use App\Domain\Configuracao\Usuario\Model\Assinante;
use App\Domain\Configuracao\Usuario\Repository\AssinanteRepository;
use App\Domain\Configuracao\Usuario\Transform\AssinanteTransformer;
use \Exception;

/**
 * summary
 */
class AssinanteController extends Controller
{
    private $repository;

    /**
     */
    public function __construct(AssinanteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List the users that
     * are signers of files
     */
    public function index(Request $req)
    {
        $cpf_cnpj = $req->query('cpf_cnpj', null);

        if (!empty($cpf_cnpj)) {
            return new DBJsonResponse(
                (new AssinanteTransformer())->transform(
                    $this->repository->findSignerByCpfCnpj($cpf_cnpj)
                )
            );
        }

        return new DBJsonResponse(
            (new AssinanteTransformer())->transform(
                $this->repository->findAllSigners()
            )
        );
    }

    /**
     * @param integer $idUsuario
     */
    public function getByIdUsuario($idUsuario)
    {
        return new DBJsonResponse(
            (new AssinanteTransformer())->transform(
                $this->repository->findSignerByIdUsuario($idUsuario)
            )
        );
    }

    public function newSignerPermission(Request $req)
    {
        return $this->saveRepositorySignerPermission($req);
    }

    public function updateSignerPermission(Request $req)
    {
        $codigo = $req->input('codigo');
        
        if (empty($codigo)) {
            throw new Exception('Código do assinante não informado');
        }
        
        return $this->saveRepositorySignerPermission($req, $codigo);
    }
    
    public function saveSignerPermission(Request $req, $codigo = null)
    {
        $codigo = $req->input('codigo');
        
        if (empty($codigo)) {
            return $this->newSignerPermission($req);
        }
        
        return $this->updateSignerPermission($req);
    }
    
    protected function saveRepositorySignerPermission(Request $req, $codigo = null)
    {
        $id_usuario = $req->input('id_usuario');
        $nome       = $req->input('nome');
        $cpf_cnpj   = $req->input('cpf_cnpj');
        $tipo       = $req->input('tipo');
        $permissao  = $req->input('permissao');
        
        if (empty($id_usuario) || empty($nome) || empty($cpf_cnpj) || empty($tipo) || empty($permissao)) {
            throw new Exception('Verifique os parâmetros informados');
        }

        $fields = [
            'db67_id_usuario' => $id_usuario,
            'db67_nome'       => $nome,
            'db67_cpf_cnpj'   => $cpf_cnpj,
            'db67_tipo'       => $tipo,
            'db67_permissao'  => $permissao
        ];

        if (!empty($codigo)) {
            $fields['db67_codigo'] = $codigo;
        }

        return new DBJsonResponse($this->repository->saveSignerPermission($fields));
    }

    public function getAllSignersPermission(Request $req)
    {
        return new DBJsonResponse($this->repository->getAllSignersPermission());
    }

    public function deleteSignerPermission($assinante_id)
    {
        if (empty($assinante_id)) {
            throw new Exception('Informe o id do assinante a excluir.');
        }

        $this->repository->deleteSignerPermission($assinante_id);

        return new DBJsonResponse((object) ['message' => 'Excluído com sucesso.']);
    }
}
