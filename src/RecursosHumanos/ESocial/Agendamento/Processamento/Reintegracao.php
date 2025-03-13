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
namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

class Reintegracao extends ProcessamentoAbstract implements ProcessamentoInterface
{
    private $cgm;

    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    public function processar()
    {
        $bAlteracao = false;
        $oDadosESocial = new DadosESocial();
        $oDadosESocial->setCgmEmpregador($this->cgm);

        $oDadosPreenchimento = $oDadosESocial->getPorTipo(Tipo::REINTEGRACAO);
        $oFormatter = FormatterFactory::get(Tipo::S2298);
        $aDadosPreenchimento = $oFormatter->formatar($oDadosPreenchimento);

        foreach ($aDadosPreenchimento as $iIndice => $oDados) {
            $oEvento = new Evento(Tipo::S2298, $this->cgm, $oDados->referencia, $oDados);
            $oEvento->iContador = $iIndice;

            if ($oEvento->adicionarFila()) {
                $bAlteracao = true;
            }
        }

        return $bAlteracao;
    }

    public function getCgm()
    {
        return $this->cgm;
    }

    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }
}
