<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20874 extends Migration
{

    public function dicionarioDedadosUp()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        INSERT INTO db_sysarquivo VALUES (1010925, 'persona', 'Tipos de Personas ', 'p120', '2022-05-20', 'persona', 0, 'f', 'f', 'f', 'f' );
        INSERT INTO db_sysarqmod VALUES (4,1010925);
        INSERT INTO db_sysarqarq VALUES(0,1010925);
        INSERT INTO db_syscampo VALUES (1014136,'p120_sequencial','int4','Código da Persona','0', 'Código persona',8,'f','f','t',1,'text','Código persona');
        INSERT INTO db_syscampo VALUES (1014137,'p120_descricao','varchar(150)','Descrição da persona','', 'Descrição da persona',150,'f','t','f',0,'text','Descrição da persona');
        INSERT INTO db_syscampo VALUES (1014138,'p120_objetivo','varchar(150)','Objetivo','', 'Objetivo',150,'f','t','f',0,'text','Objetivo');
        INSERT INTO db_sysarqcamp VALUES (1010925,1014138,1,0);
        INSERT INTO db_sysarqcamp VALUES (1010925,1014137,2,0);
        INSERT INTO db_sysarqcamp VALUES (1010925,1014136,3,0);

        INSERT INTO db_sysarquivo VALUES (1010926, 'personacgm', 'Vinculo da persona com o cgm', 'p121', '2022-05-23', 'personacgm', 0, 'f', 'f', 'f', 'f' );
        INSERT INTO db_sysarqmod VALUES (4,1010926);
        INSERT INTO db_sysarqarq VALUES (0,1010926);
        INSERT INTO db_syscampo VALUES (1014139,'p121_sequencial','int8','Sequencial','0', 'Sequencial',8,'f','f','f',1,'text','Sequencial');
        INSERT INTO db_syscampo VALUES (1014140,'p121_persona','int8','Persona','0', 'Persona',8,'f','f','f',1,'text','Persona');
        INSERT INTO db_syscampo VALUES (1014141,'p121_cgm','int8','CGM','0', 'CGM',8,'f','f','f',1,'text','CGM');
        INSERT INTO db_sysarqcamp VALUES (1010926,1014141,1,0);
        INSERT INTO db_sysarqcamp VALUES (1010926,1014140,2,0);
        INSERT INTO db_sysarqcamp VALUES (1010926,1014139,3,0);

        insert into db_sysarquivo values (1010934, 'tipoprocpersona', 'Relaciona personas ao tipo de processo', 'ov34', '2022-05-24', 'tipoprocpersona', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (66,1010934);
        insert into db_sysarqarq values(0,1010934);
        insert into db_syscampo values(1014161,'ov34_sequencial','int8','Sequencial Tipo processo Persona','0', 'Sequencial Tipo processo Persona',8,'f','f','f',1,'text','Sequencial Tipo processo Persona');
        insert into db_syscampo values(1014163,'ov34_persona','int8','Persona','0', 'Persona',8,'f','f','f',1,'text','Persona');
        insert into db_syscampo values(1014164,'ov34_tipoproc','int8','Tipo Processo','0', 'Tipo Processo',8,'f','f','f',1,'text','Tipo Processo');
        insert into db_sysarqcamp values(1010934,1014164,1,0);
        insert into db_sysarqcamp values(1010934,1014161,2,0);
        insert into db_sysarqcamp values(1010934,1014163,3,0);


SQL
        );
    }

    public function tablesUp()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        create table IF NOT EXISTS protocolo.persona (
            p120_sequencial serial,
            p120_descricao varchar(150) NOT NULL,
            p120_objetivo text NOT NULL ,
            PRIMARY KEY(p120_sequencial)
        );
        CREATE INDEX  sequencial_persona ON protocolo.persona (p120_sequencial);
        SELECT configuracoes.fc_auditoria_cria_funcao('protocolo.persona');

        insert into protocolo.persona (p120_descricao,p120_objetivo ) values('CIDADAO', 'TODOS CGMs');
        insert into protocolo.persona (p120_descricao,p120_objetivo ) values('CONTRIBUINTE IMOBILIARIO','CGM Vinculado ao imóvel');
        insert into protocolo.persona (p120_descricao,p120_objetivo ) values('ESCRITORIO CONTABIL', 'vinculado a cadastro de escritório contábil');
        insert into protocolo.persona (p120_descricao,p120_objetivo ) values('PESSOA JURIDICA', 'CGM vinculado a CNPJ');
        insert into protocolo.persona (p120_descricao,p120_objetivo ) values('PESSOA FISICA', 'CGM vinculado CPF');
        insert into protocolo.persona (p120_descricao,p120_objetivo ) values('SERVIDOR', 'CGM com matrícula no módulo Pessoal');

         create table  IF NOT EXISTS protocolo.personacgm (
             p121_sequencial serial,
             p121_persona bigint NOT NULL ,
             p121_cgm bigint NOT NULL,
            PRIMARY KEY(p121_sequencial),
            CONSTRAINT fk_p121_persona  FOREIGN KEY(p121_persona) REFERENCES protocolo.persona(p120_sequencial),
            CONSTRAINT fk_p121_cgm  FOREIGN KEY(p121_cgm) REFERENCES protocolo.cgm(z01_numcgm)
         );
         CREATE INDEX  sequencial_personacgm ON protocolo.personacgm(p121_sequencial);
         CREATE INDEX  persona_personacgm ON protocolo.personacgm(p121_persona);
         CREATE INDEX  cgm_personacgm ON protocolo.personacgm(p121_cgm);

         SELECT configuracoes.fc_auditoria_cria_funcao('protocolo.personacgm');

        insert into personacgm(p121_cgm,p121_persona) select distinct z01_numcgm,1 from cgm;
        insert into personacgm(p121_cgm,p121_persona) select distinct z01_numcgm,2 from proprietario;
        insert into personacgm(p121_cgm,p121_persona) select distinct q10_numcgm,3 from escrito;
        insert into personacgm(p121_cgm,p121_persona) select distinct z01_numcgm,4 from db_cgmcgc;
        insert into personacgm(p121_cgm,p121_persona) select distinct z01_numcgm,5 from db_cgmcpf;
        insert into personacgm(p121_cgm,p121_persona) select distinct rh01_numcgm,6 from rhpessoal;

        create table IF NOT EXISTS ouvidoria.tipoprocpersona (
            ov34_sequencial serial,
            ov34_persona bigint NOT NULL,
            ov34_tipoproc bigint NOT NULL,
            PRIMARY KEY(ov34_sequencial),
            CONSTRAINT fk_ov34_persona FOREIGN KEY(ov34_persona) REFERENCES protocolo.persona(p120_sequencial),
            CONSTRAINT fk_ov34_tipoproc  FOREIGN KEY(ov34_tipoproc) REFERENCES protocolo.tipoproc(p51_codigo)
        );
        CREATE INDEX  sequencial_tipoprocpersona ON ouvidoria.tipoprocpersona(ov34_sequencial);
        CREATE INDEX  persona_tipoprocpersona ON ouvidoria.tipoprocpersona(ov34_persona);
        CREATE INDEX  tipoproc_tipoprocpersona ON ouvidoria.tipoprocpersona(ov34_tipoproc);
        SELECT configuracoes.fc_auditoria_cria_funcao('ouvidoria.tipoprocpersona');
SQL
        );
    }

    public function tablesDown()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            select configuracoes.fc_auditoria_remove_funcao('ouvidoria.tipoprocpersona ');
            DROP  TABLE IF EXISTS ouvidoria.tipoprocpersona;

            select configuracoes.fc_auditoria_remove_funcao('protocolo.personacgm');
            DROP  TABLE IF EXISTS protocolo.personacgm;

            select configuracoes.fc_auditoria_remove_funcao('protocolo.persona');
            DROP  TABLE  IF EXISTS protocolo.persona;
