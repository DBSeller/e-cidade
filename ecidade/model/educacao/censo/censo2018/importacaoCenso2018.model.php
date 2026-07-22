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
 * Class importacaoCenso2018
 */
class importacaoCenso2018 extends importacaoCenso2015
{
    private $iCodigoEscola = null;
    private $linhasTurmas = array();

    function __construct($iAnoEscolhido, $iCodigoInepEscola = null)
    {
        parent::__construct($iAnoEscolhido, $iCodigoInepEscola, 303);
        $this->sCampoChave = 'tipo_registro';
        $this->validarArquivoEscola = false;
    }

    public function importarCodigoInep($aLinhasArquivo)
    {
        foreach ($aLinhasArquivo as $iIndLinha => $oLinha) {

            if ($oLinha->{$this->sCampoChave} == '20') {
                $this->linhasTurmas[$oLinha->codigo_escola_inep][$oLinha->codigo_turma_inep] = $oLinha;
            }

            if (!in_array($oLinha->{$this->sCampoChave}, array(20, 30, 60, 80))) {
                continue;
            }

            if ($this->lImportarTurma && $oLinha->{$this->sCampoChave} == "20") {
                $this->atualizaCodigoInepTurma($oLinha);
            }

            if ($this->lImportarDocente && $oLinha->{$this->sCampoChave} == "30") {
                $this->atualizaCodigoInepDocente($oLinha);
            }


            if ($this->lImportarAluno && $oLinha->{$this->sCampoChave} == "60") {
                $this->atualizaCodigoInepAluno($oLinha);
            }

            if ($this->lImportarAluno && $oLinha->{$this->sCampoChave} == "80") {

                // valida se é uma turma regular. Se for AEE ou NE não importa a matrícula INEP
                if ($this->isTurmaEspecial($oLinha)) {
                    continue;
                }

                $dao = new cl_alunomatcenso();
                if (empty($oLinha->codigo_aluno_entidade_escola)) {
                    continue;
                }

                $where = " ed280_i_aluno = {$oLinha->codigo_aluno_entidade_escola}";
                $dao->excluir(null, $where);

                if ($dao->erro_status == 0) {
                    throw new Exception("Erro ao atualizar a matrícula INEP do aluno.");
                }

                $dao->ed280_i_codigo = null;
                $dao->ed280_i_aluno = $oLinha->codigo_aluno_entidade_escola;
                $dao->ed280_i_turmacenso = $oLinha->codigo_turma_inep;
                $dao->ed280_i_ano = $this->iAnoEscolhido;
                $dao->ed280_i_matcenso = $oLinha->codigo_matricula_aluno;

                $dao->incluir(null);

                if ($dao->erro_status == 0) {
                    throw new Exception("Erro ao atualizar a matrícula INEP do aluno.");
                }
            }
        }
    }

    /**
     * Valida as turmas normais para atualizar o codigo do inep
     * @param  DBLayoutLinha $oLinha
     * @throws DBException
     * @return boolean
     */
    protected function validaTurma(DBLayoutLinha $oLinha)
    {
        $sNomeTurmaCensoNovo = str_replace(array('ª', 'º'), array('', ''), trim($oLinha->nome_turma));

        $aWhere = array();
        if (!empty($oLinha->codigo_turma_entidade_escola)) {
            $aWhere[] = " ed57_i_codigo = {$oLinha->codigo_turma_entidade_escola} ";
        } else {
            $aWhere[] = " translate(to_ascii(ed57_c_descr, 'LATIN1'), ' ', '') = '{$sNomeTurmaCensoNovo}' ";
        }
        $aWhere[] = " ed57_i_tipoatend = " . trim($oLinha->tipo_atendimento);
        $aWhere[] = " ed52_i_ano = {$this->iAnoEscolhido} ";
        $aWhere[] = " ed10_i_tipoensino = " . trim($oLinha->modalidade_turma);
        $aWhere[] = " ed18_c_codigoinep = '{$oLinha->codigo_escola_inep}' ";

        $sWhereTurma = implode(" and ", $aWhere);
        $oDaoTurma = new cl_turma();
        $sSqlTurma = $oDaoTurma->sql_query_censo("", "ed57_i_codigo", "", $sWhereTurma);
        $rsTurma = db_query($sSqlTurma);

        if (!$rsTurma) {
            throw new DBException("Erro tentar atualizar o código do INEP das turmas.\n" . pg_last_error());
        }
        if (pg_num_rows($rsTurma) == 0) {

            $sMsg = "TURMA: [" . $oLinha->codigo_escola_inep . "] " . $sNomeTurmaCensoNovo;
            $sMsg .= " não foi encontrada no sistema.\n";
            $this->log($sMsg);

            return false;
        }

        return db_utils::fieldsmemory($rsTurma, 0);;
    }

