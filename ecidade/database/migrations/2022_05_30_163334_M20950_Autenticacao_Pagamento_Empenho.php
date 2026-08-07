<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M20950AutenticacaoPagamentoEmpenho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        --drop function fc_autentemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer);
        --drop function fc_autentemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer,integer,integer);
        
        create or replace function fc_autentemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer,integer,integer,integer, integer)
        returns varchar as
        $$
        
        declare
        
          numemp         alias for $1;
          conta          alias for $2;
          contarp        alias for $3;
          dtemite        alias for $4;
          valor          alias for $5;
          cheque         alias for $6;
          ipterm         alias for $7;
          codche         alias for $8;  -- Codigo do cheque do Movimento da agenda (empageconfche->e91_codcheque)
          codord         alias for $9;
          instit         alias for $10;
          codmov         alias for $11; -- Codigo do movimento da agenda (empagemov->e81_codmov)
          iCodCorGrupo   alias for $12; -- Codigo do grupo das autenticações (corgrupo->k104_sequencial)
          iCodTipoGrupo  alias for $13; -- Codigo do tipo do grupo das autenticações (corgrupotipo->k106_sequencial)
        
          sAutenticacao  varchar; 
        
          codaut         integer ;
          idterm         integer ;
          codmovimento   integer;
        
          hora           char(5) ;
          ident1         char(1) ;
          ident2         char(1) ;
          ident3         char(1) ;
        
          estorno        boolean ;
        
        begin
        
          select k11_id,
                 k11_ident1,
                 k11_ident2,
                 k11_ident3 
            into idterm,ident1,ident2,ident3
            from cfautent 
           where k11_ipterm = ipterm 
             and k11_instit = instit;
        
          if not idterm is null then
             select max(k12_autent) 
               into codaut
               from corrente 
              where k12_id     = idterm 
                and k12_data   = dtemite 
                and k12_instit = instit;
        
             if valor < 0 then
               estorno := true;
             else
               estorno := false;
             end if;
        
             if codaut is null then
               codaut := 1;
             else
               codaut := codaut + 1;
             end if;
        
             -- grava autenticacao
             hora := to_char(now(), 'HH24:MI');
             insert into 
               corrente (
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_hora,
                 k12_conta,
                 k12_valor,
                 k12_estorn,
                 k12_instit
               ) values (
                 idterm,
                 dtemite,
                 codaut,
                 hora,
                 conta,
                 valor,
                 estorno,
                 instit
               );
             
             insert into 
               coremp (
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_empen,
                 k12_cheque,
                 k12_codord
               ) values (
                 idterm,
                 dtemite,
                 codaut,
                 numemp,
                 cheque,
                 codord
               );
        
             if iCodCorGrupo > 0 then
                insert into 
                  corgrupocorrente (
                    k105_sequencial,
                    k105_corgrupo,
                    k105_data,
                    k105_autent,
                    k105_id,
                    k105_corgrupotipo
                  ) values (
                    nextval('corgrupocorrente_k105_sequencial_seq'),
                    iCodCorGrupo,
                    dtemite,
                    codaut,
                    idterm,
                    iCodTipoGrupo
                 );   
             end if;
        
             if contarp != 0 then
                insert into 
                  corlanc (
                    k12_id,
                    k12_data,
                    k12_autent,
                    k12_conta,
                    k12_codigo
                  ) values (
                    idterm,
                    dtemite,
                    codaut,
                    contarp,
                    0
                  );
             end if;
        
             if codche > 0 then
                if valor > 0 then
                  insert into 
                    corconf (
                      k12_id,
                      k12_data,
                      k12_autent,
                      k12_codmov,
          			      k12_ativo 
                    ) values (
                      idterm,
                      dtemite,
                      codaut,
                      codche,
          			      true
                    );
                else
                  update corconf
            			set k12_ativo = true
                  where k12_codmov = codche;
          
              		insert into
                    corconf (
                      k12_id,
                      k12_data,
                      k12_autent,
                      k12_codmov,
              			  k12_ativo
                    ) values (
                      idterm,
                      dtemite,
                      codaut,
                      codche,
                      false
                    );
                end if;
             end if;
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             if codmov is not null and codmov > 0 and valor > 0 then
        
                insert into
                  corempagemov (
                    k12_sequencial,
                    k12_id,
                    k12_data,
                    k12_autent,
                    k12_codmov
                  ) values (
                    nextval('corempagemov_k12_sequencial_seq'),
                    idterm,
                    dtemite,
                    codaut,
                    codmov
                  );
        
                -- Gera Ligacao do Movimento da Agenda com a Autenticacao de pagamento, pois quando ha estorno, esse vinculo eh perdido
                insert into
                  corempagemovpagamento (
                    k12_sequencial,
                    k12_id,
                    k12_data,
                    k12_autent,
                    k12_codmov
                  ) values (
                    nextval('corempagemovpagamento_k12_sequencial_seq'),
                    idterm,
                    dtemite,
                    codaut,
                    codmov
                  );
        
             elsif valor < 0 then
               
               select k12_codmov
                 into codmovimento
                 from coremp
                      join corempagemov on corempagemov.k12_id     = coremp.k12_id
                                       and corempagemov.k12_data   = coremp.k12_data 
                                       and corempagemov.k12_autent = coremp.k12_autent
                where coremp.k12_codord = codord;
         
               delete from corempagemov
                where k12_codmov = codmovimento ;
         
             end if;
        
          else
            -- erro quando o terminal nao esta cadastrado
            return '2 terminal nao cadastrado';
          end if; 
              
          -- autenticacao correta
          if valor < 0 then
             sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'-';
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             insert into
               corempagemovestorno (
                 k12_sequencial,
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_codmov
               ) values (
                 nextval('corempagemovestorno_k12_sequencial_seq'),
                 idterm,
                 dtemite,
                 codaut,
                 codmov
               );
          else
            sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'+';
          end if;
          /**
           * salva a autenticao na tabela corautent
           */
          insert into corautent (k12_id, k12_data, k12_autent, k12_codautent)
                         values (idterm,dtemite,codaut, sAutenticacao);
        
          return sAutenticacao;
        
        end;
        $$ 
        language 'plpgsql';
        
        
        create or replace function fc_estornaemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer,integer,integer,integer, integer)
        returns varchar as
        $$
        
        declare
        
          numemp         alias for $1;
          conta          alias for $2;
          contarp        alias for $3;
          dtemite        alias for $4;
          valor          alias for $5;
          cheque         alias for $6;
          ipterm         alias for $7;
          codche         alias for $8;  -- Codigo do cheque do Movimento da agenda (empageconfche->e91_codcheque)
          codord         alias for $9;
          instit         alias for $10;
          codmov         alias for $11; -- Codigo do movimento da agenda (empagemov->e81_codmov)
          iCodCorGrupo   alias for $12; -- Codigo do grupo das autenticações (corgrupo->k104_sequencial)
          iCodTipoGrupo  alias for $13; -- Codigo do tipo do grupo das autenticações (corgrupotipo->k106_sequencial)
        
          sAutenticacao  varchar;
        
          codaut         integer;
          idterm         integer;
          codmovimento   integer;
        
          hora           char(5);
          ident1         char(1);
          ident2         char(1);
          ident3         char(1);
        
          nValorRetencao numeric;
        
          estorno        boolean;
        
        begin
        	
          select k11_id, k11_ident1, k11_ident2, k11_ident3
            into idterm, ident1, ident2, ident3
            from cfautent
           where k11_ipterm = ipterm
             and k11_instit = instit;
        
          if not idterm is null then
             select max(k12_autent)
               into codaut
               from corrente
              where k12_id     = idterm
                and k12_data   = dtemite
                and k12_instit = instit;
        
             if valor < 0 then
                estorno := true;
             else
                estorno := false;
             end if;
        
             if codaut is null then
                codaut := 1;
             else
                codaut := codaut + 1;
             end if;
        
             -- grava autenticacao
             hora := to_char(now(), 'HH24:MI');
             insert into corrente ( k12_id,
                                    k12_data,
                                    k12_autent,
                                    k12_hora,
                                    k12_conta,
                                    k12_valor,
                                    k12_estorn,
                                    k12_instit )
                           values ( idterm,
                                    dtemite,
                                    codaut,
                                    hora,
                                    conta,
                                    valor,
                                    estorno,
                                    instit );
             
             insert into coremp ( k12_id,
                                  k12_data,
                                  k12_autent,
                                  k12_empen,
                                  k12_cheque,
                                  k12_codord ) 
                         values ( idterm,
                                  dtemite,
                                  codaut,
                                  numemp,
                                  cheque,
                                  codord );
              
             if  iCodCorGrupo > 0 then
        
                 insert into corgrupocorrente ( k105_sequencial,
                                                k105_corgrupo,
                                                k105_data,
                                                k105_autent,
                                                k105_id,
                                                k105_corgrupotipo ) 
                                       values ( nextval('corgrupocorrente_k105_sequencial_seq'),
                                                iCodCorGrupo,
                                                dtemite,
                                                codaut,
                                                idterm,
                                                iCodTipoGrupo );
               
             end if;
            
             if contarp != 0 then
             
               insert into corlanc ( k12_id,
                                     k12_data,
                                     k12_autent,
                                     k12_conta,
                                     k12_codigo ) 
                            values ( idterm,
                                     dtemite,
                                     codaut,
                                     contarp,
                                     0);
                 
             end if;
        
             if codche > 0 then
                if valor > 0 then
                
                   insert into corconf (k12_id,
                                        k12_data,
                                        k12_autent,
                                        k12_codmov,
                                  	 		 k12_ativo)
                                values ( idterm,
                                         dtemite,
                                         codaut,
                                         codche,
          			                         true );
                else
                   update corconf set k12_ativo = false where k12_codmov = codche;
          
          		     insert into corconf ( k12_id,
                                         k12_data,
                                         k12_autent,
                                         k12_codmov,
          		                           k12_ativo )
          		     	            values ( idterm,
                                         dtemite,
                                         codaut,
                                         codche,
          		     	                     false );
          
                end if;
             end if;
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             if codmov is not null and codmov > 0 and valor > 0 then
             
                insert into corempagemov ( k12_sequencial,
                                           k12_id,
                                           k12_data,
                                           k12_autent,
                                           k12_codmov )
                                  values ( nextval('corempagemov_k12_sequencial_seq'),
                                           idterm,
                                           dtemite,
                                           codaut,
                                           codmov );
        
                -- Gera Ligacao do Movimento da Agenda com a Autenticacao de pagamento, pois quando ha estorno, esse vinculo eh perdido
                insert into corempagemovpagamento
                                         ( k12_sequencial,
                                           k12_id,
                                           k12_data,
                                           k12_autent,
                                           k12_codmov )
                                  values ( nextval('corempagemovpagamento_k12_sequencial_seq'),
                                           idterm,
                                           dtemite,
                                           codaut,
                                           codmov );
        
             elsif valor < 0 then
              
                /* consultamos o tipo do movimento.
                 * caso é um estorno de retencao verificamos se o usuario 
                 * escolheu o valor total do movimento 
                 */
                if iCodTipoGrupo = 5 then 
         
                   nValorRetencao := fc_valorretencaomov(codmov, true);
                   if nValorRetencao = abs(valor) then
        
                      select k12_sequencial
                        into codmovimento
                        from coremp
                             join corempagemov     on corempagemov.k12_id     = coremp.k12_id
                                                  and corempagemov.k12_data   = coremp.k12_data
                                                  and corempagemov.k12_autent = coremp.k12_autent
                             join corgrupocorrente on corempagemov.k12_id     = k105_id
                                                  and corempagemov.k12_data   = k105_data
                                                  and corempagemov.k12_autent = k105_autent
                       where coremp.k12_codord = codord
                         and corempagemov.k12_codmov = codmov
                         and k105_corgrupotipo = 2;
             
                      delete from corempagemov where k12_sequencial  =  codmovimento;
        
                   end if;
                  
                else        
        
                   select k12_sequencial
                     into codmovimento
                     from coremp
                          join corempagemov     on corempagemov.k12_id     = coremp.k12_id
                                               and corempagemov.k12_data   = coremp.k12_data
                                               and corempagemov.k12_autent = coremp.k12_autent
                          join corgrupocorrente on corempagemov.k12_id     = k105_id
                                               and corempagemov.k12_data   = k105_data
                                               and corempagemov.k12_autent = k105_autent
                    where coremp.k12_codord       = codord
                      and corempagemov.k12_codmov = codmov
                      and k105_corgrupotipo = 1;
        
                   delete from corempagemov where k12_sequencial = codmovimento ;
                
                end if;
             end if;
          else
             -- erro quando o terminal nao esta cadastrado
             return '2 terminal nao cadastrado';
          end if; 
          
          -- autenticacao correta
          if valor < 0 then
             sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'-';
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             insert into
               corempagemovestorno (
                 k12_sequencial,
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_codmov
               ) values (
                 nextval('corempagemovestorno_k12_sequencial_seq'),
                 idterm,
                 dtemite,
                 codaut,
                 codmov
               );
          else
             sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'+';
          end if;
        
          /**
           * salva a autenticao na tabela corautent
           */
          insert into corautent (k12_id, 
                                 k12_data, 
                                 k12_autent, 
                                 k12_codautent)
                         values (idterm,
                                 dtemite,
                                 codaut, 
                                 sAutenticacao
                                );
        
          return sAutenticacao;
        
        end;
        $$ 
        language 'plpgsql';

