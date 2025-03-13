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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoI as Anexo2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoI as Anexo2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\AnexoI as Anexo2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoI as Anexo2020;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoIIN13;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoIRondonia;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoIMDF;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2021\AnexoIIN13 as AnexoIIN132021;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2021\AnexoIRondonia as AnexoIRondonia2021;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2021\AnexoIMDF as AnexoIMDF2021;

use Exception;
use Periodo;

/**
 * Class AnexoI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory
 */
class AnexoI
{
    /**
     * @param $iAno
     * @param Periodo $oPeriodo
     * @param $aInstituicoes
     * @param $iModelo
     * @return Anexo2017|Anexo2018|Anexo2019
     * @throws Exception
     */
    public static function getInstance($iAno, Periodo $oPeriodo, $aInstituicoes, $iModelo)
    {
        if ($iAno < 2017) {
            throw new Exception("Modelo exclusivo para o ano maior ou igual à 2017.");
        }

        $oAnexo = null;
        $opcaoRelatorio = \ECidade\Configuracao\Opcao\Opcao::get('modelo_anexo_1_rgf', $iAno);

        switch ($iAno) {
            case 2017:
                $oAnexo = new Anexo2017($iAno, $oPeriodo, $aInstituicoes, $iModelo);
                break;
            case 2018:
            case 2019:
                $oAnexo = new Anexo2018($iAno, $oPeriodo, $aInstituicoes, Anexo2018::MODELO_DETALHAMENTO_MENSAL);
                break;
            case 2020:
                if (!empty($opcaoRelatorio) && $opcaoRelatorio->getValor() == 'mdf') {
                    $oAnexo = new AnexoIMDF($iAno, $oPeriodo, $aInstituicoes);
                }

                if (!empty($opcaoRelatorio) && $opcaoRelatorio->getValor() === 'rondonia') {
                    $oAnexo = new AnexoIRondonia($iAno, $oPeriodo, $aInstituicoes);
                }

                if (!empty($opcaoRelatorio) && $opcaoRelatorio->getValor() === 'in13') {
                    $oAnexo = new AnexoIIN13($iAno, $oPeriodo, $aInstituicoes);
                }

                break;
        }

        if ($iAno >= 2021) {
            if (empty($opcaoRelatorio) || $opcaoRelatorio->getValor() == 'mdf') {
                $oAnexo = new AnexoIMDF2021($iAno, $oPeriodo, $aInstituicoes);
            }

            if (!empty($opcaoRelatorio) && $opcaoRelatorio->getValor() === 'rondonia') {
                $oAnexo = new AnexoIRondonia2021($iAno, $oPeriodo, $aInstituicoes);
            }

            if (!empty($opcaoRelatorio) && $opcaoRelatorio->getValor() === 'in13') {
                $oAnexo = new AnexoIIN132021($iAno, $oPeriodo, $aInstituicoes);
            }
        }

        if (empty($oAnexo)) {
            throw new Exception("Anexo não localizado para o {$iAno}.");
        }
        return $oAnexo;
    }
}
