<?php

use Classes\PostgresMigration;

class M7816CorrecaoDiaUtil extends PostgresMigration
{
    public function up()
    {
        $sFuncao = <<<EOT
create or replace function fc_corre(integer,date,float8,date,int4,date) returns float8 as $$
DECLARE
  RECE_CORR               ALIAS FOR $1;
  DATA_VENC               ALIAS FOR $2;
  VALOR_B                 ALIAS FOR $3;
  DATA_HOJE               ALIAS FOR $4;
  SUBDIR                  ALIAS FOR $5;
  DTVENC                  ALIAS FOR $6;

  INFLATOR_CORRECAO       varchar(5);
  VALOR_RETORNO           float8;
  V_TABREC                RECORD;
  DT_VENC                 DATE;
    --tabrec%ROWTYPE;

  lRaise     boolean default false;
BEGIN

    lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );
  if lRaise is true then
    if trim(fc_getsession('db_debug')) <> '' then
      perform fc_debug('  <corre>  - INICIANDO PROCESSAMENTO... ',lRaise,false,false);
    else
      perform fc_debug('  <corre>  - INICIANDO PROCESSAMENTO... ',lRaise,true,false);
    end if;
  end if;

  VALOR_RETORNO := 0;

  if lRaise is true then
    perform fc_debug('  <corre>  - '                                    ,lRaise,false,false);
    perform fc_debug('  <corre>  - Parametros  '                        ,lRaise,false,false);
    perform fc_debug('  <corre>  - Receita ...............:'||RECE_CORR ,lRaise,false,false);
    perform fc_debug('  <corre>  - Data de Vencimento 1...:'||DATA_VENC ,lRaise,false,false);
    perform fc_debug('  <corre>  - Valor .................:'||VALOR_B   ,lRaise,false,false);
    perform fc_debug('  <corre>  - Data Corrente .........:'||DATA_HOJE ,lRaise,false,false);
    perform fc_debug('  <corre>  - Ano ...................:'||SUBDIR    ,lRaise,false,false);
    perform fc_debug('  <corre>  - Data de Vencimento 2 ..:'||DTVENC    ,lRaise,false,false);
  end if;

  IF DATA_VENC::date > DATA_HOJE::date THEN

    if lRaise is true then
      perform fc_debug('  <corre>  - Data de Vencimento1 ('||DATA_VENC||') é maior que a data corrente ('||DATA_HOJE||')',lRaise,false,false);
      perform fc_debug('  <corre>  - Retornando valor de correcao o proprio valor da receita: '||round(VALOR_B,2)        ,lRaise,false,false);
    END IF;

    RETURN round(VALOR_B,2);

  END IF;

  if lRaise is true then
    perform fc_debug('  <corre>  - Verificando o cadastro da receita '||RECE_CORR      ,lRaise,false,false);
  END IF;
  SELECT *
    INTO V_TABREC
    FROM TABREC
         inner join tabrecjm on tabrec.k02_codjm = tabrecjm.k02_codjm
   WHERE K02_CODIGO = RECE_CORR ;
  IF NOT FOUND THEN

    if lRaise is true then
      perform fc_debug('  <corre>  - Nao encontrou cadastro para a receita '||RECE_CORR,lRaise,false,false);
      perform fc_debug('  <corre>  - Retornando valor de correcao -1'                  ,lRaise,false,false);
    END IF;

    RETURN -1;

  END IF;


  if lRaise is true then
    perform fc_debug('  <corre>  - Verificando forma de correcao de acordo com o cadastro da receita (campo k02_corven)...',lRaise,false,false);
    perform fc_debug('  <corre>  - K02_corven = '||V_TABREC.K02_CORVEN                                                     ,lRaise,false,false);
  end if;
  IF V_TABREC.K02_CORVEN THEN

    DT_VENC := DTVENC;

  ELSE

    DT_VENC := DATA_VENC;

  END IF;

  DT_VENC := fc_proximo_dia_util(DT_VENC::date);

  if lRaise is true then
    perform fc_debug('  <corre>  - Data para vencimento que sera utilizada ..: '||DT_VENC          ,lRaise,false,false);
    perform fc_debug('  <corre>  - Inflator para correcao ...................: '||V_TABREC.K02_CORR,lRaise,false,false);
  end if;

