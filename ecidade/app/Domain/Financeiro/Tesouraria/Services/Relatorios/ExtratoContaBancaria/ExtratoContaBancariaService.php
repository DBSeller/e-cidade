<?php

namespace App\Domain\Financeiro\Tesouraria\Services\Relatorios\ExtratoContaBancaria;

class ExtratoContaBancariaService
{
    
    protected $dataInicial;
    protected $dataFinal;
    protected $instit;
    protected $ano;
    protected $contaBancariaCodigo;
    protected $filtroSomenteContasBancarias;
    protected $filtroSomenteContasComMovimento;
    protected $filtroImprimeAnalitico;
    protected $filtroImprimeHistorico ;
    protected $filtroTotalizadorDiario;
    protected $filtroAgrupaPor;
    protected $filtroReceitasPor;
    protected $filtroPagempenhos;
    protected $filtroModeloRelatorio;
    protected $reduzido;

    public function setDataInicial($dataInicial)
    {
        $this->dataInicial = $dataInicial;
    }
    
    public function setDataFinal($dataFinal)
    {
        $this->dataFinal = $dataFinal;
    }

    public function setInstit($instit)
    {
        $this->instit = $instit;
    }
    public function setAno($ano)
    {
        $this->ano = $ano;
    }
    
    public function setContaBancariaCodigo($contaBancariaCodigo)
    {
        $this->contaBancariaCodigo = $contaBancariaCodigo;
    }

    public function setfiltroSomenteContasBancarias($filtroSomenteContasBancarias)
    {
        $this->filtroSomenteContasBancarias = $filtroSomenteContasBancarias;
    }

    public function setfiltroSomenteContasComMovimento($filtroSomenteContasComMovimento)
    {
        $this->filtroSomenteContasComMovimento = $filtroSomenteContasComMovimento;
    }

    public function setfiltroImprimeAnalitico($filtroImprimeAnalitico)
    {
        $this->filtroImprimeAnalitico = $filtroImprimeAnalitico;
    }

    public function setfiltroImprimeHistorico($filtroImprimeHistorico)
    {
        $this->filtroImprimeHistorico = $filtroImprimeHistorico;
    }


    public function setfiltroTotalizadorDiario($filtroTotalizadorDiario)
    {
        $this->filtroTotalizadorDiario = $filtroTotalizadorDiario;
    }

    public function setfiltroReceitasPor($filtroReceitasPor)
    {
        $this->filtroReceitasPor = $filtroReceitasPor;
    }

    public function setfiltroAgrupaPor($filtroAgrupaPor)
    {
        $this->filtroAgrupaPor = $filtroAgrupaPor;
    }

    public function setfiltroPagempenhos($filtroPagempenhos)
    {
        $this->filtroPagempenhos = $filtroPagempenhos;
    }

    public function setfiltroModeloRelatorio($filtroModeloRelatorio)
    {
        $this->filtroModeloRelatorio = $filtroModeloRelatorio;
    }

    public function setReduzido($reduzido)
    {
        $this->reduzido = $reduzido;
    }
    
