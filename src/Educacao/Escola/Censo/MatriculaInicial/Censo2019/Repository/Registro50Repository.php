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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository;

use cl_regencia;
use cl_turmaachorarioprofissional;
use cl_turmaatividadecomplementar;
use cl_turmaoutrosprofissionais;
use db_utils;
use DBDate;
use ECidade\Educacao\Escola\Model\CensoDisciplina;
use ECidade\Educacao\Escola\Registry\CensoDisciplinaRegistry;
use ECidade\Educacao\Escola\Repository\Repository;
use Exception;
use stdClass;

class Registro50Repository extends Repository
{
    public function scopeTurma($codigoTurma)
    {
        $this->scopes['turma'] = "ed57_i_codigo = {$codigoTurma}";
        return $this;
    }

    /**
     * @param $codigoTurma
     * @return $this
     */
    public function scopeTurmaEspecial($codigoTurma)
    {
        $this->scopes['turma'] = "ed268_i_codigo = {$codigoTurma}";
        return $this;
    }

    /**
     * @param DBDate $data
     * @return Registro50Repository
     */
    public function scopeProfissionaisAtivosDataCenso(DBDate $data)
    {
        $this->scopes['ingresso']  = "(ed75_d_ingresso <= '{$data->getDate()}')";
        $this->scopes['saida']  = "(ed75_i_saidaescola is null or ed75_i_saidaescola >= '{$data->getDate()}')";
        return $this;
    }

    /**
     * @param DBDate $data
     * @return $this
     */
    public function regentesAtivos(DBDate $data)
    {
        $this->scopes['regente_ativos'] = " '{$data->getDate()}' between ed58_datainicio and ed58_datafim";
        return $this;
    }

    /**
     * @param $codigo
     * @return $this
     */
    public function scopeCodigoRecHumano($codigo)
    {
        $this->scopes['rechumano'] = "ed58_i_rechumano = {$codigo}";
        return $this;
    }

    public function scopeDocentePresente(DBDate $date)
    {
        $this->scopes['docente_presente'] = "
            not exists(
             select 1
              from docenteausencia
               where docenteausencia.ed321_rechumano = rechumano.ed20_i_codigo
                 and docenteausencia.ed321_escola = rechumanoescola.ed75_i_escola
                 and (    (ed321_inicio <= '{$date->getDate()}' and ed321_final is null)
                       or ('{$date->getDate()}' between ed321_inicio and ed321_final)
                     )
             )
        ";

        return $this;
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public function getProfissionaisTurmasEspecial()
    {
        $dao = new cl_turmaachorarioprofissional();
        $sql = $dao->sqlDadosCenso($this->scopes);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar profissionais de turmas Atividade Complementar/AEE");
        }

        return db_utils::getCollectionByRecord($rs);
    }

    /**
     * @return stdClass[]
     * @throws Exception
     */
    public function getOutrosProfissionaisTurma()
    {
        $dao = new cl_turmaoutrosprofissionais();
        $sql = $dao->sqlDadosCenso($this->scopes);

        $dao = new cl_turmaatividadecomplementar();
        $sql .= " union " . $dao->sqlDadosCenso($this->scopes);

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar profissionais de turmas Atividade Complementar/AEE");
        }

        return db_utils::getCollectionByRecord($rs);
    }

    public function getProfessoresTurma()
    {
        $scopes = $this->scopes;
        $scopes[] = "ed01_c_regencia = 'S'";

        $dao = new cl_regencia();
        $sql = $dao->sqlDadosCenso($scopes);
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar professores da turma.");
        }

        return db_utils::getCollectionByRecord($rs);
    }

    /**
     * @return CensoDisciplina[]
     * @throws Exception
     */
    public function getDisciplinasProfessor()
    {
        $dao = new cl_regencia();
        $sql = $dao->sqlDisciplinasCenso($this->scopes);
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar professores da turma.");
        }

        return db_utils::makeCollectionFromRecord($rs, function ($disciplina) {
            return CensoDisciplinaRegistry::get($disciplina->censo_disciplina);
        });
    }
}
