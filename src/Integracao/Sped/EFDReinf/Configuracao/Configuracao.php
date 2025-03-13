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

namespace ECidade\Integracao\Sped\EFDReinf\Configuracao;

use cl_efdreinfversao;
use cl_efdreinfversaoformulario;
use db_utils;
use ECidade\Integracao\Sped\Common\Interfaces\ConfiguracaoInterface;
use Exception;

class Configuracao implements ConfiguracaoInterface
{
    /**
     * @var
     */
    private $versao;

    /**
     * @return mixed
     * @throws Exception
     */
    public function getVersao()
    {
        if (empty($this->versao)) {
            $dao = new cl_efdreinfversao();
            $sql = $dao->sql_query_file(null, 'efd01_versao', '1 desc limit 1');
            $rs = db_query($sql);

            if (!$rs) {
                throw new Exception('Não foi possível buscar a versão configurada do EFD Reinf');
            }

            $this->versao = db_utils::fieldsMemory($rs, 0)->efd01_versao;
        }

        return $this->versao;
    }

    /**
     * @param $tipo
     * @return mixed
     * @throws Exception
     */
    public function getFormulario($tipo)
    {
        $where = "    efd03_esocialformulariotipo = {$tipo}";
        $where .= "and efd03_versao = '" . $this->getVersao() . "'";

        $dao = new cl_efdreinfversaoformulario();
        $sql = $dao->sql_query_file(null, 'efd03_avaliacao', null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar formulários do EFD Reinf');
        }

        if (pg_num_rows($rs) == 0) {
            throw new Exception("Não foi localizado o formulário do EFD Reinf na versão {$this->getVersao()}");
        }

        return db_utils::fieldsMemory($rs, 0)->efd03_avaliacao;
    }

    /**
     * @param $sVersao
     * @return \stdClass[]
     * @throws Exception
     */
    public function getFormulariosPorVersao($sVersao)
    {
        $where = "efd03_versao = '{$sVersao}'";
        $campos = 'efd03_avaliacao as formulario, efd03_esocialformulariotipo as tipo, efd03_versao as versao';
        $dao = new cl_efdreinfversaoformulario();
        $sql = $dao->sql_query_file(null, $campos, null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar formulários do EFD Reinf');
        }

        return db_utils::getCollectionByRecord($rs);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getVersoesAtualizar()
    {
        $where = " efd03_versao::float >= " . $this->getVersao();
        $dao = new cl_esocialversaoformulario();
        $sql = $dao->sql_query_file(null, 'distinct efd03_versao', 'efd03_versao', $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar as versões do EFD Reinf');
        }

        return db_utils::makeCollectionFromRecord($rs, function ($data) {
            return $data->efd03_versao;
        });
    }

    /**
     * Retorna todos formulários da versão atual
     *
     * @return \stdClass[]
     * @throws \Exception
     */
    public static function getFormulariosVersaoAtual()
    {
        $configuracao = new static();
        return $configuracao->getFormulariosPorVersao($configuracao->getVersao());
    }


    /**
     * Retorna o Formulario do tipo informado na versão atual
     *
     * @param $tipoFormulario
     * @return stdClass|null
     * @throws Exception
     */
    public static function getFormularioDoTipoNaVersaoAtual($tipoFormulario)
    {
        $formularios = self::getFormulariosVersaoAtual();

        foreach ($formularios as $formulario) {
            if ($formulario->tipo == $tipoFormulario) {
                return $formulario;
            }
        }
        return null;
    }
}