<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 20/03/18
 * Time: 15:25
 */

namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Repository;

use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Entity\HistoricoOcorrenciaMatricula;

class HistoricoOcorrenciaMatriculaRepository
{

    /**
     * @var TABLE string
     */
    const TABLE = "histocorrenciamatric";

    /**
     * @var SEQUENCE_NAME string
     */
    const SEQUENCE_NAME = "histocorrenciamatric_ar25_sequencial_seq";


    /**
     * Persiste uma dupla de ocorrencia do banco de dados.
     *
     * @param HistoricoOcorrencia $oHistoricoOcorrencia
     */
    public function persist(HistoricoOcorrenciaMatricula $oHistoricoOcorrenciaMat)
    {
        $params = array(
            'ar25_sequencial' => $oHistoricoOcorrenciaMat->getSequencial(),
            'ar25_matric' => $oHistoricoOcorrenciaMat->getMatric(),
            'ar25_histocorrencia' => $oHistoricoOcorrenciaMat->getHistocorrencia(),
        );

        if (empty($params['ar25_sequencial'])) {
            $sSql = $this->insert($params);
        } else {
            $sSql = $this->update($params);
        }

        $rsHistoricoOcorrencia = db_query($sSql);

        if (!$rsHistoricoOcorrencia) {
            throw  new \Exception(pg_last_error());
        }

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

        $params['ar25_sequencial'] = "nextval('" . self::SEQUENCE_NAME . "')";
        $filds  = implode(",", array_keys($params));
        $values = implode(",", $params);
        $sSql   = "INSERT INTO " . self::TABLE . "($filds) values ($values) returning ar25_sequencial;";


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
}