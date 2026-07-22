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

namespace ECidade\Financeiro\Contabilidade\Sagres\V2022;

use ECidade\Financeiro\Contabilidade\Sagres\OutrosArquivosSagresFiscal;
use ECidade\Financeiro\Contabilidade\Sagres\Repository\V2022\SagresRepository;

/**
 * Arquivo 4.41
 * Class PloaAcao
 * @package ECidade\Financeiro\Contabilidade\Sagres\V2022
 */
class PloaAcao extends OutrosArquivosSagresFiscal
{
    const TAG = 'PloaAcao';

    protected $colunasTemplate = [
        'codUnidadeGestora',
        'codigo',
        'descricao',
        'tipoAcao',
        'descMeta',
        'descUnidade',
    ];

    protected function processar()
    {
        return $this->getLinhasTemplate();
    }

    protected function getLinhasTemplate()
    {
        $repository = new SagresRepository();
        return $repository->{'get'.static::TAG}();
    }
}