SQL
        );
    }

    public function upFcCgmAltexc()
    {

        DB::connection()->getPdo()->exec(<<<SQL
   CREATE OR REPLACE FUNCTION public.fc_cgm_altexc()
 RETURNS trigger
 LANGUAGE plpgsql
AS $$

        DECLARE

            iSizeCnpjCpf   integer     default 0;

            sCnpj          varchar(14) default '';
            sCpf           varchar(14) default '';
            sHoraAtual     varchar(5)  default (to_char(now(), 'HH24:MI'));

            sSqlMatriculas text        default '';
            rMatriculas    record;

            BEGIN

            select char_length(z01_cgccpf)
              into iSizeCnpjCpf
              from cgm
             where z01_numcgm = OLD.z01_numcgm;

            if iSizeCnpjCpf > 11  then

              sCnpj := OLD.z01_numcgm;
            else

              sCpf := OLD.z01_numcgm;
            end if;


                IF (TG_OP = 'DELETE') THEN

                  INSERT
                    INTO cgmalt (
                                 z05_sequencia,
                                 z05_ufcon,
                                 z05_uf,
                                 z05_tipcre,
                                 z05_telef,
                                 z05_telcon,
                                 z05_telcel,
                                 z05_profis,
                                 z05_numero,
                                 z05_numcon,
                                 z05_numcgm,
                                 z05_nome,
                                 z05_nacion,
                                 z05_munic,
                                 z05_muncon,
                                 z05_login,
                                 z05_incest,
                                 z05_ident,
                                 z05_estciv,
                                 z05_ender,
                                 z05_endcon,
                                 z05_emailc,
                                 z05_email,
                                 z05_cxpostal,
                                 z05_cxposcon,
                                 z05_cpf,
                                 z05_compl,
                                 z05_comcon,
                                 z05_cgccpf,
                                 z05_cgc,
                                 z05_cepcon,
                                 z05_cep,
                                 z05_celcon,
                                 z05_cadast,
                                 z05_bairro,
                                 z05_baicon,
                                 z05_tipo_alt,
                                 z05_hora,
                                 z05_login_alt,
                                 z05_data_alt,
                                 z05_hora_alt,
                                 z05_ultalt,
                                 z05_mae,
                                 z05_pai,
                                 z05_nomefanta,
                                 z05_contato,
                                 z05_sexo,
                                 z05_nasc,
                                 z05_fax,
                                 z05_nomecomple,
                                 z05_identorgao,
                                 z05_cnh,
                                 z05_categoria,
                                 z05_dtemissao,
                                 z05_dthabilitacao,
                                 z05_dtvencimento,
                                 z05_dtfalecimento,
                                 z05_escolaridade,
                                 z05_naturalidade,
                                 z05_identdtexp,
                                 z05_trabalha,
                                 z05_renda,
                                 z05_localtrabalho,
                                 z05_pis,
                                 z05_obs
                                 ) values (

                                 nextval('cgmalt_z05_sequencia_seq'),
                                 OLD.z01_ufcon,
                                 OLD.z01_uf,
                                 OLD.z01_tipcre,
                                 OLD.z01_telef,
                                 OLD.z01_telcon,
                                 OLD.z01_telcel,
                                 OLD.z01_profis,
                                 OLD.z01_numero,
                                 OLD.z01_numcon,
                                 OLD.z01_numcgm,
                                 OLD.z01_nome,
                                 OLD.z01_nacion,
                                 OLD.z01_munic,
                                 OLD.z01_muncon,
                                 OLD.z01_login,
                                 OLD.z01_incest,
                                 OLD.z01_ident,
                                 OLD.z01_estciv,
                                 OLD.z01_ender,
                                 OLD.z01_endcon,
                                 OLD.z01_emailc,
                                 OLD.z01_email,
                                 OLD.z01_cxpostal,
                                 OLD.z01_cxposcon,
                                 sCpf,
                                 OLD.z01_compl,
                                 OLD.z01_comcon,
                                 OLD.z01_cgccpf,
                                 sCnpj,
                                 OLD.z01_cepcon,
                                 OLD.z01_cep,
                                 OLD.z01_celcon,
                                 OLD.z01_cadast,
                                 OLD.z01_bairro,
                                 OLD.z01_baicon,
                                 'E',
                                 OLD.z01_hora,
                                 cast((select fc_getsession('DB_id_usuario')) as integer),
                                 cast((select fc_getsession('DB_datausu')) as date),
                                 sHoraAtual,
                                 OLD.z01_ultalt,
                                 OLD.z01_mae,
                                 OLD.z01_pai,
                                 OLD.z01_nomefanta,
                                 OLD.z01_contato,
                                 OLD.z01_sexo,
                                 OLD.z01_nasc,
                                 OLD.z01_fax,
                                 OLD.z01_nomecomple,
                                 OLD.z01_identorgao,
                                 OLD.z01_cnh,
                                 OLD.z01_categoria,
                                 OLD.z01_dtemissao,
                                 OLD.z01_dthabilitacao,
                                 OLD.z01_dtvencimento,
                                 OLD.z01_dtfalecimento,
                                 OLD.z01_escolaridade,
                                 OLD.z01_naturalidade,
                                 OLD.z01_identdtexp,
                                 OLD.z01_trabalha,
                                 OLD.z01_renda,
                                 OLD.z01_localtrabalho,
                                 OLD.z01_pis,
                                 OLD.z01_obs
                                 );
                   DELETE FROM personacgm WHERE p121_cgm = OLD.z01_numcgm;
                    RETURN OLD;
                    ELSIF (TG_OP = 'UPDATE') THEN
                                INSERT
                    INTO cgmalt (
                                 z05_sequencia,
                                 z05_ufcon,
                                 z05_uf,
                                 z05_tipcre,
                                 z05_telef,
                                 z05_telcon,
                                 z05_telcel,
                                 z05_profis,
                                 z05_numero,
                                 z05_numcon,
                                 z05_numcgm,
                                 z05_nome,
                                 z05_nacion,
                                 z05_munic,
                                 z05_muncon,
                                 z05_login,
                                 z05_incest,
                                 z05_ident,
                                 z05_estciv,
                                 z05_ender,
                                 z05_endcon,
                                 z05_emailc,
                                 z05_email,
                                 z05_cxpostal,
                                 z05_cxposcon,
                                 z05_cpf,
                                 z05_compl,
                                 z05_comcon,
                                 z05_cgccpf,
                                 z05_cgc,
                                 z05_cepcon,
                                 z05_cep,
                                 z05_celcon,
                                 z05_cadast,
                                 z05_bairro,
                                 z05_baicon,
                                 z05_tipo_alt,
                                 z05_hora,
                                 z05_login_alt,
                                 z05_data_alt,
                                 z05_hora_alt,
                                 z05_ultalt,
                                 z05_mae,
                                 z05_pai,
                                 z05_nomefanta,
                                 z05_contato,
                                 z05_sexo,
                                 z05_nasc,
                                 z05_fax,
                                 z05_nomecomple,
                                 z05_identorgao,
                                 z05_cnh,
                                 z05_categoria,
                                 z05_dtemissao,
                                 z05_dthabilitacao,
                                 z05_dtvencimento,
                                 z05_dtfalecimento,
                                 z05_escolaridade,
                                 z05_naturalidade,
                                 z05_identdtexp,
                                 z05_trabalha,
                                 z05_renda,
                                 z05_localtrabalho,
                                 z05_pis,
                                 z05_obs

                                 ) values (
                                 nextval('cgmalt_z05_sequencia_seq'),
                                 OLD.z01_ufcon,
                                 OLD.z01_uf,
                                 OLD.z01_tipcre,
                                 OLD.z01_telef,
                                 OLD.z01_telcon,
                                 OLD.z01_telcel,
                                 OLD.z01_profis,
                                 OLD.z01_numero,
                                 OLD.z01_numcon,
                                 OLD.z01_numcgm,
                                 OLD.z01_nome,
                                 OLD.z01_nacion,
                                 OLD.z01_munic,
                                 OLD.z01_muncon,
                                 OLD.z01_login,
                                 OLD.z01_incest,
                                 OLD.z01_ident,
                                 OLD.z01_estciv,
                                 OLD.z01_ender,
                                 OLD.z01_endcon,
                                 OLD.z01_emailc,
                                 OLD.z01_email,
                                 OLD.z01_cxpostal,
                                 OLD.z01_cxposcon,
                                 sCpf,
                                 OLD.z01_compl,
                                 OLD.z01_comcon,
                                 OLD.z01_cgccpf,
                                 sCnpj,
                                 OLD.z01_cepcon,
                                 OLD.z01_cep,
                                 OLD.z01_celcon,
                                 OLD.z01_cadast,
                                 OLD.z01_bairro,
                                 OLD.z01_baicon,
                                 'A',
                                 OLD.z01_hora,
                                 cast((select fc_getsession('DB_id_usuario')) as integer),
                                 cast((select fc_getsession('DB_datausu')) as date),
                                 sHoraAtual,
                                 OLD.z01_ultalt,
                                 OLD.z01_mae,
                                 OLD.z01_pai,
                                 OLD.z01_nomefanta,
                                 OLD.z01_contato,
                                 OLD.z01_sexo,
                                 OLD.z01_nasc,
                                 OLD.z01_fax,
                                 OLD.z01_nomecomple,
                                 OLD.z01_identorgao,
                                 OLD.z01_cnh,
                                 OLD.z01_categoria,
                                 OLD.z01_dtemissao,
                                 OLD.z01_dthabilitacao,
                                 OLD.z01_dtvencimento,
                                 OLD.z01_dtfalecimento,
                                 OLD.z01_escolaridade,
                                 OLD.z01_naturalidade,
                                 OLD.z01_identdtexp,
                                 OLD.z01_trabalha,
                                 OLD.z01_renda,
                                 OLD.z01_localtrabalho,
                                 OLD.z01_pis,
                                 OLD.z01_obs
                                 );


                      -- Verifica se CGM contém alguma matrícula no sistema em que o código do PIS esteja diferente
                      -- do atual cadastrado na tabela CGM, caso encontre algum registros, então é alterado o PIS

                      sSqlMatriculas := ' select rh01_regist
                                            from rhpessoal
                                                 inner join rhpesdoc on rhpesdoc.rh16_regist = rhpessoal.rh01_regist
                                           where rh01_numcgm = '||new.z01_numcgm||'
                                             and trim(coalesce(rhpesdoc.rh16_pis,\'\')) != \''||trim(coalesce(new.z01_pis,''))||'\'';


                      for rMatriculas in execute sSqlMatriculas loop

                        update rhpesdoc
                           set rh16_pis = new.z01_pis
                         where rh16_regist = rMatriculas.rh01_regist;

                      end  loop;

                      RETURN OLD;
                    END IF;
                RETURN NEW;
            END;
        $$
;

SQL
        );
    }

    public function downFcCgmAltexc()
    {

        DB::connection()->getPdo()->exec(<<<SQL
        CREATE OR REPLACE FUNCTION public.fc_cgm_altexc()
 RETURNS trigger
 LANGUAGE plpgsql
AS $$

        DECLARE

            iSizeCnpjCpf   integer     default 0;

            sCnpj          varchar(14) default '';
            sCpf           varchar(14) default '';
            sHoraAtual     varchar(5)  default (to_char(now(), 'HH24:MI'));

            sSqlMatriculas text        default '';
            rMatriculas    record;

            BEGIN

            select char_length(z01_cgccpf)
              into iSizeCnpjCpf
              from cgm
             where z01_numcgm = OLD.z01_numcgm;

            if iSizeCnpjCpf > 11  then

              sCnpj := OLD.z01_numcgm;
            else

              sCpf := OLD.z01_numcgm;
            end if;


                IF (TG_OP = 'DELETE') THEN

                  INSERT
                    INTO cgmalt (
                                 z05_sequencia,
                                 z05_ufcon,
                                 z05_uf,
                                 z05_tipcre,
                                 z05_telef,
                                 z05_telcon,
                                 z05_telcel,
                                 z05_profis,
                                 z05_numero,
                                 z05_numcon,
                                 z05_numcgm,
                                 z05_nome,
                                 z05_nacion,
                                 z05_munic,
                                 z05_muncon,
                                 z05_login,
                                 z05_incest,
                                 z05_ident,
                                 z05_estciv,
                                 z05_ender,
                                 z05_endcon,
                                 z05_emailc,
                                 z05_email,
                                 z05_cxpostal,
                                 z05_cxposcon,
                                 z05_cpf,
                                 z05_compl,
                                 z05_comcon,
                                 z05_cgccpf,
                                 z05_cgc,
                                 z05_cepcon,
                                 z05_cep,
                                 z05_celcon,
                                 z05_cadast,
                                 z05_bairro,
                                 z05_baicon,
                                 z05_tipo_alt,
                                 z05_hora,
                                 z05_login_alt,
                                 z05_data_alt,
                                 z05_hora_alt,
                                 z05_ultalt,
                                 z05_mae,
                                 z05_pai,
                                 z05_nomefanta,
                                 z05_contato,
                                 z05_sexo,
                                 z05_nasc,
                                 z05_fax,
                                 z05_nomecomple,
                                 z05_identorgao,
                                 z05_cnh,
                                 z05_categoria,
                                 z05_dtemissao,
                                 z05_dthabilitacao,
                                 z05_dtvencimento,
                                 z05_dtfalecimento,
                                 z05_escolaridade,
                                 z05_naturalidade,
                                 z05_identdtexp,
                                 z05_trabalha,
                                 z05_renda,
                                 z05_localtrabalho,
                                 z05_pis,
                                 z05_obs
                                 ) values (

                                 nextval('cgmalt_z05_sequencia_seq'),
                                 OLD.z01_ufcon,
                                 OLD.z01_uf,
                                 OLD.z01_tipcre,
                                 OLD.z01_telef,
                                 OLD.z01_telcon,
                                 OLD.z01_telcel,
                                 OLD.z01_profis,
                                 OLD.z01_numero,
                                 OLD.z01_numcon,
                                 OLD.z01_numcgm,
                                 OLD.z01_nome,
                                 OLD.z01_nacion,
                                 OLD.z01_munic,
                                 OLD.z01_muncon,
                                 OLD.z01_login,
                                 OLD.z01_incest,
                                 OLD.z01_ident,
                                 OLD.z01_estciv,
                                 OLD.z01_ender,
                                 OLD.z01_endcon,
                                 OLD.z01_emailc,
                                 OLD.z01_email,
                                 OLD.z01_cxpostal,
                                 OLD.z01_cxposcon,
                                 sCpf,
                                 OLD.z01_compl,
                                 OLD.z01_comcon,
                                 OLD.z01_cgccpf,
                                 sCnpj,
                                 OLD.z01_cepcon,
                                 OLD.z01_cep,
                                 OLD.z01_celcon,
                                 OLD.z01_cadast,
                                 OLD.z01_bairro,
                                 OLD.z01_baicon,
                                 'E',
                                 OLD.z01_hora,
                                 cast((select fc_getsession('DB_id_usuario')) as integer),
                                 cast((select fc_getsession('DB_datausu')) as date),
                                 sHoraAtual,
                                 OLD.z01_ultalt,
                                 OLD.z01_mae,
                                 OLD.z01_pai,
                                 OLD.z01_nomefanta,
                                 OLD.z01_contato,
                                 OLD.z01_sexo,
                                 OLD.z01_nasc,
                                 OLD.z01_fax,
                                 OLD.z01_nomecomple,
                                 OLD.z01_identorgao,
                                 OLD.z01_cnh,
                                 OLD.z01_categoria,
                                 OLD.z01_dtemissao,
                                 OLD.z01_dthabilitacao,
                                 OLD.z01_dtvencimento,
                                 OLD.z01_dtfalecimento,
                                 OLD.z01_escolaridade,
                                 OLD.z01_naturalidade,
                                 OLD.z01_identdtexp,
                                 OLD.z01_trabalha,
                                 OLD.z01_renda,
                                 OLD.z01_localtrabalho,
                                 OLD.z01_pis,
                                 OLD.z01_obs
                                 );

                    RETURN OLD;
                    ELSIF (TG_OP = 'UPDATE') THEN
                                INSERT
                    INTO cgmalt (
                                 z05_sequencia,
                                 z05_ufcon,
                                 z05_uf,
                                 z05_tipcre,
                                 z05_telef,
                                 z05_telcon,
                                 z05_telcel,
                                 z05_profis,
                                 z05_numero,
                                 z05_numcon,
                                 z05_numcgm,
                                 z05_nome,
                                 z05_nacion,
                                 z05_munic,
                                 z05_muncon,
                                 z05_login,
                                 z05_incest,
                                 z05_ident,
                                 z05_estciv,
                                 z05_ender,
                                 z05_endcon,
                                 z05_emailc,
                                 z05_email,
                                 z05_cxpostal,
                                 z05_cxposcon,
                                 z05_cpf,
                                 z05_compl,
                                 z05_comcon,
                                 z05_cgccpf,
                                 z05_cgc,
                                 z05_cepcon,
                                 z05_cep,
                                 z05_celcon,
                                 z05_cadast,
                                 z05_bairro,
                                 z05_baicon,
                                 z05_tipo_alt,
                                 z05_hora,
                                 z05_login_alt,
                                 z05_data_alt,
                                 z05_hora_alt,
                                 z05_ultalt,
                                 z05_mae,
                                 z05_pai,
                                 z05_nomefanta,
                                 z05_contato,
                                 z05_sexo,
                                 z05_nasc,
                                 z05_fax,
                                 z05_nomecomple,
                                 z05_identorgao,
                                 z05_cnh,
                                 z05_categoria,
                                 z05_dtemissao,
                                 z05_dthabilitacao,
                                 z05_dtvencimento,
                                 z05_dtfalecimento,
                                 z05_escolaridade,
                                 z05_naturalidade,
                                 z05_identdtexp,
                                 z05_trabalha,
                                 z05_renda,
                                 z05_localtrabalho,
                                 z05_pis,
                                 z05_obs

                                 ) values (
                                 nextval('cgmalt_z05_sequencia_seq'),
                                 OLD.z01_ufcon,
                                 OLD.z01_uf,
                                 OLD.z01_tipcre,
                                 OLD.z01_telef,
                                 OLD.z01_telcon,
                                 OLD.z01_telcel,
                                 OLD.z01_profis,
                                 OLD.z01_numero,
                                 OLD.z01_numcon,
                                 OLD.z01_numcgm,
                                 OLD.z01_nome,
                                 OLD.z01_nacion,
                                 OLD.z01_munic,
                                 OLD.z01_muncon,
                                 OLD.z01_login,
                                 OLD.z01_incest,
                                 OLD.z01_ident,
                                 OLD.z01_estciv,
                                 OLD.z01_ender,
                                 OLD.z01_endcon,
                                 OLD.z01_emailc,
                                 OLD.z01_email,
                                 OLD.z01_cxpostal,
                                 OLD.z01_cxposcon,
                                 sCpf,
                                 OLD.z01_compl,
                                 OLD.z01_comcon,
                                 OLD.z01_cgccpf,
                                 sCnpj,
                                 OLD.z01_cepcon,
                                 OLD.z01_cep,
                                 OLD.z01_celcon,
                                 OLD.z01_cadast,
                                 OLD.z01_bairro,
                                 OLD.z01_baicon,
                                 'A',
                                 OLD.z01_hora,
                                 cast((select fc_getsession('DB_id_usuario')) as integer),
                                 cast((select fc_getsession('DB_datausu')) as date),
                                 sHoraAtual,
                                 OLD.z01_ultalt,
                                 OLD.z01_mae,
                                 OLD.z01_pai,
                                 OLD.z01_nomefanta,
                                 OLD.z01_contato,
                                 OLD.z01_sexo,
                                 OLD.z01_nasc,
                                 OLD.z01_fax,
                                 OLD.z01_nomecomple,
                                 OLD.z01_identorgao,
                                 OLD.z01_cnh,
                                 OLD.z01_categoria,
                                 OLD.z01_dtemissao,
                                 OLD.z01_dthabilitacao,
                                 OLD.z01_dtvencimento,
                                 OLD.z01_dtfalecimento,
                                 OLD.z01_escolaridade,
                                 OLD.z01_naturalidade,
                                 OLD.z01_identdtexp,
                                 OLD.z01_trabalha,
                                 OLD.z01_renda,
                                 OLD.z01_localtrabalho,
                                 OLD.z01_pis,
                                 OLD.z01_obs
                                 );


                      -- Verifica se CGM contém alguma matrícula no sistema em que o código do PIS esteja diferente
                      -- do atual cadastrado na tabela CGM, caso encontre algum registros, então é alterado o PIS

                      sSqlMatriculas := ' select rh01_regist
                                            from rhpessoal
                                                 inner join rhpesdoc on rhpesdoc.rh16_regist = rhpessoal.rh01_regist
                                           where rh01_numcgm = '||new.z01_numcgm||'
                                             and trim(coalesce(rhpesdoc.rh16_pis,\'\')) != \''||trim(coalesce(new.z01_pis,''))||'\'';


                      for rMatriculas in execute sSqlMatriculas loop

                        update rhpesdoc
                           set rh16_pis = new.z01_pis
                         where rh16_regist = rMatriculas.rh01_regist;

                      end  loop;

                      RETURN OLD;
                    END IF;
                RETURN NEW;
            END;
        $$
;


SQL
        );
    }

    public function upFcCgmEnderecoIncalt()
    {
        DB::connection()->getPdo()->exec(<<<SQL
    CREATE OR REPLACE FUNCTION public.fc_cgmendereco_incalt()
     RETURNS trigger
     LANGUAGE plpgsql
    AS $$
    declare

        iCodigoEstado       integer default 0;
        iCodigoMunicipio    integer default 0;
        iCodigoBairro       integer default 0;
        iCodigoRua          integer default 0;
        iCodigoBairroRua    integer default 0;
        iCodigoLocal        integer default 0;
        iCodigoEndereco	    integer default 0;
        iCodigoRuasTipo	    integer default 0;
        iCodigoCgm          integer default 0;
        iCodigoCgmEndereco  integer default 0;
        iNumCgmEndereco     integer default 0;

        lTriggerHabilitada  boolean default true;
        lRaise              boolean default false;

    /*
        sZ01_ender	varchar(100) default '';
        sZ01_numero	varchar(8) 	 default '';
        sZ01_compl	varchar(20)  default '';
        sZ01_bairro varchar(40)	 default '';
    */
        sOperacao                text := '';

        rCgm            record;
        rCadEnderParam  record;
        rEndereco       record;


    begin

       lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

       lRaise := true;

       lTriggerHabilitada := ( case when fc_getsession('DB_habilita_trigger_endereco') is null then true else false end );

       if not lTriggerHabilitada then
         return NEW;
       end if;

        sOperacao := upper(TG_OP);
        if (sOperacao = 'INSERT') then

          iCodigoCgm := NEW.z01_numcgm;
        insert into personacgm (p121_cgm,p121_persona) values (NEW.z01_numcgm ,1);
        else

          iCodigoCgm := OLD.z01_numcgm;
        end if;

        /* Verificar se o CGM alterado esta incluído na cgmendereco
             se estiver tem que verificar campo a campo se houve alteração
             se não estiver tem que gerar um endereco novo e fazer a ligação
             da cgmendereco

        */

        select z01_numcgm,
               z01_ender,
               z01_numero::varchar,
               z01_compl,
               z01_bairro,
               z01_munic,
               z01_uf,
               z01_cep,
               z01_endcon,
               z01_numcon,
               z01_comcon,
               z01_baicon,
               z01_muncon,
               z01_ufcon,
               z01_cepcon
          into rCgm
          from cgm
         where z01_numcgm = iCodigoCgm;

        if not found then

            if lRaise then
              raise notice 'Nenhum registro retornado para o CGM {%}',rCgm.z01_numcgm;
            end if;

            return null;

        end if;

        if lRaise then
          raise notice 'Cgm encontrado ';
        end if;

        if (rCgm.z01_ender = '') then

          if lRaise then
            raise notice 'Endereço informado vazio ';
          end if;

          return null;
        end if;

        /* Leitura dos parâmetros do cadastro de endereço cadenderparam */

        select db99_cadenderpais,
               db99_cadenderestado,
               db99_cadendermunicipio,
               db70_descricao,
               db71_descricao,
               db71_sigla,
               db72_descricao
          into rCadEnderParam
          from cadenderparam
               inner join cadenderpais      on cadenderpais.db70_sequencial      = cadenderparam.db99_cadenderpais
               inner join cadenderestado    on cadenderestado.db71_sequencial    = cadenderparam.db99_cadenderestado
               inner join cadendermunicipio on cadendermunicipio.db72_sequencial = cadenderparam.db99_cadendermunicipio;

        if not found then

          if lRaise then
            raise notice 'Parâmetros do endereço não configurados {cadenderparam}';
          end if;

          return null;
        end if;

        if lRaise then
          raise notice 'Tabela de parâmetros ok !';
        end if;

        if (rCgm.z01_ender != '') then
            /* Pesquisa para verificar se existe relação do cgm com o endereco {cgmendereco} */

            select z07_sequencial,
                   z07_endereco
              into iCodigoCgmEndereco,
                   iCodigoEndereco
              from cgmendereco
             where z07_numcgm = iCodigoCgm
               and z07_tipo   = 'P';

              /* Se o cgm não existir na tabela de ligação gerar o endereço e vincular o cidadao ao cgm */
              if not found then

                if lRaise then
                  raise notice 'Cgm não encontrado na cgmendereco ';
                end if;

               /* ---------------------------- Inicio do tratameno do estado do endereço -----------------------------*/
               /* Atribuindo o codigo do estado da tabela de parametros do endereço {cadenderparam} */
                iCodigoEstado := rCadEnderParam.db99_cadenderestado;

               /* Verificar se z01_uf e z01_munic são diferentes de ''
                *  se não for atribuir o estado default para o municipio não informado RS, 0-Não Informado
                */
                if (rCgm.z01_munic = '' or rCgm.z01_uf = '' or rCgm.z01_bairro = '') then

                  select db71_sequencial
                    into iCodigoEstado
                    from cadendermunicipio
                         inner join cadenderestado on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                   where cadenderestado.db71_sigla = rCadEnderParam.db71_sigla;


                   if not found then

                    if lRaise then
                      raise notice 'Falha ao atribuir estado padrão!';
                    end if;
                    return null;
                   end if;

                else /* Aqui pesquisa pela sigla do z01_uf informado no cgm*/
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = trim(rCgm.z01_uf);
                  /* Se não localizar o estado atribuir o estado dos parametros do endereço */
                  if not found then

                    if lRaise then
                      raise notice 'Estado não encontrado pela sigla fornecida atribuido dos Parametros do endereco';
                    end if;

                    iCodigoEstado := rCadEnderParam.db99_cadenderestado;
                  end if;

                end if;/*Fechamento do if do estado*/
               /* ---------------------------- Fim do tratameno do estado do endereço -----------------------------*/
               /* ---------------------------- Inicio do tratameno do estado do Municipio -------------------------*/
                /* Se o z01_munic for igual a vazio */
                if (rCgm.z01_munic = '') then

                  if lRaise then
                    raise notice 'Definido municipio 0-Não Informado para o endereço';
                  end if;

                  iCodigoMunicipio := 0;
                else /* Se o z01_munic diferente de vazio pesquisar se existe senão incluir*/

                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao      = rCgm.z01_munic
                     and db72_cadenderestado = iCodigoEstado;
                  /* Se não encontrou o municipio entao tem que incluir o mesmo */
                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado ! incluindo .....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');

                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                   )
                                           values (iCodigoMunicipio,
                                                   rCgm.z01_munic,
                                                   iCodigoEstado
                                                  );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Municipio ----------------------------*/
               /* ---------------------------- Inicio do tratamento do Bairro ----------------------------*/
                /* Se o z01_bairro for igual a vazio */
                if (rCgm.z01_bairro = '') then

                  if lRaise then
                    raise notice 'Definindo bairro 0-Não Informado para o endereço';
                  end if;

                  iCodigoBairro := 0;

                else /* Se o z01_bairro diferente de vazio pesquisar se existe senão incluir */

                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_bairro
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado ! incluindo .....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                        values (iCodigoBairro,
                                                rCgm.z01_bairro,
                                                iCodigoMunicipio
                                               );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Bairro -------------------------------*/
               /* ---------------------------- Inicio do tratamento da Rua  -------------------------------*/
                /* Se o bairro for igual a vazio */
                if (rCgm.z01_ender = '') then

                  if lRaise then
                    raise notice 'Endereco não informado -- Inclusão Cancelada';
                  end if;

                  return null;
                else /* Se o z01_ender for diferente de vazio pesquisar se existe senão incluir */

                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_ender
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrado ! incluindo ..... ';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                     values (iCodigoRua,
                                             rCgm.z01_ender,
                                             iCodigoMunicipio
                                            );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento da Rua -------------------------------*/
               /* ---------------------------- Inicio do tratamento da RuasTipo -----------------------*/
                perform db85_sequencial
                  from cadenderruaruastipo
                 where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na cadenderruaruastipo';
                  end if;

                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (nextval('cadenderruaruastipo_db85_sequencial_seq'),
                                                   iCodigoRua,
                                                   3
                                                  );
                end if;

               /* ---------------------------- Fim do tratamento da RuasTipo --------------------------*/
               /* ---------------------------- Inicio do tratamento do Vinculo da Rua com Bairro ------*/
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua    = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                 if not found then

                   if lRaise then
                     raise notice 'Incluindo na cadenderbairrocadenderrua';
                   end if;

                   iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                   insert into cadenderbairrocadenderrua (db87_sequencial,
                                                          db87_cadenderrua,
                                                          db87_cadenderbairro
                                                         )
                                                  values (iCodigoBairroRua,
                                                          iCodigoRua,
                                                          iCodigoBairro
                                                         );

                 end if;
               /* ---------------------------- Fim do tratamento do Vinculo da Rua com Bairro ---------*/
               /* ---------------------------- Inicio do tratamento da Local --------------------------*/
                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua;

                 if not found then

                    if lRaise then
                      raise notice 'Icluindo na cadenderlocal';
                    end if;

                    iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                    insert into cadenderlocal (db75_sequencial,
                                               db75_cadenderbairrocadenderrua,
                                               db75_numero
                                              )
                                       values (iCodigoLocal,
                                               iCodigoBairroRua,
                                               rCgm.z01_numero
                                              );

                 end if;
               /* ---------------------------- Fim do tratamento da Local -----------------------------*/
               /* ---------------------------- Inicio do tratamento da Endereco -----------------------*/
                 iCodigoEndereco := nextval('endereco_db76_sequencial_seq');

                 if lRaise then
                   raise notice 'Inserindo na Endereco {%}',iCodigoEndereco;
                 end if;

                 insert into endereco (db76_sequencial,
                                       db76_cadenderlocal,
                                       db76_complemento,
                                       db76_cep
                                      )
                               values (iCodigoEndereco,
                                       iCodigoLocal,
                                       rCgm.z01_compl,
                                       rCgm.z01_cep
                                      );
               /* ---------------------------- Fim do tratamento da Endereco --------------------------*/
               /* ---------------------------- Inicio do tratamento da cgmendereco --------------------*/
                if lRaise then
                  raise notice 'Inserindo na cgmendereco';
                end if;

                insert into cgmendereco (z07_sequencial,
                                         z07_endereco,
                                         z07_numcgm,
                                         z07_tipo
                                        )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                         iCodigoEndereco,
                                         iCodigoCgm,
                                         'P'
                                        );
               /* ---------------------------- Fim do tratamento da cgmendereco -----------------------*/


              else  /* aqui se ja exisitir na cgmendereco */

               select db74_sequencial,
                      db74_descricao,
                      db75_numero,
                      db73_sequencial,
                      db73_descricao,
                      db72_sequencial,
                      db72_descricao,
                      db71_sequencial,
                      db71_descricao,
                      db71_sigla,
                      db76_sequencial,
                      db76_cep,
                      db76_pontoref,
                      db76_condominio,
                      db76_loteamento,
                      db76_caixapostal,
                      db76_complemento
                 into rEndereco
                 from endereco
                      inner join cadenderlocal             on cadenderlocal.db75_sequencial = endereco.db76_cadenderlocal
                      inner join cadenderbairrocadenderrua on cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua
                      inner join cadenderrua               on cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua
                      inner join cadenderbairro            on cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro
                      inner join cadendermunicipio         on cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio
                      inner join cadenderestado            on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                where db76_sequencial = iCodigoEndereco;

                if not found then

                  if lRaise then
                    raise notice 'Falha ao ler endereco completo para o cgm{%} e  endereco{%}',iCodigoCgm,iCodigoEndereco;
                  end if;

                  return null;
                end if;

                /* Verificar se houve mudança no estado */
                if (rEndereco.db71_sigla != rCgm.z01_uf) then
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCgm.z01_uf;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado para o cgm';
                    end if;

                    return null;
                  end if;

                else
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rEndereco.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado do endereco';
                    end if;

                    return null;
                  end if;

                end if;/*Fim do if do estado*/
                /* Fim da Verificação da mudança no estado*/

                /* Inicio da verificação de mudança no municipio */

                /* se z01_munic vazio atribui 0-Não Informaado e busca codigo do estado default 'RS' */
                if (rCgm.z01_munic = '') then
                  iCodigoMunicipio := 0;
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCadEnderParam.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler codigo do estado para municipio NI';
                    end if;

                    return null;
                  end if;
                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao = rCgm.z01_munic;

                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado para o estado cadastrado incluindo....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');
                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                  )
                                          values  (iCodigoMunicipio,
                                                   rCgm.z01_munic,
                                                   iCodigoEstado
                                                  );

                  end if;

                end if;/*Fim do if do municipio*/

                /* Fim da verificação de mudança no municipio */

                /* Inicio da verificação de mudança no bairro */

                /* se z01_bairro vazio atribui 0-Não Informado */
                if (rCgm.z01_bairro = '') then

                  iCodigoBairro := 0;

                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_bairro
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                       values  (iCodigoBairro,
                                                rCgm.z01_bairro,
                                                iCodigoMunicipio
                                               );

                  end if;

                end if;/*Fim do if do bairro*/


                /* Fim da verificação de mudança no bairro */

                /* Inicio da verificação de mudança na Rua */
                if (rCgm.z01_ender ='') then

                  if lRaise then
                    raise notice 'Campo z01_ender vazio';
                  end if;

                  return null;
                else
                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_ender
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrada para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                    values  (iCodigoRua,
                                             rCgm.z01_ender,
                                             iCodigoMunicipio
                                            );

                  end if;

                end if;/* fim do if da Rua*/

                /* Fim da verificação de mudança na Rua */

                /* Inicio da verificação de mudança na RuasTipo */
                perform db85_sequencial
                   from cadenderruaruastipo
                  where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na ruastipo';
                  end if;

                  iCodigoRuasTipo := nextval('cadenderruaruastipo_db85_sequencial_seq');
                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (iCodigoRuasTipo,
                                                   iCodigoRua,
                                                   3
                                                  );

                end if;

                /* Fim da verificação de mudança na RuasTipo */
                /* Inicio da verificação de mudança na BairroRua */
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na BairroRua';
                  end if;

                  iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                  insert into cadenderbairrocadenderrua (db87_sequencial,
                                                         db87_cadenderrua,
                                                         db87_cadenderbairro
                                                        )
                                                 values (iCodigoBairroRua,
                                                         iCodigoRua,
                                                         iCodigoBairro
                                                        );

                end if;
                /* Fim da verificação de mudança na BairroRua */

                /* Inicio da verificação de mudança na Local */

                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua
                    and db75_numero = cast(rCgm.z01_numero as text);

                if not found then

                  iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                  insert into cadenderlocal (db75_sequencial,
                                             db75_cadenderbairrocadenderrua,
                                             db75_numero
                                            )
                                     values (iCodigoLocal,
                                             iCodigoBairroRua,
                                             rCgm.z01_numero
                                            );

                end if;
                /* Fim da verificação de mudança na Local */

                /* Inicio da verificação de mudança na Endereco */
                select count(*)
                  into iNumCgmEndereco
                  from cgmendereco
                 where z07_endereco = iCodigoEndereco
                having count(*) > 1;

                /*delete na cgmendereco*/
                delete from cgmendereco
                        where z07_numcgm = iCodigoCgm
                          and z07_tipo   = 'P';


                if (iNumCgmEndereco > 0 and (rCgm.z01_compl != rEndereco.db76_complemento)) then

                  if lRaise then
                    raise notice 'Existe mais de um cgm no mesmo endereco inserindo endereco novo';
                  end if;

                  iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                  insert into endereco (db76_sequencial,
                                        db76_cadenderlocal,
                                        db76_complemento,
                                        db76_caixapostal,
                                        db76_loteamento,
                                        db76_condominio,
                                        db76_pontoref,
                                        db76_cep
                                       )
                                values (iCodigoEndereco,
                                        iCodigoLocal,
                                        rCgm.z01_compl,
                                        rEndereco.db76_caixapostal,
                                        rEndereco.db76_loteamento,
                                        rEndereco.db76_condominio,
                                        rEndereco.db76_pontoref,
                                        rCgm.z01_cep
                                       );



                else

                  perform db76_sequencial
                     from endereco
                    where db76_sequencial    = iCodigoEndereco
                      and db76_cadenderlocal = iCodigoLocal;

                  if not found then
                    iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                    insert into endereco (db76_sequencial,
                                          db76_cadenderlocal,
                                          db76_complemento,
                                          db76_caixapostal,
                                          db76_loteamento,
                                          db76_condominio,
                                          db76_pontoref,
                                          db76_cep
                                         )
                                  values (iCodigoEndereco,
                                          iCodigoLocal,
                                          rCgm.z01_compl,
                                          rEndereco.db76_caixapostal,
                                          rEndereco.db76_loteamento,
                                          rEndereco.db76_condominio,
                                          rEndereco.db76_pontoref,
                                          rCgm.z01_cep
                                         );


                  else
                      update endereco set db76_cadenderlocal = iCodigoLocal,
                                          db76_complemento   = rCgm.z01_compl,
                                          db76_cep           = rCgm.z01_cep
                                    where db76_sequencial = rEndereco.db76_sequencial;
                  end if;
                end if;

                /* Inserindo na cgmendereco */
                insert into cgmendereco(z07_sequencial,
                                          z07_endereco,
                                          z07_numcgm,
                                          z07_tipo
                                         )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                          iCodigoEndereco,
                                          iCodigoCgm,
                                          'P'
                                         );


                /* Fim da verificação de mudança na Endereco */
                if lRaise then
                  raise notice 'Fim da atualização do cgm {%} endereco {%}',iCodigoCgm,iCodigoEndereco;
                end if;

              end if;/* Finaliza bloco de quando não existe ligação com a tabela cgmendereco para tipo 'P' */
        end if; /* Fecha o if do endereço primario tipo 'P' */
    /*----------------------------------------  Aqui inicia no endereço secundario  ----------------------------------*/
        if (rCgm.z01_endcon != '') then
            /* Pesquisa para verificar se existe relação do cgm com o endereco {cgmendereco} */
            iCodigoEstado      := 0;
            iCodigoMunicipio   := 0;
            iCodigoBairro      := 0;
            iCodigoRua         := 0;
            iCodigoBairroRua   := 0;
            iCodigoLocal       := 0;
            iCodigoEndereco    := 0;
            iCodigoRuasTipo    := 0;
            iCodigoCgmEndereco := 0;
            iNumCgmEndereco    := 0;

            select z07_sequencial,
                   z07_endereco
              into iCodigoCgmEndereco,
                   iCodigoEndereco
              from cgmendereco
             where z07_numcgm = iCodigoCgm
               and z07_tipo   = 'S';

              /* Se o cgm não existir na tabela de ligação gerar o endereço e vincular o cidadao ao cgm */
              if not found then

                if lRaise then
                  raise notice 'Cgm não encontrado na cgmendereco ';
                end if;

               /* ---------------------------- Inicio do tratameno do estado do endereço -----------------------------*/
               /* Atribuindo o codigo do estado da tabela de parametros do endereço {cadenderparam} */
                iCodigoEstado := rCadEnderParam.db99_cadenderestado;

               /* Verificar se z01_uf e z01_munic são diferentes de ''
                *  se não for atribuir o estado default para o municipio não informado RS, 0-Não Informado
                */
                if (rCgm.z01_muncon = '' or rCgm.z01_ufcon = '' or rCgm.z01_baicon = '') then

                  select db71_sequencial
                    into iCodigoEstado
                    from cadendermunicipio
                         inner join cadenderestado on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                   where cadenderestado.db71_sigla = rCadEnderParam.db71_sigla;

                   if not found then

                    if lRaise then
                      raise notice 'Falha ao atribuir estado padrão!';
                    end if;

                    return null;
                   end if;

                else /* Aqui pesquisa pela sigla do z01_uf informado no cgm*/
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = trim(rCgm.z01_ufcon);
                  /* Se não localizar o estado atribuir o estado dos parametros do endereço */
                  if not found then

                    if lRaise then
                      raise notice 'Estado não encontrado pela sigla fornecida atribuido dos Parametros do endereco';
                    end if;

                    iCodigoEstado := rCadEnderParam.db99_cadenderestado;
                  end if;

                end if;/*Fechamento do if do estado*/
               /* ---------------------------- Fim do tratameno do estado do endereço -----------------------------*/
               /* ---------------------------- Inicio do tratameno do estado do Municipio -------------------------*/
                /* Se o z01_munic for igual a vazio */
                if (rCgm.z01_muncon = '') then

                  if lRaise then
                    raise notice 'Definido municipio 0-Não Informado para o endereço';
                  end if;

                  iCodigoMunicipio := 0;
                else /* Se o z01_munic diferente de vazio pesquisar se existe senão incluir*/

                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao      = rCgm.z01_muncon
                     and db72_cadenderestado = iCodigoEstado;
                  /* Se não encontrou o municipio entao tem que incluir o mesmo */
                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado ! incluindo .....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');

                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                   )
                                           values (iCodigoMunicipio,
                                                   rCgm.z01_muncon,
                                                   iCodigoEstado
                                                  );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Municipio ----------------------------*/
               /* ---------------------------- Inicio do tratamento do Bairro ----------------------------*/
                /* Se o z01_bairro for igual a vazio */
                if (rCgm.z01_baicon = '') then

                  if lRaise then
                    raise notice 'Definindo bairro 0-Não Informado para o endereço';
                  end if;

                  iCodigoBairro := 0;

                else /* Se o z01_bairro diferente de vazio pesquisar se existe senão incluir */

                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_baicon
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado ! incluindo .....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                        values (iCodigoBairro,
                                                rCgm.z01_baicon,
                                                iCodigoMunicipio
                                               );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Bairro -------------------------------*/
               /* ---------------------------- Inicio do tratamento da Rua  -------------------------------*/
                /* Se o bairro for igual a vazio */
                if (rCgm.z01_endcon = '') then

                  if lRaise then
                    raise notice 'Endereco não informado -- Inclusão Cancelada';
                  end if;

                  return null;
                else /* Se o z01_ender for diferente de vazio pesquisar se existe senão incluir */

                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_endcon
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrado ! incluindo ..... ';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                     values (iCodigoRua,
                                             rCgm.z01_endcon,
                                             iCodigoMunicipio
                                            );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento da Rua -------------------------------*/
               /* ---------------------------- Inicio do tratamento da RuasTipo -----------------------*/
                perform db85_sequencial
                  from cadenderruaruastipo
                 where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na cadenderruaruastipo';
                  end if;

                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (nextval('cadenderruaruastipo_db85_sequencial_seq'),
                                                   iCodigoRua,
                                                   3
                                                  );
                end if;

               /* ---------------------------- Fim do tratamento da RuasTipo --------------------------*/
               /* ---------------------------- Inicio do tratamento do Vinculo da Rua com Bairro ------*/
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua    = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                 if not found then

                   if lRaise then
                     raise notice 'Incluindo na cadenderbairrocadenderrua';
                   end if;

                   iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                   insert into cadenderbairrocadenderrua (db87_sequencial,
                                                          db87_cadenderrua,
                                                          db87_cadenderbairro
                                                         )
                                                  values (iCodigoBairroRua,
                                                          iCodigoRua,
                                                          iCodigoBairro
                                                         );

                 end if;
               /* ---------------------------- Fim do tratamento do Vinculo da Rua com Bairro ---------*/
               /* ---------------------------- Inicio do tratamento da Local --------------------------*/
                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua;

                 if not found then

                    if lRaise then
                      raise notice 'Icluindo na cadenderlocal';
                    end if;

                    iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                    insert into cadenderlocal (db75_sequencial,
                                               db75_cadenderbairrocadenderrua,
                                               db75_numero
                                              )
                                       values (iCodigoLocal,
                                               iCodigoBairroRua,
                                               rCgm.z01_numcon
                                              );

                 end if;
               /* ---------------------------- Fim do tratamento da Local -----------------------------*/
               /* ---------------------------- Inicio do tratamento da Endereco -----------------------*/
                 iCodigoEndereco := nextval('endereco_db76_sequencial_seq');

                 if lRaise then
                   raise notice 'Inserindo na Endereco {%}',iCodigoEndereco;
                 end if;

                 insert into endereco (db76_sequencial,
                                       db76_cadenderlocal,
                                       db76_complemento,
                                       db76_cep
                                      )
                               values (iCodigoEndereco,
                                       iCodigoLocal,
                                       rCgm.z01_comcon,
                                       rCgm.z01_cepcon
                                      );
               /* ---------------------------- Fim do tratamento da Endereco --------------------------*/
               /* ---------------------------- Inicio do tratamento da cgmendereco --------------------*/

                if lRaise then
                  raise notice 'Inserindo na cgmendereco';
                end if;

                insert into cgmendereco (z07_sequencial,
                                         z07_endereco,
                                         z07_numcgm,
                                         z07_tipo
                                        )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                         iCodigoEndereco,
                                         iCodigoCgm,
                                         'S'
                                        );
               /* ---------------------------- Fim do tratamento da cgmendereco -----------------------*/


              else  /* aqui se ja exisitir na cgmendereco */

               select db74_sequencial,
                      db74_descricao,
                      db75_numero,
                      db73_sequencial,
                      db73_descricao,
                      db72_sequencial,
                      db72_descricao,
                      db71_sequencial,
                      db71_descricao,
                      db71_sigla,
                      db76_sequencial,
                      db76_cep,
                      db76_pontoref,
                      db76_condominio,
                      db76_loteamento,
                      db76_caixapostal,
                      db76_complemento
                 into rEndereco
                 from endereco
                      inner join cadenderlocal             on cadenderlocal.db75_sequencial = endereco.db76_cadenderlocal
                      inner join cadenderbairrocadenderrua on cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua
                      inner join cadenderrua               on cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua
                      inner join cadenderbairro            on cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro
                      inner join cadendermunicipio         on cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio
                      inner join cadenderestado            on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                where db76_sequencial = iCodigoEndereco;

                if not found then

                  if lRaise then
                    raise notice 'Falha ao ler endereco completo para o cgm{%} e  endereco{%}',iCodigoCgm,iCodigoEndereco;
                  end if;

                  return null;
                end if;

                /* Verificar se houve mudança no estado */
                if (rEndereco.db71_sigla != rCgm.z01_ufcon) then
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCgm.z01_ufcon;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado para o cgm';
                    end if;

                    return null;
                  end if;

                else
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rEndereco.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado do endereco';
                    end if;

                    return null;
                  end if;

                end if;/*Fim do if do estado*/
                /* Fim da Verificação da mudança no estado*/

                /* Inicio da verificação de mudança no municipio */

                /* se z01_munic vazio atribui 0-Não Informaado e busca codigo do estado default 'RS' */
                if (rCgm.z01_muncon = '') then
                  iCodigoMunicipio := 0;
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCadEnderParam.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler codigo do estado para municipio NI';
                    end if;

                    return null;
                  end if;
                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao = rCgm.z01_muncon;

                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado para o estado cadastrado incluindo....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');
                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                  )
                                          values  (iCodigoMunicipio,
                                                   rCgm.z01_muncon,
                                                   iCodigoEstado
                                                  );

                  end if;

                end if;/*Fim do if do municipio*/

                /* Fim da verificação de mudança no municipio */

                /* Inicio da verificação de mudança no bairro */

                /* se z01_bairro vazio atribui 0-Não Informado */
                if (rCgm.z01_baicon = '') then

                  iCodigoBairro := 0;

                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_baicon
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                       values  (iCodigoBairro,
                                                rCgm.z01_baicon,
                                                iCodigoMunicipio
                                               );

                  end if;

                end if;/*Fim do if do bairro*/


                /* Fim da verificação de mudança no bairro */

                /* Inicio da verificação de mudança na Rua */
                if (rCgm.z01_endcon ='') then

                  if lRaise then
                    raise notice 'Campo z01_ender vazio';
                  end if;

                  return null;
                else
                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_endcon
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrada para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                    values  (iCodigoRua,
                                             rCgm.z01_endcon,
                                             iCodigoMunicipio
                                            );

                  end if;

                end if;/* fim do if da Rua*/

                /* Fim da verificação de mudança na Rua */

                /* Inicio da verificação de mudança na RuasTipo */
                perform db85_sequencial
                   from cadenderruaruastipo
                  where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na ruastipo';
                  end if;

                  iCodigoRuasTipo := nextval('cadenderruaruastipo_db85_sequencial_seq');
                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (iCodigoRuasTipo,
                                                   iCodigoRua,
                                                   3
                                                  );

                end if;

                /* Fim da verificação de mudança na RuasTipo */
                /* Inicio da verificação de mudança na BairroRua */
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua    = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na BairroRua';
                  end if;


                  iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                  insert into cadenderbairrocadenderrua (db87_sequencial,
                                                         db87_cadenderrua,
                                                         db87_cadenderbairro
                                                        )
                                                 values (iCodigoBairroRua,
                                                         iCodigoRua,
                                                         iCodigoBairro::integer
                                                        );

                end if;
                /* Fim da verificação de mudança na BairroRua */

                /* Inicio da verificação de mudança na Local */

                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua
                    and db75_numero::integer = rCgm.z01_numcon;

                if not found then

                  iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                  insert into cadenderlocal (db75_sequencial,
                                             db75_cadenderbairrocadenderrua,
                                             db75_numero
                                            )
                                     values (iCodigoLocal,
                                             iCodigoBairroRua,
                                             rCgm.z01_numcon
                                            );

                end if;
                /* Fim da verificação de mudança na Local */

                /* Inicio da verificação de mudança na Endereco */
                select count(*)
                  into iNumCgmEndereco
                  from cgmendereco
                 where z07_endereco = iCodigoEndereco
                having count(*) > 1;

                /*delete na cgmendereco*/
                delete from cgmendereco
                        where z07_numcgm = iCodigoCgm
                          and z07_tipo   = 'S';


                if (iNumCgmEndereco > 0 and (rCgm.z01_comcon != rEndereco.db76_complemento)) then

                  if lRaise then
                    raise notice 'Existe mais de um cgm no mesmo endereco inserindo endereco novo';
                  end if;

                  iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                  insert into endereco (db76_sequencial,
                                        db76_cadenderlocal,
                                        db76_complemento,
                                        db76_caixapostal,
                                        db76_loteamento,
                                        db76_condominio,
                                        db76_pontoref,
                                        db76_cep
                                       )
                                values (iCodigoEndereco,
                                        iCodigoLocal,
                                        rCgm.z01_comcon,
                                        rEndereco.db76_caixapostal,
                                        rEndereco.db76_loteamento,
                                        rEndereco.db76_condominio,
                                        rEndereco.db76_pontoref,
                                        rCgm.z01_cepcon
                                       );



                else

                  perform db76_sequencial
                     from endereco
                    where db76_sequencial    = iCodigoEndereco
                      and db76_cadenderlocal = iCodigoLocal;

                  if not found then
                    iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                    insert into endereco (db76_sequencial,
                                          db76_cadenderlocal,
                                          db76_complemento,
                                          db76_caixapostal,
                                          db76_loteamento,
                                          db76_condominio,
                                          db76_pontoref,
                                          db76_cep
                                         )
                                  values (iCodigoEndereco,
                                          iCodigoLocal,
                                          rCgm.z01_comcon,
                                          rEndereco.db76_caixapostal,
                                          rEndereco.db76_loteamento,
                                          rEndereco.db76_condominio,
                                          rEndereco.db76_pontoref,
                                          rCgm.z01_cepcon
                                         );


                  else
                      update endereco set db76_cadenderlocal = iCodigoLocal,
                                          db76_complemento   = rCgm.z01_comcon,
                                          db76_cep           = rCgm.z01_cepcon
                                    where db76_sequencial = rEndereco.db76_sequencial;
                  end if;
                end if;

                /* Inserindo na cgmendereco */
                insert into cgmendereco(z07_sequencial,
                                          z07_endereco,
                                          z07_numcgm,
                                          z07_tipo
                                         )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                          iCodigoEndereco,
                                          iCodigoCgm,
                                          'S'
                                         );


                /* Fim da verificação de mudança na Endereco */
                if lRaise then
                  raise notice 'Fim da atualização do cgm {%} endereco {%}',iCodigoCgm,iCodigoEndereco;
                end if;
              end if;/* Finaliza bloco de quando não existe ligação com a tabela cgmendereco para tipo 'P' */
        end if; /* Fecha o if do endereço primario tipo 'P' */


        return null;
    end;

    $$;
