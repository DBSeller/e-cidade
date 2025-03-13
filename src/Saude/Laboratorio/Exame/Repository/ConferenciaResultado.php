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

namespace ECidade\Saude\Laboratorio\Exame\Repository;

use CID;
use cl_lab_conferencia;
use DBDate;
use db_utils;
use ECidade\Saude\Laboratorio\Exame\Model\ConferenciaResultado as ConferenciaResultadoModel;
use ECidade\Saude\Laboratorio\Exame\Repository\RequisicaoExame as RequisicaoExameRepository;
use Exception;
use ProcedimentoSaudeRepository;
use RequisicaoExame as RequisicaoExameModel;
use stdClass;

/**
 * Class ConferenciaResultado
 * @package ECidade\Saude\Laboratorio\Exame\Repository
 */
class ConferenciaResultado extends \BaseClassRepository
{
    /**
     * @param ConferenciaResultadoModel $conferenciaResultado
     * @throws Exception
     */
    public function salvar(ConferenciaResultadoModel $conferenciaResultado)
    {
        $dao = new cl_lab_conferencia();
        $codigo = $conferenciaResultado->getCodigo();
        $acao = !empty($codigo) ? 'alterar' : 'incluir';

        $dao->la47_i_codigo = $conferenciaResultado->getCodigo();
        $dao->la47_d_data = $conferenciaResultado->getData()->getDate();
        $dao->la47_c_hora = $conferenciaResultado->getHora();
        $dao->la47_i_login = $conferenciaResultado->getUsuarioSistema()->getCodigo();
        $dao->la47_i_requiitem = $conferenciaResultado->getRequisicaoExame()->getCodigo();
        $dao->la47_i_resultado = $conferenciaResultado->getResultado();
        $dao->la47_t_observacao = $conferenciaResultado->getObservacao();
        $dao->la47_i_cid = null;
        $dao->la47_i_procedimento = $conferenciaResultado->getProcedimento()->getCodigo();

        if ($conferenciaResultado->getCID() instanceof CID) {
            $dao->la47_i_cid = $conferenciaResultado->getCID()->getCodigo();
        }

        $dao->{$acao}($codigo);

        if ($dao->erro_status === '0') {
            $mensagem = "Erro ao salvar a conferência do resultado do seguinte exame:";
            $mensagem .= "\n - Requisição: {$conferenciaResultado->getRequisicaoExame()->getCodigoRequisicao()}";
            $mensagem .= "\n - Exame: {$conferenciaResultado->getRequisicaoExame()->getExame()->getNome()}";

            throw new Exception($mensagem);
        }
    }

    /**
     * @param RequisicaoExameModel $requisicaoExame
     * @return ConferenciaResultadoModel|null
     * @throws \DBException
     */
    public function getByRequisicaoExame(RequisicaoExameModel $requisicaoExame)
    {
        $conferenciaResultado = $this->getInstanceCollectionByRequisicaoExame($requisicaoExame);

        if ($conferenciaResultado instanceof ConferenciaResultadoModel) {
            return $conferenciaResultado;
        }

        $dao = new cl_lab_conferencia();
        $where = "la47_i_requiitem = {$requisicaoExame->getCodigo()}";
        $sql = $dao->sql_query_file(null, '*', null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Erro ao buscar a conferência do resultado.');
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return $this->makeByStdClass(db_utils::fieldsMemory($rs, 0));
    }

    /**
     * @param RequisicaoExameModel $requisicaoExame
     * @return null
     */
    private function getInstanceCollectionByRequisicaoExame(RequisicaoExameModel $requisicaoExame)
    {
        if (empty($this->aColecao)) {
            return null;
        }

        foreach ($this->aColecao as $conferenciaResultado) {
            if ($conferenciaResultado->getRequisicaoExame()->getCodigo() === $requisicaoExame->getCodigo()) {
                return $conferenciaResultado;
            }
        }

        return null;
    }

    /**
     * @param stdClass $stdClass
     * @return ConferenciaResultadoModel
     * @throws \DBException
     */
    protected function makeByStdClass(stdClass $stdClass)
    {
        $requisicaoExameRepository = RequisicaoExameRepository::getInstance();
        $requisicaoExameModel = $requisicaoExameRepository->getRequisicaoExameByCodigo($stdClass->la47_i_requiitem);

        $procedimentoSaudeRepository = ProcedimentoSaudeRepository::getByCodigo($stdClass->la47_i_procedimento);

        $conferenciaResultado = new ConferenciaResultadoModel();
        $conferenciaResultado->setCodigo($stdClass->la47_i_codigo);
        $conferenciaResultado->setRequisicaoExame($requisicaoExameModel);
        $conferenciaResultado->setUsuarioSistema(\UsuarioSistemaRepository::getPorCodigo($stdClass->la47_i_login));
        $conferenciaResultado->setData(DBDate::create($stdClass->la47_d_data));
        $conferenciaResultado->setHora($stdClass->la47_c_hora);
        $conferenciaResultado->setResultado($stdClass->la47_i_resultado);
        $conferenciaResultado->setObservacao($stdClass->la47_t_observacao);
        $conferenciaResultado->setProcedimento($procedimentoSaudeRepository);
        $conferenciaResultado->setCID(new CID($stdClass->la47_i_cid));

        self::getInstance()->add($conferenciaResultado);

        return $conferenciaResultado;
    }
}
