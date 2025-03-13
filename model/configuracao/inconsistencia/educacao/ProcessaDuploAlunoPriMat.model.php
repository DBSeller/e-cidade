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

require_once modification("model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php");

/**
 * Processa as exceções encontradas quando tentamos remover os duplos da alunoprimat
 * @package configuracao
 * @subpackage inconsistencia
 * @subpackage educacao
 * @author Andrio <andrio.costa@dbseller.com.br>
 */
class ProcessaDuploAlunoPriMat implements IExcecaoProcessamentoDependencias {

  private $sMsgErro;

  /**
   * A tabela alunoprimat não pode ter 2 registros do mesmo aluno, portanto
   * devemos remover do aluno informado como incorréto
   *
   * @param integer $iChaveCorreta   código do aluno corréto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

    $oDaoAlunoPriMat = new cl_alunoprimat();
    $oDaoAlunoPriMat->excluir(null, "ed76_i_aluno = {$iChaveIncorreta}");
    
    
    if ($oDaoAlunoPriMat->erro_status == 0) {

      $this->sMsgErro  = "Erro ao excluir registro da tabela alunoprimat. ";
      $this->sMsgErro .= "Registro incorréto: {$iChaveIncorreta} \n";
      
      $this->sMsgErro = utf8_encode($this->sMsgErro);
    }
    return true;
  }

  /**
   * @see IExcecaoProcessamentoDependencias::getMensagemErro()
   */
  public function getMensagemErro() {
    return $this->sMsgErro;
  }
}