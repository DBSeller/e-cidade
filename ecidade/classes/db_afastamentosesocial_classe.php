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
 * Class cl_afastamentosesocial
 */
class cl_afastamentosesocial extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('esocial.afastamentosesocial');
    }

    public function sql_query_grupo_assentamento($campos = "*", $where = "")
    {
        $sql  = " select $campos";
        $sql .= "  from  afastamentosesocial ";
        $sql .= "        inner join tipoasse on eso08_tipoasse = h12_codigo ";
        $sql .= "        inner join grupomotivoafastamentoesocial on eso10_sequencial = eso08_grupomotivoafastamentoesocial ";

        if(!empty($where)) {
            $sql .= " where {$where} ";
        }

        return $sql;
    }

    public function sql_query_grupo_assentamento_codigos()
    {
        $sql = " select
                    eso08_sequencial,
                    h12_assent,
                    h12_descr,
                    eso10_sequencial as db_eso10_sequencial, 
                    eso10_descricao || ' - código(s) eSocial:' 
                        || string_agg(db18_opcao,', 'order by db18_opcao::integer) as eso10_descricao 
                from 
                    afastamentosesocial
                    inner join tipoasse on eso08_tipoasse = h12_codigo
                    inner join grupomotivoafastamentoesocial on eso10_sequencial = eso08_grupomotivoafastamentoesocial
                    inner join db_cadattdinamico on eso10_db_cadattdinamico = db118_sequencial 
                    inner join db_cadattdinamicoatributos on db109_db_cadattdinamico = db118_sequencial 
                    inner join db_cadattdinamicoatributosopcoes on db18_cadattdinamicoatributos = db109_sequencial      
                WHERE db109_nome = 'motivo_esocial'
                group by 
                    eso08_sequencial,
                    h12_assent,
                    h12_descr,
                    eso10_sequencial,
                    eso10_descricao,
                    eso10_db_cadattdinamico
                order by eso08_sequencial asc 
        ";
        if(!empty($where)) {
            $sql .= " where {$where} ";
        }

        return $sql;
    }

    /**
     * @param string $campos
     * @param string $where
     * @return string
     */
    public function sql_query_tipo_assentamento($campos = "*", $where)
    {
        $sql  = " select {$campos} ";
        $sql .= "   from afastamentosesocial ";
        $sql .= "        inner join tipoasse on h12_codigo = eso08_tipoasse ";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }

        return $sql;
    }

    public function sql_query_mapeamento_atributos( $campos = "*", $where ){

        $sql  = " select {$campos} ";
        $sql .= "   from afastamentosesocial ";
        $sql .= "    inner join tipoassedb_cadattdinamico on tipoassedb_cadattdinamico.h79_tipoasse = afastamentosesocial.eso08_tipoasse ";
        $sql .= "    inner join db_cadattdinamicoatributos on db_cadattdinamicoatributos.db109_db_cadattdinamico = tipoassedb_cadattdinamico.h79_db_cadattdinamico ";
        $sql .= "    inner join mapeamentoatributosesocial on mapeamentoatributosesocial.db39_camponovo = db_cadattdinamicoatributos.db109_sequencial ";
        $sql .= "    left join db_cadattdinamicoatributosvalor on db_cadattdinamicoatributosvalor.db110_db_cadattdinamicoatributos = mapeamentoatributosesocial.db39_camponovo";
        
        if (!empty($where)) {
            $sql .= "  where {$where} ";
        }

        return $sql;
    }

    public function sql_query_atributos_valores($campos = "*", $where){

        $sql  = " SELECT {$campos}  ";
        $sql .= "   FROM afastamentosesocial ";
        $sql .= "    INNER JOIN grupomotivoafastamentoesocial ON grupomotivoafastamentoesocial.eso10_sequencial = afastamentosesocial.eso08_grupomotivoafastamentoesocial ";
        $sql .= "    INNER JOIN db_cadattdinamico ON db_cadattdinamico.db118_sequencial = grupomotivoafastamentoesocial.eso10_db_cadattdinamico ";
        $sql .= "    INNER JOIN db_cadattdinamicoatributos ON db_cadattdinamicoatributos.db109_db_cadattdinamico = db_cadattdinamico.db118_sequencial  ";
        $sql .= "    INNER JOIN db_cadattdinamicoatributosvalor ON db_cadattdinamicoatributosvalor.db110_db_cadattdinamicoatributos = db_cadattdinamicoatributos.db109_sequencial  ";

        if (!empty($where)) {
            $sql .= " where {$where}";
        }

        return $sql;
    }

}