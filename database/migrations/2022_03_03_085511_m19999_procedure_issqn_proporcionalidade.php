<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19999ProcedureIssqnProporcionalidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
        create or replace function fc_issqn_proporcionalidade(numeric,char(1),date,date,integer,date) returns tp_issqn_proporcionalidade as
$$
declare

  nValorSemProporcionalidade    alias   for $1;
  sTipoProporcionalidade        alias   for $2;
  dInicioAtividade              alias   for $3;
  dFinalAtividade               alias   for $4;
  iAnousu                       alias   for $5;
  dDataBaixa                    alias   for $6;

  iMesFinal                     integer default 0;
  iMesInicio                    integer default 0;
  iMesBaixa                     integer default 0;
  iDiasDesdeInicio              integer default 0;
  iDiasAno                      integer default 0;
  iMesInicioCalculo             integer default 0;
  iMesFinalCalculo              integer default 0;
  iTrimestre                    integer default 0;

  dUltDiaAno                    date;
  dPriDiaAno                    date;
  dInicioCalculo                date;
  dFinalCalculo                 date;

  nValorProporcional            numeric default 0;

  lRaise                        boolean;

  rtp_issqn_proporcionalidade   tp_issqn_proporcionalidade%ROWTYPE;

begin

  lRaise     := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug('-- CALCULO DE PROPORCIONALIDADE --',lRaise,false,false);
  perform fc_debug('------------------------------------------------------------------------------------------------------------------',lRaise,false,false);
  perform fc_debug('Data de inicio (1) -- '||dInicioAtividade||' Data final -- '||dFinalAtividade,lRaise,false,false);

  if extract(year from dInicioAtividade)::integer < iAnousu then

    dInicioCalculo    := (iAnousu::varchar||'-01-01')::date;
    if extract(year from dFinalAtividade)::integer = iAnousu then
      dFinalCalculo     := coalesce(dFinalAtividade::date,  (iAnousu::varchar||'-12-31')::date);
    else
      dFinalCalculo     := (iAnousu::varchar||'-12-31')::date;
    end if;

  else

    dInicioCalculo    := coalesce(dInicioAtividade::date, (iAnousu::varchar||'-01-01')::date);
    dFinalCalculo     := coalesce(dFinalAtividade::date,  (iAnousu::varchar||'-12-31')::date);

  end if;
  -- perform fc_debug('Data de inicio (2) -- '||dInicioAtividade||' Data final -- '||dFinalAtividade,lRaise,false,false);
  perform fc_debug('Data de inicio (2) -- '||dInicioCalculo||' Data final -- '||dFinalCalculo,lRaise,false,false);

  dPriDiaAno        := dInicioCalculo;
  dUltDiaAno        := dFinalCalculo;

  iDiasAno          := ( (iAnousu::varchar||'-12-31')::date - (iAnousu::varchar||'-01-01')::date ) + 1;

  iMesInicioCalculo := extract(month from dInicioCalculo);

  -- antes da T56559 sempre a variavel iMesFinalCalculo era o mes da dFinalCalculo e agora testamos para fazer isso apenas
  -- se o ano do calculo ? o mesmo, e sen?o a variavel recebe 12, para que a proporcionalidade fique correta;
  -- ocorria erro por exemplo se a data inicial for 10/09/2011 e a data final fosse 10/09/2012 numa inscricao provisoria
  -- e o ano do c?lculo for 2011, o sistema calculava 1 mes ao inves de 4 de proporcionalidade, justamente porque
  -- diminuia 10 de 9 ao inves de 12 de 9 - digitado por Evandro em 03/11/2011;
  if extract(year from dFinalCalculo)::integer = iAnousu then
    iMesFinalCalculo  := extract(month from dFinalCalculo);
  else
    iMesFinalCalculo  := 12;
  end if;

  perform fc_debug('',lRaise,false,false);
  perform fc_debug('',lRaise,false,false);
  perform fc_debug('',lRaise,false,false);

  rtp_issqn_proporcionalidade.rnValorProporcional     := 0;
  rtp_issqn_proporcionalidade.rsTipoProporcionalidade := '';

  iMesInicio := extract( month from dInicioCalculo );

  if dFinalAtividade is not null then
    iMesFinal := extract( month from dFinalAtividade );
  end if;

  perform fc_debug('Valor sem proporcionalidade : '||nValorSemProporcionalidade||' Data de inicio das atividades : '||dInicioCalculo||' Data final para calculo : '||coalesce(dFinalAtividade,'1990-01-01')||' Exercicio do calculo : '||iAnousu);

  --
  -- Se tipo de proporcionalidade for quinzenal "Q"
  --
  if sTipoProporcionalidade = 'Q' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Quinzenal';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : QUINZENAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    if dDataBaixa is not null then

       if extract(day from dInicioCalculo) < 16 then
         iMesInicio := iMesInicioCalculo;
       else
         iMesInicio := iMesInicioCalculo+1;
       end if;

       if extract(day from dFinalAtividade) < 16 then
          iMesFinal := iMesFinalCalculo-1;
       else
          iMesFinal := iMesFinalCalculo;
       end if;

    else

      if extract(day from dInicioCalculo) < 16 then
        iMesInicio := iMesInicioCalculo;
      else
        iMesInicio := iMesInicioCalculo+1;
      end if;

      if extract(day from dFinalAtividade) < 16 then
        iMesFinal := iMesFinalCalculo+1;
      else
        iMesFinal := iMesFinalCalculo;
      end if;
    end if;

    perform fc_debug('Mes Inicial : '||iMesInicio||' Mes Final : '||iMesFinal||' Valor sem proporcionalidade : '||nValorSemProporcionalidade,lRaise,false,false);

    --
    -- Calculo de valor proporcional para proporcionalidade quinzenal :
    --     valorProporcional = ( valorIntegral / 12 ) * ( qtdMesesAtividade - mesDeInicio )
    --     OBS - na proporcionalidade quinzenal so considera o mes inicial e final se dia < 16
    --
    nValorProporcional = ( ( nValorSemProporcionalidade/12 ) * ( iMesFinal - iMesInicio + 1) )::numeric;

  --
  -- Se tipo de proporcionalidade for mensal "M"
  --
  elsif sTipoProporcionalidade = 'M' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Mensal';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : MENSAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('Valor atual : '||nValorSemProporcionalidade||' Mes de inicio : '||iMesInicio||' Inicio : '||dInicioCalculo ,lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    select extract (month from dInicioCalculo)
      into iMesInicio;

    iMesFinal := iMesFinalCalculo+1;

    perform fc_debug('Mes inicio -- '||iMesInicio||' Mes final -- '||iMesFinal,lRaise,false,false);

    --
    -- Calculo de valor proporcional para proporcionalidade mensal :
    --     valorProporcional = ( valorIntegral / 12 ) * ( qtdMesesAtividade - mesDeInicio )
    --
    nValorProporcional = nValorSemProporcionalidade/12 * (iMesFinal - iMesInicio)::numeric;

  --
  -- Se tipo de proporcionalidade for semestral "S"
  --
  elsif sTipoProporcionalidade = 'S' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Semestral';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : SEMESTRAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    --
    -- Se inicio das atividades da empresa no exercicio foi no segundo semestre divide valor integral por 2 se nao cobra integral
    --
    if extract (month from dInicioCalculo) > 6 then

      nValorProporcional = nValorSemProporcionalidade / 2;

    else

      if extract (month from dFinalCalculo) < 7 then
        nValorProporcional = nValorSemProporcionalidade / 2;
      else
        nValorProporcional = nValorSemProporcionalidade;
      end if;

    end if;

  --
  -- Se tipo de proporcionalidade for semestral "T"
  --
  elsif sTipoProporcionalidade = 'T' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Trimestral';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE: TRIMESTRAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    --
    -- Se inicio das atividades da empresa no exercicio foi no segundo semestre divide valor integral por 2 se nao cobra integral
    --
    select extract (quarter from dInicioCalculo) into iTrimestre;
    nValorProporcional = nValorSemProporcionalidade / 4 * (5-iTrimestre);

  --
  -- Se tipo de proporcionalidade for Diaria "D"
  --
  elsif sTipoProporcionalidade = 'D' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Diaria';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : DIARIA ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('Inicio : '||dInicioCalculo||' Valor original : '||nValorSemProporcionalidade,lRaise,false,false);

    --
    iDiasDesdeInicio = dFinalCalculo::date - dInicioCalculo::date + 1;

    perform fc_debug('Dias desde o inicio : '||iDiasDesdeInicio||' Dias ano : '||iDiasAno,lRaise,false,false);
    --
    -- Calculo de valor proporcional para proporcionalidade diaria :
    --     valorProporcional = ( valorIntegral / totalDiasDoAno ) * qtdDiasDeAtividade
    --
    nValorProporcional = ( ( nValorSemProporcionalidade / iDiasAno ) * ( iDiasDesdeInicio ) )::float8;

  end if;
  
  if dDataBaixa is not null then
    if extract(year from dDataBaixa)::integer < iAnousu then
    
      nValorProporcional = 0;
    end if;
  end if;

  perform fc_debug('Valor com proporcional calculado : '||nValorProporcional,lRaise,false,false);

  rtp_issqn_proporcionalidade.rnValorProporcional := coalesce(nValorProporcional,0)::numeric;

  return rtp_issqn_proporcionalidade;

