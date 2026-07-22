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

namespace ECidade\Patrimonial\Ouvidoria\Externa\Repository;

use CgmRepository;
use cl_preprocesso;
use cl_preprocessoprotprocesso;
use DBDate;
use DBDepartamentoRepository;
use db_utils;
use ECidade\Patrimonial\Ouvidoria\Externa\Collection\PreProcesso as PreProcessoCollection;
use ECidade\Patrimonial\Ouvidoria\Externa\Model\PreProcesso as PreProcessoModel;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Repository\TipoProcesso as TipoProcessoRepository;
use Exception;
use Instituicao;
use InstituicaoRepository;
use UsuarioSistemaRepository;

/**
 * Class PreProcesso
 * @package ECidade\Patrimonial\Ouvidoria\Repository
 */
class PreProcesso
{
    /**
     * @var PreProcesso
     */
    private static $instancia;

    /**
     * @var PreProcessoCollection
     */
    private $collection;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return PreProcesso
     */
    public static function getInstancia()
    {
        if (static::$instancia === null) {
            static::$instancia = new PreProcesso();
        }

        return static::$instancia;
    }

    /**
     * @return PreProcessoCollection
     */
    public function getCollection()
    {
        if (static::getInstancia()->collection === null) {
            static::getInstancia()->collection = new PreProcessoCollection();
        }

        return static::getInstancia()->collection;
    }

    /**
     * @param PreProcessoModel $preProcesso
     * @return PreProcessoModel
     * @throws Exception
     */
    public function salvar(PreProcessoModel $preProcesso)
    {
        $dao = new cl_preprocesso();
        $dao->p106_sequencial = $preProcesso->getSequencial();
        $dao->p106_data = $preProcesso->getData()->getDate();
        $dao->p106_usuario = $preProcesso->getUsuario()->getCodigo();
        $dao->p106_cgm = $preProcesso->getCgm()->getCodigo();
        $dao->p106_requerente = $preProcesso->getRequerente();
        $dao->p106_departamento = $preProcesso->getDepartamento()->getCodigo();
        $dao->p106_observacao = $preProcesso->getObservacao();
        $dao->p106_despacho = $preProcesso->getDespacho();
        $dao->p106_hora = $preProcesso->getHora();
        $dao->p106_interno = $preProcesso->isInterno() === true ? 'true' : 'false';
        $dao->p106_publico = $preProcesso->isPublico() === true ? 'true' : 'false';
        $dao->p106_instituicao = $preProcesso->getInstituicao()->getCodigo();
        $dao->p106_ano = $preProcesso->getAno();
        $dao->p106_metadados = $preProcesso->getMetadados();
        $dao->p106_tipoprocesso = $preProcesso->getTipoProcesso()->getCodigo();

        $acao = $preProcesso->getSequencial() === null ? 'incluir' : 'alterar';
        $dao->{$acao}($preProcesso->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception('Erro ao salvar o pré-processo.');
        }

        $preProcesso->setSequencial($dao->p106_sequencial);

        return $preProcesso;
    }

