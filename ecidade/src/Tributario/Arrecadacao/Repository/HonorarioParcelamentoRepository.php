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

namespace ECidade\Tributario\Arrecadacao\Repository;

use cl_honorariosparcelamento;
use DBException;
use ECidade\Tributario\Arrecadacao\Custas\Enum\TipoLancamento;
use ECidade\Tributario\Juridico\Inicial\Repository\Inicial;
use ECidade\Tributario\Juridico\ProcessoForo\ProcessoForo;
use Exception;
use db_utils;
use ECidade\Tributario\Arrecadacao\Model\HonorarioParcelamento as HonorarioParcelamentoModel;
use ECidade\Tributario\Juridico\ProcessoForo\Repository\ProcessoForo as ProcessoForoRepository;
use ECidade\Tributario\Juridico\Inicial\Inicial as InicialModel;

/**
 * Class ParametrosTributario
 * @package ECidade\Tributario\Arrecadacao\Repository
 */
class HonorarioParcelamentoRepository
{
    /**
     * @var self
     */
    protected static $instance;

    /**
     * @var array
     */
    protected $colecao = array();

    /**
     * Retorna uma HonorarioParcelamento filtrando por cÃ³digo.
     *
     * @param integer $code
     *
     * @return HonorarioParcelamentoModel
     *
     * @throws Exception
     */
    public function getByCode($code)
    {
        $dao = new cl_honorariosparcelamento();
        $sql = $dao->sql_query($code);

        $result = db_query($sql);

        if (!pg_num_rows($result)) {
            return null;
        }

        $entity = null;
        foreach (pg_fetch_all($result) as $item) {
            $entity = $this->make((object)$item);
            break;
        }

        return $entity;
    }

    /**
     * Formata o HonorarioParcelamento para entidade.
     * @param \stdClass $item
     * @return HonorarioParcelamentoModel
     * @throws \DBException
     * @throws \Exception
     */
    public function make($item)
    {
        $entity = new HonorarioParcelamentoModel();
        $entity->setSequencial($item->ar43_sequencial);
        $entity->setNumeroParcelas($item->ar43_numeroparcelas);

        $taxaRepository = Taxa::getInstance();

        if (!empty($item->ar43_processoforo)) {
            $processoForoRepository = ProcessoForoRepository::getInstance();
            $processoForo = $processoForoRepository->getByCodigo($item->ar43_processoforo);
            $entity->setProcessoForo($processoForo);

            $taxasModel = $taxaRepository->getTaxasProcessuais();

            if (!empty($taxasModel)) {
                $entity->setTaxas($taxasModel);
            }
        }

        if (!empty($item->ar43_inicial)) {
            $inicialRepository = Inicial::getInstance();
            $inicial = $inicialRepository->getByCode($item->ar43_inicial);
            $entity->setInicial($inicial);

            $taxasModel = $taxaRepository->getTaxasAdministrativas();

            if (!empty($taxasModel)) {
                $entity->setTaxas($taxasModel);
            }
        }

        $honorarioRepository = self::getInstance();
        $honorarioRepository->add($entity);

        return $entity;
    }

    /**
     * @return HonorarioParcelamentoRepository
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @param HonorarioParcelamentoModel $honorarioParcelamentoModel
     */
    protected function add(HonorarioParcelamentoModel $honorarioParcelamentoModel)
    {
        static::getInstance()->colecao[$honorarioParcelamentoModel->getSequencial()] = $honorarioParcelamentoModel;
    }

