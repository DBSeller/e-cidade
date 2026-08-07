<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 20/03/18
 * Time: 11:21
 */

namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository;

use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Entity\HistoricoOcorrencia;

class HistoricoOcorrenciaRepository
{

    /**
     * @var TABLE string
     */
    const TABLE = "histocorrencia";

    /**
     * @var SEQUENCE_NAME string
     */
    const SEQUENCE_NAME = "histocorrencia_ar23_sequencial_seq";

    /**
     * @var $sequence integer
     */
    private $sequence;

    /**
     * Persiste uma dupla de ocorrencia do banco de dados.
     *
     * @param HistoricoOcorrencia $oHistoricoOcorrencia
     */
    public function persist(HistoricoOcorrencia $oHistoricoOcorrencia)
    {

        $params = array(
            'ar23_sequencial' => $oHistoricoOcorrencia->getSequencial(),
            'ar23_id_usuario' => $oHistoricoOcorrencia->getIdUsuario(),
            'ar23_instit' => $oHistoricoOcorrencia->getInstit(),
            'ar23_modulo' => $oHistoricoOcorrencia->getModulo(),
            'ar23_id_itensmenu' => $oHistoricoOcorrencia->getIdItensmenu(),
            'ar23_data' => $oHistoricoOcorrencia->getData(),
            'ar23_hora' => $oHistoricoOcorrencia->getHora(),
            'ar23_tipo' => $oHistoricoOcorrencia->getTipo(),
            'ar23_descricao' => $oHistoricoOcorrencia->getDescricao(),
            'ar23_ocorrencia' => $oHistoricoOcorrencia->getOcorrencia(),
        );

        if (empty($params['ar23_sequencial'])) {
            $sSql = $this->insert($params);
        } else {
            $sSql = $this->update($params);
        }


        $rsHistoricoOcorrencia = db_query($sSql);

        if (!$rsHistoricoOcorrencia) {
            throw  new \Exception(pg_last_error());
        }

        $oPersisted = pg_fetch_object($rsHistoricoOcorrencia);
        $this->setSequence($oPersisted->ar23_sequencial);

        return pg_num_rows($rsHistoricoOcorrencia);

    }

    /**
     * @param $params
     * @return string
     */
    private function insert($params)
    {
        $params = array_filter($params);

        $params = array_map(function($value) {

            return "'".$value."'";

        }, $params);

        $params['ar23_sequencial'] = "nextval('" . self::SEQUENCE_NAME . "')";
        $filds  = implode(",", array_keys($params));
        $values = implode(",", $params);
        $sSql   = "INSERT INTO " . self::TABLE . "($filds) values ($values) returning ar23_sequencial;";


        return $sSql;
    }

    /**
     * @param $params
     * @return string
     */
    private function update($params)
    {

        $sSql = "UPDATE " . self::TABLE . "SET ";
        foreach ($params as $column => $value) {

            if ($value === null || $value === false) {
                continue;
            }

            $next = next($params);
            $token = ",";

            if (empty($next)) {
                $token = "";
            }

            $sSql .= $column . " = '" . $value . "' " . $token;
        }

        return $sSql;
    }

    /**
     * @return integer
     */
    public function getLastInsertSequence()
    {
        return $this->sequence;
    }

    /**
     * @param $sequence integer
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }



}