  INFLATOR_CORRECAO := trim(V_TABREC.K02_CORR);
  IF INFLATOR_CORRECAO != 'REAL' THEN

    IF NOT VALOR_B ISNULL AND VALOR_B <> 0  THEN

      if lRaise is true then
        perform fc_debug('  <corre>  - Chamando a funcao fc_infla para calcular o valor da correcao...',lRaise,false,false);
        perform fc_debug('  <corre>  - '                                                               ,lRaise,false,false);
      end if;

      VALOR_RETORNO := fc_INFLA(INFLATOR_CORRECAO,round(VALOR_B,2),DT_VENC,DATA_HOJE);

      if lRaise is true then
        perform fc_debug('  <corre>  - '                                                                      ,lRaise,false,false);
        perform fc_debug('  <corre>  - Valor retornado da Funcao: '||VALOR_RETORNO                            ,lRaise,false,false);
        perform fc_debug('  <corre>  - Fim da chamada da funcao fc_infla para calcular o valor da correcao...',lRaise,false,false);
        perform fc_debug('  <corre>  - '                                                                      ,lRaise,false,false);
      end if;

    END IF;

  ELSE

    if lRaise is true then
      perform fc_debug('  <corre>  - Inflator REAL...'    ,lRaise,false,false);
      perform fc_debug('  <corre>  - Não calcula correcao',lRaise,false,false);
      perform fc_debug('  <corre>  - '                    ,lRaise,false,false);
    end if;
    VALOR_RETORNO := VALOR_B;

  END IF;

  IF VALOR_RETORNO = 0 THEN
    VALOR_RETORNO := VALOR_B;
  END IF;

