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

use cl_categoriatipoproc;
use cl_categoriatipoprocvinculo;
use BusinessException;
use DBException;
use db_utils;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Collection\TipoProcesso as TipoProcessoCollection;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\CategoriaTipoProcesso as CategoriaTipoProcessoModel;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso;
use ParameterException;

/**
 * Class CategoriaTipoProcesso
 * @package ECidade\Patrimonial\Protocolo\TipoProcesso\Repository
 */
class CategoriaTipoProcesso
{
    /**
     * @var CategoriaTipoProcesso
     */
    protected static $instancia;

    protected function __construct()
    {
        return;
    }

    protected function __clone()
    {
        return;
    }

    /**
     * @return CategoriaTipoProcesso
     */
    public static function getInstancia()
    {
        if (empty(static::$instancia)) {
            static::$instancia = new static;
        }

        return static::$instancia;
    }

    /**
     * @param CategoriaTipoProcessoModel $categoriaTipoProcesso
     * @throws BusinessException
     * @throws DBException
     */
    public function salvar(CategoriaTipoProcessoModel $categoriaTipoProcesso)
    {
        static::getInstancia()->existeCategoriaMesmoNome($categoriaTipoProcesso);

        $dao = new cl_categoriatipoproc();
        $dao->p104_sequencial = $categoriaTipoProcesso->getSequencial();
        $dao->p104_nome = $categoriaTipoProcesso->getNome();
        $dao->p104_descricao = $categoriaTipoProcesso->getDescricao();

        $acao = $categoriaTipoProcesso->getSequencial() === null ? 'incluir' : 'alterar';
        $dao->{$acao}($categoriaTipoProcesso->getSequencial());

        if ($dao->erro_status === '0') {
            throw new DBException('Erro ao salvar a categoria.' . $dao->erro_msg);
        }

        $categoriaTipoProcesso->setSequencial($dao->p104_sequencial);
        self::salvarTipoProcessoVinculo($categoriaTipoProcesso);
    }

    /**
     * @param CategoriaTipoProcessoModel $categoriaTipoProcesso
     * @throws DBException
     * @throws ParameterException
     */
    public function remover(CategoriaTipoProcessoModel $categoriaTipoProcesso)
    {
        if ($categoriaTipoProcesso->getSequencial() === null) {
            throw new ParameterException('Sequencial não informado.');
        }

        self::removerTipoProcessoVinculo($categoriaTipoProcesso);

        $dao = new cl_categoriatipoproc();
        $dao->excluir($categoriaTipoProcesso->getSequencial());
    }

    /**
     * @param CategoriaTipoProcessoModel $categoriaTipoProcesso
     * @throws BusinessException
     * @throws DBException
     */
    private function existeCategoriaMesmoNome(CategoriaTipoProcessoModel $categoriaTipoProcesso)
    {
        $dao = new cl_categoriatipoproc();
        $where = "p104_nome = '{$categoriaTipoProcesso->getNome()}'";

        if ($categoriaTipoProcesso->getSequencial() !== null) {
            $where .= " AND p104_sequencial <> {$categoriaTipoProcesso->getSequencial()}";
        }

        $sql = $dao->sql_query_file(null, 'p104_sequencial', null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException('Erro ao validar a existência da categoria informada.');
        }

        if (pg_num_rows($rs) > 0) {
            throw new BusinessException('Já há uma categoria cadastrada com o mesmo nome.');
        }
    }

    /**
     * @param CategoriaTipoProcessoModel $categoriaTipoProcesso
     * @return bool
     * @throws DBException
     */
    private function salvarTipoProcessoVinculo(CategoriaTipoProcessoModel $categoriaTipoProcesso)
    {
        self::removerTipoProcessoVinculo($categoriaTipoProcesso);

        $tiposProcesso = $categoriaTipoProcesso->getTiposProcesso()->getAll();

        if (empty($tiposProcesso)) {
            return false;
        }

        foreach ($tiposProcesso as $tipoProcesso) {
            $daoVinculo = new cl_categoriatipoprocvinculo();
            $daoVinculo->p105_categoriatipoproc = $categoriaTipoProcesso->getSequencial();
            $daoVinculo->p105_tipoproc = $tipoProcesso->getCodigo();
            $daoVinculo->p105_sequencial = null;
            $daoVinculo->incluir();

            if ($daoVinculo->erro_status === '0') {
                throw new DBException('Erro ao vincular o tipo de processo a categoria.');
            }
        }
    }

    /**
     * @param CategoriaTipoProcessoModel $categoriaTipoProcesso
     * @throws DBException
     */
    private function removerTipoProcessoVinculo(CategoriaTipoProcessoModel $categoriaTipoProcesso)
    {
        $daoVinculo = new cl_categoriatipoprocvinculo();
        $daoVinculo->excluir(null, "p105_categoriatipoproc = {$categoriaTipoProcesso->getSequencial()}");

        if ($daoVinculo->erro_status === '0') {
            throw new DBException('Erro ao excluir os tipos de processo vinculados a categoria.');
        }
    }

    /**
     * @param CategoriaTipoProcessoModel $categoriaTipoProcesso
     * @return TipoProcessoCollection|null
     * @throws DBException
     * @throws ParameterException
     */
    public function buscarTiposProcessoVinculados(CategoriaTipoProcessoModel $categoriaTipoProcesso)
    {
        if ($categoriaTipoProcesso->getSequencial() === null) {
            throw new ParameterException('Categoria não informada.');
        }

        $daoVinculo = new cl_categoriatipoprocvinculo();
        $sqlVinculo = $daoVinculo->sql_query(
            null,
            'p105_tipoproc, p51_descr',
            null,
            "p105_categoriatipoproc = {$categoriaTipoProcesso->getSequencial()}"
        );
        $rsVinculo = db_query($sqlVinculo);

        if (!$rsVinculo) {
            throw new DBException('Erro ao buscar os tipos de processo vinculados a categoria.');
        }

        if (pg_num_rows($rsVinculo) === 0) {
            return null;
        }

        $totalRegistros = pg_num_rows($rsVinculo);
        $tipoProcessoCollection = new TipoProcessoCollection();

        for ($contador = 0; $contador < $totalRegistros; $contador++) {
            $retorno = db_utils::fieldsMemory($rsVinculo, $contador);

            $tipoProcessoModel = new TipoProcesso();
            $tipoProcessoModel->setCodigo($retorno->p105_tipoproc);
            $tipoProcessoModel->setDescricao($retorno->p51_descr);

            $tipoProcessoCollection->add($tipoProcessoModel);
        }

        return $tipoProcessoCollection;
    }
}
