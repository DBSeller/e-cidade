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

namespace ECidade\Patrimonial\Patrimonio\Incorporacao\Repository;

use cl_bempendenteincorporacao;
use db_utils;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Model\MaterialPendenteIncorporacaoModel;
use Exception;
use JSON;

class MaterialPendenteIncorporacaoRepository
{

    /**
     * @var MaterialPendenteIncorporacaoModel[]
     */
    private $materiaisPendenteIncorporacao;

    public function make($dao)
    {
        $bemPendente = new MaterialPendenteIncorporacaoModel();
        $bemPendente->setCodigo($dao->t12_sequencial)
            ->setServico($dao->t12_servico == 't')
            ->setVinculoEstoque($dao->t12_matestoqueinimei)
            ->setValorUnitario($dao->t12_valorunitario)
            ->setEmpenho($dao->t12_empenho)
            ->setQuantidade($dao->quantidade)
            ->setDescricao($dao->m60_descr)
            ->setCodigoMaterial($dao->m70_codmatmater)
            ->setCodigoDepartamento($dao->m70_coddepto);
        return $bemPendente;
    }

    private function montaQueryBuscarDados(array $where)
    {
        $campos = "bempendenteincorporacao.*, 
        m82_quant - coalesce((select sum(t13_quantidade) from bemincorporado where t13_bempendenteincorporacao = t12_sequencial), 0) as quantidade, 
        m60_descr,
        m70_codmatmater,
        m70_coddepto";
        $dao = new cl_bempendenteincorporacao();
        $rs = db_query($dao->sql_origem($campos, null, implode(" and ", $where)));
        if (!$rs) {
            throw new Exception("Erro ao buscar os materiais ou serviços incorporáveis.");
        }

        return $rs;
    }

    /**
     * @param $codigoEmpenho
     * @return MaterialPendenteIncorporacaoModel[]|array
     * @throws Exception
     */
    public function getBensPorEmpenho($codigoEmpenho)
    {
        $rs = $this->montaQueryBuscarDados(array("e60_numemp = {$codigoEmpenho}"));
        if (pg_num_rows($rs) == 0) {
            return array();
        }

        $classe = $this;
        $this->materiaisPendenteIncorporacao = db_utils::makeCollectionFromRecord($rs, function ($dado) use ($classe) {
            return $classe->make($dado);
        });

        return $this->materiaisPendenteIncorporacao;
    }


    /**
     * @param MaterialPendenteIncorporacaoModel[] $materiaisPendenteIncorporacao
     * @return string com um array de objetos json
     */
    public function toJson(array $materiaisPendenteIncorporacao)
    {

        $bens = array_map(function ($data) {
            return $data->jsonSerialize();
        }, $materiaisPendenteIncorporacao);

        return $bens;
    }

    /**
     * @param $codigo
     * @return MaterialPendenteIncorporacaoModel
     * @throws Exception
     */
    public function getById($codigo)
    {
        $rs = $this->montaQueryBuscarDados(array("t12_sequencial = {$codigo}"));
        if (pg_num_rows($rs) == 0) {
            return array();
        }

        $bemPendente = $this->make(db_utils::fieldsMemory($rs, 0));

        $this->materiaisPendenteIncorporacao[] = $bemPendente;

        return $bemPendente;
    }

    /**
     * @param MaterialPendenteIncorporacaoModel $objeto
     */
    public function add(MaterialPendenteIncorporacaoModel $objeto)
    {
        $this->materiaisPendenteIncorporacao[] = $objeto;
    }

    public function persiste()
    {
        foreach ($this->materiaisPendenteIncorporacao as $material) {
            $dao = new cl_bempendenteincorporacao();
            $dao->t12_sequencial = $material->getCodigo();
            $dao->t12_matestoqueinimei = $material->getVinculoEstoque();
            $dao->t12_servico = $material->isServico() ? 'true' : 'false';
            $dao->t12_valorunitario = $material->getValorUnitario();
            $dao->t12_empenho = $material->getEmpenho();
            $dao->incluir(null);
            if ($dao->erro_status == 0) {
                throw new Exception("Erro ao retornar material pendente." . $dao->erro_msg);
            }
        }

        return true;
    }
}
