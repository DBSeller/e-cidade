<?php
/*
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
 * Model para retornar configurações do módulo CAIXA
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package caixa
 * @version $Revision: 1.3 $
 */
final class ParametroCaixa {

  /**
   * Retorna o código do recurso configurado para FUNDEB configurado para a instituição informada na sessão. Caso a
   * instituição não possua recurso configurado, irá retornar 0 (zero)
   * @return integer
   */
  public static function getCodigoRecursoFUNDEB($iInstituicao) {

    $oDaoCaiParametro   = db_utils::getDao('caiparametro');
    $sSqlBuscaParametro = $oDaoCaiParametro->sql_query_file($iInstituicao, "k29_orctiporecfundeb");
    $rsBuscaParametro   = $oDaoCaiParametro->sql_record($sSqlBuscaParametro);

    $iCodigoRecurso = 0;
    if ($oDaoCaiParametro->numrows == 1) {

      $iCodigoRecurso = db_utils::fieldsMemory($rsBuscaParametro, 0)->k29_orctiporecfundeb;
      if (empty($iCodigoRecurso)) {
        $iCodigoRecurso = 0;
      }
    }
    return $iCodigoRecurso;
  }
}
?>