SQL
);

        DB::connection()->getPdo()->exec(<<<SQL

          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;
  
          DROP SEQUENCE IF EXISTS corempagemovpagamento_k12_sequencial_seq cascade;
          CREATE SEQUENCE caixa.corempagemovpagamento_k12_sequencial_seq
            INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
  
          CREATE TABLE IF NOT EXISTS caixa.corempagemovpagamento (
         
             k12_sequencial integer NOT NULL DEFAULT nextval('caixa.corempagemovpagamento_k12_sequencial_seq'),
             k12_id integer DEFAULT 0 NOT NULL,
             k12_data date NOT NULL,
             k12_autent integer DEFAULT 0 NOT NULL,
             k12_codmov integer DEFAULT 0 NOT NULL,
             CONSTRAINT corempagemovpagamento_sequ_pk PRIMARY KEY (k12_sequencial)
          );
  
          CREATE INDEX IF NOT EXISTS corempagemovpagamento_codmov_in ON caixa.corempagemovpagamento USING btree (k12_codmov);
          CREATE INDEX IF NOT EXISTS corempagemovpagamento_id_data_autent_in
                    ON caixa.corempagemovpagamento USING btree (k12_id, k12_data, k12_autent);
  
          ALTER TABLE ONLY caixa.corempagemovpagamento
             ADD CONSTRAINT corempagemovpagamento_codmov_fk FOREIGN KEY (k12_codmov)
             REFERENCES empenho.empagemov(e81_codmov) MATCH FULL DEFERRABLE;
  
          ALTER TABLE ONLY caixa.corempagemovpagamento
             ADD CONSTRAINT corempagemovpagamento_id_data_autent_fk FOREIGN KEY (k12_id, k12_data, k12_autent)
             REFERENCES caixa.corrente(k12_id, k12_data, k12_autent) MATCH FULL DEFERRABLE;
  
          COMMENT ON TABLE caixa.corempagemovpagamento
             IS '{"k12_sequencial": "Tabela que liga uma autenticacao (corrente) de pagamento a um movimento da agenda de pagamentos (empagemov)",
                  "sigla": "k12",
                  "dataincl": "2022-05-20",
                  "rotulo": "corempagemovpagamento",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';
  
          COMMENT ON COLUMN caixa.corempagemovpagamento.k12_sequencial
             IS '{ "descricao": "Código interno sequencial",
                   "rotulo": "Código sequencial",
                   "rotulorel": "Código sequencial",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;
  
          COMMENT ON COLUMN caixa.corempagemovpagamento.k12_id
             IS '{ "descricao": "Código do cadastro da autenticadora",
                   "rotulo": "Código Autenticadora",
                   "rotulorel": "Código Autenticadora",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;
  
          COMMENT ON COLUMN caixa.corempagemovpagamento.k12_data
             IS '{ "descricao": "Data da geração da autenticação",
                   "rotulo": "Data Autenticação",
                   "rotulorel": "Data Autenticação",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 8,
                   "tipoobj": "text"
                 }' ;
  
          COMMENT ON COLUMN caixa.corempagemovpagamento.k12_autent
             IS '{ "descricao": "Código da Autenticação gerado pelo caixa. Este numero começa em um para cada caixa e cada dia que possuir autenticação",
                   "rotulo": "Código Autenticação ",
                   "rotulorel": "Código Autenticação ",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;
  
          COMMENT ON COLUMN caixa.corempagemovpagamento.k12_codmov
             IS '{ "descricao": "Código de movimento da Agenda de Pagamentos. (Na tabela CORCONF NÃO é o código do movimento da agenda e sim o codigo do cheque gerado - empageconfche. Na tabela COREMPAGEMOV É o código do movimento da agenda - empagemov).",
                   "rotulo": "Movimento",
                   "rotulorel": "Movimento",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;
  
          SELECT fc_gera_dicionario_apartir_tabela('caixa', 'corempagemovpagamento');
  
          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
          ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

