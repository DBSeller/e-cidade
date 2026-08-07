<?php

use Classes\PostgresMigration;

class M17161AjustaPlAlteraIptuTaxaMaquine extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL
        CREATE OR REPLACE FUNCTION public.altera_iptu_taxa_maquine(integer)
            RETURNS boolean AS $$
        declare

            iptu_record record;
            numpre integer;
            iptutaxanump_codigo integer;
            iptutaxacalv_codigo integer;
            iCadvencdesc integer;

            Exercicio alias for $1;

            iIptucadtaxaexe integer;
            Tabrec integer;
            Arretipo integer;

            rCadvenc record;

            iNumtot integer;

            fValor float;
            iNumcgm integer;
            sDtOper date;

            fDiferencaValorTotal float;

            iValorTotalParcelas float default 0;

            i integer;
            t timestamp;

        begin

            perform fc_startsession();

            perform fc_putsession('DB_instit'::varchar, codigo::varchar),
                    fc_putsession('DB_datausu'::varchar, current_date::varchar),
                    fc_putsession('DB_anousu'::varchar, extract(year from current_date)::varchar),
                    fc_putsession('DB_id_usuario'::varchar, '1'),
                    fc_putsession('DB_debugon'::varchar, '1'),
                    fc_putsession('DB_debugRaise'::varchar, '1'),
                    fc_putsession('DB_use_pcasp'::varchar, '1')
            from configuracoes.db_config
            where prefeitura is true;

            select j08_iptucadtaxaexe, j08_tabrec, j08_arretipo, j08_cadvencdesc
            into iIptucadtaxaexe, Tabrec, Arretipo, iCadvencdesc
            from iptucadtaxaexe
            where j08_anousu = Exercicio;

            sDtOper := (to_char(Exercicio, 'FM0000') || '-01-02')::date;

            t := clock_timestamp();
            i := 0;

            for iptu_record in

             select j21_anousu,
                    j21_matric,
                    j21_receit,
                    sum(j21_valor) as j21_valor,
                    min(j21_quant) as j21_quant,
                    min(j21_codhis) as j21_codhis,
                    j20_numpre
               from iptucalv
          left join iptunump on iptunump.j20_anousu = iptucalv.j21_anousu
                            and iptunump.j20_matric = iptucalv.j21_matric
              where iptucalv.j21_codhis in (2, 4) /* iptucalh de taxa(2) e isencao de taxa(4) */
                and iptucalv.j21_anousu = Exercicio
                and iptunump.j20_matric is not null
           group by j21_anousu, j21_matric, j21_receit, j20_numpre

            loop /* Início do Loop do For Externo*/

                i := i + 1;

                raise notice '% - %', i, iptu_record;

                iptutaxanump_codigo := null;
                numpre := null;

                perform j151_codigo
                from iptutaxanump inner join iptucadtaxaexe on j08_iptucadtaxaexe = j151_iptucadtaxaexe
                where j151_matric        = iptu_record.j21_matric
                and j08_iptucadtaxaexe = iIptucadtaxaexe
                and j08_anousu         = Exercicio;

                if found then

                    select j151_codigo,
                        j151_numpre
                    into iptutaxanump_codigo,
                        numpre
                    from iptutaxanump inner join iptucadtaxaexe on j08_iptucadtaxaexe = j151_iptucadtaxaexe
                    where j151_matric        = iptu_record.j21_matric
                    and j08_iptucadtaxaexe = iIptucadtaxaexe
                    and j08_anousu         = Exercicio;

                else

                    select nextval('iptutaxanump_j151_codigo_seq') into iptutaxanump_codigo;

                    if iptu_record.j20_numpre <> 0 or iptu_record.j20_numpre is not null then

                        select nextval('numpref_k03_numpre_seq') into numpre;

                    end if;

                    insert into iptutaxanump (
                        j151_codigo,
                        j151_matric,
                        j151_numpre,
                        j151_iptucadtaxaexe
                    ) values (
                        iptutaxanump_codigo,
                        iptu_record.j21_matric,
                        numpre,
                        iIptucadtaxaexe
                    );

                end if;

                delete from arrecad
                where arrecad.k00_numpre = iptu_record.j20_numpre
                and arrecad.k00_receit = Tabrec;

                select max(q82_parc)
                into iNumtot
                from cadvenc
                where q82_codigo = iCadvencdesc;

                select j01_numcgm
                into iNumcgm
                from iptubase
                where j01_matric = iptu_record.j21_matric;

                for rCadvenc in
                    select q82_parc,
                        q82_perc,
                        q82_hist,
                        q82_venc
                    from cadvenc
                    where q82_codigo = iCadvencdesc

                loop /*Início do Loop Interno*/

                    fValor := round(iptu_record.j21_valor * (rCadvenc.q82_perc / 100), 2);

                    iValorTotalParcelas = iValorTotalParcelas + fValor;

                    if rCadvenc.q82_parc = iNumtot then
                        if iValorTotalParcelas < iptu_record.j21_valor then
                            fDiferencaValorTotal := round(iptu_record.j21_valor - iValorTotalParcelas, 2);
                            fValor := fValor + fDiferencaValorTotal;
                        end if ;
                        if iValorTotalParcelas > iptu_record.j21_valor then
                            fDiferencaValorTotal := round(iValorTotalParcelas - iptu_record.j21_valor, 2);
                            fValor := fValor + fDiferencaValorTotal;
                        end if ;
                    end if ;

                    insert into arrecad values (
                        numpre,
                        rCadvenc.q82_parc,
                        iNumcgm,
                        sDtOper,
                        iptu_record.j21_receit,
                        rCadvenc.q82_hist,
                        fValor,
                        rCadvenc.q82_venc,
                        iNumtot,
                        0,--numdig,
                        Arretipo,
                        0--tipojm
                    );

                end loop; /*Fim do Loop Interno*/

            insert into arrematric (
                    k00_numpre,
                    k00_matric,
                    k00_perc
                )
                select numpre,
                    k00_matric,
                    k00_perc
                from arrematric
                where arrematric.k00_numpre = iptu_record.j20_numpre;

                raise notice 'insert - %', numpre;

                select nextval('iptutaxacalv_j152_codigo_seq') into iptutaxacalv_codigo;

                insert into iptutaxacalv (
                    j152_codigo,
                    j152_iptutaxanump,
                    j152_codhis,
                    j152_receit,
                    j152_valor,
                    j152_quant
                ) values (
                    iptutaxacalv_codigo,
                    iptutaxanump_codigo,
                    iptu_record.j21_codhis,
                    iptu_record.j21_receit,
                    iptu_record.j21_valor,
                    iptu_record.j21_quant
                );

                update isentaxa
                set j56_iptucadtaxaexe = iIptucadtaxaexe
                where j56_receit = Tabrec
                and j56_codigo in (
                    select iptuisen.j46_codigo
                    from iptuisen
                            inner join isenexe on isenexe.j47_codigo = iptuisen.j46_codigo
                    where iptuisen.j46_matric = iptu_record.j21_matric
                    and isenexe.j47_anousu = Exercicio
                );

                delete from iptucalv
                where j21_matric = iptu_record.j21_matric
                and j21_anousu = iptu_record.j21_anousu
                and j21_receit = iptu_record.j21_receit;

                update iptubase set j01_matric = j01_matric where j01_matric = iptu_record.j21_matric;

            iValorTotalParcelas := 0;
            fDiferencaValorTotal := 0;

            end loop; /*Fim do Loop do For Externo*/

            raise notice 'tempo total = %', clock_timestamp() - t;

            perform fc_debug('tempo total = %' || (clock_timestamp() - t), true, false, false);

            return true;

        end;
        $$ language 'plpgsql';
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        CREATE OR REPLACE FUNCTION public.altera_iptu_taxa_maquine(integer)
            RETURNS boolean AS $$
        declare

            iptu_record record;
            numpre integer;
            iptutaxanump_codigo integer;
            iptutaxacalv_codigo integer;
            iCadvencdesc integer;

            Exercicio alias for $1;

            iIptucadtaxaexe integer;
            Tabrec integer;
            Arretipo integer;

            rCadvenc record;

            iNumtot integer;

            fValor float;
            iNumcgm integer;
            sDtOper date;

            fDiferencaValorTotal float;

            iValorTotalParcelas float default 0;

            i integer;
            t timestamp;

        begin

            perform fc_startsession();

            perform fc_putsession('DB_instit'::varchar, codigo::varchar),
                    fc_putsession('DB_datausu'::varchar, current_date::varchar),
                    fc_putsession('DB_anousu'::varchar, extract(year from current_date)::varchar),
                    fc_putsession('DB_id_usuario'::varchar, '1'),
                    fc_putsession('DB_debugon'::varchar, '1'),
                    fc_putsession('DB_debugRaise'::varchar, '1'),
                    fc_putsession('DB_use_pcasp'::varchar, '1')
            from configuracoes.db_config
            where prefeitura is true;

            select j08_iptucadtaxaexe, j08_tabrec, j08_arretipo, j08_cadvencdesc
            into iIptucadtaxaexe, Tabrec, Arretipo, iCadvencdesc
            from iptucadtaxaexe
            where j08_anousu = Exercicio;

            sDtOper := (to_char(Exercicio, 'FM0000') || '-01-02')::date;

            t := clock_timestamp();
            i := 0;

            for iptu_record in

             select j21_anousu,
                    j21_matric,
                    j21_receit,
                    sum(j21_valor) as j21_valor,
                    min(j21_quant) as j21_quant,
                    min(j21_codhis) as j21_codhis,
                    j20_numpre
               from iptucalv
          left join iptunump on iptunump.j20_anousu = iptucalv.j21_anousu
                            and iptunump.j20_matric = iptucalv.j21_matric
              where iptucalv.j21_codhis in (2, 4) /* iptucalh de taxa(2) e isencao de taxa(4) */
                and iptucalv.j21_anousu = Exercicio
                and iptunump.j20_matric is not null
           group by j21_anousu, j21_matric, j21_receit, j20_numpre

            loop /* Início do Loop do For Externo*/

                i := i + 1;

                raise notice '% - %', i, iptu_record;

                iptutaxanump_codigo := null;
                numpre := null;

                perform j151_codigo
                from iptutaxanump inner join iptucadtaxaexe on j08_iptucadtaxaexe = j151_iptucadtaxaexe
                where j151_matric        = iptu_record.j21_matric
                and j08_iptucadtaxaexe = iIptucadtaxaexe
                and j08_anousu         = Exercicio;

                if found then

                    select j151_codigo,
                        j151_numpre
                    into iptutaxanump_codigo,
                        numpre
                    from iptutaxanump inner join iptucadtaxaexe on j08_iptucadtaxaexe = j151_iptucadtaxaexe
                    where j151_matric        = iptu_record.j21_matric
                    and j08_iptucadtaxaexe = iIptucadtaxaexe
                    and j08_anousu         = Exercicio;

                else

                    select nextval('iptutaxanump_j151_codigo_seq') into iptutaxanump_codigo;

                    if iptu_record.j20_numpre <> 0 or iptu_record.j20_numpre is not null then

                        select nextval('numpref_k03_numpre_seq') into numpre;

                    end if;

                    insert into iptutaxanump (
                        j151_codigo,
                        j151_matric,
                        j151_numpre,
                        j151_iptucadtaxaexe
                    ) values (
                        iptutaxanump_codigo,
                        iptu_record.j21_matric,
                        numpre,
                        iIptucadtaxaexe
                    );

                end if;

                delete from arrecad
                where arrecad.k00_numpre = iptu_record.j20_numpre
                and arrecad.k00_receit = Tabrec;

                select max(q82_parc)
                into iNumtot
                from cadvenc
                where q82_codigo = iCadvencdesc;

                select j01_numcgm
                into iNumcgm
                from iptubase
                where j01_matric = iptu_record.j21_matric;

                for rCadvenc in
                    select q82_parc,
                        q82_perc,
                        q82_hist,
                        q82_venc
                    from cadvenc
                    where q82_codigo = iCadvencdesc

                loop /*Início do Loop Interno*/

                    fValor := round(iptu_record.j21_valor * (rCadvenc.q82_perc / 100), 2);

                    iValorTotalParcelas = iValorTotalParcelas + fValor;

                    if rCadvenc.q82_parc = iNumtot then
                        if iValorTotalParcelas < iptu_record.j21_valor then
                            fDiferencaValorTotal := round(iptu_record.j21_valor - iValorTotalParcelas, 2);
                            fValor := fValor + fDiferencaValorTotal;
                        end if ;
                        if iValorTotalParcelas > iptu_record.j21_valor then
                            fDiferencaValorTotal := round(iValorTotalParcelas - iptu_record.j21_valor, 2);
                            fValor := fValor + fDiferencaValorTotal;
                        end if ;
                    end if ;

                    insert into arrecad values (
                        numpre,
                        rCadvenc.q82_parc,
                        iNumcgm,
                        sDtOper,
                        iptu_record.j21_receit,
                        rCadvenc.q82_hist,
                        fValor,
                        rCadvenc.q82_venc,
                        iNumtot,
                        0,--numdig,
                        Arretipo,
                        0--tipojm
                    );

                end loop; /*Fim do Loop Interno*/

            insert into arrematric (
                    k00_numpre,
                    k00_matric,
                    k00_perc
                )
                select numpre,
                    k00_matric,
                    k00_perc
                from arrematric
                where arrematric.k00_numpre = iptu_record.j20_numpre;

                raise notice 'insert - %', numpre;

                select nextval('iptutaxacalv_j152_codigo_seq') into iptutaxacalv_codigo;

                insert into iptutaxacalv (
                    j152_codigo,
                    j152_iptutaxanump,
                    j152_codhis,
                    j152_receit,
                    j152_valor,
                    j152_quant
                ) values (
                    iptutaxacalv_codigo,
                    iptutaxanump_codigo,
                    iptu_record.j21_codhis,
                    iptu_record.j21_receit,
                    iptu_record.j21_valor,
                    iptu_record.j21_quant
                );

                update isentaxa
                set j56_iptucadtaxaexe = iIptucadtaxaexe
                where j56_receit = Tabrec
                and j56_codigo in (
                    select iptuisen.j46_codigo
                    from iptuisen
                            inner join isenexe on isenexe.j47_codigo = iptuisen.j46_codigo
                    where iptuisen.j46_matric = iptu_record.j21_matric
                    and isenexe.j47_anousu = Exercicio
                );

                delete from iptucalv
                where j21_matric = iptu_record.j21_matric
                and j21_anousu = iptu_record.j21_anousu
                and j21_receit = iptu_record.j21_receit;

                update iptubase set j01_matric = j01_matric where j01_matric = iptu_record.j21_matric;

            iValorTotalParcelas := 0;
            fDiferencaValorTotal := 0;

            end loop; /*Fim do Loop do For Externo*/

            raise notice 'tempo total = %', clock_timestamp() - t;

            perform fc_debug('tempo total = %' || (clock_timestamp() - t), true, false, false);

            return true;

        end;
        $$ language 'plpgsql';
SQL
        );
    }
}
