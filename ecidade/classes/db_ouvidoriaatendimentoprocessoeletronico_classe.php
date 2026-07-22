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

class cl_ouvidoriaatendimentoprocessoeletronico extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("ouvidoria.ouvidoriaatendimentoprocessoeletronico");
    }

    /**
     *  Buscar processo eletronico por cgm e tipo de processo
     * @param cgm int
     * @param tipoprocesso int
     * @param campos string
     * @param where string
     * @return string
     */
    public function getProcessoEletronicoByCgmAndTipoProcesso(
        $cgm, 
        $tipoprocesso, 
        $campos = "*", 
        $where = ""
    )
    {   
        $sql = "
        select
            {$campos}
        from
            ouvidoriaatendimentoprocessoeletronico
            join ouvidoriaatendimento on ov01_sequencial = ov33_ouvidoriaatendimento
            join processoouvidoria on ov09_ouvidoriaatendimento = ov01_sequencial
            join protprocesso on p58_codproc = ov09_protprocesso
            join cgm on z01_numcgm = p58_numcgm
            left join arqproc on p68_codproc = p58_codproc
        where
            z01_numcgm = {$cgm}
            and p58_obs ilike '%Recadastramento acesso arquivado pelo sistema%'
        ";

        if (!empty($where)) {
            $sql .= " and {$where}";
        }

        return $sql;
    } 
}
