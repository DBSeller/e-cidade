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

/**
 * Class cl_controlehorasextrasmatriculas
 * @property int rh234_sequencial
 * @property int rh234_instituicao
 * @property int rh234_matricula
 * @property int rh234_ano
 * @property int rh234_mes
 * @property string rh234_horas_liberadas
 */
class cl_controlehorasextrasmatriculas extends DAOBasica
{
    function __construct()
    {
        parent::__construct("pessoal.controlehorasextrasmatriculas");
    }

    /**
     * @param int $instituicao
     * @param int $ano
     * @param int $mes
     * @param null|int $matricula
     * @return string
     */
    public function sql_query_controle_horas_extras($instituicao, $ano, $mes, $matricula = null)
    {
        $whereMatricula = '';

        if (!empty($matricula)) {
            $whereMatricula = " AND rh234_matricula = {$matricula} ";
        }

        return "
            WITH registro_atual AS (
                SELECT rh234_sequencial, rh234_instituicao, rh234_matricula, rh234_horas_liberadas
                FROM pessoal.controlehorasextrasmatriculas
                WHERE rh234_instituicao = {$instituicao}
                    AND rh234_ano = {$ano}
                    AND rh234_mes = {$mes}
                    {$whereMatricula}
            ),
                 ultimos_registros AS (
                     SELECT controle.rh234_instituicao,
                            controle.rh234_matricula,
                            controle.rh234_ano,
                            controle.rh234_mes
                     FROM pessoal.controlehorasextrasmatriculas AS controle
                              INNER JOIN registro_atual
                                         ON registro_atual.rh234_matricula = controle.rh234_matricula
                                             AND registro_atual.rh234_instituicao = controle.rh234_instituicao
                     WHERE (
                        (controle.rh234_ano > {$ano}) OR
                        (controle.rh234_ano = {$ano} AND controle.rh234_mes >= {$mes})
                     )
                     ORDER BY rh234_ano DESC, rh234_mes DESC
                 )
            SELECT registro_atual.rh234_sequencial,
                registro_atual.rh234_instituicao,
                registro_atual.rh234_matricula,
                registro_atual.rh234_horas_liberadas,
                max(ultimos_registros.rh234_ano) as rh234_ano,
                max(ultimos_registros.rh234_mes) as rh234_mes
            FROM ultimos_registros INNER JOIN registro_atual ON ultimos_registros.rh234_matricula = registro_atual.rh234_matricula
                                                            AND ultimos_registros.rh234_instituicao = registro_atual.rh234_instituicao
            GROUP BY registro_atual.rh234_sequencial,
                registro_atual.rh234_instituicao,
                registro_atual.rh234_matricula,
                registro_atual.rh234_horas_liberadas
            ORDER BY registro_atual.rh234_matricula
        ";
    }

    /**
     * @param int $instituicao
     * @param int $ano
     * @param int $mes
     * @param int $matricula
     * @return string
     */
    public function sql_query_dados_matricula($instituicao, $ano, $mes, $matricula)
    {
        $sql = "
            SELECT rh02_hrsmen FROM rhpessoalmov
            LEFT JOIN controlehorasextrasmatriculas
                ON rhpessoalmov.rh02_anousu = controlehorasextrasmatriculas.rh234_ano
                AND rhpessoalmov.rh02_mesusu = controlehorasextrasmatriculas.rh234_mes
                AND rhpessoalmov.rh02_regist = controlehorasextrasmatriculas.rh234_matricula
            WHERE controlehorasextrasmatriculas.rh234_sequencial IS NULL
                  AND rh02_instit = {$instituicao}
                  AND rh02_anousu = {$ano}
                  AND rh02_mesusu = {$mes}
                  AND rh02_regist = {$matricula}
        ";
        return $sql;
    }
}