  if lRaise is true then
    perform fc_debug('  <corre>  - Valor retornado de correcao ..:  '||VALOR_RETORNO||' = '||round(VALOR_RETORNO,2) ,lRaise,false,false);
  end if;

RETURN round(VALOR_RETORNO,2);
END;
$$ language 'plpgsql';

EOT;
        $this->execute($sFuncao);
    }

    public function down()
    {
        $sFuncao = <<<EOT
--drop function fc_corre(integer,date,float8,date,int4,date);
create or replace function fc_corre(integer,date,float8,date,int4,date) returns float8 as $$
DECLARE
  RECE_CORR               ALIAS FOR $1;
  DATA_VENC               ALIAS FOR $2;
  VALOR_B                 ALIAS FOR $3;
  DATA_HOJE               ALIAS FOR $4;
  SUBDIR                  ALIAS FOR $5;
  DTVENC                  ALIAS FOR $6;

  INFLATOR_CORRECAO       varchar(5);
  VALOR_RETORNO           float8;
  V_TABREC                RECORD;
  DT_VENC                 DATE;
    --tabrec%ROWTYPE;

  lRaise     boolean default false;
BEGIN

    lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );
  if lRaise is true then
    if trim(fc_getsession('db_debug')) <> '' then
      perform fc_debug('  <corre>  - INICIANDO PROCESSAMENTO... ',lRaise,false,false);
    else
      perform fc_debug('  <corre>  - INICIANDO PROCESSAMENTO... ',lRaise,true,false);
    end if;
  end if;

  VALOR_RETORNO := 0;

  if lRaise is true then
    perform fc_debug('  <corre>  - '                                    ,lRaise,false,false);
    perform fc_debug('  <corre>  - Parametros  '                        ,lRaise,false,false);
    perform fc_debug('  <corre>  - Receita ...............:'||RECE_CORR ,lRaise,false,false);
    perform fc_debug('  <corre>  - Data de Vencimento 1...:'||DATA_VENC ,lRaise,false,false);
    perform fc_debug('  <corre>  - Valor .................:'||VALOR_B   ,lRaise,false,false);
    perform fc_debug('  <corre>  - Data Corrente .........:'||DATA_HOJE ,lRaise,false,false);
    perform fc_debug('  <corre>  - Ano ...................:'||SUBDIR    ,lRaise,false,false);
    perform fc_debug('  <corre>  - Data de Vencimento 2 ..:'||DTVENC    ,lRaise,false,false);
  end if;

  IF DATA_VENC > DATA_HOJE THEN

    if lRaise is true then
      perform fc_debug('  <corre>  - Data de Vencimento1 ('||DATA_VENC||') é maior que a data corrente ('||DATA_HOJE||')',lRaise,false,false);
      perform fc_debug('  <corre>  - Retornando valor de correcao o proprio valor da receita: '||round(VALOR_B,2)        ,lRaise,false,false);
    END IF;

    RETURN round(VALOR_B,2);

  END IF;

  if lRaise is true then
    perform fc_debug('  <corre>  - Verificando o cadastro da receita '||RECE_CORR      ,lRaise,false,false);
  END IF;
  SELECT *
    INTO V_TABREC
    FROM TABREC
         inner join tabrecjm on tabrec.k02_codjm = tabrecjm.k02_codjm
   WHERE K02_CODIGO = RECE_CORR ;
  IF NOT FOUND THEN

    if lRaise is true then
      perform fc_debug('  <corre>  - Nao encontrou cadastro para a receita '||RECE_CORR,lRaise,false,false);
      perform fc_debug('  <corre>  - Retornando valor de correcao -1'                  ,lRaise,false,false);
    END IF;

    RETURN -1;

  END IF;


  if lRaise is true then
    perform fc_debug('  <corre>  - Verificando forma de correcao de acordo com o cadastro da receita (campo k02_corven)...',lRaise,false,false);
    perform fc_debug('  <corre>  - K02_corven = '||V_TABREC.K02_CORVEN                                                     ,lRaise,false,false);
  end if;
  IF V_TABREC.K02_CORVEN THEN

    DT_VENC := DTVENC;

  ELSE

    DT_VENC := DATA_VENC;

  END IF;

  if lRaise is true then
    perform fc_debug('  <corre>  - Data para vencimento que sera utilizada ..: '||DT_VENC          ,lRaise,false,false);
    perform fc_debug('  <corre>  - Inflator para correcao ...................: '||V_TABREC.K02_CORR,lRaise,false,false);
  end if;

  INFLATOR_CORRECAO := trim(V_TABREC.K02_CORR);
  IF INFLATOR_CORRECAO != 'REAL' THEN

    IF NOT VALOR_B ISNULL AND VALOR_B <> 0  THEN

      if lRaise is true then
        perform fc_debug('  <corre>  - Chamando a funcao fc_infla para calcular o valor da correcao...',lRaise,false,false);
        perform fc_debug('  <corre>  - '                                                               ,lRaise,false,false);
      end if;

      VALOR_RETORNO := fc_INFLA(INFLATOR_CORRECAO,round(VALOR_B,2),DT_VENC,DATA_HOJE);

      if lRaise is true then
        perform fc_debug('  <corre>  - '                                                                      ,lRaise,false,false);
        perform fc_debug('  <corre>  - Valor retornado da Funcao: '||VALOR_RETORNO                            ,lRaise,false,false);
        perform fc_debug('  <corre>  - Fim da chamada da funcao fc_infla para calcular o valor da correcao...',lRaise,false,false);
        perform fc_debug('  <corre>  - '                                                                      ,lRaise,false,false);
      end if;

    END IF;

  ELSE

    if lRaise is true then
      perform fc_debug('  <corre>  - Inflator REAL...'    ,lRaise,false,false);
      perform fc_debug('  <corre>  - Não calcula correcao',lRaise,false,false);
      perform fc_debug('  <corre>  - '                    ,lRaise,false,false);
    end if;
    VALOR_RETORNO := VALOR_B;

  END IF;

  IF VALOR_RETORNO = 0 THEN
    VALOR_RETORNO := VALOR_B;
  END IF;

  if lRaise is true then
    perform fc_debug('  <corre>  - Valor retornado de correcao ..:  '||VALOR_RETORNO||' = '||round(VALOR_RETORNO,2) ,lRaise,false,false);
  end if;

RETURN round(VALOR_RETORNO,2);
END;
$$ language 'plpgsql';

EOT;
        $this->execute($sFuncao);
    }
}
