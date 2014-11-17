<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
 * Class PeriodoRelatorioContabil
 *
 * Responsável por controlar os períodos disponíveis no sistema
 *
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.1 $
 */
final class Periodo {

  /**
   * Código
   * @var integer
   */
  private $iCodigo;

  /**
   * Descrição
   * @var string
   */
  private $sDescricao;

  /**
   * Carrega as informações do objeto
   * @throws BusinessException
   * @param $iCodigo
   */
  public function __construct($iCodigo) {

    $oDaoPeriodo    = new cl_periodo();
    $rsBuscaPeriodo = $oDaoPeriodo->sql_record($oDaoPeriodo->sql_query_file($iCodigo));
    if ($oDaoPeriodo->erro_status == "0") {
      throw new BusinessException("Período [{$iCodigo}] não encontrado.");
    }
    $oStdPeriodo      = db_utils::fieldsMemory($rsBuscaPeriodo, 0);
    $this->iCodigo    = $iCodigo;
    $this->sDescricao = $oStdPeriodo->o114_descricao;
    unset($oStdPeriodo);
  }

  /**
   * Retorna o código sequencial
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descrição completa
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}