    /**
     * Persiste um HonorarioParcelamento no banco de dados.
     *
     * @param HonorarioParcelamentoModel $entity
     *
     * @return HonorarioParcelamentoModel
     *
     * @throws Exception
     */
    public function persist(HonorarioParcelamentoModel $entity)
    {
        if ($entity->getInicial() === null && $entity->getProcessoForo() === null) {
            throw new Exception("Necessário informar um processo de foro ou inicial!");
        }

        $dao = new cl_honorariosparcelamento();

        $dao->ar43_sequencial = $entity->getSequencial();
        $dao->ar43_processoforo = $entity->getProcessoForo() !== null ? $entity->getProcessoForo()->getCodigo() : null;
        $dao->ar43_inicial = $entity->getInicial() !== null ? $entity->getInicial()->getCodigo() : null;
        $dao->ar43_numeroparcelas = $entity->getNumeroParcelas();

        if (!empty($dao->ar43_sequencial)) {
            $dao->alterar($dao->ar43_sequencial);
        } else {
            $dao->incluir(null);
            $entity->setSequencial($dao->ar43_sequencial);
        }

        if ($dao->erro_status == 0) {
            throw new Exception($dao->erro_msg);
        }

        return $entity;
    }

    /**
     * @param HonorarioParcelamentoModel $entity
     * @return HonorarioParcelamentoModel
     * @throws Exception
     */
    public function delete(HonorarioParcelamentoModel $entity)
    {
        $dao = new cl_honorariosparcelamento();
        $dao->ar43_sequencial = $entity->getSequencial();
        $dao->excluir($dao->ar43_sequencial);

        if ($dao->erro_status == 0) {
            throw new Exception($dao->erro_msg);
        }

        return $entity;
    }

    /**
     * @param InicialModel $inicial
     * @return HonorarioParcelamentoModel|mixed|null
     * @throws DBException
     */
    public function getByInicial(InicialModel $inicial)
    {
        $instance = $this->getInstanceByInicial($inicial);

        if ($instance instanceof HonorarioParcelamentoModel) {
            return $instance;
        }

        $condicao = "ar43_inicial = {$inicial->getCodigo()}";

        return $this->getHonorario($condicao);
    }

    /**
     * @param InicialModel $inicial
     * @return mixed|null
     */
    private function getInstanceByInicial(InicialModel $inicial)
    {
        $instance = self::getInstance();

        foreach ($instance->colecao as $honorarioParcelamentoModel) {
            if ($honorarioParcelamentoModel->getInicial()) {
                if ($honorarioParcelamentoModel->getInicial()->getCodigo() === $inicial->getCodigo()) {
                    return $honorarioParcelamentoModel;
                }
            }
        }

        return null;
    }

    /**
     * @param $condicao
     * @return HonorarioParcelamentoModel|null
     * @throws DBException
     */
    private function getHonorario($condicao)
    {
        $dao = new cl_honorariosparcelamento();
        $sql = $dao->sql_query_file(null, '*', null, $condicao);
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException('Erro ao buscar o número de parcelas do honorário.');
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return $this->make(db_utils::fieldsMemory($rs, 0));
    }

    /**
     * @param ProcessoForo $processoForo
     * @return HonorarioParcelamentoModel|mixed|null
     * @throws DBException
     */
    public function getByProcessoForo(ProcessoForo $processoForo)
    {
        $instance = $this->getInstanceByProcessoForo($processoForo);

        if ($instance instanceof HonorarioParcelamentoModel) {
            return $instance;
        }

        $condicao = "ar43_processoforo = {$processoForo->getCodigo()}";

        return $this->getHonorario($condicao);
    }

    /**
     * @param ProcessoForo $processoForo
     * @return mixed|null
     */
    private function getInstanceByProcessoForo(ProcessoForo $processoForo)
    {
        $instance = self::getInstance();

        foreach ($instance->colecao as $honorarioParcelamentoModel) {
            if ($honorarioParcelamentoModel->getProcessoForo()->getCodigo() === $processoForo->getCodigo()) {
                return $honorarioParcelamentoModel;
            }
        }

        return null;
    }

