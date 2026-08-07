<?php


/**
 * Class cl_processoforomulta
 * @property integer j150_sequencial
 * @property integer j150_processoforo
 * @property string j150_data
 * @property float j150_percentual
 * @property integer j150_receita
 * @property float j150_valortotal
 */
class cl_processoforomulta extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('juridico.processoforomulta');
    }

    /**
     * @param DBDate $dataInicio
     * @param DBDate $dataFim
     * @param int $ano
     * @return string
     */
    public function sql_consulta_debitos_processo(\DBDate $dataInicio, \DBDate $dataFim, $ano)
    {
        if ($dataInicio->getTimeStamp() > $dataFim->getTimeStamp()) {
            throw new \ParameterException('Data de Inicio não pode ser maior que data final');
        }

        if (empty($dataInicio)) {
           throw new \ParameterException('Data Inicial não informada.');
        }

        if (empty($dataFim)) {
            throw new \ParameterException('Data final não informada.');
        }
        if (empty($ano)) {
            throw new \ParameterException('Ano não informado.');
        }

        $sql  = " SELECT ";
        $sql .= " k00_numpre, ";
        $sql .= " k00_numpar, ";
        $sql .= " k00_receit, ";
        $sql .= " j150_receita, ";
        $sql .= " k00_numcgm, ";
        $sql .= " k00_hist, ";
        $sql .= " k00_tipo, ";
        $sql .= " k00_numtot, ";
        $sql .= " round((total/100) * j150_percentual, 2) AS total ";
        $sql .= " FROM (SELECT ";
        $sql .= "             k00_numpre, ";
        $sql .= "             k00_numpar, ";
        $sql .= "             k00_receit, ";
        $sql .= "             j150_receita, ";
        $sql .= "             k00_numcgm, ";
        $sql .= "             k00_tipo, ";
        $sql .= "             j150_percentual, ";
        $sql .= "             min(k00_hist)   AS k00_hist, ";
        $sql .= "             max(k00_numtot) AS k00_numtot, ";
        $sql .= "             sum((substr(fc_calcula, 15, 13) :: FLOAT8 + substr(fc_calcula, 28, 13) :: FLOAT8 + ";
        $sql .= "                 substr(fc_calcula, 41, 13) :: FLOAT8 - substr(fc_calcula, 54, 13) :: FLOAT8)) :: NUMERIC AS total ";
        $sql .= "           FROM (SELECT ";
        $sql .= "                   arrecad.k00_numpre, ";
        $sql .= "                   arrecad.k00_numpar, ";
        $sql .= "                   k00_receit, ";
        $sql .= "                   j150_receita, ";
        $sql .= "                   k00_numcgm, ";
        $sql .= "                   k00_tipo, ";
        $sql .= "                   k00_hist, ";
        $sql .= "                   k00_numtot, ";
        $sql .= "                   j150_percentual, ";
        $sql .= "                   fc_calcula(arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit, '{$dataInicio->getDate()}', '{$dataFim->getDate()}', {$ano}) ";
        $sql .= "                 FROM arrecad ";
        $sql .= "                   INNER JOIN inicialnumpre ON k00_numpre = v59_numpre ";
        $sql .= "                   INNER JOIN processoforoinicial pi ON pi.v71_inicial = v59_inicial ";
        $sql .= "                   INNER JOIN processoforomulta pm ON pm.j150_processoforo = pi.v71_processoforo ";
        $sql .= "                 WHERE NOT EXISTS(SELECT j150_receita ";
        $sql .= "                                  FROM processoforomulta ";
        $sql .= "                                  WHERE j150_receita = k00_receit)) AS x ";
        $sql .= "           GROUP BY k00_numpre, k00_numpar, k00_receit, k00_numcgm, k00_tipo, j150_percentual, j150_receita ";
        $sql .= "           ORDER BY k00_numpre, k00_numpar) AS t; ";

        return $sql;
    }
}
