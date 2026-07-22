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

/**
 * @property integer v35_sequencial
 * @property integer v35_inicial
 * @property integer v35_tipolancamento
 * @property string  v35_dtpagamento
 * @property string  v35_obs
 * @property float   v35_valorpartilha
 * @property string  v35_datapartilha
 * @property string  v35_justificativa
 */
class cl_inicialpartilha extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("juridico.inicialpartilha");
    }

    /**
     * @param $taxa
     * @param $inicial
     * @return string
     */
    public function sql_parcelas_pagas($taxa, $inicial)
    {
        $sql = "
          SELECT DISTINCT k00_numpar
          FROM inicialpartilha
                   INNER JOIN inicialpartilhacustas ON v36_inicialpartilha = v35_sequencial
                   INNER JOIN recibopaga ON v36_numnov = k00_numnov
          WHERE v35_dtpagamento IS NOT NULL
            AND v36_taxa = $taxa
            AND v35_inicial = $inicial
          union
          SELECT DISTINCT recibopagaold.k00_numpar
          FROM inicialpartilha
                   INNER JOIN inicialpartilhacustas ON v36_inicialpartilha = v35_sequencial
                   INNER JOIN recibo ON v36_numnov = recibo.k00_numpre
                   INNER JOIN recibopagaold ON recibo.k00_numpre = recibopagaold.k00_numpreold
              and recibo.k00_receit = recibopagaold.k00_receit
              and recibo.k00_hist = recibopagaold.k00_hist
          WHERE v35_dtpagamento IS NOT NULL
            AND v36_taxa = $taxa
            AND v35_inicial = $inicial;
        ";

        return $sql;
    }
}
