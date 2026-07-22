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

namespace ECidade\Saude\Laboratorio\Integracao\Luckmann\Service;

use ECidade\Saude\Laboratorio\Integracao\Luckmann\Collection\TagsXML;
use ECidade\Library\File\File;
use ECidade\Saude\Laboratorio\Integracao\Luckmann\Factory\Arquivo;
use ECidade\Saude\Laboratorio\Integracao\Luckmann\Model\TagXML as TagXMLModel;

/**
 * Class TagXML
 * Converte cada campo do JSON, em uma instância de TagXML
 * @package ECidade\Saude\Laboratorio\Integracao\Luckmann\Service
 */
class TagXML
{
    /**
     * @var TagsXML
     */
    private $tagsXmlCollection;

    /**
     * @var File
     */
    private $file;

    /**
     * @var int
     */
    private $tipoArquivo;

    /**
     * @var array
     */
    private $estruturaXml;

    /**
     * TagXML constructor.
     * @param TagsXML $tagsXml
     * @param File $file
     * @param $tipoArquivo
     */
    public function __construct(TagsXML $tagsXml, File $file, $tipoArquivo)
    {
        $this->tagsXmlCollection = $tagsXml;
        $this->file = $file;
        $this->tipoArquivo = $tipoArquivo;
    }

    /**
     * @return TagsXML
     * @throws \Exception
     */
    public function criarInstancias()
    {
        $arquivo = Arquivo::getPorTipo($this->tipoArquivo);
        $this->estruturaXml = json_decode($this->file->read($arquivo), true);

        $this->percorreEstruturaAtual(current($this->estruturaXml));

        return $this->tagsXmlCollection;
    }

    /**
     * @param $elementoArray
     */
    private function percorreEstruturaAtual($elementoArray)
    {
        $tagXml = new TagXMLModel();
        $tagXml->setNome($elementoArray['nome']);
        $tagXml->setCampoPai($elementoArray['campo_pai']);
        $tagXml->setUnico(isset($elementoArray['unico']) ? $elementoArray['unico'] : true);

        $this->tagsXmlCollection->add($tagXml);

        if (is_array($elementoArray['campos_filho'])) {
            foreach ($elementoArray['campos_filho'] as $elementoFilho) {
                $this->percorreEstruturaAtual(current($elementoFilho));
            }
        }
    }
}
