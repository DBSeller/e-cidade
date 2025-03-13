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

namespace ECidade\Patrimonial\Ouvidoria\Externa\Service;

use ECidade\Patrimonial\Ouvidoria\Externa\Model\PreProcesso as PreProcessoModel;
use ECidade\Patrimonial\Ouvidoria\Externa\TipoDado\Converter\Arquivo as ArquivoConverter;
use ECidade\Patrimonial\Ouvidoria\Externa\TipoDado\Collection\Arquivos as ArquivosCollection;
use ECidade\Patrimonial\Ouvidoria\Externa\TipoDado\Enum\Tipo;
use ECidade\Patrimonial\Ouvidoria\Externa\TipoDado\Model\Arquivo as ArquivoModel;

/**
 * Class PreProcesso
 * @package ECidade\Patrimonial\Ouvidoria\Externa\Service
 */
class PreProcesso
{
    /**
     * @var PreProcessoModel
     */
    private $preProcessoModel;

    /**
     * PreProcesso constructor.
     * @param PreProcessoModel $preProcessoModel
     */
    public function __construct(PreProcessoModel $preProcessoModel)
    {
        $this->preProcessoModel = $preProcessoModel;
    }

    /**
     * @return ArquivosCollection
     * @throws \Exception
     */
    public function getArquivos()
    {
        $json = \JSON::create()->parse(preg_replace('/\n/', ' ', $this->preProcessoModel->getMetadados()));
        $metadados = (array)$json->metadados;
        $arquivosCollection = new ArquivosCollection();

        foreach ($metadados as $dados) {
            if ($dados->tipo !== Tipo::ARQUIVO) {
                continue;
            }

            foreach ($dados->valor as $valor) {
                $arquivoModel = new ArquivoModel();
                $arquivoModel->setNome($dados->descricao);
                $arquivoModel->setValor($valor);

                $arquivoModel->setCaminho(ArquivoConverter::createFileByUrl($valor));

                $arquivosCollection->add($arquivoModel);
            }
        }

        return $arquivosCollection;
    }
}
