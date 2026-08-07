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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoI as AnexoIFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoII as AnexoIIFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoIII as AnexoIIIFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoIV as AnexoIVFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoV as AnexoVFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoVI as AnexoVI2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\AnexoI as AnexoI2019;
use Exception;
use stdClass;

/**
 * Class AnexoVI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019
 */
class AnexoVI extends AnexoVI2018
{
    /**
     * @return stdClass
     * @throws Exception
     */
    protected function simplificadoAnexoI()
    {
        if (empty($this->simplificadoAnexoI)) {
            $anexo = AnexoIFactory::getInstance(
                $this->ano,
                $this->periodo,
                $this->instituicoes,
                AnexoI2019::MODELO_DETALHAMENTO_MENSAL
            );
            $this->simplificadoAnexoI = $anexo->getDadosSimplificado();
        }

        return $this->simplificadoAnexoI;
    }

    /**
     * @return stdClass
     * @throws Exception
     */
    protected function simplificadoAnexoII()
    {
        if (empty($this->simplificadoAnexoII)) {
            $anexo = AnexoIIFactory::getInstance($this->ano, $this->periodo);
            $this->simplificadoAnexoII = $anexo->getDadosSimplificado();
        }

        return $this->simplificadoAnexoII;
    }

    /**
     * @return stdClass
     */
    protected function simplificadoAnexoIII()
    {
        if (empty($this->simplificadoAnexoIII)) {
            $anexo = AnexoIIIFactory::getInstance($this->ano, $this->periodo);
            $this->simplificadoAnexoIII = $anexo->getDadosSimplificado();
        }
        return $this->simplificadoAnexoIII;
    }

    /**
     * @return stdClass
     * @throws Exception
     */
    protected function simplificadoAnexoIV()
    {
        if (empty($this->simplificadoAnexoIV)) {
            $anexo = AnexoIVFactory::getInstance($this->ano, $this->periodo);
            $this->simplificadoAnexoIV = $anexo->getDadosSimplificado();
        }

        return $this->simplificadoAnexoIV;
    }

    /**
     * @return object|void
     * @throws Exception
     */
    protected function simplificadoAnexoV()
    {
        if (empty($this->simplificadoAnexoV)) {
            $instituicoes = array_map(function ($instituicao) {
                return $instituicao->getCodigo();
            }, $this->instituicoes);

            $anexo = AnexoVFactory::getInstance($this->ano, $this->periodo);
            $anexo->setInstituicoes(implode(',', $instituicoes));
            $this->simplificadoAnexoV = $anexo->getDadosSimplificado();
        }

        return $this->simplificadoAnexoV;
    }
}
