<?php

use Classes\PostgresMigration;

class M15149ConsistenciaRp extends PostgresMigration
{
    public function up()
    {

        $sql = "delete from consistenciasistema where db160_json ilike '%uid%c4ca4238a0b923820dcc509a6f75849b%'";
        $this->execute($sql);

        $sql = <<<SQL_CONSISTENCIA
insert into consistenciasistema
   values ( nextval('consistenciasistema_db160_sequencial_seq'), 100, '{
  "tipo": 100,
  "uid": "c4ca4238a0b923820dcc509a6f75849b",
  "nome": "Comparativo encerramento de RP com Bal. Ver.",
  "descricao": "Valida os valores das contas de encerramento e compara com balancete de verificação",
  "ajuda": "Valida os valores das contas de encerramento e compara com balancete de verificação",
  "formulario": {
    "campos": [
        {
          "propriedade": "reduzido",
          "nome": "Reduzido",
          "chave_primaria": true

        },
        {
          "propriedade": "estrutural",
          "nome": "Estrutural"
        },
        {
          "propriedade": "valor_encerramento",
          "nome": "Valor Encerramento"
        },
        {
          "propriedade": "saldo_final_bal_ver",
          "nome": "Saldo Bal. Ver."
        },
        {
          "propriedade": "diferenca",
          "nome": "Diferença"
        }
      ]
    },
    "sql": {
      "consistencia": " drop table if exists estruturais_encerramento;
create temp table estruturais_encerramento
(
    estrutural     text,
    resto_anterior boolean
);
insert into estruturais_encerramento
values (''6221301'', false),
       (''6221302'', false),
       (''6221303'', false),
       (''6314'', true),
       (''6319'', true),
       (''6322'', true),
       (''6329'', true),
       (''6313'', true);


select *, (valor_encerramento - saldo_final_bal_ver) as diferenca from (
select c61_reduz as reduzido,
       c60_estrut estrutural,
       coalesce(
               (select sum(valor)
                from fc_valores_encerramento_empenho_rp(estrutural || ''%'', resto_anterior)), 0
           )                           as valor_encerramento,
       round(planosaldo[4]::float8, 2) as saldo_final_bal_ver,
       planosaldo[6]::text             as sinal_do_saldo
from estruturais_encerramento
         inner join (select conplanoreduz.*,
                            c60_estrut,
                            (fc_planosaldonovo_array(fc_getsession(''DB_anousu'')::integer,
                                                     c61_reduz,
                                                     (select cast(fc_getsession(''DB_anousu'') || ''-01-01'' as text))::date,
                                                     (select cast(fc_getsession(''DB_anousu'') || ''-12-31'' as text))::date,
                                                     false)
                            ) as planosaldo
                     from contabilidade.conplano
                              inner join contabilidade.conplanoreduz on conplanoreduz.c61_codcon = conplano.c60_codcon
                         and conplanoreduz.c61_anousu = conplano.c60_anousu
                     where c60_anousu = fc_getsession(''DB_anousu'')::integer
                       and c61_instit = fc_getsession(''DB_instit'')::integer
                       and (c60_estrut ilike ''6221301%'' or
                            c60_estrut ilike ''6221302%'' or
                            c60_estrut ilike ''6221303%'' or
                            c60_estrut ilike ''6314%'' or
                            c60_estrut ilike ''6319%'' or
                            c60_estrut ilike ''6322%'' or
                            c60_estrut ilike ''6329%'' or
                            c60_estrut ilike ''6313%'')) as x
                    on estrutural = substr(c60_estrut, 1, length(estrutural))) as fim
where saldo_final_bal_ver > 0 
  and not (case
               when valor_encerramento >= 0 and valor_encerramento = saldo_final_bal_ver and sinal_do_saldo = ''C''
                   then true
               when valor_encerramento < 0 and valor_encerramento = saldo_final_bal_ver and sinal_do_saldo = ''D''
                   then true
               else false
    end)"
    }
}' );
SQL_CONSISTENCIA;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "delete from consistenciasistema where db160_json ilike '%uid%c4ca4238a0b923820dcc509a6f75849b%'";
        $this->execute($sql);

    }
}
