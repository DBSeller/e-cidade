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

namespace ECidade\Patrimonial\Ouvidoria\Externa\WebService;

use ECidade\Patrimonial\Protocolo\Servicos\AndamentoProcessoService;
use ECidade\Patrimonial\Protocolo\Modelo\AndamentoProcessoInterno;
use Exception;
use stdClass;
use Symfony\Component\Debug\Tests\DebugClassLoaderTest;

class EnviarMensagem
{
    /**
     * @var string
     */
    private $mensagem;

    /**
     * @var int
     */
    private $codigoProcesso;

    /**
     * @var int
     */
    private $codigoAndamento;

    /**
     * @var array
     */
    private $anexos;

    /**
     * @var boolean
     */
    private $respostaOuvidoria;

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * @param string $mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = urldecode($mensagem);
    }

    /**
     * @return int
     */
    public function getCodigoProcesso()
    {
        return $this->codigoProcesso;
    }

    /**
     * @param int $codigoProcesso
     */
    public function setCodigoProcesso($codigoProcesso)
    {
        $this->codigoProcesso = $codigoProcesso;
    }

    /**
     * @return array
     */
    public function getAnexos()
    {
        return $this->anexos;
    }

    /**
     * @param array $anexos
     */
    public function setAnexos($anexos)
    {
        $this->anexos = $anexos;
    }

    /**
     * @return int
     */
    public function getCodigoAndamento()
    {
        return $this->codigoAndamento;
    }

    /**
     * @param int $codigoAndamento
     */
    public function setCodigoAndamento($codigoAndamento)
    {
        $this->codigoAndamento = $codigoAndamento;
    }

    /**
     * @return boolean
     */
    public function isRespostaOuvidoria()
    {
        return $this->respostaOuvidoria;
    }

    /**
     * @param boolean $respostaOuvidoria
     */
    public function setRespostaOuvidoria($respostaOuvidoria)
    {
        $this->respostaOuvidoria = $respostaOuvidoria == 0 ? false : true;
    }

    /**
     * @throws \ParameterException
     * @throws \Exception
     */
    public function salvar()
    {
        $response = new stdClass();

        try {
            $parametros = new stdClass();

            $parametros->codigoProcesso = $this->codigoProcesso;
            $parametros->codigoAndamento = $this->codigoAndamento;
            $parametros->mensagem = urldecode($this->mensagem);
            $parametros->respostaOuvidoria = $this->isRespostaOuvidoria();
            $parametros->acao = "mensagemCidadao";
            if ($this->isRespostaOuvidoria()) {
                $parametros->acao = "respostaCidadao";
            }

            if (!empty($this->anexos)) {
                $parametros->anexos = array();
                foreach ($this->anexos as $anexo) {
                    $parametros->anexos[] = $anexo;
                }
            }

            $service = new AndamentoProcessoService($parametros);
            db_inicio_transacao();
            $service->salvarMensagemOuvidoria();

            db_fim_transacao(false);

            $response->message = "Enviado com sucesso!";
            $response->success = true;
        } catch (\Exception $ex) {
            $response->message = $ex->getMessage();
            $response->success = false;

            db_inicio_transacao(true);
        }

        return json_encode($response);
    }
}
