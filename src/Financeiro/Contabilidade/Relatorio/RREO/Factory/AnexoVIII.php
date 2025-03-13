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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVIII as Anexo2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoVIII as Anexo2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\AnexoVIII as Anexo2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\AnexoVIII as Anexo2021;

/**
 * Factory
 */
class AnexoVIII
{
    /**
     * Retorna a instância do relatório
     * @param $ano
     * @param $periodo
     * @return Anexo2017|Anexo2018|Anexo2019
     * @throws \Exception
     */
    public static function getInstance($ano, $periodo)
    {

        switch ($ano) {
            case "2017":
                return new Anexo2017($ano, Anexo2017::CODIGO_RELATORIO, $periodo);
            break;

            case "2018":
                return new Anexo2018($ano, $periodo);
            break;

            case "2019":
            case "2020":
                return new Anexo2019($ano, $periodo);
            break;

            case "2021":
                if (db_getsession("DB_itemmenu_acessado") == 331379 ||
                    db_getsession("DB_itemmenu_acessado") == 8079) {
                    return new Anexo2019($ano, $periodo);
                } else {
                    return new Anexo2021($ano, $periodo);
                }
                return new Anexo2021($ano, $periodo);
            break;


            default:
                throw new \Exception("Não foi encontrado Modelo para o ano de {$ano}.");
        }
    }

    /**
     * Retorna o código do relatorio para o ano informado
     * @param $ano
     * @return int
     * @throws \Exception
     */
    public static function getCodigoRelatorio($ano)
    {

        switch ($ano) {
            case "2017":
                return Anexo2017::CODIGO_RELATORIO;
            break;

            case "2018":
                return Anexo2018::CODIGO_RELATORIO;
            break;

            case "2019":
            case "2020":
                return Anexo2019::CODIGO_RELATORIO;
            break;

            case "2021":
                if (db_getsession("DB_itemmenu_acessado") == 331379 ||
                    db_getsession("DB_itemmenu_acessado") == 8079) {
                    return Anexo2019::CODIGO_RELATORIO;
                } else {
                    return Anexo2021::CODIGO_RELATORIO;
                }

                return Anexo2021::CODIGO_RELATORIO;
            break;


            default:
                throw new \Exception("Não foi encontrado cadastro do relatório para o ano de {$ano}.");
        }
    }
}