SQL
        );
    }

    public function downFcCgmEnderecoIncalt()
    {

        DB::connection()->getPdo()->exec(<<<SQL
    CREATE OR REPLACE FUNCTION public.fc_cgmendereco_incalt()
     RETURNS trigger
     LANGUAGE plpgsql
    AS $$
    declare

        iCodigoEstado       integer default 0;
        iCodigoMunicipio    integer default 0;
        iCodigoBairro       integer default 0;
        iCodigoRua          integer default 0;
        iCodigoBairroRua    integer default 0;
        iCodigoLocal        integer default 0;
        iCodigoEndereco	    integer default 0;
        iCodigoRuasTipo	    integer default 0;
        iCodigoCgm          integer default 0;
        iCodigoCgmEndereco  integer default 0;
        iNumCgmEndereco     integer default 0;

        lTriggerHabilitada  boolean default true;
        lRaise              boolean default false;

    /*
        sZ01_ender	varchar(100) default '';
        sZ01_numero	varchar(8) 	 default '';
        sZ01_compl	varchar(20)  default '';
        sZ01_bairro varchar(40)	 default '';
    */
        sOperacao                text := '';

        rCgm            record;
        rCadEnderParam  record;
        rEndereco       record;


    begin

       lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

       lRaise := true;

       lTriggerHabilitada := ( case when fc_getsession('DB_habilita_trigger_endereco') is null then true else false end );

       if not lTriggerHabilitada then
         return NEW;
       end if;

        sOperacao := upper(TG_OP);
        if (sOperacao = 'INSERT') then

          iCodigoCgm := NEW.z01_numcgm;
        else

          iCodigoCgm := OLD.z01_numcgm;
        end if;

        /* Verificar se o CGM alterado esta incluído na cgmendereco
             se estiver tem que verificar campo a campo se houve alteração
             se não estiver tem que gerar um endereco novo e fazer a ligação
             da cgmendereco

        */

        select z01_numcgm,
               z01_ender,
               z01_numero::varchar,
               z01_compl,
               z01_bairro,
               z01_munic,
               z01_uf,
               z01_cep,
               z01_endcon,
               z01_numcon,
               z01_comcon,
               z01_baicon,
               z01_muncon,
               z01_ufcon,
               z01_cepcon
          into rCgm
          from cgm
         where z01_numcgm = iCodigoCgm;

        if not found then

            if lRaise then
              raise notice 'Nenhum registro retornado para o CGM {%}',rCgm.z01_numcgm;
            end if;

            return null;

        end if;

        if lRaise then
          raise notice 'Cgm encontrado ';
        end if;

        if (rCgm.z01_ender = '') then

          if lRaise then
            raise notice 'Endereço informado vazio ';
          end if;

          return null;
        end if;

        /* Leitura dos parâmetros do cadastro de endereço cadenderparam */

        select db99_cadenderpais,
               db99_cadenderestado,
               db99_cadendermunicipio,
               db70_descricao,
               db71_descricao,
               db71_sigla,
               db72_descricao
          into rCadEnderParam
          from cadenderparam
               inner join cadenderpais      on cadenderpais.db70_sequencial      = cadenderparam.db99_cadenderpais
               inner join cadenderestado    on cadenderestado.db71_sequencial    = cadenderparam.db99_cadenderestado
               inner join cadendermunicipio on cadendermunicipio.db72_sequencial = cadenderparam.db99_cadendermunicipio;

        if not found then

          if lRaise then
            raise notice 'Parâmetros do endereço não configurados {cadenderparam}';
          end if;

          return null;
        end if;

        if lRaise then
          raise notice 'Tabela de parâmetros ok !';
        end if;

        if (rCgm.z01_ender != '') then
            /* Pesquisa para verificar se existe relação do cgm com o endereco {cgmendereco} */

            select z07_sequencial,
                   z07_endereco
              into iCodigoCgmEndereco,
                   iCodigoEndereco
              from cgmendereco
             where z07_numcgm = iCodigoCgm
               and z07_tipo   = 'P';

              /* Se o cgm não existir na tabela de ligação gerar o endereço e vincular o cidadao ao cgm */
              if not found then

                if lRaise then
                  raise notice 'Cgm não encontrado na cgmendereco ';
                end if;

               /* ---------------------------- Inicio do tratameno do estado do endereço -----------------------------*/
               /* Atribuindo o codigo do estado da tabela de parametros do endereço {cadenderparam} */
                iCodigoEstado := rCadEnderParam.db99_cadenderestado;

               /* Verificar se z01_uf e z01_munic são diferentes de ''
                *  se não for atribuir o estado default para o municipio não informado RS, 0-Não Informado
                */
                if (rCgm.z01_munic = '' or rCgm.z01_uf = '' or rCgm.z01_bairro = '') then

                  select db71_sequencial
                    into iCodigoEstado
                    from cadendermunicipio
                         inner join cadenderestado on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                   where cadenderestado.db71_sigla = rCadEnderParam.db71_sigla;


                   if not found then

                    if lRaise then
                      raise notice 'Falha ao atribuir estado padrão!';
                    end if;
                    return null;
                   end if;

                else /* Aqui pesquisa pela sigla do z01_uf informado no cgm*/
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = trim(rCgm.z01_uf);
                  /* Se não localizar o estado atribuir o estado dos parametros do endereço */
                  if not found then

                    if lRaise then
                      raise notice 'Estado não encontrado pela sigla fornecida atribuido dos Parametros do endereco';
                    end if;

                    iCodigoEstado := rCadEnderParam.db99_cadenderestado;
                  end if;

                end if;/*Fechamento do if do estado*/
               /* ---------------------------- Fim do tratameno do estado do endereço -----------------------------*/
               /* ---------------------------- Inicio do tratameno do estado do Municipio -------------------------*/
                /* Se o z01_munic for igual a vazio */
                if (rCgm.z01_munic = '') then

                  if lRaise then
                    raise notice 'Definido municipio 0-Não Informado para o endereço';
                  end if;

                  iCodigoMunicipio := 0;
                else /* Se o z01_munic diferente de vazio pesquisar se existe senão incluir*/

                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao      = rCgm.z01_munic
                     and db72_cadenderestado = iCodigoEstado;
                  /* Se não encontrou o municipio entao tem que incluir o mesmo */
                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado ! incluindo .....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');

                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                   )
                                           values (iCodigoMunicipio,
                                                   rCgm.z01_munic,
                                                   iCodigoEstado
                                                  );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Municipio ----------------------------*/
               /* ---------------------------- Inicio do tratamento do Bairro ----------------------------*/
                /* Se o z01_bairro for igual a vazio */
                if (rCgm.z01_bairro = '') then

                  if lRaise then
                    raise notice 'Definindo bairro 0-Não Informado para o endereço';
                  end if;

                  iCodigoBairro := 0;

                else /* Se o z01_bairro diferente de vazio pesquisar se existe senão incluir */

                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_bairro
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado ! incluindo .....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                        values (iCodigoBairro,
                                                rCgm.z01_bairro,
                                                iCodigoMunicipio
                                               );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Bairro -------------------------------*/
               /* ---------------------------- Inicio do tratamento da Rua  -------------------------------*/
                /* Se o bairro for igual a vazio */
                if (rCgm.z01_ender = '') then

                  if lRaise then
                    raise notice 'Endereco não informado -- Inclusão Cancelada';
                  end if;

                  return null;
                else /* Se o z01_ender for diferente de vazio pesquisar se existe senão incluir */

                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_ender
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrado ! incluindo ..... ';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                     values (iCodigoRua,
                                             rCgm.z01_ender,
                                             iCodigoMunicipio
                                            );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento da Rua -------------------------------*/
               /* ---------------------------- Inicio do tratamento da RuasTipo -----------------------*/
                perform db85_sequencial
                  from cadenderruaruastipo
                 where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na cadenderruaruastipo';
                  end if;

                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (nextval('cadenderruaruastipo_db85_sequencial_seq'),
                                                   iCodigoRua,
                                                   3
                                                  );
                end if;

               /* ---------------------------- Fim do tratamento da RuasTipo --------------------------*/
               /* ---------------------------- Inicio do tratamento do Vinculo da Rua com Bairro ------*/
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua    = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                 if not found then

                   if lRaise then
                     raise notice 'Incluindo na cadenderbairrocadenderrua';
                   end if;

                   iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                   insert into cadenderbairrocadenderrua (db87_sequencial,
                                                          db87_cadenderrua,
                                                          db87_cadenderbairro
                                                         )
                                                  values (iCodigoBairroRua,
                                                          iCodigoRua,
                                                          iCodigoBairro
                                                         );

                 end if;
               /* ---------------------------- Fim do tratamento do Vinculo da Rua com Bairro ---------*/
               /* ---------------------------- Inicio do tratamento da Local --------------------------*/
                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua;

                 if not found then

                    if lRaise then
                      raise notice 'Icluindo na cadenderlocal';
                    end if;

                    iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                    insert into cadenderlocal (db75_sequencial,
                                               db75_cadenderbairrocadenderrua,
                                               db75_numero
                                              )
                                       values (iCodigoLocal,
                                               iCodigoBairroRua,
                                               rCgm.z01_numero
                                              );

                 end if;
               /* ---------------------------- Fim do tratamento da Local -----------------------------*/
               /* ---------------------------- Inicio do tratamento da Endereco -----------------------*/
                 iCodigoEndereco := nextval('endereco_db76_sequencial_seq');

                 if lRaise then
                   raise notice 'Inserindo na Endereco {%}',iCodigoEndereco;
                 end if;

                 insert into endereco (db76_sequencial,
                                       db76_cadenderlocal,
                                       db76_complemento,
                                       db76_cep
                                      )
                               values (iCodigoEndereco,
                                       iCodigoLocal,
                                       rCgm.z01_compl,
                                       rCgm.z01_cep
                                      );
               /* ---------------------------- Fim do tratamento da Endereco --------------------------*/
               /* ---------------------------- Inicio do tratamento da cgmendereco --------------------*/
                if lRaise then
                  raise notice 'Inserindo na cgmendereco';
                end if;

                insert into cgmendereco (z07_sequencial,
                                         z07_endereco,
                                         z07_numcgm,
                                         z07_tipo
                                        )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                         iCodigoEndereco,
                                         iCodigoCgm,
                                         'P'
                                        );
               /* ---------------------------- Fim do tratamento da cgmendereco -----------------------*/


              else  /* aqui se ja exisitir na cgmendereco */

               select db74_sequencial,
                      db74_descricao,
                      db75_numero,
                      db73_sequencial,
                      db73_descricao,
                      db72_sequencial,
                      db72_descricao,
                      db71_sequencial,
                      db71_descricao,
                      db71_sigla,
                      db76_sequencial,
                      db76_cep,
                      db76_pontoref,
                      db76_condominio,
                      db76_loteamento,
                      db76_caixapostal,
                      db76_complemento
                 into rEndereco
                 from endereco
                      inner join cadenderlocal             on cadenderlocal.db75_sequencial = endereco.db76_cadenderlocal
                      inner join cadenderbairrocadenderrua on cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua
                      inner join cadenderrua               on cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua
                      inner join cadenderbairro            on cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro
                      inner join cadendermunicipio         on cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio
                      inner join cadenderestado            on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                where db76_sequencial = iCodigoEndereco;

                if not found then

                  if lRaise then
                    raise notice 'Falha ao ler endereco completo para o cgm{%} e  endereco{%}',iCodigoCgm,iCodigoEndereco;
                  end if;

                  return null;
                end if;

                /* Verificar se houve mudança no estado */
                if (rEndereco.db71_sigla != rCgm.z01_uf) then
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCgm.z01_uf;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado para o cgm';
                    end if;

                    return null;
                  end if;

                else
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rEndereco.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado do endereco';
                    end if;

                    return null;
                  end if;

                end if;/*Fim do if do estado*/
                /* Fim da Verificação da mudança no estado*/

                /* Inicio da verificação de mudança no municipio */

                /* se z01_munic vazio atribui 0-Não Informaado e busca codigo do estado default 'RS' */
                if (rCgm.z01_munic = '') then
                  iCodigoMunicipio := 0;
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCadEnderParam.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler codigo do estado para municipio NI';
                    end if;

                    return null;
                  end if;
                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao = rCgm.z01_munic;

                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado para o estado cadastrado incluindo....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');
                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                  )
                                          values  (iCodigoMunicipio,
                                                   rCgm.z01_munic,
                                                   iCodigoEstado
                                                  );

                  end if;

                end if;/*Fim do if do municipio*/

                /* Fim da verificação de mudança no municipio */

                /* Inicio da verificação de mudança no bairro */

                /* se z01_bairro vazio atribui 0-Não Informado */
                if (rCgm.z01_bairro = '') then

                  iCodigoBairro := 0;

                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_bairro
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                       values  (iCodigoBairro,
                                                rCgm.z01_bairro,
                                                iCodigoMunicipio
                                               );

                  end if;

                end if;/*Fim do if do bairro*/


                /* Fim da verificação de mudança no bairro */

                /* Inicio da verificação de mudança na Rua */
                if (rCgm.z01_ender ='') then

                  if lRaise then
                    raise notice 'Campo z01_ender vazio';
                  end if;

                  return null;
                else
                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_ender
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrada para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                    values  (iCodigoRua,
                                             rCgm.z01_ender,
                                             iCodigoMunicipio
                                            );

                  end if;

                end if;/* fim do if da Rua*/

                /* Fim da verificação de mudança na Rua */

                /* Inicio da verificação de mudança na RuasTipo */
                perform db85_sequencial
                   from cadenderruaruastipo
                  where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na ruastipo';
                  end if;

                  iCodigoRuasTipo := nextval('cadenderruaruastipo_db85_sequencial_seq');
                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (iCodigoRuasTipo,
                                                   iCodigoRua,
                                                   3
                                                  );

                end if;

                /* Fim da verificação de mudança na RuasTipo */
                /* Inicio da verificação de mudança na BairroRua */
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na BairroRua';
                  end if;

                  iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                  insert into cadenderbairrocadenderrua (db87_sequencial,
                                                         db87_cadenderrua,
                                                         db87_cadenderbairro
                                                        )
                                                 values (iCodigoBairroRua,
                                                         iCodigoRua,
                                                         iCodigoBairro
                                                        );

                end if;
                /* Fim da verificação de mudança na BairroRua */

                /* Inicio da verificação de mudança na Local */

                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua
                    and db75_numero = cast(rCgm.z01_numero as text);

                if not found then

                  iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                  insert into cadenderlocal (db75_sequencial,
                                             db75_cadenderbairrocadenderrua,
                                             db75_numero
                                            )
                                     values (iCodigoLocal,
                                             iCodigoBairroRua,
                                             rCgm.z01_numero
                                            );

                end if;
                /* Fim da verificação de mudança na Local */

                /* Inicio da verificação de mudança na Endereco */
                select count(*)
                  into iNumCgmEndereco
                  from cgmendereco
                 where z07_endereco = iCodigoEndereco
                having count(*) > 1;

                /*delete na cgmendereco*/
                delete from cgmendereco
                        where z07_numcgm = iCodigoCgm
                          and z07_tipo   = 'P';


                if (iNumCgmEndereco > 0 and (rCgm.z01_compl != rEndereco.db76_complemento)) then

                  if lRaise then
                    raise notice 'Existe mais de um cgm no mesmo endereco inserindo endereco novo';
                  end if;

                  iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                  insert into endereco (db76_sequencial,
                                        db76_cadenderlocal,
                                        db76_complemento,
                                        db76_caixapostal,
                                        db76_loteamento,
                                        db76_condominio,
                                        db76_pontoref,
                                        db76_cep
                                       )
                                values (iCodigoEndereco,
                                        iCodigoLocal,
                                        rCgm.z01_compl,
                                        rEndereco.db76_caixapostal,
                                        rEndereco.db76_loteamento,
                                        rEndereco.db76_condominio,
                                        rEndereco.db76_pontoref,
                                        rCgm.z01_cep
                                       );



                else

                  perform db76_sequencial
                     from endereco
                    where db76_sequencial    = iCodigoEndereco
                      and db76_cadenderlocal = iCodigoLocal;

                  if not found then
                    iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                    insert into endereco (db76_sequencial,
                                          db76_cadenderlocal,
                                          db76_complemento,
                                          db76_caixapostal,
                                          db76_loteamento,
                                          db76_condominio,
                                          db76_pontoref,
                                          db76_cep
                                         )
                                  values (iCodigoEndereco,
                                          iCodigoLocal,
                                          rCgm.z01_compl,
                                          rEndereco.db76_caixapostal,
                                          rEndereco.db76_loteamento,
                                          rEndereco.db76_condominio,
                                          rEndereco.db76_pontoref,
                                          rCgm.z01_cep
                                         );


                  else
                      update endereco set db76_cadenderlocal = iCodigoLocal,
                                          db76_complemento   = rCgm.z01_compl,
                                          db76_cep           = rCgm.z01_cep
                                    where db76_sequencial = rEndereco.db76_sequencial;
                  end if;
                end if;

                /* Inserindo na cgmendereco */
                insert into cgmendereco(z07_sequencial,
                                          z07_endereco,
                                          z07_numcgm,
                                          z07_tipo
                                         )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                          iCodigoEndereco,
                                          iCodigoCgm,
                                          'P'
                                         );


                /* Fim da verificação de mudança na Endereco */
                if lRaise then
                  raise notice 'Fim da atualização do cgm {%} endereco {%}',iCodigoCgm,iCodigoEndereco;
                end if;

              end if;/* Finaliza bloco de quando não existe ligação com a tabela cgmendereco para tipo 'P' */
        end if; /* Fecha o if do endereço primario tipo 'P' */
    /*----------------------------------------  Aqui inicia no endereço secundario  ----------------------------------*/
        if (rCgm.z01_endcon != '') then
            /* Pesquisa para verificar se existe relação do cgm com o endereco {cgmendereco} */
            iCodigoEstado      := 0;
            iCodigoMunicipio   := 0;
            iCodigoBairro      := 0;
            iCodigoRua         := 0;
            iCodigoBairroRua   := 0;
            iCodigoLocal       := 0;
            iCodigoEndereco    := 0;
            iCodigoRuasTipo    := 0;
            iCodigoCgmEndereco := 0;
            iNumCgmEndereco    := 0;

            select z07_sequencial,
                   z07_endereco
              into iCodigoCgmEndereco,
                   iCodigoEndereco
              from cgmendereco
             where z07_numcgm = iCodigoCgm
               and z07_tipo   = 'S';

              /* Se o cgm não existir na tabela de ligação gerar o endereço e vincular o cidadao ao cgm */
              if not found then

                if lRaise then
                  raise notice 'Cgm não encontrado na cgmendereco ';
                end if;

               /* ---------------------------- Inicio do tratameno do estado do endereço -----------------------------*/
               /* Atribuindo o codigo do estado da tabela de parametros do endereço {cadenderparam} */
                iCodigoEstado := rCadEnderParam.db99_cadenderestado;

               /* Verificar se z01_uf e z01_munic são diferentes de ''
                *  se não for atribuir o estado default para o municipio não informado RS, 0-Não Informado
                */
                if (rCgm.z01_muncon = '' or rCgm.z01_ufcon = '' or rCgm.z01_baicon = '') then

                  select db71_sequencial
                    into iCodigoEstado
                    from cadendermunicipio
                         inner join cadenderestado on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                   where cadenderestado.db71_sigla = rCadEnderParam.db71_sigla;

                   if not found then

                    if lRaise then
                      raise notice 'Falha ao atribuir estado padrão!';
                    end if;

                    return null;
                   end if;

                else /* Aqui pesquisa pela sigla do z01_uf informado no cgm*/
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = trim(rCgm.z01_ufcon);
                  /* Se não localizar o estado atribuir o estado dos parametros do endereço */
                  if not found then

                    if lRaise then
                      raise notice 'Estado não encontrado pela sigla fornecida atribuido dos Parametros do endereco';
                    end if;

                    iCodigoEstado := rCadEnderParam.db99_cadenderestado;
                  end if;

                end if;/*Fechamento do if do estado*/
               /* ---------------------------- Fim do tratameno do estado do endereço -----------------------------*/
               /* ---------------------------- Inicio do tratameno do estado do Municipio -------------------------*/
                /* Se o z01_munic for igual a vazio */
                if (rCgm.z01_muncon = '') then

                  if lRaise then
                    raise notice 'Definido municipio 0-Não Informado para o endereço';
                  end if;

                  iCodigoMunicipio := 0;
                else /* Se o z01_munic diferente de vazio pesquisar se existe senão incluir*/

                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao      = rCgm.z01_muncon
                     and db72_cadenderestado = iCodigoEstado;
                  /* Se não encontrou o municipio entao tem que incluir o mesmo */
                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado ! incluindo .....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');

                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                   )
                                           values (iCodigoMunicipio,
                                                   rCgm.z01_muncon,
                                                   iCodigoEstado
                                                  );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Municipio ----------------------------*/
               /* ---------------------------- Inicio do tratamento do Bairro ----------------------------*/
                /* Se o z01_bairro for igual a vazio */
                if (rCgm.z01_baicon = '') then

                  if lRaise then
                    raise notice 'Definindo bairro 0-Não Informado para o endereço';
                  end if;

                  iCodigoBairro := 0;

                else /* Se o z01_bairro diferente de vazio pesquisar se existe senão incluir */

                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_baicon
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado ! incluindo .....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                        values (iCodigoBairro,
                                                rCgm.z01_baicon,
                                                iCodigoMunicipio
                                               );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento do Bairro -------------------------------*/
               /* ---------------------------- Inicio do tratamento da Rua  -------------------------------*/
                /* Se o bairro for igual a vazio */
                if (rCgm.z01_endcon = '') then

                  if lRaise then
                    raise notice 'Endereco não informado -- Inclusão Cancelada';
                  end if;

                  return null;
                else /* Se o z01_ender for diferente de vazio pesquisar se existe senão incluir */

                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_endcon
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrado ! incluindo ..... ';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                     values (iCodigoRua,
                                             rCgm.z01_endcon,
                                             iCodigoMunicipio
                                            );
                  end if;

                end if;
               /* ---------------------------- Fim do tratamento da Rua -------------------------------*/
               /* ---------------------------- Inicio do tratamento da RuasTipo -----------------------*/
                perform db85_sequencial
                  from cadenderruaruastipo
                 where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na cadenderruaruastipo';
                  end if;

                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (nextval('cadenderruaruastipo_db85_sequencial_seq'),
                                                   iCodigoRua,
                                                   3
                                                  );
                end if;

               /* ---------------------------- Fim do tratamento da RuasTipo --------------------------*/
               /* ---------------------------- Inicio do tratamento do Vinculo da Rua com Bairro ------*/
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua    = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                 if not found then

                   if lRaise then
                     raise notice 'Incluindo na cadenderbairrocadenderrua';
                   end if;

                   iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                   insert into cadenderbairrocadenderrua (db87_sequencial,
                                                          db87_cadenderrua,
                                                          db87_cadenderbairro
                                                         )
                                                  values (iCodigoBairroRua,
                                                          iCodigoRua,
                                                          iCodigoBairro
                                                         );

                 end if;
               /* ---------------------------- Fim do tratamento do Vinculo da Rua com Bairro ---------*/
               /* ---------------------------- Inicio do tratamento da Local --------------------------*/
                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua;

                 if not found then

                    if lRaise then
                      raise notice 'Icluindo na cadenderlocal';
                    end if;

                    iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                    insert into cadenderlocal (db75_sequencial,
                                               db75_cadenderbairrocadenderrua,
                                               db75_numero
                                              )
                                       values (iCodigoLocal,
                                               iCodigoBairroRua,
                                               rCgm.z01_numcon
                                              );

                 end if;
               /* ---------------------------- Fim do tratamento da Local -----------------------------*/
               /* ---------------------------- Inicio do tratamento da Endereco -----------------------*/
                 iCodigoEndereco := nextval('endereco_db76_sequencial_seq');

                 if lRaise then
                   raise notice 'Inserindo na Endereco {%}',iCodigoEndereco;
                 end if;

                 insert into endereco (db76_sequencial,
                                       db76_cadenderlocal,
                                       db76_complemento,
                                       db76_cep
                                      )
                               values (iCodigoEndereco,
                                       iCodigoLocal,
                                       rCgm.z01_comcon,
                                       rCgm.z01_cepcon
                                      );
               /* ---------------------------- Fim do tratamento da Endereco --------------------------*/
               /* ---------------------------- Inicio do tratamento da cgmendereco --------------------*/

                if lRaise then
                  raise notice 'Inserindo na cgmendereco';
                end if;

                insert into cgmendereco (z07_sequencial,
                                         z07_endereco,
                                         z07_numcgm,
                                         z07_tipo
                                        )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                         iCodigoEndereco,
                                         iCodigoCgm,
                                         'S'
                                        );
               /* ---------------------------- Fim do tratamento da cgmendereco -----------------------*/


              else  /* aqui se ja exisitir na cgmendereco */

               select db74_sequencial,
                      db74_descricao,
                      db75_numero,
                      db73_sequencial,
                      db73_descricao,
                      db72_sequencial,
                      db72_descricao,
                      db71_sequencial,
                      db71_descricao,
                      db71_sigla,
                      db76_sequencial,
                      db76_cep,
                      db76_pontoref,
                      db76_condominio,
                      db76_loteamento,
                      db76_caixapostal,
                      db76_complemento
                 into rEndereco
                 from endereco
                      inner join cadenderlocal             on cadenderlocal.db75_sequencial = endereco.db76_cadenderlocal
                      inner join cadenderbairrocadenderrua on cadenderbairrocadenderrua.db87_sequencial = cadenderlocal.db75_cadenderbairrocadenderrua
                      inner join cadenderrua               on cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua
                      inner join cadenderbairro            on cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro
                      inner join cadendermunicipio         on cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio
                      inner join cadenderestado            on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado
                where db76_sequencial = iCodigoEndereco;

                if not found then

                  if lRaise then
                    raise notice 'Falha ao ler endereco completo para o cgm{%} e  endereco{%}',iCodigoCgm,iCodigoEndereco;
                  end if;

                  return null;
                end if;

                /* Verificar se houve mudança no estado */
                if (rEndereco.db71_sigla != rCgm.z01_ufcon) then
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCgm.z01_ufcon;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado para o cgm';
                    end if;

                    return null;
                  end if;

                else
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rEndereco.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler estado do endereco';
                    end if;

                    return null;
                  end if;

                end if;/*Fim do if do estado*/
                /* Fim da Verificação da mudança no estado*/

                /* Inicio da verificação de mudança no municipio */

                /* se z01_munic vazio atribui 0-Não Informaado e busca codigo do estado default 'RS' */
                if (rCgm.z01_muncon = '') then
                  iCodigoMunicipio := 0;
                  select db71_sequencial
                    into iCodigoEstado
                    from cadenderestado
                   where db71_sigla = rCadEnderParam.db71_sigla;

                  if not found then

                    if lRaise then
                      raise notice 'Falha ao ler codigo do estado para municipio NI';
                    end if;

                    return null;
                  end if;
                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db72_sequencial
                    into iCodigoMunicipio
                    from cadendermunicipio
                   where db72_descricao = rCgm.z01_muncon;

                  if not found then

                    if lRaise then
                      raise notice 'Municipio não encontrado para o estado cadastrado incluindo....';
                    end if;

                    iCodigoMunicipio := nextval('cadendermunicipio_db72_sequencial_seq');
                    insert into cadendermunicipio (db72_sequencial,
                                                   db72_descricao,
                                                   db72_cadenderestado
                                                  )
                                          values  (iCodigoMunicipio,
                                                   rCgm.z01_muncon,
                                                   iCodigoEstado
                                                  );

                  end if;

                end if;/*Fim do if do municipio*/

                /* Fim da verificação de mudança no municipio */

                /* Inicio da verificação de mudança no bairro */

                /* se z01_bairro vazio atribui 0-Não Informado */
                if (rCgm.z01_baicon = '') then

                  iCodigoBairro := 0;

                /* Verifica se houve mudança no municipio cadastrado
                 * procurar pelo z01_munic se existe se não cadastrar
                 */
                else
                  select db73_sequencial
                    into iCodigoBairro
                    from cadenderbairro
                   where db73_descricao = rCgm.z01_baicon
                     and db73_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Bairro não encontrado para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoBairro := nextval('cadenderbairro_db73_sequencial_seq');
                    insert into cadenderbairro (db73_sequencial,
                                                db73_descricao,
                                                db73_cadendermunicipio
                                               )
                                       values  (iCodigoBairro,
                                                rCgm.z01_baicon,
                                                iCodigoMunicipio
                                               );

                  end if;

                end if;/*Fim do if do bairro*/


                /* Fim da verificação de mudança no bairro */

                /* Inicio da verificação de mudança na Rua */
                if (rCgm.z01_endcon ='') then

                  if lRaise then
                    raise notice 'Campo z01_ender vazio';
                  end if;

                  return null;
                else
                  select db74_sequencial
                    into iCodigoRua
                    from cadenderrua
                   where db74_descricao = rCgm.z01_endcon
                     and db74_cadendermunicipio = iCodigoMunicipio;

                  if not found then

                    if lRaise then
                      raise notice 'Rua não encontrada para o municipio cadastrado incluindo....';
                    end if;

                    iCodigoRua := nextval('cadenderrua_db74_sequencial_seq');
                    insert into cadenderrua (db74_sequencial,
                                             db74_descricao,
                                             db74_cadendermunicipio
                                            )
                                    values  (iCodigoRua,
                                             rCgm.z01_endcon,
                                             iCodigoMunicipio
                                            );

                  end if;

                end if;/* fim do if da Rua*/

                /* Fim da verificação de mudança na Rua */

                /* Inicio da verificação de mudança na RuasTipo */
                perform db85_sequencial
                   from cadenderruaruastipo
                  where db85_cadenderrua = iCodigoRua;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na ruastipo';
                  end if;

                  iCodigoRuasTipo := nextval('cadenderruaruastipo_db85_sequencial_seq');
                  insert into cadenderruaruastipo (db85_sequencial,
                                                   db85_cadenderrua,
                                                   db85_ruastipo
                                                  )
                                           values (iCodigoRuasTipo,
                                                   iCodigoRua,
                                                   3
                                                  );

                end if;

                /* Fim da verificação de mudança na RuasTipo */
                /* Inicio da verificação de mudança na BairroRua */
                 select db87_sequencial
                   into iCodigoBairroRua
                   from cadenderbairrocadenderrua
                  where db87_cadenderrua    = iCodigoRua
                    and db87_cadenderbairro = iCodigoBairro;

                if not found then

                  if lRaise then
                    raise notice 'Incluindo na BairroRua';
                  end if;


                  iCodigoBairroRua := nextval('cadenderbairrocadenderrua_db87_sequencial_seq');
                  insert into cadenderbairrocadenderrua (db87_sequencial,
                                                         db87_cadenderrua,
                                                         db87_cadenderbairro
                                                        )
                                                 values (iCodigoBairroRua,
                                                         iCodigoRua,
                                                         iCodigoBairro::integer
                                                        );

                end if;
                /* Fim da verificação de mudança na BairroRua */

                /* Inicio da verificação de mudança na Local */

                 select db75_sequencial
                   into iCodigoLocal
                   from cadenderlocal
                  where db75_cadenderbairrocadenderrua = iCodigoBairroRua
                    and db75_numero::integer = rCgm.z01_numcon;

                if not found then

                  iCodigoLocal := nextval('cadenderlocal_db75_sequencial_seq');
                  insert into cadenderlocal (db75_sequencial,
                                             db75_cadenderbairrocadenderrua,
                                             db75_numero
                                            )
                                     values (iCodigoLocal,
                                             iCodigoBairroRua,
                                             rCgm.z01_numcon
                                            );

                end if;
                /* Fim da verificação de mudança na Local */

                /* Inicio da verificação de mudança na Endereco */
                select count(*)
                  into iNumCgmEndereco
                  from cgmendereco
                 where z07_endereco = iCodigoEndereco
                having count(*) > 1;

                /*delete na cgmendereco*/
                delete from cgmendereco
                        where z07_numcgm = iCodigoCgm
                          and z07_tipo   = 'S';


                if (iNumCgmEndereco > 0 and (rCgm.z01_comcon != rEndereco.db76_complemento)) then

                  if lRaise then
                    raise notice 'Existe mais de um cgm no mesmo endereco inserindo endereco novo';
                  end if;

                  iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                  insert into endereco (db76_sequencial,
                                        db76_cadenderlocal,
                                        db76_complemento,
                                        db76_caixapostal,
                                        db76_loteamento,
                                        db76_condominio,
                                        db76_pontoref,
                                        db76_cep
                                       )
                                values (iCodigoEndereco,
                                        iCodigoLocal,
                                        rCgm.z01_comcon,
                                        rEndereco.db76_caixapostal,
                                        rEndereco.db76_loteamento,
                                        rEndereco.db76_condominio,
                                        rEndereco.db76_pontoref,
                                        rCgm.z01_cepcon
                                       );



                else

                  perform db76_sequencial
                     from endereco
                    where db76_sequencial    = iCodigoEndereco
                      and db76_cadenderlocal = iCodigoLocal;

                  if not found then
                    iCodigoEndereco := nextval('endereco_db76_sequencial_seq');
                    insert into endereco (db76_sequencial,
                                          db76_cadenderlocal,
                                          db76_complemento,
                                          db76_caixapostal,
                                          db76_loteamento,
                                          db76_condominio,
                                          db76_pontoref,
                                          db76_cep
                                         )
                                  values (iCodigoEndereco,
                                          iCodigoLocal,
                                          rCgm.z01_comcon,
                                          rEndereco.db76_caixapostal,
                                          rEndereco.db76_loteamento,
                                          rEndereco.db76_condominio,
                                          rEndereco.db76_pontoref,
                                          rCgm.z01_cepcon
                                         );


                  else
                      update endereco set db76_cadenderlocal = iCodigoLocal,
                                          db76_complemento   = rCgm.z01_comcon,
                                          db76_cep           = rCgm.z01_cepcon
                                    where db76_sequencial = rEndereco.db76_sequencial;
                  end if;
                end if;

                /* Inserindo na cgmendereco */
                insert into cgmendereco(z07_sequencial,
                                          z07_endereco,
                                          z07_numcgm,
                                          z07_tipo
                                         )
                                  values (nextval('cgmendereco_z07_sequencial_seq'),
                                          iCodigoEndereco,
                                          iCodigoCgm,
                                          'S'
                                         );


                /* Fim da verificação de mudança na Endereco */
                if lRaise then
                  raise notice 'Fim da atualização do cgm {%} endereco {%}',iCodigoCgm,iCodigoEndereco;
                end if;
              end if;/* Finaliza bloco de quando não existe ligação com a tabela cgmendereco para tipo 'P' */
        end if; /* Fecha o if do endereço primario tipo 'P' */


        return null;
    end;

    $$;