SQL
);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        SELECT fc_remove_dicionario_tabela('caixa', 'corempagemovpagamento');
        DROP TABLE IF EXISTS caixa.corempagemovpagamento;

SQL
);


        DB::connection()->getPdo()->exec(<<<SQL
        
        --drop function fc_autentemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer);
        --drop function fc_autentemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer,integer,integer);
        
        create or replace function fc_autentemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer,integer,integer,integer, integer)
        returns varchar as
        $$
        
        declare
        
          numemp         alias for $1;
          conta          alias for $2;
          contarp        alias for $3;
          dtemite        alias for $4;
          valor          alias for $5;
          cheque         alias for $6;
          ipterm         alias for $7;
          codche         alias for $8;  -- Codigo do cheque do Movimento da agenda (empageconfche->e91_codcheque)
          codord         alias for $9;
          instit         alias for $10;
          codmov         alias for $11; -- Codigo do movimento da agenda (empagemov->e81_codmov)
          iCodCorGrupo   alias for $12; -- Codigo do grupo das autenticações (corgrupo->k104_sequencial)
          iCodTipoGrupo  alias for $13; -- Codigo do tipo do grupo das autenticações (corgrupotipo->k106_sequencial)
        
          sAutenticacao  varchar; 
          codaut         integer;
          idterm         integer;
          codmovimento   integer;
        
          hora           char(5);
          ident1         char(1);
          ident2         char(1);
          ident3         char(1);
        
          estorno        boolean;
        
        begin
        
          select k11_id,
                 k11_ident1,
                 k11_ident2,
                 k11_ident3 
            into idterm,ident1,ident2,ident3
            from cfautent 
           where k11_ipterm = ipterm 
             and k11_instit = instit;
        
          if not idterm is null then
             select max(k12_autent) 
               into codaut
               from corrente 
              where k12_id     = idterm 
                and k12_data   = dtemite 
                and k12_instit = instit;
        
             if valor < 0 then
               estorno := true;
             else
               estorno := false;
             end if;
        
             if codaut is null then
               codaut := 1;
             else
               codaut := codaut + 1;
             end if;
        
             -- grava autenticacao
             hora := to_char(now(), 'HH24:MI');
             insert into 
               corrente (
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_hora,
                 k12_conta,
                 k12_valor,
                 k12_estorn,
                 k12_instit
               ) values (
                 idterm,
                 dtemite,
                 codaut,
                 hora,
                 conta,
                 valor,
                 estorno,
                 instit
               );
             
             insert into 
               coremp (
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_empen,
                 k12_cheque,
                 k12_codord
               ) values (
                 idterm,
                 dtemite,
                 codaut,
                 numemp,
                 cheque,
                 codord
               );
        
             if iCodCorGrupo > 0 then
                insert into 
                  corgrupocorrente (
                    k105_sequencial,
                    k105_corgrupo,
                    k105_data,
                    k105_autent,
                    k105_id,
                    k105_corgrupotipo
                  ) values (
                    nextval('corgrupocorrente_k105_sequencial_seq'),
                    iCodCorGrupo,
                    dtemite,
                    codaut,
                    idterm,
                    iCodTipoGrupo
                 );   
             end if;
        
             if contarp != 0 then
                insert into 
                  corlanc (
                    k12_id,
                    k12_data,
                    k12_autent,
                    k12_conta,
                    k12_codigo
                  ) values (
                    idterm,
                    dtemite,
                    codaut,
                    contarp,
                    0
                  );
             end if;
        
             if codche > 0 then
                if valor > 0 then
                  insert into 
                    corconf (
                      k12_id,
                      k12_data,
                      k12_autent,
                      k12_codmov,
                            k12_ativo 
                    ) values (
                      idterm,
                      dtemite,
                      codaut,
                      codche,
                            true
                    );
                else
                  update corconf
                        set k12_ativo = true
                  where k12_codmov = codche;
          
                      insert into
                    corconf (
                      k12_id,
                      k12_data,
                      k12_autent,
                      k12_codmov,
                            k12_ativo
                    ) values (
                      idterm,
                      dtemite,
                      codaut,
                      codche,
                      false
                    );
                end if;
             end if;
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             if codmov is not null and codmov > 0 and valor > 0 then
        
                insert into
                  corempagemov (
                    k12_sequencial,
                    k12_id,
                    k12_data,
                    k12_autent,
                    k12_codmov
                  ) values (
                    nextval('corempagemov_k12_sequencial_seq'),
                    idterm,
                    dtemite,
                    codaut,
                    codmov
                  );
             elsif valor < 0 then
               
               select k12_codmov 
                 into codmovimento
                 from coremp
                      join corempagemov on corempagemov.k12_id     = coremp.k12_id
                                       and corempagemov.k12_data   = coremp.k12_data 
                                       and corempagemov.k12_autent = coremp.k12_autent
                where coremp.k12_codord = codord;
         
               delete
                 from corempagemov
                where k12_codmov = codmovimento ;
         
             end if;
        
          else
            -- erro quando o terminal nao esta cadastrado
            return '2 terminal nao cadastrado';
          end if; 
              
          -- autenticacao correta
          if valor < 0 then
             sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'-';
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             insert into
               corempagemovestorno (
                 k12_sequencial,
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_codmov
               ) values (
                 nextval('corempagemovestorno_k12_sequencial_seq'),
                 idterm,
                 dtemite,
                 codaut,
                 codmov
               );
          else
            sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'+';
          end if;
          /**
           * salva a autenticao na tabela corautent
           */
          insert into corautent (k12_id, k12_data, k12_autent, k12_codautent)
                         values (idterm,dtemite,codaut, sAutenticacao);
        
          return sAutenticacao;
        
        end;
        $$ 
        language 'plpgsql';
        
        create or replace function fc_estornaemp(integer,integer,integer,date,float8,integer,varchar(20),integer,integer,integer,integer,integer, integer)
        returns varchar as
        $$
        
        declare
        
          numemp         alias for $1;
          conta          alias for $2;
          contarp        alias for $3;
          dtemite        alias for $4;
          valor          alias for $5;
          cheque         alias for $6;
          ipterm         alias for $7;
          codche         alias for $8;  -- Codigo do cheque do Movimento da agenda (empageconfche->e91_codcheque)
          codord         alias for $9;
          instit         alias for $10;
          codmov         alias for $11; -- Codigo do movimento da agenda (empagemov->e81_codmov)
          iCodCorGrupo   alias for $12; -- Codigo do grupo das autenticações (corgrupo->k104_sequencial)
          iCodTipoGrupo  alias for $13; -- Codigo do tipo do grupo das autenticações (corgrupotipo->k106_sequencial)
        
          sAutenticacao  varchar;
        
          codaut         integer;
          idterm         integer;
          codmovimento   integer;
        
          hora           char(5);
          ident1         char(1);
          ident2         char(1);
          ident3         char(1);
        
          nValorRetencao numeric;
        
          estorno        boolean;
        
        begin
            
          select k11_id, k11_ident1, k11_ident2, k11_ident3
            into idterm, ident1, ident2, ident3
            from cfautent
           where k11_ipterm = ipterm
             and k11_instit = instit;
        
          if not idterm is null then
             select max(k12_autent)
               into codaut
               from corrente
              where k12_id     = idterm
                and k12_data   = dtemite
                and k12_instit = instit;
        
             if valor < 0 then
                estorno := true;
             else
                estorno := false;
             end if;
        
             if codaut is null then
                codaut := 1;
             else
                codaut := codaut + 1;
             end if;
        
             -- grava autenticacao
             hora := to_char(now(), 'HH24:MI');
             insert into corrente ( k12_id,
                                    k12_data,
                                    k12_autent,
                                    k12_hora,
                                    k12_conta,
                                    k12_valor,
                                    k12_estorn,
                                    k12_instit )
                           values ( idterm,
                                    dtemite,
                                    codaut,
                                    hora,
                                    conta,
                                    valor,
                                    estorno,
                                    instit );
             
             insert into coremp ( k12_id,
                                  k12_data,
                                  k12_autent,
                                  k12_empen,
                                  k12_cheque,
                                  k12_codord ) 
                         values ( idterm,
                                  dtemite,
                                  codaut,
                                  numemp,
                                  cheque,
                                  codord );
              
             if  iCodCorGrupo > 0 then
        
                 insert into corgrupocorrente ( k105_sequencial,
                                                k105_corgrupo,
                                                k105_data,
                                                k105_autent,
                                                k105_id,
                                                k105_corgrupotipo ) 
                                       values ( nextval('corgrupocorrente_k105_sequencial_seq'),
                                                iCodCorGrupo,
                                                dtemite,
                                                codaut,
                                                idterm,
                                                iCodTipoGrupo );
               
             end if;
            
             if contarp != 0 then
             
               insert into corlanc ( k12_id,
                                     k12_data,
                                     k12_autent,
                                     k12_conta,
                                     k12_codigo ) 
                            values ( idterm,
                                     dtemite,
                                     codaut,
                                     contarp,
                                     0);
                 
             end if;
        
             if codche > 0 then
                if valor > 0 then
                
                   insert into corconf (k12_id,
                                        k12_data,
                                        k12_autent,
                                        k12_codmov,
                                                k12_ativo)
                                values ( idterm,
                                         dtemite,
                                         codaut,
                                         codche,
                                               true );
                else
                   update corconf set k12_ativo = false where k12_codmov = codche;
          
                       insert into corconf ( k12_id,
                                         k12_data,
                                         k12_autent,
                                         k12_codmov,
                                             k12_ativo )
                                       values ( idterm,
                                         dtemite,
                                         codaut,
                                         codche,
                                                false );
          
                end if;
             end if;
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             if codmov is not null and codmov > 0 and valor > 0 then
             
                insert into corempagemov ( k12_sequencial,
                                           k12_id,
                                           k12_data,
                                           k12_autent,
                                           k12_codmov )
                                  values ( nextval('corempagemov_k12_sequencial_seq'),
                                           idterm,
                                           dtemite,
                                           codaut,
                                           codmov );
                 
             elsif valor < 0 then
              
                /* consultamos o tipo do movimento.
                 * caso é um estorno de retencao verificamos se o usuario 
                 * escolheu o valor total do movimento 
                 */
                if iCodTipoGrupo = 5 then 
         
                   nValorRetencao := fc_valorretencaomov(codmov, true);
                   if nValorRetencao = abs(valor) then
        
                      select k12_sequencial
                        into codmovimento
                        from coremp
                             join corempagemov     on corempagemov.k12_id     = coremp.k12_id
                                                  and corempagemov.k12_data   = coremp.k12_data
                                                  and corempagemov.k12_autent = coremp.k12_autent
                             join corgrupocorrente on corempagemov.k12_id     = k105_id
                                                  and corempagemov.k12_data   = k105_data
                                                  and corempagemov.k12_autent = k105_autent
                       where coremp.k12_codord = codord
                         and corempagemov.k12_codmov = codmov
                         and k105_corgrupotipo = 2;
             
                      delete from corempagemov where k12_sequencial  =  codmovimento;
        
                   end if;
                  
                else        
        
                   select k12_sequencial
                     into codmovimento
                     from coremp
                          join corempagemov     on corempagemov.k12_id     = coremp.k12_id
                                               and corempagemov.k12_data   = coremp.k12_data
                                               and corempagemov.k12_autent = coremp.k12_autent
                          join corgrupocorrente on corempagemov.k12_id     = k105_id
                                               and corempagemov.k12_data   = k105_data
                                               and corempagemov.k12_autent = k105_autent
                    where coremp.k12_codord       = codord
                      and corempagemov.k12_codmov = codmov
                      and k105_corgrupotipo = 1;
        
                   delete from corempagemov where k12_sequencial = codmovimento ;
                
                end if;
             end if;
          else
             -- erro quando o terminal nao esta cadastrado
             return '2 terminal nao cadastrado';
          end if; 
          
          -- autenticacao correta
          if valor < 0 then
             sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'-';
        
             -- Gera Ligacao do Movimento da Agenda com a Autenticacao
             insert into
               corempagemovestorno (
                 k12_sequencial,
                 k12_id,
                 k12_data,
                 k12_autent,
                 k12_codmov
               ) values (
                 nextval('corempagemovestorno_k12_sequencial_seq'),
                 idterm,
                 dtemite,
                 codaut,
                 codmov
               );
          else
             sAutenticacao := '1' || to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(numemp,'99999999999') || to_char(abs(valor),'999999999.99')||'+';
          end if;
          /**
           * salva a autenticao na tabela corautent
           */
          insert into corautent (k12_id, 
                                 k12_data, 
                                 k12_autent, 
                                 k12_codautent)
                         values (idterm,
                                 dtemite,
                                 codaut, 
                                 sAutenticacao
                                );
        
          return sAutenticacao;
        
        end;
        $$ 
        language 'plpgsql';

SQL
);

    }
}
