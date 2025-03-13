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

namespace ECidade\RecursosHumanos\ESocial\Model;

use cl_esocialversao;
use cl_esocialversaoformulario;
use db_utils;
use Exception;
use stdClass;

/**
 * Class Configuracao
 * @package ECidade\RecursosHumanos\ESocial\Model
 */
class Configuracao
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
            $dao = new cl_esocialversao();
            $sql = $dao->sql_query_file(null, 'rh210_versao', '1 desc limit 1');
            $rs = db_query($sql);

            if (!$rs) {
                throw new Exception('Não foi possível buscar a versão configurada do e-Social');
            }

            $this->versao = db_utils::fieldsMemory($rs, 0)->rh210_versao;
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
        $where = "    rh211_esocialformulariotipo = {$tipo}";
        $where .= " and rh211_versao = '" . $this->getVersao() . "'";

        $dao = new cl_esocialversaoformulario();
        $sql = $dao->sql_query_file(null, 'rh211_avaliacao', null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar formulários do e-Social');
        }

        if (pg_num_rows($rs) == 0) {
            throw new Exception("Não foi localizado o formulário do e-Social na versão {$this->getVersao()}");
        }

        return db_utils::fieldsMemory($rs, 0)->rh211_avaliacao;
    }

    /**
     * @param $sVersao
     * @return stdClass[]
     * @throws Exception
     */
    public function getFormulariosPorVersao($sVersao)
    {
        $where = "rh211_versao = '{$sVersao}'";
        $campos = 'rh211_avaliacao as formulario, rh211_esocialformulariotipo as tipo, rh211_versao as versao';
        $dao = new cl_esocialversaoformulario();
        $sql = $dao->sql_query_file(null, $campos, null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar formulários do e-Social');
        }

        return db_utils::getCollectionByRecord($rs);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getVersoesAtualizar()
    {

        $where = " rh211_versao >= '{$this->getVersao()}'";
        $dao = new cl_esocialversaoformulario();
        $sql = $dao->sql_query_file(null, 'distinct rh211_versao', 'rh211_versao', $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar as versões do e-Social');
        }

        return db_utils::makeCollectionFromRecord($rs, function ($data) {
            return $data->rh211_versao;
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
