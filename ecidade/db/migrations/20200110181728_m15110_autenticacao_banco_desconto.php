<?php

use Classes\PostgresMigration;

class M15110AutenticacaoBancoDesconto extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL
  /**
   * Trigger que alimenta a tabela cornumpdesconto que e uma copia da tabela cornump. A unica diferenca é que mudamos
   * o codigo da receit para a conta do plano orcamentario.
   */
  create or replace function fc_cornumpdesconto()
  returns trigger
  as $$
    declare
      
      iCodigoReceitaDesconto integer default 0;
      iInstit                integer default 0;
      iAnousu                integer default 0;
      nValorMultiplicar      integer default 1;
      nValorDesconto         float8 default 0;
      rDadosDesconto         record;

      receitaExtraOrcamentaria integer default 0;
  
      lUsePcasp              boolean default false;
      
  begin
    
    raise info 'Debug dentro da trigger !!! ';

    lUsePcasp := cast( fc_getsession('DB_use_pcasp') as boolean );
    
    if lUsePcasp is null then
      raise exception 'Variavel de sessao [DB_use_pcasp] nao declarada.';
    end if; 
  
    if lUsePcasp is false then
      return new;
    end if;
    
    iInstit := cast( fc_getsession('DB_instit') as integer );
    if iInstit is null then
      raise exception 'Variavel de sessao [DB_instit] nao declarada.';
    end if;
  
    iAnousu := cast( fc_getsession('DB_anousu') as integer );
    if iAnousu is null then
      raise exception 'Variavel de sessao [DB_anousu] nao declarada.';
    end if;
    
    select case when k12_estorn is true then -1 else 1 end
      into nValorMultiplicar
      from corrente 
     where corrente.k12_id       = new.k12_id    
       and corrente.k12_data     = new.k12_data  
       and corrente.k12_autent   = new.k12_autent;
       
    /**
     * Pagamento de carne ( cornump.k00_numnov = 0 ) via caixa
     *   neste caso buscamos o valor do desconto do arrecant
     */
    for rDadosDesconto in select sum(arrecant.k00_valor) as k00_valor,
                                 cornump.k12_id,                                 
                                 cornump.k12_data,  
                                 cornump.k12_autent,
                                 cornump.k12_numpre,
                                 cornump.k12_numpar,
                                 cornump.k12_receit
                            from cornump 
                                 inner join arrecant on arrecant.k00_numpre = cornump.k12_numpre 
                                                    and arrecant.k00_numpar = cornump.k12_numpar
                                                    and arrecant.k00_receit = cornump.k12_receit
                                 left  join corcla   on corcla.k12_id       = cornump.k12_id    
                                                    and corcla.k12_data     = cornump.k12_data  
                                                    and corcla.k12_autent   = cornump.k12_autent                                                  
                           where corcla.k12_id is null 
                             and cornump.k12_id     = new.k12_id                               
                             and cornump.k12_data   = new.k12_data                    
                             and cornump.k12_autent = new.k12_autent
                             and cornump.k12_numpre = new.k12_numpre
                             and cornump.k12_numpar = new.k12_numpar
                             and cornump.k12_receit = new.k12_receit
                             and cornump.k12_numnov = 0
                             and arrecant.k00_valor < 0
                           group by cornump.k12_id,                                 
                                    cornump.k12_data,  
                                    cornump.k12_autent,
                                    cornump.k12_numpre,
                                    cornump.k12_numpar,
                                    cornump.k12_receit
    loop
    
    raise info 'Debug dentro primeiro for pagamento de carne via caixa !!! ';
    if rDadosDesconto.k00_valor = 0 then
      continue;
    end if;

    select k02_codigo
    into receitaExtraOrcamentaria
    from tabplan
    where k02_codigo = new.k12_receit;

    if (receitaExtraOrcamentaria is not null) then
        continue;
    end if;

         insert into cornumpdesconto (k12_id,
                                      k12_data,
                                      k12_autent,
                                      k12_numpre,
                                      k12_numpar,
                                      k12_numtot,
                                      k12_numdig,
                                      k12_receit,
                                      k12_valor,
                                      k12_numnov,
                                      k12_receitaprincipal) 
                              values (new.k12_id,
                                      new.k12_data,
                                      new.k12_autent,
                                      new.k12_numpre,
                                      new.k12_numpar,
                                      new.k12_numtot,
                                      coalesce(new.k12_numdig,0)::integer,
                                      fc_buscaReceitaDeducao(new.k12_receit, iAnousu, iInstit),
                                      round(rDadosDesconto.k00_valor*nValorMultiplicar, 2),
                                      new.k12_numnov,
                                      new.k12_receit);
    end loop;
  
    
    /**
     * Pagamento de recibo por caixa
     *   neste caso buscamos o valor do desconto da recibopaga (numpre, numpar, receita e numnov do cornump)
     *   pois nem sempre termos o desconto no arrecant (caso de descontos por regra)
     */  
    if not found then
    
    for rDadosDesconto in select sum(recibopaga.k00_valor) as k00_valor,
                                 recibopaga.k00_numnov,
                                 recibopaga.k00_numpre,
                                 recibopaga.k00_numpar,
                                 recibopaga.k00_receit
                            from recibopaga
                           where recibopaga.k00_numpre = new.k12_numpre 
                             and recibopaga.k00_numpar = new.k12_numpar
                             and recibopaga.k00_receit = new.k12_receit
                             and recibopaga.k00_numnov = new.k12_numnov
                             and recibopaga.k00_valor < 0
                           group by recibopaga.k00_numpre,
                                    recibopaga.k00_numpar,
                                    recibopaga.k00_numnov,
                                    recibopaga.k00_receit
  
    loop                                

      if rDadosDesconto.k00_valor is null or rDadosDesconto.k00_valor = 0 then
        continue;
      end if;

    raise info 'Debug dentro segundo for pagamento de recibo via caixa !!! ';

      select k02_codigo
      into receitaExtraOrcamentaria
      from tabplan
      where k02_codigo = new.k12_receit;

      if (receitaExtraOrcamentaria is not null) then
          continue;
      end if;

           insert into cornumpdesconto (k12_id,
                                 k12_data,
                                 k12_autent,
                                 k12_numpre,
                                 k12_numpar,
                                 k12_numtot,
                                 k12_numdig,
                                 k12_receit,
                                 k12_valor,
                                 k12_numnov,
                                 k12_receitaprincipal) 
                         values (new.k12_id,
                                 new.k12_data,
                                 new.k12_autent,
                                 new.k12_numpre,
                                 new.k12_numpar,
                                 new.k12_numtot,
                                 coalesce(new.k12_numdig,0)::integer,
                                 fc_buscaReceitaDeducao(new.k12_receit, iAnousu, iInstit),
                                 round(rDadosDesconto.k00_valor*nValorMultiplicar, 2),
                                 new.k12_numnov,
                                 new.k12_receit);
  
    end loop;
    
    end if;
  
    /**
     * Pagamentos via baixa de banco
     */
   perform 1
      from corcla 
     where corcla.k12_id     = new.k12_id                               
       and corcla.k12_data   = new.k12_data                    
       and corcla.k12_autent = new.k12_autent 
     limit 1; 
     
    if found then
    
     for rDadosDesconto in  select sum(k00_valor) as k00_valor,
                                   x.k12_id,                                 
                                   x.k12_data,  
                                   x.k12_autent,
                                   x.k12_numpre,
                                   x.k12_numpar,
                                   x.k12_numnov,
                                   x.k12_receit
                              from (  
                              /*select sum(recibopaga.k00_valor) as k00_valor,
                                            cornump.k12_id,                                 
                                            cornump.k12_data,  
                                            cornump.k12_autent,
                                            cornump.k12_numpre,
                                            cornump.k12_numpar,
                                            cornump.k12_numnov,
                                            cornump.k12_receit
                                       from cornump 
                                            inner join corcla   on corcla.k12_id    = cornump.k12_id    
                                                              and corcla.k12_data   = cornump.k12_data  
                                                              and corcla.k12_autent = cornump.k12_autent
                                           inner join disrec   on disrec.codcla     = corcla.k12_codcla
                                           inner join disbanco   on disbanco.idret  = disrec.idret
                                           inner join arreidret  on arreidret.idret = disbanco.idret
                                           inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre                                                               
                                                                and arreidret.k00_numpar  = recibopaga.k00_numpar
                                                                and recibopaga.k00_receit = new.k12_receit
                                      where cornump.k12_id     = new.k12_id                               
                                        and cornump.k12_data   = new.k12_data                    
                                        and cornump.k12_autent = new.k12_autent
                                        and cornump.k12_receit = new.k12_receit
                                        and recibopaga.k00_valor < 0
                                      group by cornump.k12_id,                                 
                                               cornump.k12_data,  
                                               cornump.k12_autent,
                                               cornump.k12_numpre,
                                               cornump.k12_numpar,
                                               cornump.k12_numnov,
                                               cornump.k12_receit */
                                      select sum(k00_valor)  as k00_valor,
                                             new.k12_id,
                                             new.k12_data,
                                             new.k12_autent,
                                             new.k12_numpre,
                                             new.k12_numpar,
                                             new.k12_numnov,
                                             new.k12_receit
                                        from recibopaga
                                       where k00_numnov in ( select distinct
                                                                   k00_numpre
                                                              from corcla   
                                                                   inner join disrec   on disrec.codcla     = corcla.k12_codcla
                                                                   inner join disbanco on disbanco.idret  = disrec.idret
                                                             where corcla.k12_id     = new.k12_id
                                                               and corcla.k12_data   = new.k12_data
                                                               and corcla.k12_autent = new.k12_autent )
                                         and recibopaga.k00_receit  = new.k12_receit
                                         and recibopaga.k00_valor   < 0
                                        
                                      union all
                                      
                                      select sum(arrecant.k00_valor) as k00_valor,
                                             cornump.k12_id,                                 
                                             cornump.k12_data,  
                                             cornump.k12_autent,
                                             cornump.k12_numpre,
                                             cornump.k12_numpar,
                                             0    as k12_numnov,
                                             cornump.k12_receit
                                        from cornump 
                                             inner join corcla   on corcla.k12_id     = cornump.k12_id    
                                                                and corcla.k12_data   = cornump.k12_data  
                                                                and corcla.k12_autent = cornump.k12_autent
                                             inner join disrec   on disrec.codcla     = corcla.k12_codcla
                                             inner join disbanco on disbanco.idret    = disrec.idret
                                             inner join arrecant on arrecant.k00_numpre = disbanco.k00_numpre
                                                                and arrecant.k00_numpar = disbanco.k00_numpar
                                                                and arrecant.k00_receit = new.k12_receit
                                       where cornump.k12_id     = new.k12_id                               
                                         and cornump.k12_data   = new.k12_data                    
                                         and cornump.k12_autent = new.k12_autent
                                         and cornump.k12_receit = new.k12_receit
                                         and cornump.k12_receit = new.k12_receit
                                         and arrecant.k00_valor < 0
                                       group by cornump.k12_id,                                 
                                                cornump.k12_data,  
                                                cornump.k12_autent,
                                                cornump.k12_numpre,
                                                cornump.k12_numpar,
                                                cornump.k12_numnov,
                                                cornump.k12_receit
                                        ) as x
                                      group by x.k12_id,                                 
                                               x.k12_data,  
                                               x.k12_autent,
                                               x.k12_numpre,
                                               x.k12_numpar,
                                               x.k12_numnov,
                                               x.k12_receit 
     loop
      
      if rDadosDesconto.k00_valor is null or rDadosDesconto.k00_valor = 0 then
        continue;
      end if;

    raise info 'Debug dentro terceiro for pagamento de recibo e carne via baixa de banco !!! valor % -- fator % receita : % ',rDadosDesconto.k00_valor,
                                                                                                                              nValorMultiplicar,
                                                                                                                              new.k12_receit;

      select k02_codigo
        into receitaExtraOrcamentaria
        from tabplan
       where k02_codigo = new.k12_receit;

      if (receitaExtraOrcamentaria is not null) then
          continue;
      end if;
      
        insert into cornumpdesconto (k12_id,
                             k12_data,
                             k12_autent,
                             k12_numpre,
                             k12_numpar,
                             k12_numtot,
                             k12_numdig,
                             k12_receit,
                             k12_valor,
                             k12_numnov,
                             k12_receitaprincipal) 
                     values (new.k12_id,
                             new.k12_data,
                             new.k12_autent,
                             new.k12_numpre,
                             new.k12_numpar,
                             new.k12_numtot,
                             coalesce(new.k12_numdig,0)::integer,
                             fc_buscaReceitaDeducao(new.k12_receit, iAnousu, iInstit),
                             round(rDadosDesconto.k00_valor*nValorMultiplicar, 2),
                             new.k12_numnov,
                             new.k12_receit);
     end loop;  
    end if;
  
    /**
     * Pagamento de carne de quota unica via caixa
     */
  
    if not found then
    
    for rDadosDesconto in  select k00_numpre,
                                  k00_numpar,
                                  k00_receit,
                                  sum(k00_valor) as k00_valor, 
                                  (select sum(k00_valor) 
                                     from arrecant 
                                   where arrecant.k00_numpre = arrepaga.k00_numpre 
                                     and arrecant.k00_numpar = arrepaga.k00_numpar 
                                     and arrecant.k00_receit = arrepaga.k00_receit ) as valor_original  
                             from arrepaga 
                                  inner join cornump c  on c.k12_numpre = arrepaga.k00_numpre 
                                                       and c.k12_numpar = arrepaga.k00_numpar 
                                                       and c.k12_receit = arrepaga.k00_receit 
                            where arrepaga.k00_numpre = new.k12_numpre                               
                              and arrepaga.k00_numpar = new.k12_numpar                    
                              and arrepaga.k00_receit = new.k12_receit
                              and c.k12_id     = new.k12_id
                              and c.k12_data   = new.k12_data
                              and c.k12_autent = new.k12_autent
                              and arrepaga.k00_hist in (990) 
                            group by k00_numpre,                                   
                                     k00_numpar,
                                     k00_receit
    loop                                
  
      if rDadosDesconto.k00_valor is null or rDadosDesconto.valor_original is null then
        continue;
      end if;
      
      nValorDesconto := ( ( rDadosDesconto.valor_original - rDadosDesconto.k00_valor ) * -1);
    
      raise info 'Debug dentro quarto for pagamento de carne de unica via caixa !!! Original : % Valor : % Desconto : % ',rDadosDesconto.valor_original, rDadosDesconto.k00_valor, nValorDesconto;
  
      if nValorDesconto >= 0 then
        continue;
      end if;

      select k02_codigo
      into receitaExtraOrcamentaria
      from tabplan
      where k02_codigo = new.k12_receit;

      if (receitaExtraOrcamentaria is not null) then
          continue;
      end if;
     
  
      insert into cornumpdesconto (k12_id,
                                 k12_data,
                                 k12_autent,
                                 k12_numpre,
                                 k12_numpar,
                                 k12_numtot,
                                 k12_numdig,
                                 k12_receit,
                                 k12_valor,
                                 k12_numnov,
                                 k12_receitaprincipal) 
                         values (new.k12_id,
                                 new.k12_data,
                                 new.k12_autent,
                                 new.k12_numpre,
                                 new.k12_numpar,
                                 new.k12_numtot,
                                 coalesce(new.k12_numdig,0)::integer,
                                 fc_buscaReceitaDeducao(new.k12_receit, iAnousu, iInstit),
                                 round(nValorDesconto*nValorMultiplicar, 2),
                                 new.k12_numnov, 
                                 new.k12_receit
                                 );
  
    end loop;
    
    end if;
     
    return new;
  end;
  $$ language 'plpgsql';
  
  /**
   * Funcao que busca o codigo da receita referente a conta de deducao.
   *   Quando nao existe essa deducao,e lancada uma exception
   */
  create or replace function fc_buscaReceitaDeducao(integer,integer,integer)
  returns integer
  as $$
    declare
    
      iCodigoReceita alias for $1;
      iAnousu        alias for $2;
      iInstit        alias for $3;
      
      iCodigoReceitaDeducao integer default 0;
      sEstruturalReceita    varchar;
      sEstruturalDeducao    varchar;
    
  begin
      
	    select k02_estorc 
	      into sEstruturalReceita
	      from taborc 
	     where k02_codigo = iCodigoReceita
	       and k02_anousu = iAnousu;
	       
	      sEstruturalDeducao := '9' || substr(sEstruturalReceita, 2, length(sEstruturalReceita));
	     
      select k164_taborcdeducao 
        into iCodigoReceitaDeducao 
        from taborcvinculodeducao
       where k164_taborcprincipal = iCodigoReceita
         and k164_anousu          = iAnousu ;
         
      if iCodigoReceitaDeducao is null then
        raise notice 'Receita: %', iCodigoReceita;
        raise exception 'Receita de deducao nao encontrada na tesouraria para o ano de %. Estrutural receita : %, estrutural receita deducao : %', iAnousu,sEstruturalReceita,sEstruturalDeducao;
      end if;
      return iCodigoReceitaDeducao;
  
  end;
  $$ language 'plpgsql';
  
  create or replace function fc_buscaReceitaDeducaoTabrec(integer,integer,integer)
  returns integer
  as $$
    declare
    
      iCodigoReceita alias for $1;
      iAnousu        alias for $2;
      iInstit        alias for $3;
      
      iCodigoReceitaDeducao integer;
      sEstruturalReceita    varchar;
      sEstruturalDeducao    varchar;
    
  begin
    
    /*
     * Buscamos o estrutural da receita
     */
    select conplanoorcamento.c60_estrut
      into sEstruturalReceita
      from tabrec
           inner join taborc                     on tabrec.k02_codigo     = taborc.k02_codigo
           inner join orcreceita                 on orcreceita.o70_codrec = taborc.k02_codrec                                                      
                                                and orcreceita.o70_anousu = taborc.k02_anousu                                                   
           inner join orcfontes                  on orcfontes.o57_codfon  =  orcreceita.o70_codfon                                              
                                                and orcfontes.o57_anousu  =  orcreceita.o70_anousu                                              
           inner join conplanoorcamento          on conplanoorcamento.c60_codcon = orcfontes.o57_codfon                                       
                                                and conplanoorcamento.c60_anousu = orcfontes.o57_anousu                      
           inner join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon    
                                                and conplanoconplanoorcamento.c72_anousu            = conplanoorcamento.c60_anousu              
           inner join conplano                   on conplano.c60_codcon = conplanoconplanoorcamento.c72_conplano                            
                                                and conplano.c60_anousu = conplanoconplanoorcamento.c72_anousu
           inner join conplanoreduz              on conplanoreduz.c61_codcon = conplano.c60_codcon 
                                                and conplanoreduz.c61_anousu = conplano.c60_anousu
     where tabrec.k02_codigo        = iCodigoReceita
       and conplanoreduz.c61_instit = iInstit
       and conplanoreduz.c61_anousu = iAnousu;
       
    sEstruturalDeducao := '9' || substr(sEstruturalReceita, 2, length(sEstruturalReceita));
  
    select tabrec.k02_codigo
      into iCodigoReceitaDeducao
      from tabrec
           inner join taborc                     on tabrec.k02_codigo     = taborc.k02_codigo
           inner join orcreceita                 on orcreceita.o70_codrec = taborc.k02_codrec                                                      
                                                and orcreceita.o70_anousu = taborc.k02_anousu                                                   
           inner join orcfontes                  on orcfontes.o57_codfon  =  orcreceita.o70_codfon                                              
                                                and orcfontes.o57_anousu  =  orcreceita.o70_anousu                                              
           inner join conplanoorcamento          on conplanoorcamento.c60_codcon = orcfontes.o57_codfon                                       
                                                and conplanoorcamento.c60_anousu = orcfontes.o57_anousu                      
           inner join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_conplanoorcamento = conplanoorcamento.c60_codcon    
                                                and conplanoconplanoorcamento.c72_anousu            = conplanoorcamento.c60_anousu              
           inner join conplano                   on conplano.c60_codcon = conplanoconplanoorcamento.c72_conplano                            
                                                and conplano.c60_anousu = conplanoconplanoorcamento.c72_anousu
           inner join conplanoreduz              on conplanoreduz.c61_codcon = conplano.c60_codcon 
                                                and conplanoreduz.c61_anousu = conplano.c60_anousu
     where conplanoorcamento.c60_estrut      = sEstruturalDeducao
       and conplanoreduz.c61_instit = iInstit
       and conplanoreduz.c61_anousu = iAnousu;
       
     if not found then
       return null;
     end if;
     
     return iCodigoReceitaDeducao;
  
  end;
  $$ language 'plpgsql';
  
  drop   trigger if exists tg_cornumpdesconto_inc on cornump;
  create trigger tg_cornumpdesconto_inc after INSERT on cornump for each row execute procedure fc_cornumpdesconto();

SQL;

        $this->execute($sql);

    }


    public function down()
    {

    }
}
