<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

class cl_tomador extends DAOBasica
{

    public function __construct()
    {
        parent::__construct("pessoal.tomador");
    }


    /**
     * Metodo realiza busca no banco  pelo sequencia rhpessoalmov e retorna objeto
     *
     * @param $seqpes
     * @return object|null
     */
    public function getCgmTomadorBySeqPes($seqpes)
    {
        $sSql  = "SELECT * FROM tomador INNER JOIN cgm  ON  cgm.z01_numcgm = tomador.rh216_numcgm  ";
        $sSql .= " WHERE  rh216_seqpes = {$seqpes}";

        $rsTomador =  db_query($sSql);

        return pg_fetch_object($rsTomador);
    }

    /**
     * Metodo realiza busca no banco pelo sequencia rhpessoalmov e retorna objeto tomador do servidor | sua instituicao de trabalho
     *
     * @param $seqpes
     * @return object|null
     */
    public function getCgmOwn($seqpes)
    {
        $sSql = " SELECT cgm.* FROM  rhpessoalmov  
                  INNER JOIN rhpessoal ON 
                  rhpessoal.rh01_regist = rhpessoalmov.rh02_regist 
                  and rhpessoal.rh01_instit = rhpessoalmov.rh02_instit
                  INNER JOIN db_config ON  codigo = rhpessoalmov.rh02_instit 
                  INNER JOIN  cgm  ON   cgm.z01_numcgm = db_config.numcgm    
               where rh02_seqpes = {$seqpes};";

        $rsTomadorOwn =  db_query($sSql);

        return pg_fetch_object($rsTomadorOwn);
    }

}
