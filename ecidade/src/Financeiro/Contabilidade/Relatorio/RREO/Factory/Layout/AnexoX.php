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

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoX as Anexo2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoX as Anexo2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2022\Layout\AnexoX as Anexo2022;
use PDFDocument;

/**
 * Class AnexoX
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout
 */
class AnexoX
{
    /**
     * Retorna a instância do layout
     *
     * @param  integer $ano Ano atual
     * @return Anexo2018
     */
    public static function getInstance($ano)
    {
        if ($ano < 2018) {
            return new Anexo2017(PDFDocument::PRINT_PORTRAIT);
        }

        if ($ano >= 2018 && $ano < 2022) {
            return new Anexo2018(PDFDocument::PRINT_PORTRAIT);
        }
        if ($ano >= 2022) {
            return new Anexo2022(PDFDocument::PRINT_PORTRAIT);
        }
    }
}
