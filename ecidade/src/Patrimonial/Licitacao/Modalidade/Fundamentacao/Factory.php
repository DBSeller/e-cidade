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
namespace ECidade\Patrimonial\Licitacao\Modalidade\Fundamentacao;

use \ParameterException;

/**
 * Factory que cria a modalidade com as suas fundamentações pelo código
 */
class Factory
{
    public function getModalidadeDepara($iModalidade)
    {
        switch ($iModalidade) {
            case 29:
                return new PRI();
            break;

            case 30:
                return new CNV();
            break;
      
            case 31:
                return new TMP();
            break;

            case 32:
                return new CNC();
            break;

            case 33:
                return new PRP();
            break;
      
            case 34:
                return new PRE();
            break;

            case 35:
                return new RIN();
            break;

            case 48:
                return new CNS();
            break;
      
            case 49:
                return new RDC();
            break;
      
            case 50:
                return new RPO();
            break;

            case 28:
            case 51:
            case 52:
                return new PRD();
            break;

            case 53:
                return new CHP();
            break;

            case 54:
                return new CPC();
            break;
      
            case 55:
                return new LEI();
            break;
      
            case 56:
                return new MAI();
            break;
      
            case 57:
                return new ESE();
            break;
      
            case 58:
                return new EST();
            break;
      
            case 59:
                return new LEE();
            break;
      
            case 60:
                return new RDE();
            break;

            case 62:
                return new PDE();
            break;
      
            case 61:
                return new CPP();
            break;

            case 63:
                return new CCP();
            break;

            case 64:
                return new CCE();
            break;

            case 65:
                return new PCE();
            break;

            case 66:
                return new PCP();
            break;

            default:
                throw new ParameterException("A Modalidade informada não tem Fundamentações.");
            break;
        }
    }
}