SQL
        );
    }

    public function dicionarioDadosDown()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        DELETE FROM  db_sysarqcamp  where codcam IN (1014136 ,1014137, 1014138);
        DELETE FROM  db_syscampo where codcam IN (1014138,1014137,1014136);
        DELETE FROM db_sysarqarq WHERE codarq = 1010925;
        DELETE FROM  db_sysarqmod WHERE codarq =  1010925;
        DELETE FROM  db_sysarquivo WHERE codarq =  1010925;

        DELETE FROM  db_sysarqcamp  where codcam IN (1014141,1014140,1014139);
        DELETE FROM  db_syscampo where codcam IN (1014141,1014140,1014139);
        DELETE FROM db_sysarqarq WHERE codarq = 1010926;
        DELETE FROM  db_sysarqmod WHERE codarq =  1010926;
        DELETE FROM  db_sysarquivo WHERE codarq =  1010926;

        DELETE FROM  db_sysarqcamp  where codcam IN (1014164,1014161,1014163);
        DELETE FROM  db_syscampo where codcam IN (1014164,1014161,1014163);
        DELETE FROM db_sysarqarq WHERE codarq = 1010934;
        DELETE FROM  db_sysarqmod WHERE codarq =  1010934;
        DELETE FROM  db_sysarquivo WHERE codarq =  1010934;