    public function sqlContasMovimento()
    {
        $where = [];
        $orderby = " order by ";
        
        if (!empty($this->contaBancariaCodigo)) {
            $oDaoConPlanoContaBancaria = new \cl_conplanocontabancaria;
            $whereConPlanoContaBancaria = "c56_contabancaria = {$this->contaBancariaCodigo}";
            $whereConPlanoContaBancaria .= " and c56_anousu = ".db_getsession("DB_anousu");
            $sqlConPlanoContaBancaria = $oDaoConPlanoContaBancaria->sql_query_file(
                null,
                "c56_reduz",
                null,
                $whereConPlanoContaBancaria
            );

            $where[] = "c61_reduz in ({$sqlConPlanoContaBancaria})";
        }

        if ($this->filtroSomenteContasBancarias == 's') {
            $where[] = 'c60_codsis = 6';
        }

        $where = "where ".implode(' and ', $where);
        $orderby .= ' substr(k13_descr,1,3),k13_reduz ';

        $sql_contasmovimentacao = "
        select   
             k13_reduz,
             k13_descr,
             k13_dtimplantacao,
             c60_estrut,
             c60_codsis,
             c63_conta,
             substr(fc_saltessaldo,2,13)::float8 as anterior,
             substr(fc_saltessaldo,15,13)::float8 as debitado ,
             substr(fc_saltessaldo,28,13)::float8 as creditado,
             substr(fc_saltessaldo,41,13)::float8 as atual
          from (
             select 
                  k13_reduz,
                  k13_descr,
                  k13_dtimplantacao,
                  c60_estrut,
                  c60_codsis,
                  c63_conta,
                  fc_saltessaldo(k13_reduz,'" . $this->dataInicial . "','"
                  . $this->dataFinal . "',null, {$this->instit})
               from saltes
         inner join conplanoexe    on k13_reduz   = c62_reduz
                                  and c62_anousu  = {$this->ano}
         inner join conplanoreduz  on c61_anousu  = c62_anousu 
                                  and c61_reduz   = c62_reduz 
                                  and c61_instit  = {$this->instit}
         inner join conplano       on c60_codcon  = c61_codcon 
                                  and c60_anousu  = c61_anousu
         left  join conplanoconta  on c60_codcon  = c63_codcon
                                  and c63_anousu  = c60_anousu
                                  and c63_reduz   = c61_reduz
             $where
                ) as x $orderby";
                                     
        return $sql_contasmovimentacao;
    }

    public function sqlMovimentacaoConta()
    {
        $sqltotal  = "{$this->sqlEmpenho()}";
        $sqltotal .= " UNION ALL {$this->sqlAnalitico()}{$this->sqlSlip()}";
        $sqltotal .= " UNION ALL {$this->sqlTef()}";
        return $sqltotal;
    }
    
    public function sqlEmpenho()
    {
        $sqlempenho = "
            /* empenhos- despesa orçamentaria */
            /*   EMPENHO */

                select
                     corrente.k12_id as caixa,
                     corrente.k12_data as data,
          	         0 as valor_debito,
          	         corrente.k12_valor as valor_credito,
          	         'Pgto. Emp. '||e60_codemp||'/'||e60_anousu::text||' OP: '|| 
                       coremp.k12_codord::text as tipo_movimentacao,
                     e60_codemp||'/'||e60_anousu::text as codigo,
                     'empenho'::text as tipo,
                     0 as receita,
          	         null::text as receita_descr,
          	         corhist.k12_histcor::text as historico,
          	         coremp.k12_cheque::text as cheque,
          	         null::text as contrapartida,
          	         coremp.k12_codord as ordem,
          	         z01_nome::text as credor,
          	         z01_numcgm::text as numcgm,
          	         k12_codautent,
          	         k105_corgrupotipo,
          	         '' as codret,
          	         '' as dtretorno,
          	         '' as arqret,
          	         '' as dtarquivo 

                  from corrente
            inner join coremp           on coremp.k12_id        = corrente.k12_id 
                                       and coremp.k12_data      = corrente.k12_data
                                       and coremp.k12_autent    = corrente.k12_autent
            inner join empempenho       on e60_numemp           = coremp.k12_empen
            inner join cgm              on z01_numcgm           = e60_numcgm
                   /*
                     se habilitar o left abaixo e o empenho tiver mais de um cheque 
                     os registros ficam duplicados
                     left join empord on e82_codord = coremp.k12_codord
             	       left join empageconfche on e91_codcheque = e82_codmov
                   */
             left join corhist          on  corhist.k12_id      = corrente.k12_id    
                                       and corhist.k12_data     = corrente.k12_data  
                                       and corhist.k12_autent   = corrente.k12_autent
             left join corautent	    on corautent.k12_id     = corrente.k12_id   
                                       and corautent.k12_data   = corrente.k12_data
                                       and corautent.k12_autent = corrente.k12_autent
             left join corgrupocorrente on corrente.k12_data    = k105_data 
                                       and corrente.k12_id      = k105_id 
                                       and corrente.k12_autent  = k105_autent
                 where corrente.k12_conta = {$this->reduzido}  
                   and corrente.k12_data between '{$this->dataInicial}'
                   and '{$this->dataFinal}'
                   and corrente.k12_instit = {$this->instit}";
        
        return $sqlempenho;
    }

    public function sqlAnalitico()
    {

        $sqlanalitico = "
             /* RECIBO */
           
            select
                 caixa,
           		 data,
           		 valor_debito,
           		 valor_credito,
           		 tipo_movimentacao,
           		 codigo,
           		 tipo,
           		 receita,
                 receita_descr,
           		 historico,
           		 cheque,
           		 contrapartida,
                 ordem,
           		 credor,
           		 ''::text as numcgm,
           		 k12_codautent,
           		 0 as k105_corgrupotipo,
           		 '' as codret,
           		 '' as dtretorno,
           		 '' as arqret,
           		 '' as dtarquivo
	         from (
      	   select
	            caixa,
		        data,
		        sum(valor_debito) as valor_debito,
		        valor_credito,
		        tipo_movimentacao::text,
		        codigo::text,
		        tipo::text,
		        receita,
                receita_descr::text,
		        historico::text,
		        cheque::text,
		        null::text as contrapartida,
		        ordem,
		        credor::text,
		        k12_codautent
	        from (
                 select
	                  corrente.k12_id as caixa,
                      corrente.k12_data as data,
		              cornump.k12_valor as valor_debito,
		              0 as valor_credito,
	                  ('Recibo '||k12_numpre||'-'||k12_numpar)::text
	                  as tipo_movimentacao,
			          k12_numpre||'-'||k12_numpar::text as codigo,
                      'recibo'::text as tipo,
		              cornump.k12_receit as receita,
			          tabrec.k02_drecei::text as receita_descr,
			          (coalesce(corhist.k12_histcor,'.'))::text as historico,
			          null::text as cheque,
			          null::text as contrapartida,
			          e20_pagordem as ordem,
		              (select z01_nome::text from arrepaga 
                                       inner join cgm on z01_numcgm = k00_numcgm 
                                       where k00_numpre=cornump.k12_numpre limit 1 ) as credor,			  
                      k12_codautent

                   from corrente
             inner join cornump                      on cornump.k12_id        = corrente.k12_id 
                                                    and cornump.k12_data      = corrente.k12_data
                                                    and cornump.k12_autent    = corrente.k12_autent
              left join corgrupocorrente             on corrente.k12_id       = k105_id
                                                    and corrente.k12_autent   = k105_autent 
                                                    and corrente.k12_data     = k105_data
              left join retencaocorgrupocorrente     on e47_corgrupocorrente  = k105_sequencial
              left join retencaoreceitas             on e47_retencaoreceita   = e23_sequencial
              left join retencaopagordem             on e23_retencaopagordem  = e20_sequencial
             inner join tabrec                       on tabrec.k02_codigo     = cornump.k12_receit
		      left join corhist                      on  corhist.k12_id       = corrente.k12_id 
                                                    and corhist.k12_data      = corrente.k12_data
                                                    and corhist.k12_autent    = corrente.k12_autent
			  left join corautent	                 on corautent.k12_id      = corrente.k12_id 
                                                    and corautent.k12_data    = corrente.k12_data
		 			                                and corautent.k12_autent  = corrente.k12_autent
              left join corcla                       on corcla.k12_id         = corrente.k12_id 
                                                    and corcla.k12_data       = corrente.k12_data
                                                    and corcla.k12_autent     = corrente.k12_autent
              left join corplacaixa                  on corrente.k12_id       = k82_id 
                                                    and corrente.k12_data  = k82_data
                                                    and corrente.k12_autent   = k82_autent
	              where corrente.k12_conta = {$this->reduzido}
                    and (corrente.k12_data between '{$this->dataInicial}' and '{$this->dataFinal}')
		            and corrente.k12_instit = {$this->instit}
                    and k12_codcla is null
                    and k82_seqpla is null

              ) as x
	   group by
		     caixa,
		     data,
		     valor_credito,
		     tipo_movimentacao,
		     codigo,
		     tipo,
		     receita,
             receita_descr,
		     historico,
		     cheque,
		     contrapartida,
		     ordem,
		     credor,
             k12_codautent
             ) as xx

      /* PLANILHA */
      union all
	     select
              caixa,
              data,
      		  valor_debito,
		      valor_credito,
		      tipo_movimentacao,
		      codigo,
		      tipo,
		      receita,
		      receita_descr,
		      historico,
		      cheque,
              contrapartida,
		      ordem,
		      credor,
		      ''::text as numcgm,
		      k12_codautent,
			  0 as k105_corgrupotipo,
		      '' as codret,
			  '' as dtretorno,
			  '' as arqret,
			  '' as dtarquivo
	       from (
	           select
	                caixa,
		            data,
		            sum(valor_debito) as valor_debito,
		            valor_credito,
		            tipo_movimentacao::text,
		            codigo::text,
		            tipo::text,
		            receita,
		            receita_descr::text,
		            historico::text,
		            cheque::text,
		            null::text as contrapartida,
		            ordem,
		            credor::text	,
                    " . ($this->filtroImprimeAnalitico == 'a' ? 'k12_codautent' : 'NULL::text AS k12_codautent') . "
	            from (
                    select
	                     corrente.k12_id as caixa,
                         corrente.k12_data as data,
                         case when k12_valor > 0 then k12_valor else 0 end as valor_debito,
                         case when k12_valor < 0 then k12_valor else 0 end as valor_credito,
	                     ('planilha :'||k81_codpla)::text as tipo_movimentacao,
			             k81_codpla::text as codigo,
           	             'planilha'::text as tipo,
		   	             k81_receita as receita,
                         tabrec.k02_drecei as receita_descr,
		                 (coalesce(placaixarec.k81_obs,'.'))::text as historico,
		                 null::text as cheque,
			             null::text as contrapartida,
		                 0 as ordem,
			             null::text as credor ,
	                     k12_codautent
                      from corrente
			    inner join corplacaixa on k12_id               = k82_id  
                                      and k12_data             = k82_data
                                      and k12_autent           = k82_autent
                inner join placaixarec on k81_seqpla           = k82_seqpla
			    inner join tabrec      on tabrec.k02_codigo    = k81_receita
		                   /*
		                   left  join arrenumcgm on k00_numpre = cornump.k12_numpre
                           left join cgm on k00_numcgm = z01_numcgm
                           */
	             left join corhist     on corhist.k12_id       = corrente.k12_id     
                                      and corhist.k12_data     = corrente.k12_data
                                      and  corhist.k12_autent  = corrente.k12_autent
                inner join corautent   on corautent.k12_id     = corrente.k12_id 
                                      and corautent.k12_data   = corrente.k12_data
                                      and corautent.k12_autent = corrente.k12_autent
           			 where corrente.k12_conta = {$this->reduzido}  
                       and (corrente.k12_data between '{$this->dataInicial}' and '{$this->dataFinal}')
		               and corrente.k12_instit = {$this->instit}
                    ) as x
		    group by
		          caixa,
			      data,
			      valor_credito,
			      tipo_movimentacao,
			      codigo,
			      tipo,
			      receita,
			      receita_descr,
			      historico,
                  cheque,
	              contrapartida,
			      ordem,
			      credor,
			      k12_codautent
            )  as xx
            /* BAIXA DE BANCO */
           union all
              select
                   caixa,
		           data,
		           valor_debito,
		           valor_credito,
		           tipo_movimentacao,
		           codigo,
		           tipo,
		           receita,
                   receita_descr,
		           historico,
		           cheque,
		           contrapartida,
                   ordem,
		           credor,
		           ''::text as numcgm,
		           k12_codautent,
			       0 as k105_corgrupotipo,
		           codret::text,
			       dtretorno::text,
			       arqret::text,
			       dtarquivo::text
                from (
	               select
	                    caixa,
      		            data,
		                sum(valor_debito) as valor_debito,
		                valor_credito,
		                tipo_movimentacao::text,
		                codigo::text,
		                tipo::text,
		                receita,
                        receita_descr::text,
		                historico::text,
		                cheque::text,
		                null::text as contrapartida,
		                ordem,
		                credor::text,
		                k12_codautent,
		                codret,
			            dtretorno,
			            arqret,
			            dtarquivo
	                 from (
                        select
	                         corrente.k12_id as caixa,
                             corrente.k12_data as data,
		                     cornump.k12_valor as valor_debito,
		                     0 as valor_credito,
	                         ('Baixa da banco ')::text as tipo_movimentacao,
		                     discla.codret as codigo,
                             'Baixa'::text as tipo,
		                     cornump.k12_receit as receita,
		                     tabrec.k02_drecei::text as receita_descr,
		                     (coalesce(corhist.k12_histcor,'.'))::text as historico,
		                     null::text as cheque,
		                     null::text as contrapartida,
			                 0 as ordem,
			                 disarq.codret as codret,
			                 disarq.dtretorno as dtretorno,
			                 disarq.arqret as arqret,
			                 disarq.dtarquivo as dtarquivo,
		                     (select z01_nome::text from recibopaga 
                                              inner join cgm on z01_numcgm = k00_numcgm 
                                              where k00_numpre=cornump.k12_numpre limit 1 ) as credor,
                             k12_codautent
                          from corrente
                    inner join cornump     on cornump.k12_id          = corrente.k12_id 
                                          and cornump.k12_data        = corrente.k12_data
                                          and cornump.k12_autent      = corrente.k12_autent
                    inner join tabrec      on tabrec.k02_codigo       = cornump.k12_receit
	                        /*
                            left join arrenumcgm on k00_numpre = cornump.k12_numpre
                            left join cgm on k00_numcgm = z01_numcgm
                            */
	                 left join corhist     on  corhist.k12_id        = corrente.k12_id  
                                          and  corhist.k12_data      = corrente.k12_data
                                          and  corhist.k12_autent    = corrente.k12_autent
                     left join corautent   on corautent.k12_id       = corrente.k12_id
                                          and corautent.k12_data     = corrente.k12_data
                                          and corautent.k12_autent   = corrente.k12_autent    
		            inner join corcla      on corcla.k12_id          = corrente.k12_id  
                                          and corcla.k12_data        = corrente.k12_data
                                          and corcla.k12_autent      = corrente.k12_autent
                                       
                    inner join discla      on discla.codcla          = corcla.k12_codcla 
                                          and discla.instit          = {$this->instit}
           		    inner join disarq      on disarq.codret          = discla.codret 
                                          and disarq.instit          = discla.instit
                     left join corplacaixa on corplacaixa.k82_id     = corrente.k12_id
                                          and corplacaixa.k82_data   = corrente.k12_data
                                          and corplacaixa.k82_autent = corrente.k12_autent
			             where corrente.k12_conta = {$this->reduzido}
                           and (corrente.k12_data between '{$this->dataInicial}' 
                           and '{$this->dataFinal}')
                           and corrente.k12_instit = {$this->instit}
                           and corplacaixa.k82_id is null
                           and corplacaixa.k82_data is null
                           and corplacaixa.k82_autent is null
                        ) as x
		    group by
		          caixa,
		          data,
			      valor_credito,
			      tipo_movimentacao,
			      codigo,
			      tipo,
			      receita,
                  receita_descr,
			      historico,
			      cheque,
			      contrapartida,
			      ordem,
			      credor,
                  k12_codautent,
                  codret,
			      dtretorno,
			      arqret,
			      dtarquivo
                  ) as xx";

        return $sqlanalitico;
    }

    public function sqlSintetico($sqlanalitico)
    {

        $sqlsintetico = "
          union all
             select 
                  caixa,
                  data,
                  valor_debito,
                  valor_credito,
                  null::text as tipo_movimentacao,
                  codigo,
                  tipo,
                  0 as receita,
                  null::text as receita_descr,
                  historico,
                  cheque,
                  contrapartida,
                  ordem,
                  credor,
                  ''::text as numcgm,
                  k12_codautent,
                  0 as k105_corgrupotipo,
                  '' as codret,
        		  '' as dtretorno,
        		  '' as arqret,
        		  '' as dtarquivo
               from (
                  select 
                       caixa,
                       data,
                       sum(valor_debito) as valor_debito,
                       sum(valor_credito) as valor_credito,
                       codigo,
                       tipo,
                       historico,
                       cheque,
                       contrapartida,
                       ordem,
                       credor,
                       k12_codautent
                    from ({$sqlanalitico}) as agrupado
           	    group by
           		      caixa,
           		      data,
           	          codigo,
           		      tipo,
           		      historico,
           		      cheque,
           		      contrapartida,
           		      ordem,
           		      credor,
           		      k12_codautent
           	) as autent_recibo";
        
        return $sqlsintetico;
    }

    public function sqlSlip()
    {

        $sqlslip = "

 	       union all
	          /* transferencias a debito - entradas*/
	          select
	               corrente.k12_id as caixa,
	               corlanc.k12_data as data,
		           corrente.k12_valor as valor_debito,
		           0 as valor_credito,
		           'Slip '||k12_codigo::text as tipo_movimentacao,
		           k12_codigo::text as codigo,
		           'slip'::text as tipo,
		           0 as receita,
                   null::text as receita_descr,
		           slip.k17_texto::text as historico,
		           e91_cheque::text as cheque,
                   k17_debito||' - '||c60_descr as contrapartida,
		           0 as ordem,
		           z01_nome::text as credor,
		           z01_numcgm::text as numcgm,
                   k12_codautent,
                   0 as k105_corgrupotipo,
                   '' as codret,
	               '' as dtretorno,
	               '' as arqret,
	               '' as dtarquivo
	            from corlanc
	      inner join corrente      on corrente.k12_id             = corlanc.k12_id    
                                  and corrente.k12_data           = corlanc.k12_data  
                                  and corrente.k12_autent         = corlanc.k12_autent
          inner join slip          on slip.k17_codigo             = corlanc.k12_codigo
		  inner join conplanoreduz on c61_reduz                   = slip.k17_credito
                                  and c61_anousu                  = {$this->ano}
          inner join conplano      on c60_codcon                  = c61_codcon
                                  and c60_anousu                  = c61_anousu
		   left join slipnum       on slipnum.k17_codigo          = slip.k17_codigo
		   left join cgm           on slipnum.k17_numcgm          = z01_numcgm
		   left join corconf       on corconf.k12_id              = corlanc.k12_id 				
                                  and corconf.k12_data            = corlanc.k12_data 		
                                  and corconf.k12_autent          = corlanc.k12_autent 
                                  and corconf.k12_ativo is true
           left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov 
                                  and corconf.k12_ativo is true 
                                  and empageconfche.e91_ativo is true
           left join corhist       on   corhist.k12_id            = corrente.k12_id    
                                  and corhist.k12_data            = corrente.k12_data  
                                  and corhist.k12_autent          = corrente.k12_autent
		  left join corautent	   on corautent.k12_id            = corrente.k12_id
		  					      and corautent.k12_data          = corrente.k12_data
		  					      and corautent.k12_autent        = corrente.k12_autent
			   where corlanc.k12_conta = {$this->reduzido}  
                 and corlanc.k12_data between '{$this->dataInicial}' 
                 and '{$this->dataFinal}'
	       union all
               /* SLIP CREDITO */
	          select
	               corrente.k12_id as caixa,
	               corlanc.k12_data as data,
		           0 as valor_debito,
		           corrente.k12_valor as valor_credito,
		           'Slip '||k12_codigo::text as tipo_movimentacao,
		           k12_codigo::text as codigo,
		           'slip'::text as tipo,
		           0 as receita,
		           null::text as receita_descr,
		           slip.k17_texto::text as historico,
		           e91_cheque::text as cheque,
		           k17_debito||' - '||c60_descr as contrapartida,
		           0 as ordem,
		           z01_nome::text as credor,
		           z01_numcgm::text as numcgm,
                   k12_codautent,
                   0 as k105_corgrupotipo,
                   '' as codret,
			       '' as dtretorno,
			       '' as arqret,
			       '' as dtarquivo
	            from corrente
	      inner join corlanc         on corrente.k12_id             = corlanc.k12_id
                                    and corrente.k12_data           = corlanc.k12_data
                                    and corrente.k12_autent         = corlanc.k12_autent
		  inner join slip            on  slip.k17_codigo            = corlanc.k12_codigo
          inner join conplanoreduz   on c61_reduz                   = slip.k17_debito
                                    and c61_anousu                  = {$this->ano}
          inner join conplano        on c60_codcon                  = c61_codcon
                                    and c60_anousu                  = c61_anousu
		   left join slipnum         on slipnum.k17_codigo          = slip.k17_codigo
		   left join cgm             on slipnum.k17_numcgm          = z01_numcgm
		   left join corconf         on corconf.k12_id              = corlanc.k12_id
                                    and corconf.k12_data            = corlanc.k12_data
                                    and	corconf.k12_autent          = corlanc.k12_autent
                                    and corconf.k12_ativo is true
           left join empageconfche   on empageconfche.e91_codcheque = corconf.k12_codmov
          							and	corconf.k12_ativo is true
          							and empageconfche.e91_ativo is true
	       left join corhist         on corhist.k12_id              = corrente.k12_id
                                    and corhist.k12_data            = corrente.k12_data
                                    and corhist.k12_autent          = corrente.k12_autent
           left join corautent	     on corautent.k12_id            = corrente.k12_id
		  					        and corautent.k12_data          = corrente.k12_data
		  					        and corautent.k12_autent        = corrente.k12_autent

	           where corrente.k12_conta = {$this->reduzido}  
                 and corrente.k12_data between '{$this->dataInicial}' 
                 and '{$this->dataFinal}'";

        return $sqlslip ;
    }

    public function sqlTef()
    {

        $sqlTef = "
        
            SELECT 
                 corrente.k12_id AS caixa,
                 corlanc.k12_data AS DATA,
                 corrente.k12_valor AS valor_debito,
                 0 AS valor_credito,
                 '' ||  k12_codigo::text AS tipo_movimentacao,
                 k12_codigo::text AS codigo,
                 '' :: text AS tipo,
                 0 AS receita,
                 NULL::text AS receita_descr,
                 k12_histcor::text AS historico,
                 ''::text AS cheque,
                 corrente.k12_conta ||' - '||c60_descr AS contrapartida,
                 0 AS ordem,
                 'Retorno TEF'::text AS credor,
                 ''::text AS numcgm,
                 k12_codautent,
                 0 AS k105_corgrupotipo,
                 '' AS codret,
                 '' AS dtretorno,
                 '' AS arqret,
                 '' AS dtarquivo

             FROM corrente
       INNER JOIN corlanc           ON corrente.k12_id      = corlanc.k12_id
                                   AND corrente.k12_data    = corlanc.k12_data
                                   AND corrente.k12_autent  = corlanc.k12_autent
       INNER JOIN conplanoreduz     ON c61_reduz            = corrente.k12_conta
                                   AND c61_anousu           = {$this->ano}
       INNER JOIN conplano          ON c60_codcon           = c61_codcon
                                   AND c60_anousu           = c61_anousu   
        LEFT JOIN corhist           ON corhist.k12_id       = corrente.k12_id
                                   AND corhist.k12_data     = corrente.k12_data
                                   AND corhist.k12_autent   = corrente.k12_autent
        LEFT JOIN corautent         ON corautent.k12_id     = corrente.k12_id
                                   AND corautent.k12_data   = corrente.k12_data
                                   AND corautent.k12_autent = corrente.k12_autent 
       INNER JOIN conlancamcorrente ON corrente.k12_id      = c86_id
                                   AND corrente.k12_data    = c86_data
                                   AND corrente.k12_autent  = c86_autent
       INNER JOIN conlancamdoc      ON   c86_conlancam      = c71_codlan
                                   AND c71_coddoc           = 169
            WHERE corrente.k12_conta = {$this->reduzido}  
              AND corrente.k12_data between '{$this->dataInicial}' and '{$this->dataFinal}'

         ORDER BY 
               DATA,
               caixa,
               k12_codautent,
               codigo";
    
        return $sqlTef;
    }

    public function getReduzidoReceita($receita)
    {
        $sqlReduzidoReceita = "
               select c61_reduz
                 from taborc
           inner join orcreceita    on o70_codrec = taborc.k02_codrec 
                                   and o70_anousu = k02_anousu 
                                   and o70_instit = {$this->instit}
           inner join conplanoreduz on c61_codcon = o70_codfon 
                                   and c61_instit = o70_instit 
                                   and c61_anousu = o70_anousu
                where  k02_codigo = {$receita}
                  and  k02_anousu = {$this->ano}

                union
                    select c61_reduz
                      from tabplan
                inner join conplanoreduz on c61_reduz  = k02_reduz 
                                        and c61_instit = {$this->instit} 
                                        and c61_anousu = k02_anousu
                      where k02_codigo = {$receita}
                        and k02_anousu = {$this->ano}";


        return $sqlReduzidoReceita;
    }
}
