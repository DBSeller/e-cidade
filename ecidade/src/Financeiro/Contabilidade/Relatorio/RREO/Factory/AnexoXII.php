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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoXII as Anexo2019;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoXII as Anexo2020;

class AnexoXII
{
    /**
     * @param $ano
     * @param $periodo
     * @return Anexo2019|Anexo2020
     * @throws \Exception
     */
    public static function getInstance($ano, $periodo)
    {
        if ($ano == 2019) {
            return new Anexo2019($ano, $periodo);
        } elseif ($ano >= 2020) {
            return new Anexo2020($ano, null, $periodo);
        } else {
            throw new \Exception("Não foi encontrado Anexo XII para o ano de {$ano}.");
        }
    }
}