SQL
        );
    }

    public function upFcAtualizaPersonaCgmCpf()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        CREATE OR REPLACE FUNCTION fc_atualiza_personacgm_cpf()
        RETURNS trigger
        LANGUAGE plpgsql
        AS $$
        declare

        sOperacao varchar default lower(TG_OP);

        begin

        if sOperacao in ('insert') then

          insert into personacgm (p121_cgm,p121_persona) values ( NEW.z01_numcgm, 5 );

          return new;

        elseif sOperacao in ('delete') then

          delete from personacgm where p121_cgm = OLD.z01_numcgm and p121_persona  = 5;

          return old;

        else

          return new;

        end if;

        end;
        $$;
        create trigger tg_atualiza_personacgm_cpf AFTER INSERT OR DELETE on db_cgmcpf FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm_cpf();
SQL
        );
    }

    public function downFcAtualizaPersonaCgmCpf()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        DROP TRIGGER IF EXISTS tg_atualiza_personacgm_cpf  ON db_cgmcpf;
        DROP FUNCTION IF EXISTS fc_atualiza_personacgm_cpf;
SQL
        );
    }

    public function upFcAtualizaPersonaCgmCGC()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            CREATE OR REPLACE FUNCTION fc_atualiza_personacgm_cgc()
            RETURNS trigger
            LANGUAGE plpgsql
            AS $$
            declare

            sOperacao varchar default lower(TG_OP);

            begin

            if sOperacao in ('insert') then

              insert into personacgm(p121_cgm,p121_persona) values( NEW.z01_numcgm, 4 );

              return new;

            elseif sOperacao in ('delete') then

              delete from personacgm where p121_cgm = OLD.z01_numcgm
                                          and p121_persona     = 4;

              return old;

            else

              return new;

            end if;

            end;
            $$;
            create trigger tg_atualiza_personacgm_cgc AFTER INSERT OR DELETE on db_cgmcgc FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm_cgc();