    /**
     * @param ProcessoForo $processoForo
     * @return bool
     * @throws DBException
     */
    public function hasParcelamentoProcessoForo(ProcessoForo $processoForo)
    {
        $instance = $this->getInstanceByProcessoForo($processoForo);

        if ($instance instanceof HonorarioParcelamentoModel) {
            return $instance;
        }

        $condicao  = "processoforo.v70_sequencial = {$processoForo->getCodigo()}";
        $condicao .= "AND (SELECT COUNT(*)
                             FROM processoforo pf
                             JOIN processoforoinicial pfi
                               ON pfi.v71_processoforo = pf.v70_sequencial
                             JOIN termoini t
                               ON t.inicial = pfi.v71_inicial
                            WHERE pf.v70_sequencial = {$processoForo->getCodigo()}) = 
                            (SELECT COUNT(*)
                               FROM processoforo pf
                               JOIN processoforoinicial pfi
                                 ON pfi.v71_processoforo = pf.v70_sequencial
                               JOIN termoini ti
                                 ON ti.inicial = pfi.v71_inicial
                               JOIN termo t
                                 ON t.v07_parcel = ti.parcel
                               JOIN termoanu ta 
                                 ON ta.v09_parcel = t.v07_parcel
                              WHERE pf.v70_sequencial = {$processoForo->getCodigo()})";

        $dao = new \cl_processoforo();
        $sql = $dao->sql_query(null, '*', null, $condicao);
        $rs  = db_query($sql);

        if (!$rs) {
            throw new DBException('Erro ao validar o processo do foro.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param InicialModel $inicial
     * @return mixed
     * @throws DBException
     */
    public function getValidaInicial(InicialModel $inicial)
    {
        $instance = $this->getInstanceByInicial($inicial);

        if ($instance instanceof HonorarioParcelamentoModel) {
            return $instance;
        }

        $oParans = new \stdClass();

        $condicao = "v71_inicial = {$inicial->getCodigo()} AND v71_anulado = 'f'";

        $dao = new \cl_processoforoinicial();
        $sql = $dao->sql_query(null, "v71_processoforo, v70_codforo", null, $condicao);
        $rsProcesso  = db_query($sql);

        if (pg_num_rows($rsProcesso) === 0) {
            $dao = new \cl_termoini();
            $where = " inicial = {$inicial->getCodigo()} and v09_parcel is null  ";
            $sql = $dao->sql_query_anulacao($where);

            $rs  = db_query($sql);

            if (!$rs) {
                throw new DBException('Erro ao validar a inicial.');
            }

            if (pg_num_rows($rs) > 0) {
                $oParans->liberaConsulta = false;
                $oParans->mensagem       = "Esta inicial já possui parcelamento.";
            } else {
                $oParans->liberaConsulta = true;
                $oParans->mensagem       = "";
            }
        } else {
            $oProcesso = db_utils::fieldsMemory($rsProcesso, 0);

            $oParans->liberaConsulta = false;
            $oParans->mensagem       = "Esta Inicial está vinculada ao processo {$oProcesso->v70_codforo}";
            $oParans->mensagem       .= " ($oProcesso->v71_processoforo).\n\n";
            $oParans->mensagem       .= "A liberação do parcelamento deve ser efetuada para o mesmo.";
        }

        return $oParans;
    }

    public function hasPartilhaProcessoForo(ProcessoForo $processoForo)
    {
        $sql  = " select * ";
        $sql .= "   from processoforopartilha ";
        $sql .= "        inner join processoforopartilhacusta on v77_processoforopartilha = v76_sequencial ";
        $sql .= "        inner join taxa on v77_taxa = ar36_sequencial ";
        $sql .= "  where v76_processoforo = " . $processoForo->getCodigo();
        $sql .= "    and ar36_honorario is true ";
        $sql .= "    and (v76_tipolancamento = " . TipoLancamento::ISENCAO;
        $sql .= "         or v76_tipolancamento = " . TipoLancamento::PAGAMENTO_MANUAL;
        $sql .= "         or (v76_tipolancamento = " . TipoLancamento::PAGAMENTO;
        $sql .= "             and v76_dtpagamento is not null)) ";

        $rs  = db_query($sql);

        if (!$rs) {
            throw new DBException('Erro ao validar há partilha lançada para o processo do foro.');
        }

        if (pg_num_rows($rs) == 0) {
            return false;
        }

        return true;
    }

    public function getByFactory($model)
    {
        if ($model instanceof InicialModel) {
            return $this->getByInicial($model);
        }

        if ($model instanceof ProcessoForo) {
            return $this->getByProcessoForo($model);
        }

        return null;
    }
}
