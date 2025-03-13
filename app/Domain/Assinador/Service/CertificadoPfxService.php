<?php

namespace App\Domain\Assinador\Service;

use App\Domain\Assinador\CertificadoUsuario;
use DateTime;
use DBLargeObject;
use Exception;
use GuzzleHttp\Client;
use UsuarioSistema;
use NFePHP\Common\Strings;

class CertificadoPfxService
{
    /**
     * @param UsuarioSistema $usuario
     * @return object
     * @throws Exception
     */
    public function buscarCertificadoPfxUsuario(UsuarioSistema $usuario)
    {
        $arquivoPfx = null;

        $certificadoUsuario = CertificadoUsuario::where('c142_usuario', $usuario->getCodigo())->first();
        if (!empty($certificadoUsuario)) {
            $arquivoPfx = $this->getArquivoPfx($certificadoUsuario);
        }

        if (empty($arquivoPfx)) {
            $arquivoPfx = $this->gerarNovoCertificado($usuario);
        }

        return (object)[
            'url' => ECIDADE_REQUEST_PATH . $arquivoPfx,
            'path' => $arquivoPfx
        ];
    }

    /**
     * @param CertificadoUsuario $certificadoUsuario
     * @return string
     * @throws Exception
     */
    private function getArquivoPfx(CertificadoUsuario $certificadoUsuario)
    {
        $usuario = new UsuarioSistema($certificadoUsuario->c142_usuario);
        if ($certificadoUsuario->c142_validade <= new DateTime()) {
            return $this->gerarNovoCertificado($usuario);
        }

        $cgm = $usuario->getCGM();
        $oid = $certificadoUsuario->c142_arquivopfx;
        $sArquivoCertificadoPFX = sprintf(
            'tmp/%s-%s.pfx',
            $cgm->getNomeCompleto(),
            $cgm->isFisico() ? $cgm->getCpf() : $cgm->getCnpj()
        );
        $sArquivoCertificadoPFX = str_replace(' ', '_', $sArquivoCertificadoPFX);

        db_query('begin;');
        $lEmitirArquivo = DBLargeObject::leitura($oid, $sArquivoCertificadoPFX);
        db_query('commit;');

        if (!$lEmitirArquivo || !file_exists($sArquivoCertificadoPFX)) {
            throw new Exception('Erro ao emitir certificado digital!');
        }

        return $sArquivoCertificadoPFX;
    }

    /**
     * @param UsuarioSistema $usuario
     * @return string
     * @throws Exception
     */
    private function gerarNovoCertificado(UsuarioSistema $usuario)
    {
        $urlApi = env('PFX_URL');
        $user = env('PFX_USER');
        $password = env('PFX_PASSWORD');
        if (empty($urlApi) | empty($user)) {
            throw new Exception('API de Certificado Digital não configurada!');
        }

        $cgm = $usuario->getCGM();
        $nomeCompleto = Strings::toASCII($cgm->getNomeCompleto());
        $nomeCertificado = sprintf(
            '%s:%s',
            $nomeCompleto,
            $cgm->isFisico() ? $cgm->getCpf() : $cgm->getCnpj()
        );
        $http = new Client(['verify' => false]);
        $options = [
            'json' => [
                    'subject' => [
                        'CN' => $nomeCertificado
                    ]
                ],
                'auth' => [$user, $password, 'Basic']
        ];
        $response = $http->post("{$urlApi}/generate", $options);
        $response = json_decode($response->getBody());
        $responseCertificado = $http->get($response->downloadURL, $options);
        $dadosCertificado = $responseCertificado->getBody()->getContents();

        db_query("begin;");
        $iOid = DBLargeObject::criaOID(true);
        DBLargeObject::writeContent($dadosCertificado, $iOid);
        db_query("commit;");

        $certificadoUsuario = CertificadoUsuario::where('c142_usuario', $usuario->getCodigo())->first();

        if (empty($certificadoUsuario)) {
            $certificadoUsuario = new CertificadoUsuario();
            $certificadoUsuario->c142_usuario = $usuario->getCodigo();
        }
        $certificadoUsuario->c142_arquivopfx = $iOid;
        $certificadoUsuario->c142_data = new DateTime();
        $certificadoUsuario->c142_validade = (new DateTime())->modify('+2 years')->modify('-1 day');
        $certificadoUsuario->save();

        $nomeCertificado = str_replace(' ', '_', $nomeCertificado);
        $nomeCertificado = str_replace(':', '_', $nomeCertificado);
        $sArquivoCertificadoPFX = "tmp/{$nomeCertificado}.pfx";
        file_put_contents($sArquivoCertificadoPFX, $dadosCertificado);
        return $sArquivoCertificadoPFX;
    }
}
