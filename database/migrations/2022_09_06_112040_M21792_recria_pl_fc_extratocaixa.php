<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21792RecriaPlFcExtratocaixa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         * Realizadas as seguintes modificações:
         * Identacao do sql;
         * Quando informada a conta bancária é verificado se trata-se de uma conta unica;
         * Se for conta unica retorna os dados consolidados, ou seja, não é realizado a validação da instituição; 
         * Se não for informada a conta bancária ou não for conta unica, ira verificar a instituição passada por parametro
         * na função.
         */
        
        $sql = <<<SQL

drop function fc_extratocaixa(integer,integer,date,date,boolean);
drop   type tp_extratocaixa;
create type tp_extratocaixa as ( riCaixa        integer,
                                 riAutent       integer,
                                 riData         date,   
                                 rnValorDebito  numeric,
                                 riValorCredito numeric,
                                 riReceita      integer,
                                 riCheque       integer,
                                 rtCredor       varchar,
																 riEmpenho      integer,
																 riOrdem        integer,
																 riPlanilha     integer,
																 riSlip         integer,
                                 rtDetalhe      text,   
                                 rbErro         boolean);

create or replace function fc_extratocaixa(integer,integer,date,date,boolean) returns setof tp_extratocaixa as
$$
declare

    iInstit        alias for $1;
    iConta         alias for $2;
	dDataini       alias for $3;
	dDatafim       alias for $4;
    bRaise         alias for $5;

	sV             varchar default '';
    sReduz         text    default '';
    tSql           text    default '';
    tSqlReduzidos  text    default '';
    tWhereCorrente text    default ' where 1=1 ';
    tWhereCorlanc  text    default ' where 1=1 ';
    lContaUnica    boolean default false;

    iAnoUsu        integer;

    rExtratoCaixa  record;
    rReduz         record;

    rtp_extratocaixa tp_extratocaixa%ROWTYPE;

