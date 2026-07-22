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

namespace ECidade\Tributario\Divida\Certidao\Repository;

use ECidade\Tributario\Divida\Certidao\CertidaoDivida as Entity;
use ECidade\Tributario\Divida\Repository\Divida as DividaRepository;

/**
 * Class CertidaoDivida
 *
 * @method static CertidaoDivida getInstance()
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class CertidaoDivida extends \BaseClassRepository
{
    /** @var bool */
    private $returnFullItem;

    /** @var bool */
    private $persistPropagation;

    protected static $oInstance;

    /**
     * Retorna filtrando por certidao ou divida.
     *
     * @param integer|null $certidao
     * @param integer|null $divida
     *
     * @return Entity[]
     *
     * @throws \Exception
     */
    public function getByCode($certidao = null, $divida = null)
    {
        if (empty($certidao) && empty($divida)) {
            throw new \Exception('Deve ser informado uma certidao ou uma divida');
        }

        $dao = new \cl_certdiv;
        $oDaoCertter = new \cl_certter;

        $sql = $dao->sql_query($certidao, $divida);

        $sCamposCertter = "certter.*,
                           divida.*,
                           certid.*,
                           cgm.*,
                           proced.*";

   /*
     aqui começa a parte que deve achar a divida
     se descomentar as querys abaixo ela traz no documento mas sem valores
     na linha 117 tem um union pra pegar da certter



     esses dados pelo que vi ele utiliza no
     src/Tributario/Arrecadacao/Custas/Relatorio/Custas.php
     private function montarDadosCdasProcesso
   */



        $sSqlCertter = $oDaoCertter ->sql_query_parcel(
            $certidao,
            null,
            $sCamposCertter,
            null,
            null
        );



/*
        $sSqlCertter = "

        SELECT certter.*,
        divida.*,
        certid.*,
        cgm.*,
        proced.*
       from certter
 inner join certid on v14_certid = v13_certid
 inner join termo on v14_parcel = v07_parcel
 inner join termoreparc on v14_parcel = v08_parcel
 inner join termodiv on termodiv.parcel = v08_parcelorigem
 inner join divida on termodiv.coddiv = v01_coddiv
 INNER JOIN cgm ON cgm.z01_numcgm = divida.v01_numcgm
 INNER JOIN proced ON proced.v03_codigo = divida.v01_proced
 WHERE certter.v14_certid = $certidao


        ";
*/

/*
        $sql = "
             $sql
             union
             $sSqlCertter
        ";
*/
        //echo "<br>$sSqlCertter<br><br>";
        $result = \db_query($sql);

        if (!pg_num_rows($result)) {
            return null;
        }

        return $this->makeCollection($result);
    }

    /**
     * Retorna filtrando por dividas.
     *
     * @param array $dividas
     *
     * @return Entity[]
     *
     * @throws \Exception
     */
    public function getByDividas($dividas)
    {
        if (is_array($dividas)) {
            $dividas = implode(',', $dividas);
        }

        $dao = new \cl_certdiv();
        $sql = $dao->sql_query(null, null, 'DISTINCT v14_certid, certdiv.*', null, "v14_coddiv IN({$dividas})");

        $result = \db_query($sql);

        if (!pg_num_rows($result)) {
            throw new \Exception('Nenhuma certidão encontrada para as dívidas: ' . $dividas);
        }

        return $this->makeCollection($result);
    }

    /**
     * Persiste dados na tabela certdiv.
     *
     * @param Entity $certidaoDivida
     *
     * @return Entity
     *
     * @throws \Exception
     */
    public function persist(Entity $certidaoDivida)
    {
        $dao = new \cl_certdiv;

        $dao->v14_certid = $certidaoDivida->getCodigoCertidao();
        $dao->v14_coddiv = $certidaoDivida->getDivida()->getCodigoDivida();
        $dao->v14_vlrcor = $certidaoDivida->getValorCorrigido();
        $dao->v14_vlrhis = $certidaoDivida->getValorHistorico();
        $dao->v14_vlrjur = $certidaoDivida->getValorJuro();
        $dao->v14_vlrmul = $certidaoDivida->getValorMulta();

        $certidao = $this->getByCode(
            $certidaoDivida->getCodigoCertidao(),
            $certidaoDivida->getDivida()->getCodigoDivida()
        );

        if ($certidao == null) {
            $dao->incluir($dao->v14_certid, $dao->v14_coddiv);
        } else {
            $dao->alterar($dao->v14_certid, $dao->v14_coddiv);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $certidaoDivida;
    }

    /**
     * @param \stdClass $certidaoDivida
     *
     * @return Entity
     */
    protected function make($certidaoDivida)
    {
        $entity = new Entity;
        $entity
            ->setValorHistorico($certidaoDivida->v14_vlrhis)
            ->setValorCorrigido($certidaoDivida->v14_vlrcor)
            ->setValorJuro($certidaoDivida->v14_vlrjur)
            ->setValorMulta($certidaoDivida->v14_vlrmul)
            ->setCodigoCertidao($certidaoDivida->v14_certid)
            ->setDivida($certidaoDivida->v14_coddiv);

        if ($this->isReturnFullItem()) {
            $entity->setDivida(
                DividaRepository::getInstance()->make($certidaoDivida)
            );
        }

        return $entity;
    }

    /**
     * @param $result
     *
     * @return Entity[]
     */
    private function makeCollection($result)
    {
        $data = array();
        foreach (pg_fetch_all($result) as $item) {
            $data[] = $this->make((object) $item);
        }

        return $data;
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
     * @return CertidaoDivida
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
     * @return CertidaoDivida
     */
    public function setPersistPropagation($persistPropagation)
    {
        $this->persistPropagation = $persistPropagation;
        return $this;
    }

    public function findAll($where = "")
    {
        $dao = new \cl_certdiv();

        $sql = $dao->sql_query_file(null, null, "*", null, $where);

        $result = \db_query($sql);

        if (!pg_num_rows($result)) {
            return null;
        }

        return $this->makeCollection($result);
    }

    public function delete($where)
    {
        $dao = new \cl_certdiv();

        $dao->excluir(null, null, $where);

        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela certdiv: " . $dao->erro_msg);
        }
    }
}
