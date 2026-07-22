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

namespace ECidade\Tributario\Divida\Certidao\Repository;

use ECidade\Tributario\Divida\Certidao\Certidao as Entity;
use ECidade\Tributario\Divida\Certidao\Repository\CertidaoDivida as CertidaoDividaRepository;
use ECidade\Tributario\Divida\Repository\Divida as DividaRepository;
use ECidade\V3\Extension\Registry;

/**
 * Repository para operações com certidões na geral financeira.
 *
 * @method static Certidao getInstance()
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Certidao extends \BaseClassRepository
{
    /** @var bool */
    private $returnFullItem;

    /** @var bool */
    private $persistPropagation;

    protected static $oInstance;

    /**
     * @param integer $code
     *
     * @return Entity
     *
     * @throws \Exception
     */
    public function getByCode($code)
    {
        $dao = new \cl_certid;
        $sql = $dao->sql_query($code);

        $result = \db_query($sql);

        if (!pg_num_rows($result)) {
            return null;
        }

        $certidao = null;
        foreach (pg_fetch_all($result) as $item) {
            $certidao = $this->make((object) $item);
            break;
        }

        return $certidao;
    }

    /**
     * Retorna certidão filtrando por divida.
     *
     * @param integer $divida
     *
     * @return Entity|null
     *
     * @throws \Exception
     */
    public function getByDivida($divida)
    {
        $dao = new \cl_certdiv();
        $sql = $dao->sql_query(null, $divida);

        $result = \db_query($sql);

        if (!pg_num_rows($result)) {
            throw new \Exception('Nenhuma certidao encontrada.');
        }

        $certidao = null;
        foreach (pg_fetch_all($result) as $item) {
            $certidao = $this->make((object) $item);
            break;
        }

        return $certidao;
    }

    /**
     * Retorna certidao filtrando por inicial.
     *
     * @param $inicial
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getByInicial($inicial)
    {
        $sql  = "SELECT certid.*, inicialcert.*, v14_parcel ";
        $sql .= "FROM certid ";
        $sql .= "     INNER JOIN inicialcert ON v51_certidao = v13_certid ";
        $sql .= "     LEFT JOIN certter ON v14_certid = v13_certid ";
        $sql .= "WHERE v51_inicial = {$inicial}";

        $result = \db_query($sql);

        if (!pg_num_rows($result)) {
            return null;
        }

        $certidoes = array();
        foreach (pg_fetch_all($result) as $item) {
            $certidoes[] = $this->make((object) $item);
        }

        return $certidoes;
    }

    /**
     * Persiste uma certidao no banco de dados.
     *
     * @param Entity $certidao
     *
     * @return Entity
     *
     * @throws \Exception
     */
    public function persist(Entity $certidao)
    {
        $dao = new \cl_certid();

        $codigo = $certidao->getCodigo();

        $dataEmissao = $certidao->getDataEmissao();
        if (!empty($dataEmissao)) {
            $dao->v13_dtemis = $dataEmissao->format('Y-m-d H:i:s');
        }

        $dao->v13_instit = $certidao->getInstituicao();
        $dao->v13_memo = db_getsession("DB_id_usuario");
        $dao->v13_login = $certidao->getLogin();

        if (!empty($codigo)) {
            $dao->v13_certid = $codigo;
            $dao->alterar($dao->v13_certid);
        } else {
            $cdaModel = new \cda(null);
            $dao->v13_certid = $cdaModel->getNovoCodCertidao();
            $dao->incluir($dao->v13_certid);
            $certidao->setCodigo($dao->v13_certid);

            $oDaoPardivUltCodCert = \db_utils::getDao('pardivultcodcert');
            $oDaoPardivUltCodCert->v05_codultcert = $dao->v13_certid;
            $oDaoPardivUltCodCert->alterar(null);
            if ($oDaoPardivUltCodCert->erro_status == '0') {
                throw new Exception($oDaoPardivUltCodCert->erro_msg);
            }
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        if ($this->isPersistPropagation() && $certidao->getCertidaoDividas()) {
            $dividaRepository = DividaRepository::getInstance();

            $certidaoDividaRepository = CertidaoDividaRepository::getInstance();
            $certidaoDividaRepository->setPersistPropagation(true);

            foreach ($certidao->getCertidaoDividas() as $certidaoDivida) {
                $certidaoDivida->setCodigoCertidao($certidao->getCodigo());
                $dividaRepository->persist($certidaoDivida->getDivida());
                $certidaoDividaRepository->persist($certidaoDivida);
            }
        }

        return $certidao;
    }

    /**
     * @param \stdClass $certidao
     *
     * @return Entity
     */
    protected function make($certidao)
    {
        $data = new Entity;

        $data->setCodigo($certidao->v13_certid)
             ->setDataEmissao(new \DateTime($certidao->v13_dtemis))
             ->setInstituicao($certidao->v13_instit)
             ->setLogin($certidao->v13_login);

        if (empty($certidao->v14_parcel)) {
            if ($this->isReturnFullItem()) {
                $certidaoDividaRepository = CertidaoDividaRepository::getInstance();
                $certidaoDividaRepository->setReturnFullItem(true);
   
                $certidaoDivida = $certidaoDividaRepository->getByCode($certidao->v13_certid);

                $data->setCertidaoDividas($certidaoDivida);
            }
        } else {
            $certidaoTermoRepository = Registry::get('app.container')
                                               ->get('tributario.container')
                                               ->get('CertidaoTermoRepository');
            $certidaoTermoRepository->setReturnFullItem(true);

            /**
             * Utilizado método com informações fake,
             * no qual a estrutura de classes do PHP necessitava
             * de tais colunas no SQL independente da informação
             */
            $certidaoParcelamento = $certidaoTermoRepository->findTermoParcel(
                $certidao->v13_certid,
                $certidao->v14_parcel,
                "*, v07_numpre as v01_numpre, extract(year from v07_dtlanc) as v01_exerc"
            );

            $data->setCertidaoDividas($certidaoParcelamento);
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
     * @return Certidao
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
     * @return Certidao
     */
    public function setPersistPropagation($persistPropagation)
    {
        $this->persistPropagation = $persistPropagation;
        return $this;
    }

    public function findAll($where = "")
    {
        $dao = new \cl_certid();

        $sql = $dao->sql_query_file(null, "*", null, $where);

        $result = \db_query($sql);

        $certidoes = array();
        foreach (pg_fetch_all($result) as $item) {
            $certidoes[] = $this->make((object) $item);
        }

        return $certidoes;
    }

    public function delete($where)
    {
        $dao = new \cl_certid();

        $dao->excluir(null, $where);

        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela certid: " . $dao->erro_msg);
        }
    }
}
