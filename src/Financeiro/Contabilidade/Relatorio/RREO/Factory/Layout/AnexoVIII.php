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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoVIII as Layout2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoVIII as Layout2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout\AnexoVIII as Layout2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\Layout\AnexoVIII as Layout2021;

class AnexoVIII
{
    /**
     * @param $ano
     * @return Layout2017|Layout2018
     * @throws \Exception
     */
    public static function getInstance($ano)
    {

        switch ($ano) {
            case "2017":
                return new Layout2017();
            break;

            case "2018":
                return new Layout2018();
            break;

            case "2019":
            case "2020":
                return new Layout2019();
            break;

            case "2021":
                if (db_getsession("DB_itemmenu_acessado") == 331379 ||
                    db_getsession("DB_itemmenu_acessado") == 8079) {
                    return new Layout2019();
                } else {
                    return new Layout2021();
                }

                return new Layout2021();
            break;

            default:
                throw new \Exception("Não existe layout para o ano {$ano} informado.");
        }
    }
}
