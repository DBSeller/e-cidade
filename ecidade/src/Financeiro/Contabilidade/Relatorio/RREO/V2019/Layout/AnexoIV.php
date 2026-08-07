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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoIV as Layout2018;

class AnexoIV extends Layout2018
{
	CONST PREVIDENCIARIO_RECEITAS_CORRENTES                = 1;
    CONST PREVIDENCIARIO_TOTAL_RECEITAS                    = 33;
    CONST PREVIDENCIARIO_ADMNISTRACAO                      = 34;
    CONST PREVIDENCIARIO_TOTAL_DESPESAS                    = 49;
    CONST PREVIDENCIARIO_RESULTADO_PREVIDENCIÁRIO          = 50;
    CONST PREVIDENCIARIO_RECURSOS_RPPS_ARRECADADOS_VALOR   = 51;
    CONST PREVIDENCIARIO_RESERVA_ORCAMENTARIA_VALOR        = 52;
    CONST PREVIDENCIARIO_PLANO_AMORTIZACAO                 = 53;
    CONST PREVIDENCIARIO_RECURSOS_COBERTURA                = 56;
    CONST PREVIDENCIARIO_CAIXA_EQUIVALENTES                = 57;
    CONST PREVIDENCIARIO_OUTROS_BENS                       = 59;
    CONST FINANCEIRO_RECEITAS_CORRENTES                    = 60;
    CONST FINANCEIRO_TOTAL_RECEITAS                        = 91;
    CONST FINANCEIRO_ADMNISTRACAO                          = 92;
    CONST FINANCEIRO_TOTAL_DESPESAS                        = 107;
    CONST FINANCEIRO_RESULTADO_PREVIDENCIARIO              = 108;
    CONST FINANCEIRO_RECURSOS_COBERTURA                    = 109;
    CONST FINANCEIRO_RECURSOS_RESERVA                      = 110;

    function nota2()
    {
        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, '2 O resultado previdenciário poderá ser apresentada por meio da diferença entre previsão da receita e a dotação da despesa e entre a receita realizada e a despesa liquidada (do 1º ao 5º bimestre) e a despesa liquidada (no 6º bimestre).', 0, 1);
    }


}
