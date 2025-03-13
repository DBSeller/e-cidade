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

use ECidade\Educacao\Escola\Censo\Exportacao\ExportacaoCenso2018;

/**
 * Class ExportacaoCensoFactory
 */
class ExportacaoCensoFactory
{
    /**
     * @param $ano
     * @return ExportacaoCenso2018|ExportacaoCenso2012|ExportacaoCenso2013|ExportacaoCenso2014|ExportacaoCenso2015|ExportacaoCenso2016|ExportacaoCenso2017
     * @throws Exception
     */
    static function getInstanceByAno($ano)
    {
        $departamento = db_getsession('DB_coddepto');

        switch ($ano) {
            case 2012:
                require_once(modification('model/educacao/censo/ExportacaoCenso2012.model.php'));
                return new ExportacaoCenso2012($departamento, $ano);
            case 2013:
                require_once(modification('model/educacao/censo/ExportacaoCenso2013.model.php'));
                return new ExportacaoCenso2013($departamento, $ano);
            case 2014:
                require_once(modification('model/educacao/censo/censo2014/ExportacaoCenso2014.model.php'));
                return new ExportacaoCenso2014($departamento, $ano);
            case 2015:
                require_once(modification('censo2015/ExportacaoCenso2015.model.php'));
                return new ExportacaoCenso2015($departamento, $ano);
            case 2016:
                return new ExportacaoCenso2016($departamento, $ano);
            case 2017:
                return new ExportacaoCenso2017($departamento, $ano);
            case 2018:
                return new ExportacaoCenso2018($departamento, $ano);
            default:
                throw new Exception("Layout para {$ano} não implementado.");
        }
    }
}
