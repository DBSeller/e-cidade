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

namespace ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas;

use DBString;
use JSON;

class TipoLogradouro implements TabelasInterface
{
    protected $tipos = array();

    public function __construct()
    {
        $this->tipos = JSON::create()->parse(file_get_contents("arquivos/esocial/tabelas/tabela20.json"));
    }

    /**
     * Retorna o valor da tabela do esocial equivalente ao valor de um dado no e-cidade
     * @param $valor do dado no e-cidade
     * @return mixed
     */
    public function getValue($valor)
    {
        $tipo = array_filter($this->tipos, function($tipo) use ($valor) {
            return DBString::slugify($tipo->label) === DBString::slugify($valor);
        });

        $tipo = array_shift($tipo);

        return $tipo ? $tipo->value : null;
    }
}
