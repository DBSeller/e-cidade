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
 * Class cl_bemincorporado
 * @property t13_sequencial
 * @property t13_bens
 * @property t13_bempendenteincorporacao
 * @property t13_data
 * @property t13_reavaliacao
 * @property t13_quantidade
 * @property t13_ativo
 */
class cl_bemincorporado extends DAOBasica
{
    function __construct()
    {
        parent::__construct("patrimonio.bemincorporado");
    }

    function consultaMateriaisIncorporado($codigoBem)
    {
        $sql = "
        select e60_numemp, e60_codemp, e60_anousu, e60_vlremp, t13_reavaliacao, t13_quantidade,
               t13_data, t12_valorunitario, (t13_quantidade * t12_valorunitario) as valor_incorporado,
               m60_descr, t12_servico, t13_sequencial, t12_sequencial
          from bemincorporado
          join bempendenteincorporacao on bempendenteincorporacao.t12_sequencial = bemincorporado.t13_bempendenteincorporacao
          join matestoqueinimei on matestoqueinimei.m82_codigo = bempendenteincorporacao.t12_matestoqueinimei
          join matestoqueitem on matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
          join matestoque on matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
          join matmater on matmater.m60_codmater = m70_codmatmater
          join empempenho on empempenho.e60_numemp = bempendenteincorporacao.t12_empenho
         where t13_bens = {$codigoBem}
           and t13_ativo is true
         order by t13_data, e60_numemp, m60_descr ";

        return $sql;
    }
}