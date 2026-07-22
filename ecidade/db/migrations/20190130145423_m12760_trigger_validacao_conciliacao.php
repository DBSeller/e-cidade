<?php

use Classes\PostgresMigration;

class M12760TriggerValidacaoConciliacao extends PostgresMigration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {


        $this->execute("

CREATE OR REPLACE function fc_valida_conciliacao() returns trigger 
as \$\$
DECLARE 
  iInstit            integer;
  iCodCli            integer; 
  iConcilia          integer;    
  iConta             integer;
  iReduz             integer;
  iAnoUsu            integer;
  iId                integer;
  iAutent            integer;

  dData              date;

  sMsgErro           text;
  
  sOperacao          varchar default upper(TG_OP);
  sTabela            varchar default upper(TG_RELNAME);

  rConciliacao       record;
  
  lRaise             boolean default false;
  
BEGIN
      
  iInstit := cast(fc_getsession('DB_instit') as integer);
  if iInstit is null then
    raise exception 'Instituicao nao definida na sessao!';
  end if;
  
  lRaise  := ( case when fc_getsession('DB_debugon') is null or fc_getsession('DB_debugon') = '' then false else true end );
  if lRaise is true then
    raise notice 'Validando inclusão data da autenticacao para não ser uma data conciliada e salva ...';
  end if; 

  iReduz  := new.k12_conta;   
  iAnoUsu := extract(year from new.k12_data);
  iId     := new.k12_id;
  iAutent := new.k12_autent;  
  dData   := new.k12_data;
   
  select db21_codcli
    into iCodCli
    from db_config
   where codigo = iInstit;

  select c56_contabancaria
    into iConta
    from conplanocontabancaria 
         inner join conplanoreduz on c61_codcon = c56_codcon 
                                 and c61_anousu = c56_anousu 
                                 and c61_reduz  = c56_reduz
   where c61_reduz  = iReduz
     and c61_anousu = iAnoUsu;
  if found then
  
    if lRaise is true then
      raise notice 'Conta: % Data: % ',iConta, dData;
    end if;  
    
    for rConciliacao in select k68_contabancaria, 
                               k68_data
                          from concilia
                         where k68_contabancaria = iConta
                           and k68_data > dData
                           and k68_conciliastatus = 2
                         order by k68_data
  
    loop
    
       sMsgErro = '';
       sMsgErro = sMsgErro || ' 2 - Esta autenticação não será permitida porque a conta bancária '||rConciliacao.k68_contabancaria;
       sMsgErro = sMsgErro || ' está fechada na conciliação nesta data.'; 
       sMsgErro = sMsgErro || ' A operação será permitida somente a partir de '||to_char(rConciliacao.k68_data, 'dd/mm/YYYY');
       
       if lRaise is true then
         raise notice '%',sMsgErro;
       end if;
       
       raise exception '%',sMsgErro;
       return old;
       
    end loop;  

  end if;  

  return new;
        
END;
\$\$
language 'plpgsql';
  
drop   trigger if exists tg_valida_conciliacao on corrente;
drop   trigger if exists tg_valida_conciliacao  on corlanc;
create trigger tg_vaslida_conciliacao after INSERT OR UPDATE on corrente for each row execute procedure fc_valida_conciliacao();
create trigger tg_valida_conciliacao after INSERT OR UPDATE on corlanc  for each row execute procedure fc_valida_conciliacao();
");

        $this->execute(
        <<<SQL
DROP VIEW vs_planocontas;

create view vs_planocontas as
        SELECT *
        FROM CONPLANO
     	 INNER JOIN CONSISTEMA             ON C60_CODSIS = C52_CODSIS
   	     INNER JOIN CONCLASS               ON C60_CODCLA = C51_CODCLA
			 LEFT JOIN CONPLANOREDUZ           ON C60_CODCON = C61_CODCON and C60_ANOUSU =C61_ANOUSU
			 LEFT  JOIN CONPLANOCONTA          ON c63_ANOUSU = C60_ANOUSU
																				and C61_REDUZ = C63_REDUZ
  	     LEFT JOIN CONPLANOEXE             ON C61_ANOUSU = C62_ANOUSU and C61_REDUZ  = C62_REDUZ
	     LEFT JOIN ORCTIPOREC              ON C61_CODIGO = O15_CODIGO	     
	     LEFT JOIN DB_CONFIG               ON CODIGO     = CONPLANOREDUZ.C61_INSTIT
SQL
        );
    }
}