SQL
        );
    }

    public function downFcAtualizaPersonaCgmGCG()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        DROP TRIGGER IF EXISTS tg_atualiza_personacgm_cgc  ON db_cgmcgc;
        DROP FUNCTION IF EXISTS fc_atualiza_personacgm_cgc;
SQL
        );
    }

    public function upFcAtualizaPersonacgmProprietario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        CREATE OR REPLACE FUNCTION fc_atualiza_personacgm_proprietario()
            RETURNS trigger
            LANGUAGE plpgsql
            AS $$
            declare

            sOperacao varchar default lower(TG_OP);
            iCodigoInscricao integer;

            begin

            if sOperacao in ('insert') then

              select z01_numcgm
              into iCodigoInscricao
              from personacgm
              where z01_numcgm = NEW.j01_numcgm
                and codigo_persona = 2;

              if iCodigoInscricao is null then

                 insert into personacgm (p121_cgm, p121_persona) values ( NEW.j01_numcgm, 2 );

              end if;
              return new;
            elseif sOperacao in ('update') then

              select count(*)
              into iCodigoInscricao
              from iptubase
              where j01_numcgm = old.j01_numcgm;

              if iCodigoInscricao = 0 then

                 delete from personacgm where p121_cgm = OLD.j01_numcgm and p121_persona  = 2;
              end if;

              select p121_cgm
              into iCodigoInscricao
              from personacgm
              where p121_cgm = NEW.j01_numcgm
                and p121_persona = 2;

              if iCodigoInscricao is null then

                 insert into personacgm (p121_cgm, p121_persona) values( NEW.j01_numcgm, 2 );

              end if;
              return new;

            elseif sOperacao in ('delete') then

              select count(*)
              into iCodigoInscricao
              from iptubase
              where j01_numcgm = old.j01_numcgm;

              if iCodigoInscricao = 0 then

                 delete from personacgm where p121_cgm = OLD.j01_numcgm
                                             and p121_persona     = 2;
              end if;
              return old;
            else
              return new;
            end if;

            end;
            $$;

            create trigger  tg_atualiza_personacgm_proprietario AFTER INSERT OR UPDATE OR DELETE on iptubase FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm_proprietario();

