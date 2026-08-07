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
namespace ECidade\Patrimonial\Protocolo\TipoProcesso\Repository;

use cl_tipoproc;
use db_utils;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Collection\TipoProcesso as TipoProcessoCollection;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso as TipoProcessoModel;
use Exception;
use stdClass;

/**
 * Class TipoProcesso
 * @package ECidade\Patrimonial\Protocolo\TipoProcesso\Repository
 */
class TipoProcesso
{
    /**
     * @var TipoProcesso
     */
    private static $instancia;

    /**
     * @var TipoProcessoCollection
     */
    private $collection;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return TipoProcessoCollection
     */
    public function getCollection()
    {
        if (static::getInstancia()->collection === null) {
            static::getInstancia()->collection = new TipoProcessoCollection();
        }

        return static::getInstancia()->collection;
    }

    /**
     * @return TipoProcesso
     */
    public static function getInstancia()
    {
        if (static::$instancia === null) {
            static::$instancia = new TipoProcesso();
        }

        return static::$instancia;
    }

    /**
     * @param stdClass $stdClass
     * @return TipoProcessoModel
     */
    public function make(stdClass $stdClass)
    {
        $tipoProcessoModel = new TipoProcessoModel();
        $tipoProcessoModel->setCodigo($stdClass->p51_codigo);
        $tipoProcessoModel->setDescricao($stdClass->p51_descr);
        $tipoProcessoModel->setCodigoGrupo($stdClass->p51_tipoprocgrupo);
        $tipoProcessoModel->setCodigoInstituicao($stdClass->p51_instit);
        $tipoProcessoModel->setLinkSaibaMais($stdClass->p51_linksaibamais);
        $tipoProcessoModel->setItemMenu($stdClass->p51_itemmenu);
        $tipoProcessoModel->setMensagem($stdClass->p51_mensagem);
        $tipoProcessoModel->setIdentificado($stdClass->p51_identificado == 't');

        static::getCollection()->add($tipoProcessoModel);

        return $tipoProcessoModel;
    }

    /**
     * @param $codigoTipoProcesso
     * @return TipoProcessoModel|null
     * @throws Exception
     */
    public function getByCodigo($codigoTipoProcesso)
    {
        $tipoProcessoCollection = static::getCollection();
        $tipoProcessoModel = $tipoProcessoCollection->getByCodigo($codigoTipoProcesso);

        if ($tipoProcessoModel instanceof TipoProcessoModel) {
            return $tipoProcessoModel;
        }

        $dao = new cl_tipoproc();
        $sql = $dao->sql_query_file(null, '*', null, "p51_codigo = {$codigoTipoProcesso}");
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Erro ao buscar o tipo de processo.');
        }

        if (pg_num_rows($rs) === 0) {
            throw new Exception('Tipo de Processo não encontrado.');
        }

        return $this->make(db_utils::fieldsMemory($rs, 0));
    }
}
