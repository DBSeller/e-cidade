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

namespace ECidade\RecursosHumanos\Pessoal\Service;

class SigapFolhaLotacaoService
{

    const SEMUSA      =  '0165';
    const SEMED       =  '0520';
    const FUNCULTURAL =  '0134';
    const AGENCIA     =  '0543';

    public static function rawQueryLotacao($iCodigoTCE)
    {
        switch ($iCodigoTCE) {
            case self::SEMUSA:
                $sWhere  = " and rhpessoalmov.rh02_lota in (
                    9,27,60,65,66,71,93,115,123,158,160,162,163,
                    164,165,224,0302,320,420,426,427, 421, 384
                )";
                break;

            case self::SEMED:
                $sWhere  = " and rhpessoalmov.rh02_lota in (
                    21,62,217,266,267,268,269,270,271,272,
                    273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,
                    291,292,293,294,295,296,297,298,299,300,301,319,419,424,108,334
                )";
                break;

            case self::FUNCULTURAL:
                $sWhere = " and rhpessoalmov.rh02_lota in (75, 88, 161)";
                break;

            case self::AGENCIA:
                $sWhere = " ";
                break;
            
            default:
                $sWhere = " and rhpessoalmov.rh02_lota NOT IN  (
                    9,27,60,65,66,71,93,115,123,158,159,160,165,162,163,164,224,416,420,421,21,62,217,266,267,268,269,
                    270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,
                    294,295,296,297,298,299,300,301,0302,320,384,419,426, 424, 425, 427, 75,88,161, 319,108,334
                )";
                break;
        }//switch

        return $sWhere;
    }

    public static function getDadosUnidadeOrcamentaria($iCodigoTCE)
    {
        switch ($iCodigoTCE) {
            case self::SEMUSA:
                $sWhere  = " and rhpessoalmov.rh02_lota in (
                    9,27,60,65,66,71,93,115,123,158,160,162,163,
                    164,165,224,0302,320,420,426,427, 421, 384
                )";
                
                $cnpj = '11155765000117';
                
                break;

            case self::SEMED:
                $sWhere  = " and rhpessoalmov.rh02_lota in (
                    21,62,217,266,267,268,269,270,271,272,
                    273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,
                    291,292,293,294,295,296,297,298,299,300,301,319,419,424,108,334
                )";
                
                $cnpj = '30634740000140';
                
                break;

            case self::FUNCULTURAL:
                $sWhere = " and rhpessoalmov.rh02_lota in (75, 88, 161)";
                
                $cnpj = '07219320000186';
                
                break;

            case self::AGENCIA:
                $sWhere = " ";
                
                $cnpj = '27759217000136';
                
                break;
            
            default:
                $sWhere = " and rhpessoalmov.rh02_lota NOT IN (75, 88, 161)";
                
                $cnpj = '05903125000145';
                
                break;
        }//switch

        return (object)[
            'whereLotacao' => $sWhere,
            'cnpj' => $cnpj
        ];
    }
}
