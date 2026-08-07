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

namespace ECidade\Educacao\Escola\Repository;

use ECidade\Educacao\Escola\Model\RecuperacaoDiasLetivos as RecuperacaoDiasLetivosModel;

use \cl_regenciahorario;
use \cl_feriado;
use PeriodoAula;

/**
 * Class RecuperacaoDiasLetivos
 * @package ECidade\Educacao\Escola\Repository
 */
class RecuperacaoDiasLetivos extends \BaseClassRepository
{
    /**
     * Representa a instância da classe,
     *
     * @var RecuperacaoDiasLetivos
     * @access protected
     */
    protected static $oInstance;

    /**
     * Retorna todos os horários configurados para a turma informada
     * @param \Turma $turma
     * @return \stdClass[]
     * @throws \DBException
     */
    public function getRecuperacaoDiasLetivosPorTurma(\Turma $turma, \Etapa $etapa)
    {
        $daoRegenciaHorario = new cl_regenciahorario();
        $campos  = " regenciahorario.ed58_i_codigo as identificador, ";
        $campos .= " calendario.ed52_c_descr AS descricao_calendario, ";
        $campos .= " regenciahorario.ed58_datainicio AS DATA, ";
        $campos .= " turma.ed57_c_descr AS descricao_turma, ";
        $campos .= " turno.ed15_c_nome AS descricao_turno, ";
        $campos .= " caddisciplina.ed232_c_abrev AS descricao_disciplina_abreviada, ";
        $campos .= " caddisciplina.ed232_c_descr AS descricao_disciplina, ";
        $campos .= " CASE ";
        $campos .= "     WHEN rechumano.ed20_i_tiposervidor = 1 THEN cgmpessoal.z01_nome ";
        $campos .= "     ELSE cgm.z01_nome ";
        $campos .= " END AS nome_regente, ";
        $campos .= " CASE ";
        $campos .= "    when ed302_sequencial is not null then true  ";
        $campos .= "    else false ";
        $campos .= " end possui_falta_lancada, ";
        $campos .= " array_to_string(array_accum(regenciahorario.ed58_i_codigo), ',') AS codigos_regenciahorario, ";
        $campos .= " array_to_string(array_accum(trim(periodoaula.ed08_c_descr)), ',') AS periodos, ";
        $campos .= " array_to_string(array_accum(periodoescola.ed17_i_codigo), ',') AS codigos_periodos ";
        $where  = " ed57_i_codigo = {$turma->getCodigo()} AND ed58_tipovinculo = 3 ";
        $where .= " AND ed59_i_serie = {$etapa->getCodigo()} ";
        $where .= " group by 1,2,3,4,5,6,7,8,9 ";
        
        $sql = $daoRegenciaHorario->sql_query_recuperacao_dias_letivos($campos, $where);
        $rs = \db_query($sql);
        if (!$rs) {
            throw new \DBException("Erro ao buscar as recuperações de dias letivos configurados para a turma.");
        }

        return \db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            $dados = new \stdClass();
            $dados->data = $retorno->data;
            $dados->identificador = $retorno->identificador;
            $dados->disciplina = trim($retorno->descricao_disciplina);
            $dados->descricaoTurma = trim($retorno->descricao_turma);
            $dados->descricaoTurno = trim($retorno->descricao_turno);
            $dados->regente = trim($retorno->nome_regente);
            $dados->possui_falta_lancada = $retorno->possui_falta_lancada == 't';
            
            $dados->regencias = explode(',', $retorno->codigos_regenciahorario);
            $dados->regencias = array_unique($dados->regencias);

            $dados->periodos = explode(',', $retorno->periodos);
            $dados->periodos = array_unique($dados->periodos);

            $dados->codigosPeriodos = explode(',', $retorno->codigos_periodos);
            $dados->codigosPeriodos = array_unique($dados->codigosPeriodos);

            sort($dados->periodos);
            return $dados;
        });
    }

    /**
     * @param \ECidade\Educacao\Escola\Model\RecuperacaoDiasLetivos $recuperacaoDiasLetivos
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function salvar(RecuperacaoDiasLetivosModel $recuperacaoDiasLetivos)
    {

        if ($recuperacaoDiasLetivos->getTurno() == null) {
            throw new \ParameterException('Turno não informado.');
        }

        if ($recuperacaoDiasLetivos->getPeriodos() == null) {
            throw new \ParameterException('Nenhum período informado.');
        }

        if ($recuperacaoDiasLetivos->getRechumano() == null) {
            throw new \ParameterException('Regente não informado.');
        }

        if ($recuperacaoDiasLetivos->getData() == null) {
            throw new \ParameterException('Data não informada.');
        }

        if ($recuperacaoDiasLetivos->getRegencia() == null) {
            throw new \ParameterException('Disciplina não informada.');
        }

        $this->validaExistenciaPeriodoRegencia($recuperacaoDiasLetivos);

        $daoRegenciaHorario = new \cl_regenciahorario();

        foreach ($recuperacaoDiasLetivos->getPeriodos() as $periodoEscola) {
            $daoRegenciaHorario->ed58_i_codigo = null;
            $daoRegenciaHorario->ed58_i_regencia = $recuperacaoDiasLetivos->getRegencia()->getCodigo();
            $daoRegenciaHorario->ed58_i_diasemana = $recuperacaoDiasLetivos->getData()->getDiaSemana() + 1;
            $daoRegenciaHorario->ed58_i_periodo = $periodoEscola->getCodigo();
            $daoRegenciaHorario->ed58_i_rechumano = $recuperacaoDiasLetivos->getRechumano();
            $daoRegenciaHorario->ed58_ativo = 'true';
            $daoRegenciaHorario->ed58_tipovinculo = 3;
            $daoRegenciaHorario->ed58_datainicio = $recuperacaoDiasLetivos->getData()->getDate();
            $daoRegenciaHorario->ed58_datafim = $recuperacaoDiasLetivos->getData()->getDate();

            $daoRegenciaHorario->incluir(null);

            if ($daoRegenciaHorario->erro_status == '0') {
                throw new \DBException('Erro ao lançar a recuperação do dia letivo.');
            }
        }
    }

    /**
     * @param RecuperacaoDiasLetivosModel $recuperacaoDiasLetivos
     * @throws \BusinessException
     * @throws \DBException
     */
    private function validaExistenciaPeriodoRegencia(RecuperacaoDiasLetivosModel $recuperacaoDiasLetivos)
    {
        $daoRegenciaHorario = new \cl_regenciahorario();
        $periodos = array();

        foreach ($recuperacaoDiasLetivos->getPeriodos() as $periodoEscola) {
            $periodos[] = $periodoEscola->getCodigo();
        }

        $whereRegenciaHorario = "     ed58_i_regencia = {$recuperacaoDiasLetivos->getRegencia()->getCodigo()}";
        $whereRegenciaHorario .= " AND ed58_i_periodo in(" . implode(', ', $periodos) . ")";
        $whereRegenciaHorario .= " AND ed58_datainicio = '{$recuperacaoDiasLetivos->getData()->getDate()}'";

        $sqlRegenciaHorario = $daoRegenciaHorario->sql_query_file(null, 'ed58_i_codigo', null, $whereRegenciaHorario);
        $rsRegenciaHorario = db_query($sqlRegenciaHorario);

        if (!$rsRegenciaHorario) {
            throw new \DBException('Erro ao validar a existência de período lançado.');
        }

        if (pg_num_rows($rsRegenciaHorario) > 0) {
            throw new \BusinessException('Período já possui vínculo na regência, para esta data.');
        }
    }

    /**
     * Remove os horários configurados
     * @param Array $horarios - Códigos da regenciahorario
     * @throws \DBException
     */
    public function excluir($horarios)
    {
        foreach ($horarios as $codigoHorario) {
            $periodoAula = new PeriodoAula();
            $periodoAula->setCodigo($codigoHorario);
            $periodoAula->remover();
        }
    }

    /**
     * @param int $codigoCalendario
     * @return array
     * @throws \DBException
     */
    public function buscarDataFeriadoLetivoPorCalendario($codigoCalendario)
    {
        $feriado = new cl_feriado();
        $sql = $feriado->sql_query_file(
            null,
            ' ed54_c_descr, ed54_c_diasemana, ed54_d_data',
            'ed54_d_data',
            "ed54_i_calendario = {$codigoCalendario} and ed54_c_dialetivo = 'S'"
        );
        $rs = \db_query($sql);

        if (!$rs) {
            throw new \DBException('Erro ao buscar as datas de feriados e eventos letivos do calendário.');
        }

        return \db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            $data = new \stdClass();
            $data->descricao = urlencode($retorno->ed54_c_descr);
            $data->diaSemana = $retorno->ed54_c_diasemana;
            $data->data = $retorno->ed54_d_data;

            return $data;
        });
    }
}
