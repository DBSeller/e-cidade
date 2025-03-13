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

namespace ECidade\Tributario\Divida\Termo\Repository;

///var/www/e-cidade/src/Tributario/Divida/Termo/Termo.php
use ECidade\Tributario\Divida\Termo\Termo as OrigemTermo;
use ECidade\Tributario\Divida\Termo\TermoInicial as Entity;
use ECidade\Tributario\Juridico\Inicial\Repository\Inicial as InicialRepository;

/**
 * @todo document class
 *
 * @method static TermoInicial getInstance()
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class TermoInicial extends \BaseClassRepository
{
    /** @var bool */
    private $returnFullItem;

    /** @var bool */
    private $persistPropagation;

    protected static $oInstance;

    /**
     * @param Entity $entity
     * @param integer $codigoTermo
     *
     * @return Entity
     *
     * @throws \Exception
     */
    public function persist(Entity $entity, $codigoTermo)
    {
        $dao = new \cl_termoini();

        $dao->inicial = $entity->getInicial()->getCodigo();
        $dao->parcel = $codigoTermo;
        $dao->numpreant = $entity->getNumpreAnterior();
        $dao->valor = $entity->getValor();
        $dao->juros = $entity->getJuros();
        $dao->multa = $entity->getMulta();
        $dao->desconto = $entity->getDesconto();
        $dao->total = $entity->getTotal();
        $dao->vlrcor = $entity->getValorCorrigido();
        $dao->v61_perc = $entity->getPercentual();
        $dao->vlrdescjur = $entity->getValorDescontoJuros();
        $dao->vlrdescmul = $entity->getValorDescontoMulta();

        if (!$this->getByCode($dao->inicial, $dao->parcel)) {
            $dao->incluir($dao->parcel, $dao->inicial);
        } else {
            $dao->alterar($dao->parcel, $dao->inicial);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        if ($this->isPersistPropagation() && $entity->getInicial()) {
            $inicialRepository = InicialRepository::getInstance()
                ->setPersistPropagation(true);

            $inicialRepository->persist($entity->getInicial());
        }

        return $entity;
    }

    /**
     * @param \stdClass $data
     *
     * @return Entity
     *
     * @throws \Exception
     */
    protected function make($data)
    {
        $entity = new Entity();
        $entity
            ->setNumpreAnterior($data->numpreant)
            ->setValor($data->valor)
            ->setJuros($data->juros)
            ->setMulta($data->multa)
            ->setDesconto($data->desconto)
            ->setTotal($data->total)
            ->setValorCorrigido($data->vlrcor)
            ->setPercentual($data->v61_perc)
            ->setValorDescontoJuros($data->vlrdescjur)
            ->setValorDescontoMulta($data->vlrdescmul)
            ->setInicial($data->inicial);

        if ($this->isReturnFullItem()) {
            $inicialRepository = InicialRepository::getInstance()
                ->setReturnFullItem(true);

            $inicial = $inicialRepository->getByCode($data->inicial);

            if (empty($inicial)) {
                throw new \Exception('Não foi possível consultar a inicial ' . $data->inicial);
            }

            $entity->setInicial($inicial);
        }

        return $entity;
    }

    /**
     * @param integer $inicial
     * @param integer $termo
     *
     * @return Entity|null
     *
     * @throws \Exception
     */
    public function getByCode($inicial, $termo)
    {
        $dao = new \cl_termoini();
        $sql = $dao->sql_query($termo, $inicial);

        $result = \db_query($sql);

        if (!$result) {
            throw new \Exception('Não foi possível consultar o termo');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        return $this->make(pg_fetch_object($result, 0));
    }



    /**
     * @param integer $termo
     *
     * @return Entity[]|null
     *
     * @throws \Exception
     */

    public function getByTermo($termo)
    {
        $dao = new \cl_termoini();
        $termoOrigem = OrigemTermo::getOrigemTermo($termo);
        $sql = $dao->sql_query($termoOrigem);
        $result = db_query($sql);

        if (!$result) {
            throw new \Exception('Não foi possível consultar as iniciais do termo');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        $data = array();
        foreach (pg_fetch_all($result) as $item) {
            $data[] = $this->make((object) $item);
        }

        return $data;
    }

    public function inicialPossuiAnulacaoParcelamento($inicial)
    {
        $dao = new \cl_termoini();

        $result = \db_query($dao->termoAnuladoPorInicial($inicial));

        return pg_num_rows($result) > 0;
    }

    /**
     * @return bool
     */
    public function isReturnFullItem()
    {
        return $this->returnFullItem;
    }

    /**
     * @param bool $returnFullItem
     * @return TermoInicial
     */
    public function setReturnFullItem($returnFullItem)
    {
        $this->returnFullItem = $returnFullItem;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPersistPropagation()
    {
        return $this->persistPropagation;
    }

    /**
     * @param bool $persistPropagation
     * @return TermoInicial
     */
    public function setPersistPropagation($persistPropagation)
    {
        $this->persistPropagation = $persistPropagation;
        return $this;
    }

    public function delete($where)
    {
        $dao = new \cl_termoini();

        $dao->excluir(null, null, $where);

        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela termoini: " . $dao->erro_msg);
        }
    }
}
