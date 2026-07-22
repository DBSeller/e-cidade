<?php

use Classes\PostgresMigration;

class M16108EstornoPlanilha extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
-- 1 codigo da planilha
-- 2 data da autenticacao
-- 3 ip do terminal
-- 4 id do usuario que autenticou
--drop function fc_estornoplanilha(integer,date,varchar(20),integer);
create or replace function fc_estornoplanilha(integer,date,varchar(20),integer, boolean)
returns varchar
as
$$
declare
   planilha alias for $1;
   dtemite  alias for $2;
   ipterm   alias for $3;
   id_usuario alias for $4;
   lRetornaCodigoAutenticados alias for $5;

   sCodigosAutent varchar;
   sVirgula char(1);

   processa	boolean	;
   registro	record;
   japroc	date;

   iNumpre	integer;
   iSlip	integer;
   idterm	integer;
   codaut  	integer;
   hora 	varchar(5);
   instit	integer;
   ident1  char(1);
   ident2  char(1);
   ident3  char(1);

   estrut	varchar(20);
   valor 	float8;
   estorno	boolean;
   autenticacao  text;

   iInstit                  integer;
   iAnousu                  integer;

begin

  iInstit := cast(fc_getsession('DB_instit') as integer);
  iAnousu := cast(fc_getsession('DB_anousu') as integer);

  sCodigosAutent := '';
  sVirgula := '';

  processa := false;

 -- verifica se existe historico para lancar no arrehist as observacoes
  select k01_codigo
    into idterm
    from histcalc
   where k01_codigo = 1001;
  if idterm is null then
    return '6 historico (1001-estorna planilha) nao cadastrado nos historicos de arrecadacao.';
  end if;

  -- verifica se a planilha esta lancada
  select k80_codpla, k80_dtaut, k80_instit
    into idterm, japroc, instit
    from placaixa
   where k80_codpla = planilha
     and k80_instit = iInstit;
  if idterm is null then
    return '2 planilha nao cadastrada ou não pertence a esta instituição';
  end if;
  if japroc is null then
    return '3 planilha não autenticada. estorno não permitido ';
  end if;

 -- verifica de o terminal esta cadastrado
  select k11_id,k11_ident1,k11_ident2,k11_ident3
    into idterm,ident1,ident2,ident3
    from cfautent where k11_ipterm = ipterm and k11_instit = instit;
 --raise notice ''-%-'',idterm;

 if idterm is null then
    return '5 terminal nao cadastrado';
 end if;

 for registro in select *
                  from placaixa
	              	     inner join placaixarec on k80_codpla = k81_codpla
             		 where k80_codpla = planilha
  loop

    valor   := registro.k81_valor;
    estorno := true;

    if registro.k81_valor < 0 then

       -- nesse caso o valor ja foi estornado na planilha que foi selecionado
       -- como o valor esta estornado, estornando novamente deve ser gerada uma arrecadação

       estorno := false;

    end if;

    ---selecionamos o numpre gerado pela autenticacao
    select cornump.k12_numpre
      into iNumpre
      from corplacaixa
            inner join corrente    on corrente.k12_id       = k82_id
                                  and corrente.k12_data     = k82_data
                                  and corrente.k12_autent   = k82_autent
            inner join cornump     on corrente.k12_id       = cornump.k12_id
                                  and corrente.k12_data     = cornump.k12_data
                                  and corrente.k12_autent   = cornump.k12_autent
            inner join placaixarec on k82_seqpla            = k81_seqpla
            inner join placaixa    on k81_codpla            = k80_codpla
             left join taborc      on taborc.k02_codigo     = cornump.k12_receit
                                  and taborc.k02_anousu     = iAnousu
             left join orcreceita  on orcreceita.o70_codrec = taborc.k02_codrec
                                  and orcreceita.o70_anousu = taborc.k02_anousu
                                  and orcreceita.o70_anousu = iAnousu
     where k82_seqpla                                       = registro.k81_seqpla
       and k80_dtaut  is not null
       and (
             case when ( corrente.k12_estorn is false )
                    or ( corrente.k12_estorn is true and cast(substr(k02_estorc,1,1) as integer) = 9::integer )
                    or ( corrente.k12_estorn is true and corrente.k12_valor < 0 )
                  then true
                  else false
             end
           ) = true
     order by corrente.k12_data,corrente.k12_autent desc limit 1;

    --o numpre nao pode estar nulo.
    if iNumpre is null then
       return '6 codigo de arrecadacao  nao encontrado.';
    end if;

    /**
     * Verificamos o o numpre está vinculado a um slip.
     */
    select k112_slip
      into iSlip
      from cornump
           inner join slipcorrente on k112_data   = k12_data
                                  and k112_id     = k12_id
                                  and k112_autent = k12_autent
     where k112_ativo is true
       and k12_numpre = iNumpre;

    if iSlip is not null then
      return '7 planilha vinculada ao slip '|| iSlip;
    end if;


    -- proxima autenticacao
    select max(k12_autent)
    into codaut
    from corrente where k12_id = idterm and k12_data = dtemite;
    if codaut is null then
       codaut := 1;
    else
       codaut := codaut + 1;
    end if;

     -- grava autenticacao
    processa := true;
    hora     := to_char(now(), 'HH24:MI');
    insert into corrente values (
                     idterm,
		     dtemite,
		     codaut,
		     hora,
		     registro.k81_conta,
		     registro.k81_valor*-1,
		     estorno,
		     instit
		     );
    insert into cornump values (
                        idterm,
			dtemite,
			codaut,
			iNumpre,
			0,
			0,
			0,
			registro.k81_receita,
			registro.k81_valor*-1,
      iNumpre
			);
    if (length(registro.k81_obs) > 0) then

       insert into corhist  (k12_id,k12_data,k12_autent,k12_histcor)
                    values (
	  	      idterm,
		      dtemite,
		      codaut,
		      registro.k81_obs
		    );


       insert into arrehist (k00_numpre,
                             k00_numpar,
			                       k00_hist,
                   			     k00_dtoper,
                  			     k00_hora,
                  			     k00_id_usuario,
                  			     k00_histtxt,
                  			     k00_idhist)
                   values   (iNumpre,
		                         0,
                   			     1001,
                 		         dtemite,
                   			     hora,
                  			     id_usuario,
                  			     registro.k81_obs,
                  			     nextval('arrehist_k00_idhist_seq')
                            );
    end if;

    -- insere codigo da planilha - placaixarec

    insert
      into corplacaixa (k82_id,
                        k82_data,
                        k82_autent,
                        k82_seqpla
                       )
           values      (
                        idterm,
  	                    dtemite,
            	        codaut,
                  		registro.k81_seqpla
                       );

--insere no corautent
autenticacao:= to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(planilha,'99999999999') || to_char(abs(valor),'999999999.99')||'-';
    insert
      into corautent (k12_id,
                      k12_data,
                      k12_autent,
                      k12_codautent
                      )
           values      (
                        idterm,
  	                    dtemite,
            	        codaut,
                  		autenticacao
                       );



  sCodigosAutent := sCodigosAutent||sVirgula||codaut;
  sVirgula := ',';
   -- deletamos o registro da arrepaga.
   delete from arrepaga where k00_numpre = iNumpre;
end loop;

if processa then

   update placaixa set k80_dtaut = null where k80_codpla = planilha;

   autenticacao:= to_char(codaut,'999999') || dtemite || ident1 || ident2 || ident3 || to_char(planilha,'99999999999') || to_char(abs(valor),'999999999.99')||'-';

   if lRetornaCodigoAutenticados then
     return '1'||sCodigosAutent;
   end if;
   -- return ''1'' || autenticacao;
   return '1 processo concluido';
else
   return '2 nenhum registro processado';
end if;
end;
$$
language 'plpgsql';

SQL;

    $this->execute($sql);
        
    }


    public function down()
    {

    }

}
