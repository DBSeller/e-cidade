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

namespace ECidade\Tributario\Juridico\Inicial\Repository;

use ECidade\Tributario\Juridico\Inicial\InicialNome as Entity;
use Exception;

/**
 * Class InicialNome
 *
 * @method static InicialNome getInstance()
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class InicialNome extends \BaseClassRepository
{
    protected static $oInstance;

    /**
     * @param Entity $entity
     * @param integer $initial
     *
     * @return Entity
     *
     * @throws Exception
     */
    public function persist(Entity $entity, $initial)
    {
        $dao = new \cl_inicialnomes();

        if ($this->getByInitialAndName($initial, $entity->getCgm())) {
            return $entity;
        }

        $dao->incluir($initial, $entity->getCgm());

        if ($dao->erro_status == 0) {
            throw new Exception($dao->erro_msg);
        }

        return $entity;
    }

    /**
     * @param integer $initial
     * @param integer $name
     *
     * @return Entity|null
     *
     * @throws Exception
     */
    public function getByInitialAndName($initial, $name)
    {
        if (!$initial && !$name) {
            return null;
        }

        $dao = new \cl_inicialnomes();
        $sql = $dao->sql_query_file($initial, $name);

        $result = \db_query($sql);

        if (!$result) {
            throw new Exception('Não foi possível consultar a inicial.');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        $data = pg_fetch_object($result, 0);

        return $this->make($data);
    }

    /**
     * @param integer $initial
     *
     * @return Entity[]|null
     *
     * @throws Exception
     */
    public function getByInitial($initial)
    {
        $dao = new \cl_inicialnomes();
        $sql = $dao->sql_query_file($initial);

        $result = \db_query($sql);

        if (!$result) {
            throw new Exception('Não foi possível consultar a inicial.');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        $data = array();
        foreach (pg_fetch_all($result) as $item) {
            $data[] = $this->make((object)$item);
        }

        return $data;
    }

    /**
     * @param integer $initial
     *
     * @throws Exception
     */
    public function persistByInitial($initial)
    {
        $query = "
            SELECT DISTINCT
              k00_numcgm as cgm
            FROM (
                   SELECT DISTINCT
                     k00_numcgm 
                   FROM inicial
                     INNER JOIN inicialcert ON v50_inicial = v51_inicial
                     INNER JOIN certid ON v13_certid = v51_certidao
                                          AND v13_instit = 1
                     LEFT OUTER JOIN certdiv ON v14_certid = v13_certid
                     LEFT OUTER JOIN divida ON v14_coddiv = v01_coddiv
                                               AND v01_instit = 1
                     LEFT OUTER JOIN arrenumcgm ON arrenumcgm.k00_numpre = v01_numpre
                   WHERE v50_inicial = {$initial}
                                        AND v50_instit = 1
                                        UNION
                                        SELECT DISTINCT
                   k00_numcgm
                   FROM inicial
                   INNER JOIN inicialcert ON v50_inicial = v51_inicial
                                                         INNER JOIN certid ON v13_certid = v51_certidao
                                                         AND v13_instit = 1
                                                             LEFT JOIN certter ON v14_certid = v51_certidao
                   LEFT JOIN termo ON v07_parcel = v14_parcel
                   AND v07_instit = 1
                                    LEFT OUTER JOIN arrenumcgm AS x ON x.k00_numpre = v07_numpre
                                                                         WHERE v50_inicial = {$initial}
                   AND v50_instit = 1) AS x
            WHERE k00_numcgm IS NOT NULL
            ORDER BY k00_numcgm
        ";

        $records = db_query($query);

        if (!$records) {
            throw new Exception("Não foi possível buscar os cgm's.");
        }

        if (!pg_num_rows($records)) {
            return null;
        }

        foreach (pg_fetch_all($records) as $record) {
            $entity = new Entity();
            $entity->setCgm($record['cgm']);

            $this->persist($entity, $initial);
        }
    }

    /**
     * @param integer $inicial
     *
     * @return bool
     *
     * @throws Exception
     */
    public function deleteByInitial($inicial)
    {
        $dao = new \cl_inicialnomes();
        $dao->excluir($inicial);

        if ($dao->erro_status == 0) {
            throw new Exception($dao->erro_msg);
        }

        return true;
    }

    /**
     * @param \stdClass $data
     *
     * @return Entity
     */
    protected function make($data)
    {
        $entity = new Entity();
        $entity
            ->setCgm($data->v58_numcgm);

        return $entity;
    }
}