SQL
        );
    }

    public function downFcAtualizaPersonacgmProprietario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        DROP TRIGGER IF EXISTS tg_atualiza_personacgm_proprietario  ON iptubase;
        DROP FUNCTION IF EXISTS fc_atualiza_personacgm_proprietario;
SQL
        );
    }

    public function upFcAtualizaPersonacgm()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            CREATE OR REPLACE FUNCTION fc_atualiza_personacgm()
            RETURNS trigger
            LANGUAGE plpgsql
            AS $$
            declare

            sOperacao varchar default lower(TG_OP);
            sSql varchar;
            iCodigoInscricao integer;

            begin

            if sOperacao in ('insert') then

              insert into personacgm (p121_cgm,p121_persona) values( NEW.q10_numcgm, 3 );

              return new;

            elseif sOperacao in ('delete') then

              delete from personacgm where p121_cgm = OLD.q10_numcgm
                                          and p121_persona     = 3;

              return old;

            else

              return new;

            end if;

            end;
            $$;

            create trigger tg_atualiza_personacgm AFTER INSERT OR DELETE on cadescrito FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm();
SQL
        );
    }

    public function downFcAtualizaPersonacgm()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        DROP TRIGGER IF EXISTS tg_atualiza_personacgm  ON cadescrito;
        DROP FUNCTION IF EXISTS fc_atualiza_personacgm;
