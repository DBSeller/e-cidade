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

namespace ECidade\Financeiro\Contabilidade\Sigap\Resource;

use ECidade\Financeiro\Contabilidade\Sigap\Model\MeioComunicacao;

/**
 * Class MeioComunicacaoResource
 * @package ECidade\Financeiro\Contabilidade\Sigap\Resource
 */
class MeioComunicacaoResource
{
    /**
     * @param array $meiosComunicacao
     * @return object[]
     */
    public static function toArray(array $meiosComunicacao)
    {
        return array_map(function (MeioComunicacao $meioComunicacao) {
            return self::toObject($meioComunicacao);
        }, $meiosComunicacao);
    }

    /**
     * @param MeioComunicacao $meioComunicacao
     * @return object
     */
    public static function toObject(MeioComunicacao $meioComunicacao)
    {
        return (object) [
            'codigo' => $meioComunicacao->getCodigo(),
            'codigoSigap' => $meioComunicacao->getCodigoSigap(),
            'descricao' => $meioComunicacao->getDescricao(),
        ];
    }
}
