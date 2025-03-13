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

  
  Class AlunoMatriculaCenso {
    
    /**
     * Código do alunomatcenso
     * @var integer
     */
    private $iCodigo;
    
    /**
     * Instância de Aluno
     * @var Aluno
     */
    private $oAluno;
    
    /**
     * Turma censo
     * @var integer
     */
    private $iTurmaCenso;
    
    /**
     * Ano do alunomatcenso
     * @var integer
     */
    private $iAno;
    
    /**
     * Matrícula do censo
     * @var integer
     */
    private $iMatriculaCenso;
    
    /**
     * Método construtor para a Classe
     * @param Aluno   $oAluno
     * @param integer $iAno
     */
    public function __construct($oAluno, $iAno) {
      
      $oDaoAlunoMatriculaCenso = db_utils::getDao('alunomatcenso');
      $sWhere                  = "ed280_i_aluno = {$oAluno->getCodigoAluno()} and ed280_i_ano = {$iAno}";
      $sSql                    = $oDaoAlunoMatriculaCenso->sql_query_file(null, '*', null, $sWhere);
      
      $rsAlunoMatriculaCenso = $oDaoAlunoMatriculaCenso->sql_record($sSql);
      
      if($oDaoAlunoMatriculaCenso->numrows > 0) {
        
        $oDadoAlunoMatriculaCenso = db_utils::fieldsMemory($rsAlunoMatriculaCenso, 0);
        $this->setCodigo($oDadoAlunoMatriculaCenso->ed280_i_codigo);
        $this->setTurmaCenso($oDadoAlunoMatriculaCenso->ed280_i_turmacenso);
        $this->setMatriculaCenso($oDadoAlunoMatriculaCenso->ed280_i_matcenso);
      }
      
      $this->setAluno($oAluno);
      $this->setAno($iAno);
    }
    
    /**
     * Retorna o código do alunomatcenso
     * @return integer
     */
    public function getCodigo() {
      return $this->iCodigo;
    }
    
    /**
     * Seta o código do alunomatcenso
     * @param integer $iCodigoAlunoMatriculaCenso
     */
    public function setCodigo($iCodigo) {
      $this->iCodigo = $iCodigo;
    }
    
    /**
     * Retorna instância de Aluno
     * @return Aluno
     */
    public function getAluno() {
      return $this->oAluno;
    }
    
    /**
     * Seta aluno pertencente a tabela alunomatcenso
     * @param Aluno $oAluno
     */
    public function setAluno($oAluno) {
      $this->oAluno = $oAluno;
    }
    
    /**
     * Retorna a turma pertencente a tabela alunomatcenso
     * @return integer
     */
    public function getTurmaCenso() {
      return $this->iTurmaCenso;
    }
    
    /**
     * Seta a turma pertencente a tabela alunomatcenso
     * @param integer $iTurmaCenso
     */
    public function setTurmaCenso($iTurmaCenso) {
      $this->iTurmaCenso = $iTurmaCenso;
    }
    
    /**
     * Retorna o ano do alunomatcenso
     * @return integer
     */
    public function getAno() {
      return $this->iAno;
    }
    
    /**
     * Seta valor do ano referente a alunomatcenso
     * @param integer $iAno
     */
    public function setAno($iAno) {
      $this->iAno = $iAno;
    }
    
    /**
     * Retorna matrícula do censo
     * @return integer
     */
    public function getMatriculaCenso() {
      return $this->iMatriculaCenso;
    }
    
    /**
     * Seta valor da matrícula do censo
     * @param integer $iMatriculaCenso
     */
    public function setMatriculaCenso($iMatriculaCenso) {
      $this->iMatriculaCenso = $iMatriculaCenso;
    }
    
    
    /**
     * Método responsável por salvar um novo registro na tabela alunomatcenso ou caso a propriedade 
     * iCodigoAlunoMatriculaCenso esteja setada, irá alterar o registro.
     * @throws DBException
     * @return boolean
     */
    public function salvar() {
      
      $oDaoAlunoMatriculaCenso                     = db_utils::getDao('alunomatcenso');
      $oDaoAlunoMatriculaCenso->ed280_i_codigo     = $this->iCodigo;
      $oDaoAlunoMatriculaCenso->ed280_i_aluno      = $this->oAluno->getCodigoAluno();
      $oDaoAlunoMatriculaCenso->ed280_i_turmacenso = $this->iTurmaCenso;
      $oDaoAlunoMatriculaCenso->ed280_i_ano        = $this->iAno;
      $oDaoAlunoMatriculaCenso->ed280_i_matcenso   = $this->iMatriculaCenso;
      
      if (empty($this->iCodigo)) {
        $oDaoAlunoMatriculaCenso->incluir(null);
      } else {
        $oDaoAlunoMatriculaCenso->alterar($this->iCodigo);
      }
      
      if ($oDaoAlunoMatriculaCenso->erro_status == "0") {
        throw new DBException("Erro ao salvar dados do Aluno com a Matrícula Censo");
      }
      
      $this->setCodigo($oDaoAlunoMatriculaCenso->ed280_i_codigo);
      
      return true;
    }
  }
?>