SQL
        );
    }

    public function upFcAtualizaPersonacgmFuncionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        CREATE OR REPLACE FUNCTION fc_atualiza_personacgm_funcionario()
        RETURNS trigger
        LANGUAGE plpgsql
        AS $$
        declare

        sOperacao varchar default lower(TG_OP);
        iCodigoInscricao integer;

        begin

        if sOperacao in ('insert') then

          select z01_numcgm
          into iCodigoInscricao
          from personacgm
          where z01_numcgm = NEW.rh01_numcgm
            and codigo_persona = 6;

          if iCodigoInscricao is null then

             insert into personacgm (p121_cgm,p121_persona) values( NEW.rh01_numcgm, 6 );

          end if;
          return new;

        elseif sOperacao in ('update') then

          select count(*)
          into iCodigoInscricao
          from rhpessoal
          where rh01_numcgm = old.rh01_numcgm;

          if iCodigoInscricao = 0 then

             delete from personacgm where p121_cgm = OLD.rh01_numcgm
                                         and p121_persona     = 6;
          end if;

          select z01_numcgm
          into iCodigoInscricao
          from personacgm
          where p121_cgm = NEW.rh01_numcgm
            and p121_persona = 6;

          if iCodigoInscricao is null then

             insert into personacgm (p121_cgm,p121_persona) values( NEW.rh01_numcgm, 6 );

          end if;
          return new;

        elseif sOperacao in ('delete') then

          select count(*)
          into iCodigoInscricao
          from rhpessoal
          where rh01_numcgm = old.rh01_numcgm;

          if iCodigoInscricao = 1 then

             delete from personacgm where p121_cgm = OLD.rh01_numcgm
                                         and p121_persona = 6;
          end if;
          return old;
        end if;

        end;
        $$;
        create trigger tg_atualiza_personacgm_funcionario AFTER INSERT OR UPDATE OR DELETE on rhpessoal FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm_funcionario();
SQL
        );
    }

    public function downFcAtualizaPersonacgmFuncionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        DROP TRIGGER IF EXISTS tg_atualiza_personacgm_funcionario  ON rhpessoal;
        DROP FUNCTION IF EXISTS fc_atualiza_personacgm_funcionario;
SQL
        );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dicionarioDedadosUp();
        $this->tablesUp();
        $this->upFcCgmAltexc();
        $this->upFcCgmEnderecoIncalt();
        $this->upFcAtualizaPersonaCgmCpf();
        $this->upFcAtualizaPersonaCgmCGC();
        $this->upFcAtualizaPersonacgmProprietario();
        $this->upFcAtualizaPersonacgm();
        $this->upFcAtualizaPersonacgmFuncionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dicionarioDadosDown();
        $this->tablesDown();
        $this->downFcCgmAltexc();
        $this->downFcCgmEnderecoIncalt();
        $this->downFcAtualizaPersonaCgmCpf();
        $this->downFcAtualizaPersonaCgmGCG();
        $this->downFcAtualizaPersonacgmProprietario();
        $this->downFcAtualizaPersonacgm();
        $this->downFcAtualizaPersonacgmFuncionario();
    }
}
