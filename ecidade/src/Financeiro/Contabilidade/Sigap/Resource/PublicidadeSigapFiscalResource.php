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

use ECidade\Financeiro\Contabilidade\Sigap\Model\PublicidadeSigapFiscal;

/**
 * Class PublicidadeSigapFiscalResource
 */
class PublicidadeSigapFiscalResource
{
    /**
     * @param array $publicidades
     * @return object[]
     */
    public static function toArray(array $publicidades)
    {
        return array_map(function (PublicidadeSigapFiscal $publicidade) {
            return self::toObject($publicidade);
        }, $publicidades);
    }

    /**
     * @param PublicidadeSigapFiscal $publicidade
     * @return object
     */
    public static function toObject(PublicidadeSigapFiscal $publicidade)
    {
        $codigoTipoRelatorio = $publicidade->getCodigoTipoRelatorio();
        $tipoRelatorio = $publicidade->getDescricaoTipoRelatorio();

        $periodo = (object) [
          'codigo' =>  $publicidade->getPeriodo()->getCodigo(),
          'descricao' =>  $publicidade->getPeriodo()->getDescricao()
        ];

        return (object) [
            'codigo' => $publicidade->getCodigo(),
            'ano' => $publicidade->getAno(),
            'codigoTipoRelatorio' => $codigoTipoRelatorio,
            'descricaoTipoRelatorio' => $tipoRelatorio,
            'descricao' => $publicidade->getDescricao(),
            'dataPublicacao' => $publicidade->getDataPublicacao()->getDate(),
            'meioComunicacao' => MeioComunicacaoResource::toObject($publicidade->getMeioComunicacao()),
            'periodo' => $periodo,
            'link' => $publicidade->getLink(),
            'localPublicacao' => $publicidade->getLocalPublicacao(),
            'codigoInstituicao' => $publicidade->getInstituicao()->getCodigo(),
        ];
    }
}