end;
$$ language 'plpgsql';
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
        DB::statement(<<<SQL
         create or replace function fc_issqn_proporcionalidade(numeric,char(1),date,date,integer,date) returns tp_issqn_proporcionalidade as
$$
declare

  nValorSemProporcionalidade    alias   for $1;
  sTipoProporcionalidade        alias   for $2;
  dInicioAtividade              alias   for $3;
  dFinalAtividade               alias   for $4;
  iAnousu                       alias   for $5;
  dDataBaixa                    alias   for $6;

  iMesFinal                     integer default 0;
  iMesInicio                    integer default 0;
  iMesBaixa                     integer default 0;
  iDiasDesdeInicio              integer default 0;
  iDiasAno                      integer default 0;
  iMesInicioCalculo             integer default 0;
  iMesFinalCalculo              integer default 0;
  iTrimestre                    integer default 0;

  dUltDiaAno                    date;
  dPriDiaAno                    date;
  dInicioCalculo                date;
  dFinalCalculo                 date;

  nValorProporcional            numeric default 0;

  lRaise                        boolean;

  rtp_issqn_proporcionalidade   tp_issqn_proporcionalidade%ROWTYPE;

begin

  lRaise     := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug('-- CALCULO DE PROPORCIONALIDADE --',lRaise,false,false);
  perform fc_debug('------------------------------------------------------------------------------------------------------------------',lRaise,false,false);
  perform fc_debug('Data de inicio (1) -- '||dInicioAtividade||' Data final -- '||dFinalAtividade,lRaise,false,false);

  if extract(year from dInicioAtividade)::integer < iAnousu then

    dInicioCalculo    := (iAnousu::varchar||'-01-01')::date;
    if extract(year from dFinalAtividade)::integer = iAnousu then
      dFinalCalculo     := coalesce(dFinalAtividade::date,  (iAnousu::varchar||'-12-31')::date);
    else
      dFinalCalculo     := (iAnousu::varchar||'-12-31')::date;
    end if;

  else

    dInicioCalculo    := coalesce(dInicioAtividade::date, (iAnousu::varchar||'-01-01')::date);
    dFinalCalculo     := coalesce(dFinalAtividade::date,  (iAnousu::varchar||'-12-31')::date);

  end if;
  -- perform fc_debug('Data de inicio (2) -- '||dInicioAtividade||' Data final -- '||dFinalAtividade,lRaise,false,false);
  perform fc_debug('Data de inicio (2) -- '||dInicioCalculo||' Data final -- '||dFinalCalculo,lRaise,false,false);

  dPriDiaAno        := dInicioCalculo;
  dUltDiaAno        := dFinalCalculo;

  iDiasAno          := ( (iAnousu::varchar||'-12-31')::date - (iAnousu::varchar||'-01-01')::date ) + 1;

  iMesInicioCalculo := extract(month from dInicioCalculo);

  -- antes da T56559 sempre a variavel iMesFinalCalculo era o mes da dFinalCalculo e agora testamos para fazer isso apenas
  -- se o ano do calculo ? o mesmo, e sen?o a variavel recebe 12, para que a proporcionalidade fique correta;
  -- ocorria erro por exemplo se a data inicial for 10/09/2011 e a data final fosse 10/09/2012 numa inscricao provisoria
  -- e o ano do c?lculo for 2011, o sistema calculava 1 mes ao inves de 4 de proporcionalidade, justamente porque
  -- diminuia 10 de 9 ao inves de 12 de 9 - digitado por Evandro em 03/11/2011;
  if extract(year from dFinalCalculo)::integer = iAnousu then
    iMesFinalCalculo  := extract(month from dFinalCalculo);
  else
    iMesFinalCalculo  := 12;
  end if;

  perform fc_debug('',lRaise,false,false);
  perform fc_debug('',lRaise,false,false);
  perform fc_debug('',lRaise,false,false);

  rtp_issqn_proporcionalidade.rnValorProporcional     := 0;
  rtp_issqn_proporcionalidade.rsTipoProporcionalidade := '';

  iMesInicio := extract( month from dInicioCalculo );

  if dFinalAtividade is not null then
    iMesFinal := extract( month from dFinalAtividade );
  end if;

  perform fc_debug('Valor sem proporcionalidade : '||nValorSemProporcionalidade||' Data de inicio das atividades : '||dInicioCalculo||' Data final para calculo : '||coalesce(dFinalAtividade,'1990-01-01')||' Exercicio do calculo : '||iAnousu);

  --
  -- Se tipo de proporcionalidade for quinzenal "Q"
  --
  if sTipoProporcionalidade = 'Q' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Quinzenal';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : QUINZENAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    if dDataBaixa is not null then

       if extract(day from dInicioCalculo) < 16 then
         iMesInicio := iMesInicioCalculo;
       else
         iMesInicio := iMesInicioCalculo+1;
       end if;

       if extract(day from dFinalAtividade) < 16 then
          iMesFinal := iMesFinalCalculo-1;
       else
          iMesFinal := iMesFinalCalculo;
       end if;

    else

      if extract(day from dInicioCalculo) < 16 then
        iMesInicio := iMesInicioCalculo;
      else
        iMesInicio := iMesInicioCalculo+1;
      end if;

      if extract(day from dFinalAtividade) < 16 then
        iMesFinal := iMesFinalCalculo+1;
      else
        iMesFinal := iMesFinalCalculo;
      end if;
    end if;

    perform fc_debug('Mes Inicial : '||iMesInicio||' Mes Final : '||iMesFinal||' Valor sem proporcionalidade : '||nValorSemProporcionalidade,lRaise,false,false);

    --
    -- Calculo de valor proporcional para proporcionalidade quinzenal :
    --     valorProporcional = ( valorIntegral / 12 ) * ( qtdMesesAtividade - mesDeInicio )
    --     OBS - na proporcionalidade quinzenal so considera o mes inicial e final se dia < 16
    --
    nValorProporcional = ( ( nValorSemProporcionalidade/12 ) * ( iMesFinal - iMesInicio + 1) )::numeric;

  --
  -- Se tipo de proporcionalidade for mensal "M"
  --
  elsif sTipoProporcionalidade = 'M' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Mensal';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : MENSAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('Valor atual : '||nValorSemProporcionalidade||' Mes de inicio : '||iMesInicio||' Inicio : '||dInicioCalculo ,lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    select extract (month from dInicioCalculo)
      into iMesInicio;

    iMesFinal := iMesFinalCalculo+1;

    perform fc_debug('Mes inicio -- '||iMesInicio||' Mes final -- '||iMesFinal,lRaise,false,false);

    --
    -- Calculo de valor proporcional para proporcionalidade mensal :
    --     valorProporcional = ( valorIntegral / 12 ) * ( qtdMesesAtividade - mesDeInicio )
    --
    nValorProporcional = nValorSemProporcionalidade/12 * (iMesFinal - iMesInicio)::numeric;

  --
  -- Se tipo de proporcionalidade for semestral "S"
  --
  elsif sTipoProporcionalidade = 'S' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Semestral';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : SEMESTRAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    --
    -- Se inicio das atividades da empresa no exercicio foi no segundo semestre divide valor integral por 2 se nao cobra integral
    --
    if extract (month from dInicioCalculo) > 6 then

      nValorProporcional = nValorSemProporcionalidade / 2;

    else

      if extract (month from dFinalCalculo) < 7 then
        nValorProporcional = nValorSemProporcionalidade / 2;
      else
        nValorProporcional = nValorSemProporcionalidade;
      end if;

    end if;

  --
  -- Se tipo de proporcionalidade for semestral "T"
  --
  elsif sTipoProporcionalidade = 'T' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Trimestral';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE: TRIMESTRAL ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);

    --
    -- Se inicio das atividades da empresa no exercicio foi no segundo semestre divide valor integral por 2 se nao cobra integral
    --
    select extract (quarter from dInicioCalculo) into iTrimestre;
    nValorProporcional = nValorSemProporcionalidade / 4 * (5-iTrimestre);

  --
  -- Se tipo de proporcionalidade for Diaria "D"
  --
  elsif sTipoProporcionalidade = 'D' then

    rtp_issqn_proporcionalidade.rsTipoProporcionalidade := 'Diaria';

    perform fc_debug('',lRaise,false,false);
    perform fc_debug('TIPO DE PROPORCIONALIDADE : DIARIA ',lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('Inicio : '||dInicioCalculo||' Valor original : '||nValorSemProporcionalidade,lRaise,false,false);

    --
    iDiasDesdeInicio = dFinalCalculo::date - dInicioCalculo::date + 1;

    perform fc_debug('Dias desde o inicio : '||iDiasDesdeInicio||' Dias ano : '||iDiasAno,lRaise,false,false);
    --
    -- Calculo de valor proporcional para proporcionalidade diaria :
    --     valorProporcional = ( valorIntegral / totalDiasDoAno ) * qtdDiasDeAtividade
    --
    nValorProporcional = ( ( nValorSemProporcionalidade / iDiasAno ) * ( iDiasDesdeInicio ) )::float8;

  end if;
  
  perform fc_debug('Valor com proporcional calculado : '||nValorProporcional,lRaise,false,false);

  rtp_issqn_proporcionalidade.rnValorProporcional := coalesce(nValorProporcional,0)::numeric;

  return rtp_issqn_proporcionalidade;

end;
$$ language 'plpgsql';
SQL
);
    }
}
