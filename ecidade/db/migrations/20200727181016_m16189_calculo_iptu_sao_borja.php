<?php

use Classes\PostgresMigration;

class M16189CalculoIptuSaoBorja extends PostgresMigration
{

    public function up()
    {
        /* db_sysfuncoes / db_sysfuncoesparam */
        $sql = <<<SQL_UP

/* Ajusta saoborja apenas onde estao errados os codigos de funcao, estes codigos sao de maquine */        
update configuracoes.db_sysfuncoes set nomefuncao = 'fc_iptu_taxalimpeza_saoborja_2018_ant' where codfuncao = 191 and nomefuncao = 'fc_iptu_taxalimpeza_saoborja_2018';
update configuracoes.db_sysfuncoes set nomefuncao = 'fc_iptu_getaliquota_saoborja_2018_ant' where codfuncao = 190 and nomefuncao = 'fc_iptu_getaliquota_saoborja_2018';
update configuracoes.db_sysfuncoes set nomefuncao = 'fc_iptu_calculavvt_saoborja_2018_ant'  where codfuncao = 189 and nomefuncao = 'fc_iptu_calculavvt_saoborja_2018' ;
update configuracoes.db_sysfuncoes set nomefuncao = 'fc_iptu_calculavvc_saoborja_2018_ant'  where codfuncao = 188 and nomefuncao = 'fc_iptu_calculavvc_saoborja_2018' ;
update configuracoes.db_sysfuncoes set nomefuncao = 'fc_calculoiptu_saoborja_2018_ant'      where codfuncao = 187 and nomefuncao = 'fc_calculoiptu_saoborja_2018'     ;

INSERT INTO configuracoes.db_sysfuncoes select 198, 'fc_calculoiptu_saoborja_2018'     , 'calculoiptu_saoborja_2018.sql'     , 'Cálculo de IPTU de São Borja', '.', '0' where not exists (select 1 from db_sysfuncoes where codfuncao = 198);
INSERT INTO configuracoes.db_sysfuncoes select 199, 'fc_iptu_calculavvc_saoborja_2018' , 'iptu_calculavvc_saoborja_2018.sql' , 'Função de cálculo da construção de São Borja', '.', '0' where not exists (select 1 from db_sysfuncoes where codfuncao = 199);
INSERT INTO configuracoes.db_sysfuncoes select 200, 'fc_iptu_calculavvt_saoborja_2018' , 'iptu_calculavvt_saoborja_2018.sql' , 'Função de cálculo do terreno de São Borja', '.', '0' where not exists (select 1 from db_sysfuncoes where codfuncao = 200);
INSERT INTO configuracoes.db_sysfuncoes select 201, 'fc_iptu_getaliquota_saoborja_2018', 'iptu_getaliquota_saoborja_2018.sql', 'Função para buscar a alíquota de IPTU de São Borja', '.', '0' where not exists (select 1 from db_sysfuncoes where codfuncao = 201);
INSERT INTO configuracoes.db_sysfuncoes select 202, 'fc_iptu_taxalimpeza_saoborja_2018', 'iptu_taxalimpeza_saoborja_2018.sql', 'Cálculo de taxa de limpeza de São Borja', '.', '0' where not exists (select 1 from db_sysfuncoes where codfuncao = 202);

select setval('db_sysfuncoes_codfuncao_seq', (select max(codfuncao) from db_sysfuncoes)::integer);

update db_sysfuncoesparam           set db42_sysfuncoesparam = db42_sysfuncoesparam + 60 where db42_funcao in (select codfuncao from db_sysfuncoes where (codfuncao between 187 and 191) and nomefuncao ilike '%saoborja%');

update cfiptu                       set j18_db_sysfuncoes = j18_db_sysfuncoes + 11 where j18_db_sysfuncoes in (select codfuncao from db_sysfuncoes where (codfuncao between 187 and 191) and nomefuncao ilike '%saoborja%');
update db_sysfuncoesparam           set db42_funcao       = db42_funcao       + 11 where db42_funcao       in (select codfuncao from db_sysfuncoes where (codfuncao between 187 and 191) and nomefuncao ilike '%saoborja%');
update iptucadtaxaexe               set j08_db_sysfuncoes = j08_db_sysfuncoes + 11 where j08_db_sysfuncoes in (select codfuncao from db_sysfuncoes where (codfuncao between 187 and 191) and nomefuncao ilike '%saoborja%');

SQL_UP;
        /* Comentado para deploy. Bloco mantido caso seja necessario executar em base de homologacao/testes
         * $this->execute($sql);
         */ 

        $sTabela = $this->table('cadastro.iptutaxacalv');
        $sColuna = $sTabela->hasColumn('j152_areaed');

        if ( !$sColuna ){
            
            $sql_iptutaxacalv_areaed = <<<STRING_IPTUTAXACALV_AREAED
                                          alter table iptutaxacalv add COLUMN j152_areaed double precision default 0;
                                          update iptutaxacalv set j152_areaed = tot_areaed 
                                          from (select j152_codigo             as cod_iptutaxacalv, 
                                                       coalesce(tot_areaed, 0) as tot_areaed 
                                                  from iptucadtaxaexe inner join iptutaxanump 
                                                    on j151_iptucadtaxaexe = j08_iptucadtaxaexe inner join iptutaxacalv 
                                                    on j152_iptutaxanump   = j151_codigo left join (select j22_matric, 
                                                                                                           j22_anousu, sum(j22_areaed) tot_areaed 
                                                                                                      from iptucale 
                                                                                                  group by j22_matric, j22_anousu) as sq_iptucale 
                                                    on j151_matric = j22_matric 
                                                   and j08_anousu  = j22_anousu 
                                                 where j08_anousu  = 2020) as sq 
                                           where cod_iptutaxacalv  = j152_codigo;

STRING_IPTUTAXACALV_AREAED;

            $this->execute($sql_iptutaxacalv_areaed);

        }

        for ($iAno = 2015; $iAno < 2020; $iAno++) {

            $exists_cfiptu = $this->hasTable('cadastro_'.$iAno.'.cfiptu');

            if ($exists_cfiptu) {
                $sql_cfiptu = <<<STRING_CFIPTU
                                 update cadastro_$iAno.cfiptu set j18_db_sysfuncoes = j18_db_sysfuncoes + 11 where j18_db_sysfuncoes in (select codfuncao from db_sysfuncoes where (codfuncao between 187 and 191) and nomefuncao ilike '%saoborja%');
STRING_CFIPTU;
                $this->execute($sql_cfiptu);
            }

            $exists_iptucadtaxaexe = $this->hasTable('cadastro_'.$iAno.'.iptucadtaxaexe');

            if ($exists_iptucadtaxaexe) {
                $sql_iptucadtaxaexe = <<<STRING_IPTUCADTAXAEXE
                                         update cadastro_$iAno.iptucadtaxaexe set j08_db_sysfuncoes = j08_db_sysfuncoes + 11 where j08_db_sysfuncoes in (select codfuncao from db_sysfuncoes where (codfuncao between 187 and 191) and nomefuncao ilike '%saoborja%');

STRING_IPTUCADTAXAEXE;
                $this->execute($sql_iptucadtaxaexe);
            }

            $exists_iptutaxacalv = $this->hasTable('cadastro_'.$iAno.'.iptutaxacalv');
            
            if ($exists_iptutaxacalv) {

                $sTabela = $this->table('cadastro_'.$iAno.'.iptutaxacalv');
                $sColuna = $sTabela->hasColumn('j152_areaed');

                if ( !$sColuna ){
            
                      $sql_iptutaxacalv_areaed = <<<STRING_IPTUTAXACALV_AREAED
                                                    alter table cadastro_$iAno.iptutaxacalv add COLUMN j152_areaed double precision default 0;
STRING_IPTUTAXACALV_AREAED;

                      $this->execute($sql_iptutaxacalv_areaed);

                }

                $sql_iptutaxacalv = <<<STRING_IPTUTAXACALV
                                       update cadastro_$iAno.iptutaxacalv set j152_areaed = tot_areaed 
                                       from (select j152_codigo             as cod_iptutaxacalv, 
                                                    coalesce(tot_areaed, 0) as tot_areaed 
                                               from cadastro_$iAno.iptucadtaxaexe inner join cadastro_$iAno.iptutaxanump 
                                                 on j151_iptucadtaxaexe = j08_iptucadtaxaexe inner join cadastro_$iAno.iptutaxacalv 
                                                 on j152_iptutaxanump   = j151_codigo left join (select j22_matric, 
                                                                                                        j22_anousu, sum(j22_areaed) tot_areaed 
                                                                                                   from cadastro_$iAno.iptucale 
                                                                                               group by j22_matric, j22_anousu) as sq_iptucale 
                                                 on j151_matric = j22_matric 
                                                and j08_anousu  = j22_anousu 
                                              where j08_anousu  = $iAno) as sq 
                                        where cod_iptutaxacalv  = j152_codigo;

STRING_IPTUTAXACALV;
                $this->execute($sql_iptutaxacalv);
            }

        }


        $sql_ajusta_funcao = <<<STRING0

                                update configuracoes.db_sysfuncoes  set nomefuncao = 'fc_iptu_taxalimpeza_maq_2019', nomearquivo = 'fc_iptu_taxalimpeza_maq_2019', corpofuncao = '' where codfuncao = 191 and nomefuncao = 'fc_iptu_taxalimpeza_saoborja_2018_ant';
                                update configuracoes.db_sysfuncoes  set nomefuncao = 'fc_iptu_getaliquota_maq_2019', nomearquivo = 'fc_iptu_getaliquota_maq_2019', corpofuncao = '' where codfuncao = 190 and nomefuncao = 'fc_iptu_getaliquota_saoborja_2018_ant';
                                update configuracoes.db_sysfuncoes  set nomefuncao = 'fc_iptu_calculavvt_maq_2019' , nomearquivo = 'fc_iptu_calculavvt_maq_2019' , corpofuncao = '' where codfuncao = 189 and nomefuncao = 'fc_iptu_calculavvt_saoborja_2018_ant' ;
                                update configuracoes.db_sysfuncoes  set nomefuncao = 'fc_iptu_calculavvc_maq_2019' , nomearquivo = 'fc_iptu_calculavvc_maq_2019' , corpofuncao = '' where codfuncao = 188 and nomefuncao = 'fc_iptu_calculavvc_saoborja_2018_ant' ;
                                update configuracoes.db_sysfuncoes  set nomefuncao = 'fc_calculoiptu_maq_2019'     , nomearquivo = 'fc_calculoiptu_maq_2019'     , corpofuncao = '' where codfuncao = 187 and nomefuncao = 'fc_calculoiptu_saoborja_2018_ant'     ;

STRING0;
        $this->execute($sql_ajusta_funcao);

        /* uma string para cada funcao */

    }

    public function down()
    {
        return true;
    }

}
