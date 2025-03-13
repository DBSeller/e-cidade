<?php
/*
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

namespace ECidade\File;

use Exception;
use SimpleXMLIterator;

class Xml
{
    /**
     * @param string $pathXml caminho do arquivo xml
     * @return array
     * @throws Exception
     */
    public static function xmlToArray($pathXml)
    {
        if (!file_exists($pathXml)) {
            throw new Exception("Arquivo {$pathXml} não encontrado.");
        }
        $xmlIterator = new SimpleXmlIterator($pathXml, null, true);
        return static::xmlIterator($xmlIterator);
    }

    /**
     * @param SimpleXMLIterator $xmlIterator
     * @return array
     */
    protected static function xmlIterator(SimpleXMLIterator $xmlIterator)
    {
        $a = array();
        for ($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) {
            if (!array_key_exists($xmlIterator->key(), $a)) {
                $a[$xmlIterator->key()] = array();
            }
            if ($xmlIterator->hasChildren()) {
                $a[$xmlIterator->key()][] = static::xmlIterator($xmlIterator->current());
            } else {
                $a[$xmlIterator->key()][] = strval($xmlIterator->current());
            }
        }
        return $a;
    }
}
