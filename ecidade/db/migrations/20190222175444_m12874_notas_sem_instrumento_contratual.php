<?php

use Classes\PostgresMigration;

class M12874NotasSemInstrumentoContratual extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

delete from consistenciasistema where db160_sequencial in (1000, 1001);

insert into consistenciasistema
     values (1000, 1,
     '
     {
  "tipo": 1,
  "nome": "Verifica notas sem Instrumento Contratual",
  "descricao": "Consistência de notas de liquidação onde não foram informados o instrumento contratual.",
  "formulario": {
    "campos": [
      {
        "propriedade": "numero_empenho",
        "nome": "Empenho"
      },
      {
        "propriedade": "lancamento",
        "nome": "Código do Lançamento",
        "chave_primaria": true
      },
      {
        "propriedade": "nota",
        "nome": "Nota de Liquidação"
      },
      {
        "propriedade": "instrumentocontratual",
        "nome": "Instrumento Contratual",
        "tipo": "select",
        "opcoes": [
          {
            "codigo": 1,
            "descricao": "Termo de Adesão"
          },
          {
            "codigo": 2,
            "descricao": "Contrato"
          },
          {
            "codigo": 3,
            "descricao": "Termo de Fomento"
          },
          {
            "codigo": 4,
            "descricao": "Termo de Parceria"
          },
          {
            "codigo": 5,
            "descricao": "Termo de Credenciamento"
          },
          {
            "codigo": 6,
            "descricao": "Termo de Colaboração"
          }
        ]
      }
    ]
  },
  "sql" : {
    "consistencia": "select e60_codemp || \'/\' || e60_anousu as numero_empenho, c66_codlan as lancamento, c66_codnota as nota, instrumentocontratual from empempenho inner join empnota on empnota.e69_numemp = empempenho.e60_numemp inner join conlancamnota on conlancamnota.c66_codnota = empnota.e69_codnota inner join plugins.contratospadrs on plugins.contratospadrs.lancamento = conlancamnota.c66_codlan where e60_anousu = fc_getsession(\'DB_anousu\')::integer and instrumentocontratual is null order by numero_empenho, lancamento, nota;",
    "correcao": "update plugins.contratospadrs set instrumentocontratual = [instrumentocontratual], cabecalho = \'S\' where lancamento = [lancamento];"
  }
}
     ');

insert into consistenciasistema values (
    1001,1,
'{
  "tipo": 1,
  "nome": "Acordo - Base Legal",
  "descricao": "Acordos do ano vigente para alteração da Base Legal de Contratação",
  "formulario": {
    "campos": [
      {
        "propriedade": "codigo_acordo",
        "nome": "Acordo",
        "chave_primaria" : true
      },
      {
        "propriedade": "codigo_baselegal",
        "nome": "Base Legal de Contratação",
        "tipo": "select",
        "opcoes": "select sequencial as codigo, descricao from plugins.padrsbaselegalcontratacaoacordo;"
      }
    ]
  },
  "sql": {
    "consistencia": "select ac16_sequencial as codigo_acordo, baselegalcontratacao as codigo_baselegal from acordo left join plugins.padrsacordovinculo on plugins.padrsacordovinculo.acordo = acordo.ac16_sequencial where ac16_anousu = fc_getsession(\'DB_instit\')::integer;",
    "correcao": "delete from plugins.padrsacordovinculo where acordo = [codigo_acordo]; insert into plugins.padrsacordovinculo values(nextval(\'plugins.padrsacordovinculo_sequencial_seq\'::regclass), [codigo_acordo], [codigo_baselegal])"
  }
}'

);
SQL_UP
);
    }


    public function down() {

    }
}
