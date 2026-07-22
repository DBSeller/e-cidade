<?php

use Classes\PostgresMigration;

class M10561ConsistenciaPad extends PostgresMigration
{
    public function up()
    {

        $this->execute(
            <<<SQL_UP
insert into db_syscampo values(1009721,'db160_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1009722,'db160_sistema','int4','Sistema','0', 'Sistema',10,'f','f','f',1,'text','Sistema');
insert into db_syscampo values(1009723,'db160_json','text','JSON','', 'JSON',1,'f','f','f',0,'text','JSON');
insert into db_sysarquivo values (1010279, 'consistenciasistema', 'Armazena as consistencias disponiveis no sistema', 'db160', '2018-04-24', 'consistenciasistema', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (7,1010279);
delete from db_sysarqcamp where codarq = 1010279;
insert into db_sysarqcamp values(1010279,1009721,1,0);
insert into db_sysarqcamp values(1010279,1009722,2,0);
insert into db_sysarqcamp values(1010279,1009723,3,0);
insert into db_sysindices values(1008275,'consistenciasistema_sistema_in',1010279,'0');
insert into db_syscadind values(1008275,1009722,1);
insert into db_syssequencia values(1000731, 'consistenciasistema_db160_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000731 where codarq = 1010279 and codcam = 1009721;
delete from db_sysprikey where codarq = 1010279;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010279,1009721,1,1009722);

CREATE SEQUENCE consistenciasistema_db160_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE consistenciasistema(
db160_sequencial  int4 NOT NULL default 0,
db160_sistema     int4 NOT NULL default 0,
db160_json        text ,
CONSTRAINT consistenciasistema_sequ_pk PRIMARY KEY (db160_sequencial));

CREATE  INDEX consistenciasistema_sistema_in ON consistenciasistema(db160_sistema);


insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10519 ,'Consistência Informações' ,'Consistência Informações' ,'con4_consistenciainformacoes.php?tipo=1' ,'1' ,'1' ,'Consistência Informações' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 6823 ,10519 ,4 ,209 );

SQL_UP

        );

        $this->jsonInstrumentoContratual();
        $this->jsonAcordoBaseLegal();

    }

    public function down()
    {

        $this->execute(
            <<<SQL_DOWN
delete from db_sysprikey where codarq = 1010279;
delete from db_syssequencia where codsequencia = 1000731;
delete from db_syscadind where codind = 1008275;
delete from db_sysindices where codarq = 1010279; 
delete from db_sysarqcamp where codarq = 1010279;
delete from db_sysarqmod where codarq = 1010279;
delete from db_sysarquivo where codarq = 1010279;
delete from db_syscampo where codcam in (1009721, 1009722, 1009723);

DROP TABLE IF EXISTS consistenciasistema CASCADE;

DROP SEQUENCE IF EXISTS consistenciasistema_db160_sequencial_seq;


delete from db_menu where id_item_filho = 10519 AND modulo = 209;
delete from db_itensmenu where id_item  = 10519;
SQL_DOWN

        );

    }

    private function jsonInstrumentoContratual()
    {
        $this->execute(
        <<<SQL_INSTRUMENTO
insert into consistenciasistema
values (1000, 1,
              '
{
  "tipo": 1,
  "nome": "Verifica notas sem Instrumento Contratual - 2018",
  "descricao": "Consistência de notas de liquidação sem instrumento contratual vinculado",
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
  "sql": {
    "consistencia": "select e60_codemp || \'/\' || e60_anousu as numero_empenho, c66_codlan as lancamento, c66_codnota as nota, instrumentocontratual from empempenho inner join empnota on empnota.e69_numemp = empempenho.e60_numemp inner join conlancamnota on conlancamnota.c66_codnota = empnota.e69_codnota inner join plugins.contratospadrs on plugins.contratospadrs.lancamento = conlancamnota.c66_codlan where e60_anousu = 2018 and instrumentocontratual is null order by numero_empenho, lancamento, nota;",
    "correcao": "update plugins.contratospadrs set instrumentocontratual = [instrumentocontratual], cabecalho = \'S\' where lancamento = [lancamento];"
  }
}
')
SQL_INSTRUMENTO
        );

    }

    private function jsonAcordoBaseLegal()
    {
        $this->execute(
        <<<SQL_BASELEGAL
insert into consistenciasistema
values (1001, 1,
              '
{
  "tipo": 1,
  "nome": "Acordo - Base Legal 2018",
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
    "consistencia": "select ac16_sequencial as codigo_acordo, baselegalcontratacao as codigo_baselegal from acordo left join plugins.padrsacordovinculo on plugins.padrsacordovinculo.acordo = acordo.ac16_sequencial where ac16_anousu = 2018;",
    "correcao": "delete from plugins.padrsacordovinculo where acordo = [codigo_acordo]; insert into plugins.padrsacordovinculo values(nextval(\'plugins.padrsacordovinculo_sequencial_seq\'::regclass), [codigo_acordo], [codigo_baselegal]);"
  }
}
')
SQL_BASELEGAL
        );

    }
}
