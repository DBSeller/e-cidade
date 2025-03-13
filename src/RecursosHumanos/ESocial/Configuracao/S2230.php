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

namespace ECidade\RecursosHumanos\ESocial\Configuracao;
use ECidade\RecursosHumanos\ESocial\Configuracao\JSON;
/**
 * Class S2230
 * @package ECidade\RecursosHumanos\ESocial\Configuracao
 */
class S2230 extends JSON
{
    /**
     * Codigo do Arquio
     * @var string
     */
    const TIPO = 'S2230';

    /**
     * @var \DBDate
     */
    protected $dataEnvio;


    /**
     * S2230 construtor.
     */
    public function __construct()
    {
        $this->codigoArquivo = self::TIPO;
    }

    /**
     * @throws \Exception
     * @return true
     */
    public function salvar()
    {
        if (empty($this->dataEnvio)) {
            throw new \Exception("Data de envio não informada.");
        }

        $data = new \stdClass();
        $data->data_envio = $this->dataEnvio->getDate(\DBDate::DATA_EN);
        $this->escrever($data);

        return true;
    }

    /**
     * @return \stdClass|bool
     */
    public function get()
    {
        return $this->getPropriedadeArquivo(self::TIPO);
    }

    /**
     * @param \DBDate $dataEnvio
     */
    public function setDataEnvio(\DBDate $dataEnvio)
    {
        $this->dataEnvio = $dataEnvio;
    }
}