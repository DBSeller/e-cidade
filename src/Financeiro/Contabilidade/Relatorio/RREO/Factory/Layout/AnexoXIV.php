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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoXIV as Layout2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\Layout\AnexoXIV as Layout2021;

/**
 * @todo document class
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class AnexoXIV
{
    public static function getInstance($ano)
    {
        if ($ano >= 2018 && $ano <= 2020) {
                return new Layout2018();
        } elseif ($ano >= 2021) {
            if (db_getsession("DB_itemmenu_acessado") == 8079) {
                return new Layout2018();
            } else {
                return new Layout2021();
            }

            return new Layout2021();
        } else {
                throw new \Exception("Não existe layout para o Anexo XIV no ano {$ano}.");
        }
    }
}