    /**
     * @param PreProcessoModel $preProcesso
     * @param int $codigoProcesso
     * @throws Exception
     */
    public function vincularProcesso(PreProcessoModel $preProcesso, $codigoProcesso)
    {
        if ($preProcesso->getSequencial() === null) {
            throw new Exception('Pré Processo não informado.');
        }

        if ($codigoProcesso === null) {
            throw new Exception('Processo não informado.');
        }

        $dao = new cl_preprocessoprotprocesso();
        $dao->p107_sequencial = null;
        $dao->p107_preprocesso = $preProcesso->getSequencial();
        $dao->p107_protprocesso = $codigoProcesso;

        $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception('Erro ao salvar o vínculo do pré processo com o processo criado.');
        }
    }

    /**
     * @param Instituicao $instituicao
     * @param int $tipoProcessoGrupo
     * @return PreProcessoCollection|null
     * @throws Exception
     * @throws \ParameterException
     */
    public function buscarPreProcessos(Instituicao $instituicao, $tipoProcessoGrupo = TipoProcesso::GRUPO_OUVIDORIA)
    {
        $dao = new cl_preprocesso();
        $where = array(
          "p106_instituicao = {$instituicao->getCodigo()}",
          "p51_tipoprocgrupo = {$tipoProcessoGrupo}"
        );

        $condicao = "not exists(select 1 ";
        $condicao .= "            from preprocessoprotprocesso";
        $condicao .= "           where p107_preprocesso = p106_sequencial)";

        $where[] = $condicao;

        $sql = $dao->sqlPreProcessoTipoProcesso(array(), $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Erro ao buscar os pré processos.');
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return $this->makeCollection($rs);
    }

    /**
     * @param $result
     * @return PreProcessoCollection
     * @throws \ParameterException
     */
    private function makeCollection($result)
    {
        $totalRegistros = pg_num_rows($result);
        $preProcessoCollection = new PreProcessoCollection();

        for ($contador = 0; $contador < $totalRegistros; $contador++) {
            $preProcesso = $this->make(db_utils::fieldsMemory($result, $contador));
            $preProcessoCollection->add($preProcesso);
        }

        static::getInstancia()->collection = $preProcessoCollection;

        return $this->getCollection();
    }

    /**
     * @param $stdClass
     * @return PreProcessoModel
     * @throws Exception
     * @throws \ParameterException
     */
    private function make($stdClass)
    {
        $instituicao = InstituicaoRepository::getInstituicaoByCodigo($stdClass->p106_instituicao);
        $tipoProcessoRepository = TipoProcessoRepository::getInstancia();
        $tipoProcessoModel = $tipoProcessoRepository->getByCodigo($stdClass->p106_tipoprocesso);

        $preProcessoModel = new PreProcessoModel();
        $preProcessoModel->setSequencial($stdClass->p106_sequencial);
        $preProcessoModel->setData(new DBDate($stdClass->p106_data));
        $preProcessoModel->setUsuario(UsuarioSistemaRepository::getPorCodigo($stdClass->p106_usuario));
        $preProcessoModel->setCgm(CgmRepository::getByCodigo($stdClass->p106_cgm));
        $preProcessoModel->setRequerente($stdClass->p106_requerente);
        $preProcessoModel->setDepartamento(DBDepartamentoRepository::getPorCodigo($stdClass->p106_departamento));
        $preProcessoModel->setObservacao($stdClass->p106_observacao);
        $preProcessoModel->setDespacho($stdClass->p106_despacho);
        $preProcessoModel->setHora($stdClass->p106_hora);
        $preProcessoModel->setInterno($stdClass->p106_interno === 't');
        $preProcessoModel->setPublico($stdClass->p106_publico === 't');
        $preProcessoModel->setInstituicao($instituicao);
        $preProcessoModel->setAno($stdClass->p106_ano);
        $preProcessoModel->setMetadados($stdClass->p106_metadados);
        $preProcessoModel->setTipoProcesso($tipoProcessoModel);

        return $preProcessoModel;
    }

    /**
     * @param $codigoPreProcesso
     * @return PreProcessoModel|null
     * @throws Exception
     * @throws \ParameterException
     */
    public function getPreProcessoByCodigo($codigoPreProcesso)
    {
        $preProcessoCollection = static::getCollection();
        $preProcessoModel = $preProcessoCollection->getByCodigo($codigoPreProcesso);

        if ($preProcessoModel instanceof PreProcessoModel) {
            return $preProcessoModel;
        }

        $dao = new cl_preprocesso();
        $sql = $dao->sql_query_file(null, '*', null, "p106_sequencial = {$codigoPreProcesso}");
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Erro ao buscar o pré processo.');
        }

        if (pg_num_rows($rs) === 0) {
            throw new Exception('Pré Processo não encontrado.');
        }

        return $this->make(db_utils::fieldsMemory($rs, 0));
    }
}