begin
     
	if bRaise then 
		raise notice ' Instit : % Conta : % Data : % ',iInstit,iConta,dDataini;
	end if;
    
    rtp_extratocaixa.riCaixa        := 0;
    rtp_extratocaixa.riAutent       := 0;
    rtp_extratocaixa.riData         := null;
    rtp_extratocaixa.rnValorDebito  := 0;
    rtp_extratocaixa.riValorCredito := 0;
    rtp_extratocaixa.riReceita      := 0;
    rtp_extratocaixa.riCheque       := 0;
    rtp_extratocaixa.rtCredor       := '';
	rtp_extratocaixa.riEmpenho      := 0;
	rtp_extratocaixa.riOrdem        := 0;
	rtp_extratocaixa.riPlanilha     := 0;
	rtp_extratocaixa.riSlip         := 0;
    rtp_extratocaixa.rtDetalhe      := '';		
    rtp_extratocaixa.rbErro         := false;

    iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);

    if iAnoUsu is null then
     raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
    end if;

    if iConta is not null then
    
      select db83_contaunica into lContaUnica 
        from contabancaria 
       where contabancaria.db83_sequencial = iConta;  
       
      if bRaise then  
        raise notice ' lContaUnica : %',lContaUnica;  
      end if;
    
      tSqlReduzidos = 'select distinct c61_reduz 
                        from contabancaria 
                             inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial 
                             inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon 
                                                             and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu
                        where contabancaria.db83_sequencial = '||iConta;
      if lContaUnica is false then 
        tSqlReduzidos := tSqlReduzidos||' and conplanoreduz.c61_instit = '||iInstit;
      end if;
    
      for rReduz in execute tSqlReduzidos
      loop
    
        sReduz := sReduz||sV||rReduz.c61_reduz;
        sV     := ',';
    
      end loop;
    
      if sReduz = null or sReduz = '' then
        return ;
      end if;
    
      tWhereCorrente := tWhereCorrente|| ' and corrente.k12_conta in ('||sReduz||')';
      tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_conta  in ('||sReduz||')';
    
    end if;
    
    if iInstit is not null and lContaUnica is false then
       tWhereCorrente := tWhereCorrente|| ' and corrente.k12_instit = '||iInstit;
    end if;
    if dDataini is not null and dDatafim is not null then
       tWhereCorrente := tWhereCorrente|| ' and corrente.k12_data between '||quote_literal(dDataini)||' and '||quote_literal(dDatafim);
       tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_data  between '||quote_literal(dDataini)||' and '||quote_literal(dDatafim);
    elsif dDataini is not null and dDatafim is null then
       tWhereCorrente := tWhereCorrente|| ' and corrente.k12_data >= '||quote_literal(dDataini);
       tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_data >= '||quote_literal(dDataini);
    elsif dDatafim is not null and dDataini is null then
       tWhereCorrente := tWhereCorrente|| ' and corrente.k12_data <= '||quote_literal(dDataini);
       tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_data <= '||quote_literal(dDataini);
    end if;

	--	empenhos- despesa orçamentaria
    tSql := 'select corrente.k12_id           as caixa,
                    corrente.k12_data         as data,
					corrente.k12_autent       as autent,
					case
                      when corrente.k12_estorn is true
                        then
                          case
                            when corrente.k12_valor < 0
                              then corrente.k12_valor * -1
                            else corrente.k12_valor
                          end
                      else 0
                    end as valor_debito,
                    case
                      when corrente.k12_estorn is false
                        then corrente.k12_valor
                      else 0
                    end as valor_credito,
                    0                         as receita,
                    coremp.k12_cheque::text   as cheque,
                    z01_nome::text            as credor,
                    k12_empen                 as empenho,
                    k12_codord                as ordem,
                    0                         as planilha,
                    0                         as slip,
                    \'OP-\'||k12_codord||\'#Número empenho-\'||k12_empen||\'#Historico-\'||coalesce(e60_resumo,\'\')::text as detalhe
               from corrente
                    inner join coremp     on coremp.k12_id = corrente.k12_id
                                         and coremp.k12_data = corrente.k12_data
                                         and coremp.k12_autent = corrente.k12_autent
                    inner join empempenho on e60_numemp = coremp.k12_empen
                    inner join cgm        on z01_numcgm = e60_numcgm
                    left join corhist     on corhist.k12_id = corrente.k12_id
                                         and corhist.k12_data = corrente.k12_data
                                         and corhist.k12_autent = corrente.k12_autent '|| tWhereCorrente ;
		-- receitas
    tSql := tSql||'	union all
                   select caixa,
                          data,
                          autent,
                          valor_debito,
                          valor_credito,
                          receita,
                          cheque,
                          credor,
                          0 as empenho,
                          0 as ordem,
                          planilha,
                          0 as slip,
                          detalhe
                     from ( select caixa,
                                   data,
                                   autent,
                                   sum(valor_debito) as valor_debito,
                                   valor_credito,
                                   receita,
                                   historico::text,
                                   cheque::text,
                                   credor::text,
                                   planilha,
                                   detalhe
                              from ( select corrente.k12_id        as caixa,
                                            corrente.k12_data      as data,
                                            corrente.k12_autent    as autent,
                                            case
								              when corrente.k12_estorn is false
								                then cornump.k12_valor
								              else 0
								            end as valor_debito,
								            case
								              when corrente.k12_estorn is true
                                                then abs(cornump.k12_valor)
									          else 0
									        end as valor_credito,
											cornump.k12_receit     as receita,
											coalesce(k81_codpla,0) as planilha,
											(\'RECEITA: \'||tabrec.k02_drecei||\',Historico:\'||coalesce(corhist.k12_histcor,\'.\'))::text as historico,
											null::text         as cheque,
											(select z01_nome::text from arrepaga inner join cgm on z01_numcgm = k00_numcgm where k00_numpre=cornump.k12_numpre limit 1 ) as credor,
											case 
                                              when placaixarec is not null 
                                                then \'Planilha-\'||k81_codpla
                                                     ||\'#Receita-\'||k02_codigo
                                                     ||\'(\'||k02_descr||\') #Historico-\'||coalesce(k12_histcor,\'\')
                                              when corcla.k12_codcla is not null
                                                then \'Classificação-\'||corcla.k12_codcla||\'#Arquivo-\'
                                                    ||disarq.arqret
                                                    ||\'#Receita-\'||k02_codigo
                                                    ||\'(\'||k02_descr||\') #Historico-\'||coalesce(k12_histcor,\'\')
                                                    ||\'#Código arrecadação-\'||cornump.k12_numpre||\'/\'||cornump.k12_numpar
                                                    ||\'#Data de crédito-\'
                                                    ||(select case 
                                                                when dtcredito is not null 
                                                                  then to_char(dtcredito, \'DD\/MM\/YYYY\') 
                                                                else \' \' 
                                                              end as dtcredito 
                                                         from disbanco 
                                                        where disbanco.codret = disarq.codret limit 1)
                                                    ||\'#Datas pagamento-\'
                                                    ||(select array_to_string(array_accum(to_char(dtpago, \'DD\/MM\/YYYY\')),\',\')
                                                         from ( select distinct
                                                                       dtpago
                                                                  from disbanco
                                                                 where codret = disarq.codret
                                                                 order by dtpago limit 5 ) as abc )
											  else
												\'Receita-\'||k02_codigo||\'(\'||k02_descr||\') #Historico-\'||coalesce(k12_histcor,\'\')
                                                ||\'#Código arrecadação-\'||cornump.k12_numpre||\'/\'||cornump.k12_numpar
											end as detalhe
                                       from corrente
                                            inner join cornump     on cornump.k12_id         = corrente.k12_id
                                                                  and cornump.k12_data       = corrente.k12_data
                                                                  and cornump.k12_autent     = corrente.k12_autent
                                            left  join corplacaixa on corplacaixa.k82_id     = cornump.k12_id
                                                                  and corplacaixa.k82_data   = cornump.k12_data
                                                                  and corplacaixa.k82_autent = cornump.k12_autent
                                            left  join placaixarec on corplacaixa.k82_seqpla = placaixarec.k81_seqpla
                                            inner join tabrec      on tabrec.k02_codigo      = cornump.k12_receit
                                            left  join corhist     on corhist.k12_id         = corrente.k12_id
                                                                  and corhist.k12_data       = corrente.k12_data
                                                                  and corhist.k12_autent     = corrente.k12_autent
                                            left  join corcla      on corcla.k12_id          = corrente.k12_id
                                                                  and corcla.k12_data        = corrente.k12_data
                                                                  and corcla.k12_autent      = corrente.k12_autent
                                            left  join discla      on discla.codcla          = corcla.k12_codcla
                                            left  join disarq      on disarq.codret          = discla.codret
									        '||tWhereCorrente||' 
                                    ) as x
						      group by caixa,
                                       data,
                                       autent,
                                       valor_credito,
                                       historico,
                                       receita,
                                       cheque,
                                       credor,
                                       planilha,
                                       detalhe 
                           ) as xx ';

		/* transferencias a debito - entradas - corlanc */
		tSql := tSql||'	union all
		               select corrente.k12_id           as caixa,
					          corlanc.k12_data          as data,
					          corlanc.k12_autent        as autent,
					          case
					            when corrente.k12_estorn is false then
					              abs(corrente.k12_valor)
					            else 0
					          end as valor_debito,
					          case
                                when corrente.k12_estorn is true then
                                   abs(corrente.k12_valor)
                                else 0
                              end as valor_credito,
					          0                         as receita,
					          e91_cheque::text          as cheque,
					          z01_nome::text            as credor,
					          0                         as empenho,
					          0                         as ordem,
					          0                         as planilha,
					          slip.k17_codigo           as slip,
				              \'Slip-\'||slip.k17_codigo||\'#Histórico-\'||coalesce(slip.k17_texto,\'\')::text as detalhe
			             from corlanc
                              inner join corrente     on corrente.k12_id             = corlanc.k12_id
                                                     and corrente.k12_data           = corlanc.k12_data
                                                     and corrente.k12_autent         = corlanc.k12_autent
                              inner join slip         on slip.k17_codigo             = corlanc.k12_codigo
                              left join slipnum       on slipnum.k17_codigo          = slip.k17_codigo
                              left join cgm           on slipnum.k17_numcgm          = z01_numcgm
                              left join corconf       on corconf.k12_id              = corlanc.k12_id
                                                     and corconf.k12_ativo is true
                                                     and corconf.k12_data            = corlanc.k12_data
                                                     and corconf.k12_autent          = corlanc.k12_autent
                              left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
                                                     and e91_ativo is true
                                                     and corconf.k12_ativo is true
                              left join corhist       on corhist.k12_id              = corrente.k12_id
                                                     and corhist.k12_data            = corrente.k12_data
                                                     and corhist.k12_autent          = corrente.k12_autent
		                      '|| tWhereCorlanc ;

		/* transferencias a credito - saidas - corrente */
		tSql := tSql || ' union all
                         select corrente.k12_id           as caixa,
					            corlanc.k12_data          as data,
					            corlanc.k12_autent        as autent,
                                case
                                  when corrente.k12_estorn is true then
                                    abs(corrente.k12_valor)
                                  else 0
                                end as valor_debito,
                                case
                                  when corrente.k12_estorn is false then
                                    abs(corrente.k12_valor)
                                  else 0
                                end as valor_credito,
                                0                         as receita,
					            e91_cheque::text          as cheque,
					            z01_nome::text            as credor,
					            0                         as empenho,
					            0                         as ordem,
					            0                         as planilha,
					            slip.k17_codigo           as slip,
				                \'Slip-\'||slip.k17_codigo||\'#Histórico-\'||coalesce(slip.k17_texto,\'\')::text as detalhe
			               from corrente
                                inner join corlanc      on corrente.k12_id             = corlanc.k12_id
                                                       and corrente.k12_data           = corlanc.k12_data
                                                       and corrente.k12_autent         = corlanc.k12_autent
                                inner join slip         on slip.k17_codigo             = corlanc.k12_codigo
                                left join slipnum       on slipnum.k17_codigo          = slip.k17_codigo
                                left join cgm           on slipnum.k17_numcgm          = z01_numcgm
                                left join corconf       on corconf.k12_id              = corlanc.k12_id
                                                       and corconf.k12_data            = corlanc.k12_data
                                                       and corconf.k12_autent          = corlanc.k12_autent
                                                       and corconf.k12_ativo is true
                                left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
                                                       and e91_ativo is true
                                                       and corconf.k12_ativo is true
                                left join corhist       on corhist.k12_id              = corrente.k12_id
                                                       and corhist.k12_data            = corrente.k12_data
                                                       and corhist.k12_autent          = corrente.k12_autent
                                '|| tWhereCorrente ||' 
                          order by data, caixa, cheque ';

		if bRaise then
			raise notice 'SQL PRINCIPAL : % ',tSql;
		end if;

		for rExtratoCaixa in execute tSql loop

			rtp_extratocaixa.riCaixa        := rExtratoCaixa.caixa;
			rtp_extratocaixa.riAutent       := rExtratoCaixa.autent;
			rtp_extratocaixa.riData         := rExtratoCaixa.data;
			rtp_extratocaixa.rnValorDebito  := rExtratoCaixa.valor_debito;
			rtp_extratocaixa.riValorCredito := rExtratoCaixa.valor_credito;
			rtp_extratocaixa.riReceita      := rExtratoCaixa.receita;
			rtp_extratocaixa.riCheque       := rExtratoCaixa.cheque;
			rtp_extratocaixa.rtCredor       := rExtratoCaixa.credor;
			rtp_extratocaixa.riEmpenho      := rExtratoCaixa.empenho;
			rtp_extratocaixa.riOrdem        := rExtratoCaixa.ordem;
		    rtp_extratocaixa.riPlanilha     := rExtratoCaixa.planilha;
            rtp_extratocaixa.riSlip         := rExtratoCaixa.slip;
			rtp_extratocaixa.rtDetalhe      := rExtratoCaixa.detalhe;

 			return next rtp_extratocaixa;

		end loop;
--	
		return ;

end;
$$ language 'plpgsql';

SQL;
        
        DB::connection()->getPdo()->exec($sql);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Retorna a função como estava antes da modificação 
        $sql = <<<SQL

drop function fc_extratocaixa(integer,integer,date,date,boolean);
drop   type tp_extratocaixa;
create type tp_extratocaixa as ( riCaixa        integer,
                                 riAutent       integer,
                                 riData         date,   
                                 rnValorDebito  numeric,
                                 riValorCredito numeric,
                                 riReceita      integer,
                                 riCheque       integer,
                                 rtCredor       varchar,
																 riEmpenho      integer,
																 riOrdem        integer,
																 riPlanilha     integer,
																 riSlip         integer,
                                 rtDetalhe      text,   
                                 rbErro         boolean);

create or replace function fc_extratocaixa(integer,integer,date,date,boolean) returns setof tp_extratocaixa as
$$
declare

    iInstit        alias for $1;
    iConta         alias for $2;
		dDataini       alias for $3;
		dDatafim       alias for $4;
    bRaise         alias for $5;

		sV             varchar default '';
    sReduz         text    default '';
		tSql           text    default '';
    tWhereCorrente text    default ' where 1=1 ';
    tWhereCorlanc	 text    default ' where 1=1 ';

    iAnoUsu        integer;

    rExtratoCaixa  record;
    rReduz         record;

    rtp_extratocaixa tp_extratocaixa%ROWTYPE;

begin
     
		if bRaise then 
			raise notice ' Instit : % Conta : % Data : % ',iInstit,iConta,dDataini;
		end if;
    
    rtp_extratocaixa.riCaixa        := 0;
    rtp_extratocaixa.riAutent       := 0;
    rtp_extratocaixa.riData         := null;
    rtp_extratocaixa.rnValorDebito  := 0;
    rtp_extratocaixa.riValorCredito := 0;
    rtp_extratocaixa.riReceita      := 0;
    rtp_extratocaixa.riCheque       := 0;
    rtp_extratocaixa.rtCredor       := '';
		rtp_extratocaixa.riEmpenho      := 0;
		rtp_extratocaixa.riOrdem        := 0;
		rtp_extratocaixa.riPlanilha     := 0;
		rtp_extratocaixa.riSlip         := 0;
    rtp_extratocaixa.rtDetalhe      := '';		
    rtp_extratocaixa.rbErro         := false;

    iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);

    if iAnoUsu is null then
     raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
    end if;
    
		if iConta is not null then

      for rReduz in select distinct c61_reduz 
                      from contabancaria 
                           inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial 
                           inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon 
                                                           and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu
                                                           and conplanoreduz.c61_instit = iInstit
                      where contabancaria.db83_sequencial = iConta
      loop

        sReduz := sReduz||sV||rReduz.c61_reduz;
        sV     := ',';

      end loop;


      if sReduz = null or sReduz = '' then
        return ;
      end if;

		  tWhereCorrente := tWhereCorrente|| ' and corrente.k12_conta in ('||sReduz||')';
			tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_conta  in ('||sReduz||')';

		end if;
		if iInstit is not null then
		  tWhereCorrente := tWhereCorrente|| ' and corrente.k12_instit = '||iInstit;
		end if;
		if dDataini is not null and dDatafim is not null then
		  tWhereCorrente := tWhereCorrente|| ' and corrente.k12_data between '||quote_literal(dDataini)||' and '||quote_literal(dDatafim);
			tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_data  between '||quote_literal(dDataini)||' and '||quote_literal(dDatafim);
		elsif dDataini is not null and dDatafim is null then
		  tWhereCorrente := tWhereCorrente|| ' and corrente.k12_data >= '||quote_literal(dDataini);
			tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_data >= '||quote_literal(dDataini);
		elsif dDatafim is not null and dDataini is null then
		  tWhereCorrente := tWhereCorrente|| ' and corrente.k12_data <= '||quote_literal(dDataini);
			tWhereCorlanc  := tWhereCorlanc||  ' and corlanc.k12_data <= '||quote_literal(dDataini);

		end if;


	--	empenhos- despesa orçamentaria
    tSql := '
		select corrente.k12_id           as caixa,
					 corrente.k12_data         as data,
					 corrente.k12_autent       as autent,
					 case
             when corrente.k12_estorn is true
               then
                 case
                   when corrente.k12_valor < 0
                     then corrente.k12_valor * -1
                   else corrente.k12_valor
                 end
             else 0
           end as valor_debito,
           case
             when corrente.k12_estorn is false
               then corrente.k12_valor
             else 0
           end as valor_credito,
					 0                         as receita,
					 coremp.k12_cheque::text   as cheque,
					 z01_nome::text            as credor,
					 k12_empen                 as empenho,
					 k12_codord                as ordem,
					 0                         as planilha,
					 0                         as slip,
					 \'OP-\'||k12_codord||\'#Número empenho-\'||k12_empen||\'#Historico-\'||coalesce(e60_resumo,\'\')::text as detalhe
			from corrente
					 inner join coremp     on coremp.k12_id = corrente.k12_id
																and coremp.k12_data = corrente.k12_data
																and coremp.k12_autent = corrente.k12_autent
					 inner join empempenho on e60_numemp = coremp.k12_empen

					 inner join cgm        on z01_numcgm = e60_numcgm
					 left join corhist     on corhist.k12_id = corrente.k12_id
																and corhist.k12_data = corrente.k12_data
																and corhist.k12_autent = corrente.k12_autent '|| tWhereCorrente ;

		-- receitas
    tSql := tSql||'	union all
			select caixa,
						 data,
						 autent,
						 valor_debito,
						 valor_credito,
						 receita,
						 cheque,
						 credor,
						 0 as empenho,
						 0 as ordem,
					   planilha,
					   0 as slip,
						 detalhe
				from ( select caixa,
											data,
											autent,
											sum(valor_debito) as valor_debito,
											valor_credito,
											receita,
											historico::text,
											cheque::text,
											credor::text,
											planilha,
         						  detalhe
								from ( select corrente.k12_id        as caixa,
															corrente.k12_data      as data,
															corrente.k12_autent    as autent,
                              case
									              when corrente.k12_estorn is false
									                then cornump.k12_valor
									              else 0
									            end as valor_debito,
									            case
									              when corrente.k12_estorn is true
                                  then abs(cornump.k12_valor)
									              else 0
									            end as valor_credito,
															cornump.k12_receit     as receita,
															coalesce(k81_codpla,0) as planilha,
															(\'RECEITA: \'||tabrec.k02_drecei||\',Historico:\'||coalesce(corhist.k12_histcor,\'.\'))::text as historico,
															null::text         as cheque,
															(select z01_nome::text from arrepaga inner join cgm on z01_numcgm = k00_numcgm where k00_numpre=cornump.k12_numpre limit 1 ) as credor,
															case when placaixarec is not null then
															  \'Planilha-\'||k81_codpla||\'#Receita-\'||k02_codigo||\'(\'||k02_descr||\') #Historico-\'||coalesce(k12_histcor,\'\')
                              WHEN corcla.k12_codcla is not null
                               then \'Classificação-\'||corcla.k12_codcla||\'#Arquivo-\'||disarq.arqret||\'#Receita-\'||k02_codigo||
                                    \'(\'||k02_descr||\') #Historico-\'||coalesce(k12_histcor,\'\')||
                                    \'#Código arrecadação-\'||cornump.k12_numpre||\'/\'||cornump.k12_numpar||
                                    \'#Data de crédito-\'||(select case
                                                                     when dtcredito is not null
                                                                       then to_char(dtcredito, \'DD\/MM\/YYYY\')
                                                                     else
                                                                      \' \'
                                                                   end as dtcredito
                                                              from disbanco
                                                             where disbanco.codret = disarq.codret
                                                             limit 1)||
                                    \'#Datas pagamento-\'||(select array_to_string(array_accum(to_char(dtpago, \'DD\/MM\/YYYY\')),\',\')
                                                              from ( select distinct
                                                                            dtpago
                                                                       from disbanco
                                                                      where codret = disarq.codret
                                                                      order by dtpago limit 5 ) as abc )
															else
															  \'Receita-\'||k02_codigo||\'(\'||k02_descr||\') #Historico-\'||coalesce(k12_histcor,\'\')||\'#Código arrecadação-\'||cornump.k12_numpre||\'/\'||cornump.k12_numpar
															end as detalhe
												 from corrente
															inner join cornump     on cornump.k12_id             = corrente.k12_id
																										and cornump.k12_data           = corrente.k12_data
																										and cornump.k12_autent         = corrente.k12_autent
															left  join corplacaixa on corplacaixa.k82_id         = cornump.k12_id
																										and corplacaixa.k82_data       = cornump.k12_data
																										and corplacaixa.k82_autent     = cornump.k12_autent
															left  join placaixarec on corplacaixa.k82_seqpla     = placaixarec.k81_seqpla
															inner join tabrec      on tabrec.k02_codigo          = cornump.k12_receit
															left  join corhist     on corhist.k12_id             = corrente.k12_id
																										and corhist.k12_data           = corrente.k12_data
																										and corhist.k12_autent         = corrente.k12_autent
                              left  join corcla       on corcla.k12_id          = corrente.k12_id
                                                     and corcla.k12_data        = corrente.k12_data
                                                     and corcla.k12_autent      = corrente.k12_autent
                              left  join discla       on discla.codcla          = corcla.k12_codcla
                              left  join disarq       on disarq.codret          = discla.codret

											  '||tWhereCorrente||' ) as x
						 group by caixa,
											data,
											autent,
											valor_credito,
											historico,
											receita,
											cheque,
											credor,
											planilha,
											detalhe ) as xx ';

		/* transferencias a debito - entradas - corlanc */
		tSql := tSql||'	union all
		select corrente.k12_id           as caixa,
					 corlanc.k12_data          as data,
					 corlanc.k12_autent        as autent,

					 case
					   when corrente.k12_estorn is false then
					     abs(corrente.k12_valor)
					   else 0
					 end as valor_debito,
					 case
             when corrente.k12_estorn is true then
               abs(corrente.k12_valor)
             else 0
           end as valor_credito,
					 0                         as receita,
					 e91_cheque::text          as cheque,
					 z01_nome::text            as credor,
					 0                         as empenho,
					 0                         as ordem,
					 0                         as planilha,
					 slip.k17_codigo           as slip,
				   \'Slip-\'||slip.k17_codigo||\'#Histórico-\'||coalesce(slip.k17_texto,\'\')::text as detalhe
			from corlanc
					 inner join corrente     on corrente.k12_id             = corlanc.k12_id
																	and corrente.k12_data           = corlanc.k12_data
																	and corrente.k12_autent         = corlanc.k12_autent
					 inner join slip         on slip.k17_codigo             = corlanc.k12_codigo
					 left join slipnum       on slipnum.k17_codigo          = slip.k17_codigo
					 left join cgm           on slipnum.k17_numcgm          = z01_numcgm
					 left join corconf       on corconf.k12_id              = corlanc.k12_id
																	and corconf.k12_ativo is true
																	and corconf.k12_data            = corlanc.k12_data
																	and corconf.k12_autent          = corlanc.k12_autent
					 left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
																	and e91_ativo is true
																	and corconf.k12_ativo is true
					 left join corhist       on corhist.k12_id              = corrente.k12_id
																	and corhist.k12_data            = corrente.k12_data
																	and corhist.k12_autent          = corrente.k12_autent
		'|| tWhereCorlanc ;

		/* transferencias a credito - saidas - corrente */
		tSql := tSql || ' union all

		select corrente.k12_id           as caixa,
					 corlanc.k12_data          as data,
					 corlanc.k12_autent        as autent,
           case
             when corrente.k12_estorn is true then
               abs(corrente.k12_valor)
             else 0
           end as valor_debito,
           case
             when corrente.k12_estorn is false then
               abs(corrente.k12_valor)
             else 0
           end as valor_credito,
           0                         as receita,
					 e91_cheque::text          as cheque,
					 z01_nome::text            as credor,
					 0                         as empenho,
					 0                         as ordem,
					 0                         as planilha,
					 slip.k17_codigo           as slip,
				   \'Slip-\'||slip.k17_codigo||\'#Histórico-\'||coalesce(slip.k17_texto,\'\')::text as detalhe
			from corrente
					 inner join corlanc      on corrente.k12_id             = corlanc.k12_id
																	and corrente.k12_data           = corlanc.k12_data
																	and corrente.k12_autent         = corlanc.k12_autent
					 inner join slip         on slip.k17_codigo             = corlanc.k12_codigo
					 left join slipnum       on slipnum.k17_codigo          = slip.k17_codigo
					 left join cgm           on slipnum.k17_numcgm          = z01_numcgm
					 left join corconf       on corconf.k12_id              = corlanc.k12_id
																	and corconf.k12_data            = corlanc.k12_data
																	and corconf.k12_autent          = corlanc.k12_autent
																	and corconf.k12_ativo is true
					 left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
																	and e91_ativo is true
																	and corconf.k12_ativo is true
					 left join corhist       on corhist.k12_id              = corrente.k12_id
																	and corhist.k12_data            = corrente.k12_data
																	and corhist.k12_autent          = corrente.k12_autent
    '|| tWhereCorrente ||' order by data, caixa, cheque ';

		if bRaise then
			raise notice 'SQL PRINCIPAL : % ',tSql;
		end if;

		for rExtratoCaixa in execute tSql loop

			rtp_extratocaixa.riCaixa        := rExtratoCaixa.caixa;
			rtp_extratocaixa.riAutent       := rExtratoCaixa.autent;
			rtp_extratocaixa.riData         := rExtratoCaixa.data;
			rtp_extratocaixa.rnValorDebito  := rExtratoCaixa.valor_debito;
			rtp_extratocaixa.riValorCredito := rExtratoCaixa.valor_credito;
			rtp_extratocaixa.riReceita      := rExtratoCaixa.receita;
			rtp_extratocaixa.riCheque       := rExtratoCaixa.cheque;
			rtp_extratocaixa.rtCredor       := rExtratoCaixa.credor;
			rtp_extratocaixa.riEmpenho      := rExtratoCaixa.empenho;
			rtp_extratocaixa.riOrdem        := rExtratoCaixa.ordem;
		  rtp_extratocaixa.riPlanilha     := rExtratoCaixa.planilha;
		  rtp_extratocaixa.riSlip         := rExtratoCaixa.slip;
			rtp_extratocaixa.rtDetalhe      := rExtratoCaixa.detalhe;

 			return next rtp_extratocaixa;

		end loop;
--	
		return ;

end;
$$ language 'plpgsql';

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
