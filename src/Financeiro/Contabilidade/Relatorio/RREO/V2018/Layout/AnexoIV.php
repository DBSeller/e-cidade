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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoIV as Layout2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoIV as Relatorio;

class AnexoIV extends Layout2017
{

    /**
     * Sobrescreve as legendas do relatório
     * @throws \BusinessException
     */
    protected function legendas()
    {
        $this->notas();
        $oRelatorio = new \relatorioContabil(Relatorio::CODIGO_RELATORIO, false);
        $oRelatorio->notaExplicativa($this->oPdf, $this->oRelatorio->getPeriodo()->getCodigo(), $this->oPdf->getAvailWidth());
    }

    protected function notas()
    {
        $this->nota1();
        $this->nota2();
    }
    protected function nota1() {
        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, '1 Como a Portaria MPS 746/2011 determina que os recursos provenientes desses aportes devem permanecer aplicados, no mínimo, por 5 (cinco) anos, essa receita não deverá compor o total das receitas previdenciárias do período de apuração', 0, 1);
    }

    protected function nota2()
    {
        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, '2 O resultado previdenciário poderá ser apresentada por meio da diferença entre previsão da receita e a dotação da despesa e entre a receita realizada e a despesa empenhada e as despesa liquidada.', 0, 1);
    }
}