    /**
     * Alualiza o código do INEP dos alunos
     * @param  DBLayoutLinha $oLinha
     * @throws DBException
     * @return boolean
     */
    protected function atualizaCodigoInepAluno(DBLayoutLinha $oLinha)
    {
        $aDadosAluno = $this->getDadosAluno($oLinha);
        if ($aDadosAluno != null) {

            foreach ($aDadosAluno as $oDadosAluno) {

                if ($this->lImportarAlunoAtivo && ($oDadosAluno->vinculo_escola != trim($oLinha->codigo_escola_inep))) {

                    $sMsg = "Aluno [" . $oDadosAluno->ed47_c_codigoinep . "] " . $oDadosAluno->ed47_v_nome . ": aluno";
                    $sMsg .= " não está mais vinculado a esta escola.\n";
                    $this->log($sMsg);

                    return;
                }

                $oDaoAluno = new cl_aluno();
                if (!empty($oLinha->identificacao_unica_aluno)) {
                    $oDaoAluno->ed47_c_codigoinep = trim($oLinha->identificacao_unica_aluno);
                }

                $oDaoAluno->ed47_i_codigo = $oDadosAluno->ed47_i_codigo;
                $oDaoAluno->alterar($oDadosAluno->ed47_i_codigo);

                if ($oDaoAluno->erro_status == '0') {
                    throw new Exception("Erro na alteração do código inep do Aluno. Erro da classe " . $oDaoAluno->erro_msg);
                }
            }
        } else {

            $sMsg = "Aluno [" . $oLinha->identificacao_unica_aluno . "] " . $oLinha->nome_completo;
            $sMsg .= " : Nome cadastrado no censo não existe no sistema.\n";
            $this->log($sMsg);
        }

        return true;
    }

    /**
     * Atualiza o codigo INEP das turmas
     * @param  DBLayoutLinha $oLinha
     * @throws DBException
     * @return boolean
     */
    protected function atualizaCodigoInepTurma(DBLayoutLinha $oLinha)
    {
        /**
         * Atualiza turmas onde o tipo de atendimento seja
         * 0 - Não se aplica
         * 1 - Classe hospitalar
         * 2 - Unidade de internação socioeducativa
         * 3 - Unidade prisional
         */
        if (in_array(trim($oLinha->tipo_atendimento), array(0, 1, 2, 3))) {

            $oTurma = $this->validaTurma($oLinha);
            if ($oTurma && trim($oLinha->codigo_escola_inep) != "") {

                $oDaoTurma = new cl_turma();
                $oDaoTurma->ed57_i_codigoinep = $oLinha->codigo_turma_inep;
                $oDaoTurma->ed57_i_codigo = $oTurma->ed57_i_codigo;
                $oDaoTurma->alterar($oTurma->ed57_i_codigo);

                if ($oDaoTurma->erro_status == '0') {
                    throw new DBException("Erro na alteração dos dados da Turma. Erro da classe: " . $oDaoTurma->erro_msg);
                }
            }
        }

        /**
         * Atualiza turmas onde o tipo de atendimento seja
         * 4 - Atividade complementar
         * 5 - Atendimento Educacional Especializado (AEE)
         */
        if (in_array(trim($oLinha->tipo_atendimento), array(4, 5))) {

            $oTurma = $this->validarTurmaEspecial($oLinha);
            if ($oTurma && trim($oLinha->codigo_escola_inep) != "") {

                $oDaoTurmaac = new cl_turmaac();
                $oDaoTurmaac->ed268_i_codigoinep = $oLinha->codigo_turma_inep;
                $oDaoTurmaac->ed268_i_codigo = $oTurma->ed268_i_codigo;
                $oDaoTurmaac->alterar($oTurma->ed268_i_codigo);

                if ($oDaoTurmaac->erro_status == '0') {
                    throw new DBException("Erro na alteração do código inep da Turma. Erro da classe: " . $oDaoTurmaac->erro_msg);
                }
            }
        }

        return true;
    }

    private function isTurmaEspecial($linhaAluno)
    {
        $tumasEscola = $this->linhasTurmas[$linhaAluno->codigo_escola_inep];
        if (!array_key_exists($linhaAluno->codigo_turma_inep, $tumasEscola)) {
            return false;
        }

        $linhaTurma = $tumasEscola[$linhaAluno->codigo_turma_inep];

        if (in_array(trim($linhaTurma->tipo_atendimento),
                array(4, 5)) && $linhaAluno->codigo_turma_inep === $linhaTurma->codigo_turma_inep) {
            return true;
        }

        return false;
    }

}
