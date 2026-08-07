<?php

namespace App\Domain\Configuracao\Usuario\Repository;

use App\Domain\Configuracao\Usuario\Models\AssinantesDocumentos;
use App\Domain\Core\Base\Repository\BaseRepository;
use ECidade\Lib\Request\Storage\Curl\Get;
use ECidade\Lib\Request\Storage\Curl\Autenticacao;
use \Exception;
use \JSON;
use \stdClass;
use \UsuarioSistemaRepository;
use \CgmFisico;
use \cl_assinantesdocumentos;

final class AssinanteRepository extends BaseRepository
{
    protected $modelClass = AssinantesDocumentos::class;

    protected function handleResponse($storageResponse)
    {
        $infoRequest = $storageResponse->getInfo();
        $response    = $storageResponse->getResponse();

        if ($infoRequest['http_code'] != 200) {
            $response = JSON::create()->parse($response);
            $msg = $response->message;

            if (!empty($response->errors) && !empty($response->errors->permission)) {
                $msg .= "\n";
                $msg .= implode("\n", $response->errors->permission);
            }
            throw new Exception($msg);
        }

        return json_decode($response)->data;
    }

    protected function getSigners()
    {
        $getStorageData = new Get(Autenticacao::getInstance()->execute());
        $getStorageData->setRoute('/signers');
        $getStorageData->execute();

        return $getStorageData;
    }
    
    public function findSignerByCpfCnpj($cpf_cnpj)
    {
        $signers = $this->handleResponse($this->getSigners());
        $s = null;

        array_map(function ($signer) use ($cpf_cnpj, &$s) {

            $cpf_cnpj = preg_replace('/\D/', '', $cpf_cnpj);

            if ($cpf_cnpj == $signer->cpf_cnpj) {
                $s = $signer;
            }
        }, $signers);

        return is_null($s) ? [] : [$s];
    }
    
    public function findAllSigners()
    {
        return $this->handleResponse($this->getSigners());
    }
    
    public function findSignerByIdUsuario($idUsuario)
    {
        $assinante = $this->newQuery()
                          ->where('db67_id_usuario', $idUsuario)
                          ->orderBy('db67_id_usuario', 'desc')
                          ->get();

        if (empty($assinante) || $assinante->isEmpty()) {
            $usuario = UsuarioSistemaRepository::getPorCodigo($idUsuario);
            $cgm = $usuario->getCGM();

            if (!$cgm) {
                return [];
            }

            return [
                (object) [
                    'codigo'     => null,
                    'cpf_cnpj'   => ($cgm instanceof CgmFisico) ? $cgm->getCpf() : $cgm->getCnpj(),
                    'id_usuario' => $idUsuario,
                    'nome'      => $usuario->getNome(),
                    'permissao' => "ASSINANTE",
                    'tipo'      => ($cgm instanceof CgmFisico) ? "PF" : "PJ"
                ]
            ];
        }

        return $assinante;
    }

    public function findSignerByCodigo($codigo)
    {
        $assinante = $this->newQuery()
                          ->where('db67_codigo', $codigo)
                          ->orderBy('db67_codigo', 'desc')
                          ->first()
                          ->get();

        return $assinante;
    }

    public function getAllSignersPermission()
    {
        $assinantes = $this->newQuery()
                           ->orderBy('db67_codigo', 'desc')
                           ->get();

        if (empty($assinantes)) {
            return [];
        }

        return $assinantes;
    }

    public function deleteSignerPermission($assinante_id)
    {
        $this->newQuery()->where('db67_codigo', $assinante_id)->delete();
    }

    public function saveSignerPermission($attrs)
    {
        $assinante    = new AssinantesDocumentos();
        $assinanteDao = new cl_assinantesdocumentos();

        foreach ($attrs as $field => $value) {
            $assinanteDao->{$field} = $value;
            $assinante->{$field}    = $value;
        }

        if (empty($attrs['db67_codigo'])) {
            $assinanteDao->incluir(null);
            $assinante->db67_codigo = $assinanteDao->db67_codigo;
        } else {
            $assinanteDao->alterar($attrs['db67_codigo']);
        }

        if (!$assinanteDao->erro_status) {
            throw new Exception($assinanteDao->erro_msg);
        }

        return $assinante;
    }
}
