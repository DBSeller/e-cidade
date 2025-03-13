<?php
/**
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

namespace ECidade\Lib\Request\Storage\Curl;

use ECidade\Lib\Request\Curl;
use \Exception;
use \JSON;

/**
 * Class Put
 * @package ECidade\Lib\Request\Storage\Curl
 *
 */
class Put
{
    const ATTRIBUTES = [
        "visibility",
        "metadata",
        "file_father",
        "sign_required",
        "owner",
        "revoked",
        "allowed",
        "signers",
        "signers_signed"
    ];
    private $autenticacao;
    private $file_id;

    /**
     * @throws Exception
     */
    public function __construct(Autenticacao $autenticacao)
    {
        $this->autenticacao = $autenticacao;
        $this->autenticacao->execute();
    }

    public function setFileId($file_id = null)
    {
        if (!empty($file_id)) {
            $this->file_id = $file_id;
            return $this;
        }

        return $this->file_id;
    }

    /**
     * @throws Exception
     */
    public function update($attributes)
    {
        if (empty($this->file_id)) {
            throw new Exception('Necessário informar o ID do arquivo para alterar.');
        }

        if (empty($attributes) || empty(array_intersect(self::ATTRIBUTES, array_keys($attributes)))) {
            throw new Exception("Informe um destes atributos a alterar: \n". implode(", ", self::ATTRIBUTES));
        }

        $authorization  = $this->autenticacao->getTokenType();
        $authorization .= ' ';
        $authorization .= $this->autenticacao->getAccessToken();

        $headers = [
            'Authorization' => $authorization,
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $client  = new \GuzzleHttp\Client();

        try {
            $arquivo = $client->put($this->autenticacao->url() . '/api/files/' . $this->file_id, [
                'headers' => $headers,
                'form_params' => $attributes,
                'http_errors' => false
            ]);

            if ($arquivo->getStatusCode() != 200) {
                throw new \Exception($arquivo->getBody());
            }
        } catch (\Exception $e) {
            return JSON::create()->parse($e->getMessage());
        }

        $response = JSON::create()->parse($arquivo->getBody()->getContents());

        return $response;
    }
}
