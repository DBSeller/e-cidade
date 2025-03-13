<?php

use Classes\PostgresMigration;

class M18002LancesItemLicitacao extends PostgresMigration
{

  public function up()
  {
    $this->upEstrutura();
    $this->upDicionario();
  }

  public function down()
  {
    $this->downEstrutura();
    $this->downDicionario();
  }

  public function upDicionario()
  {
    $this->execute(
      <<<SQL
        insert into db_sysarquivo 
        values (1010829, 'liclicitemlances', 'Dados dos lances para os itens da licitação com as modalidades Pregão Presencial e Pregão Eletrônico', 'l49', '2021-09-15', 'liclicitemlances', 0, 'f', 't', 't', 't' );
        insert into db_sysarqmod 
        values (19,1010829);
        insert into db_syscampo 
        values(1013435,'l49_sequencial','int8','Sequencial da tabela liclicitemlances','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo 
        values(1013436,'l49_liclicitem','int8','Item Licitação','0', 'Item Licitação',10,'f','f','f',1,'text','Item Licitação');
        insert into db_syscampo 
        values(1013438,'l49_data','date','Data do Lance','null', 'Data do Lance',10,'f','f','f',1,'text','Data do Lance');
        insert into db_syscampo 
        values(1013439,'l49_hora','char(8)','Hora do lance','', 'Hora do lance',8,'f','t','f',0,'text','Hora do lance');
        insert into db_syscampo 
        values(1013440,'l49_fornecedor','int8','Fornecedor participante da licitação','0', 'Fornecedor',10,'f','f','f',1,'text','Fornecedor');
        insert into db_syscampo 
        values(1013441,'l49_valido','bool','Campo que identifica se um lance é válido','f', 'Válido',1,'f','f','f',5,'text','Válido');
        insert into db_syscampo 
        values(1013442,'l49_cancelado','bool','Campo que identifica o cancelamento de um lance','f', 'Cancelado',1,'f','f','f',5,'text','Cancelado');
        insert into db_syscampo 
        values(1013443,'l49_justificativa','text','Texto com a justificativa para o lance','', 'Justificativa',1,'f','f','f',0,'text','Justificativa');
        insert into db_syscampo 
        values(1013444,'l49_vlrun','float8','Lance com valor unitário do item','0', 'Valor unitário',10,'f','f','f',4,'text','Valor unitário');
        insert into db_syscampo 
        values(1013445,'l49_vlrtot','float8','Valor total para o item','0', 'Valor total',10,'f','f','f',4,'text','Valor total');
        insert into db_syscampo 
        values(1013446,'l49_vlrdesc','float8','Valor de desconto no item','0', 'Valor Desconto',10,'f','f','f',4,'text','Valor Desconto');
        insert into db_syscampo 
        values(1013447,'pc03_natureza','int8','Criado campo para atender Compras Públicas 1 = Produto, 2 = Serviço, 3 = Medicamento','0', 'Natureza do Grupo',10,'f','f','f',1,'text','Natureza do Grupo');
        insert into db_syscampo
        values(1013449, 'pc05_natureza', 'int8', 'Criado campo para atender Compras Públicas 1 = Produto, 2 = Serviço, 3 = Medicamento','0','Natureza', 10, 'f', 'f', 'f', 1, 'text', 'Natureza');
        insert into db_syscampo values(1013450,'l12_urlapi','varchar(50)','URL para acessar API do compras públicas','', 'URL API',50,'f','f','f',0,'text','URL API');
        insert into db_syscampo values(1013451,'l12_token','varchar(40)','Token de acesso a API do compras públicas','', 'Identificador Comprador',40,'f','f','f',0,'text','Identificador Comprador');

        insert into db_sysarqcamp 
        values(854,1013447,4,0);
        insert into db_sysarqcamp 
        values(865, 1013449, 4, 0);
        insert into db_sysarqcamp 
        values(1010829,1013435,1,0);
        insert into db_sysarqcamp 
        values(1010829,1013436,2,0);
        insert into db_sysarqcamp 
        values(1010829,1013438,3,0);
        insert into db_sysarqcamp 
        values(1010829,1013439,4,0);
        insert into db_sysarqcamp 
        values(1010829,1013440,5,0);
        insert into db_sysarqcamp 
        values(1010829,1013441,6,0);
        insert into db_sysarqcamp 
        values(1010829,1013442,7,0);
        insert into db_sysarqcamp 
        values(1010829,1013443,8,0);
        insert into db_sysarqcamp 
        values(1010829,1013444,9,0);
        insert into db_sysarqcamp 
        values(1010829,1013445,10,0);
        insert into db_sysarqcamp 
        values(1010829,1013446,11,0);
        insert into db_sysarqcamp 
        values(2055,1013450,7,0);
        insert into db_sysarqcamp 
        values(2055,1013451,8,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) 
        values(1010829,1013435,1,1013435);
        insert into db_sysforkey 
        values(1010829,1013436,1,1261,0);
        insert into db_sysforkey 
        values(1010829,1013440,1,858,0);
        insert into db_sysindices 
        values(1008691,'liclicitemlances_iclicitem_fornecedor_in',1010829,'0');
        insert into db_syscadind 
        values(1008691,1013436,1);
        insert into db_syscadind 
        values(1008691,1013440,2);
        insert into db_syssequencia 
        values(1001015, 'liclicitemlances_l49_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp 
           set codsequencia = 1001015 
         where codarq = 1010829 
           and codcam = 1013435;
        insert into db_sysarqarq 
        values(0,1010829);

        insert into db_sysarquivo values (1010830, 'pcorcamitemresultado', 'Tabela com os resultados conforme descreve o Licitacon.', 'pc220', '2021-10-05', 'Resultado do item', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (12,1010830);
        insert into db_sysarqarq values(859,1010830);
        insert into db_syscampo values(1013452,'pc220_orcamitem','int8','Item Porposta','0', 'Item Proposta',10,'f','f','f',1,'text','Item Proposta');
        insert into db_syscampo values(1013453,'pc220_resultado','char(1)','Resultado do item','', 'Resultado',1,'f','t','f',0,'text','Resultado');
        insert into db_syscampodef values(1013453,'A','Adjudicado');
        insert into db_syscampodef values(1013453,'D','Deserto');
        insert into db_syscampodef values(1013453,'F','Fracassado');
        insert into db_syscampodef values(1013453,'N','Anulado');
        insert into db_syscampodef values(1013453,'R','Revogado');
        insert into db_sysarqcamp values(1010830,1013452,1,0);
        insert into db_sysarqcamp values(1010830,1013453,2,0);
       
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010830,1013452,1,1013452);
        
        insert into db_sysforkey values(1010830,1013452,1,859,0);

SQL
    );
  }

  public function downDicionario()
  {
    $this->execute(
      <<<SQL
       
        delete
          from db_sysarqcamp 
          where codcam in (1013435, 1013436, 1013438, 1013439, 1013440, 1013441, 1013442, 1013443, 1013444, 1013445, 1013446, 1013447, 1013449, 1013450, 1013451);

        delete
          from db_sysarqmod 
         where codarq = 1010829;  
        
        delete 
          from db_sysprikey 
         where codarq = 1010829
           and codcam = 1013435;

        delete 
          from db_sysforkey 
         where codarq = 1010829
           and codcam in (1013436, 1013440);
        
        delete 
          from db_sysarquivo 
        where codarq = 1010829;
        
        delete
          from db_syscampo 
         where codcam in (1013435, 1013436, 1013438, 1013439, 1013440, 1013441, 1013442, 1013443, 1013444, 1013445, 1013446, 1013447, 1013449, 1013450, 1013451);
        
        delete
          from db_sysindices 
         where codind = 1008691;

        delete
          from db_syscadind 
         where codind = 1008691
           and codcam in (1013436, 1013440);

        delete 
          from db_syssequencia
         where codsequencia = 1001015;

        delete 
          from db_sysarqarq 
         where codarq = 1010829;

        delete 
          from db_sysarqcamp
         where codarq = 1010830; 
        
        delete 
          from db_sysarqarq 
         where codarq = 1010830;

        delete 
          from db_sysprikey 
         where codarq = 1010830;
         
        delete 
          from db_sysforkey 
         where codarq  = 1010830 
           and referen = 859; 

        delete
          from db_syscampodef
         where codcam =1013453;

        delete
          from db_syscampo
         where codcam in (1013452, 1013453);

        delete 
          from db_sysarqmod    
         where codarq = 1010830;

        delete 
          from db_sysarquivo 
         where codarq = 1010830; 
SQL
    );
  }

  public function upEstrutura()
  {
    $this->execute(
      <<<SQL
               
        -- Criando  sequences
        CREATE SEQUENCE licitacao.liclicitemlances_l49_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        
        
        -- TABELAS E ESTRUTURA
        
        -- Módulo: licitacao
        CREATE TABLE licitacao.liclicitemlances(
        l49_sequencial          bigint not null default 0,
        l49_liclicitem          integer not null, 
        l49_data                date not null,
        l49_hora                char(8) not null,
        l49_fornecedor          integer not null,
        l49_valido              boolean not null default 'f',
        l49_cancelado           boolean not null default 'f',
        l49_justificativa       text,
        l49_vlrun               numeric not null,
        l49_vlrtot              numeric not null,
        l49_vlrdesc             numeric not null,
        CONSTRAINT liclicitemlances_sequ_pk PRIMARY KEY (l49_sequencial));
        
        
        -- CHAVE ESTRANGEIRA
        ALTER TABLE licitacao.liclicitemlances
        ADD CONSTRAINT liclicitemlances_fornecedor_fk FOREIGN KEY (l49_fornecedor)
        REFERENCES pcorcamforne;
        
        ALTER TABLE licitacao.liclicitemlances
        ADD CONSTRAINT liclicitemlances_liclicitem_fk FOREIGN KEY (l49_liclicitem)
        REFERENCES licitacao.liclicitem;
        
        -- INDICES        
        CREATE  INDEX liclicitemlances_iclicitem_fornecedor_in ON licitacao.liclicitemlances(l49_liclicitem,l49_fornecedor);

        -- Módulo: Compras
        ALTER TABLE compras.pcgrupo 
          ADD COLUMN pc03_natureza integer not null default 1;

       ALTER TABLE compras.pctipo
          ADD COLUMN pc05_natureza integer not null default 1;   

       -- Parâmetros
       ALTER TABLE licitacao.licitaparam 
         ADD COLUMN l12_urlapi varchar(50);

       ALTER TABLE licitacao.licitaparam 
         ADD COLUMN l12_token varchar(40);  

      -- Cadastro Atributos dinâmicos   
      INSERT INTO configuracoes.db_cadattdinamicoatributos
            VALUES (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), 
                    (select db118_sequencial 
                       from  db_cadattdinamico 
                      where db118_descricao ilike 'Atributos da licita%' 
                      limit 1),
                    NULL, 'Casas Decimais', '2', 1, 'casas_decimais');
            
      INSERT INTO configuracoes.db_cadattdinamicoatributosopcoes
      VALUES (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
              currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '2', 'Duas casas'),
             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
              currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '3', 'Três casas'),
              (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
              currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '4', 'Quatro casas');

      CREATE TABLE compras.pcorcamitemresultado(
        pc220_orcamitem integer not null,
        pc220_resultado char(1) not null default 'A',
        CONSTRAINT pcorcamitemresultado_orcamitem_pk PRIMARY KEY (pc220_orcamitem),
        CONSTRAINT pcorcamitemresultado_orcamitem_fk FOREIGN KEY (pc220_orcamitem) REFERENCES compras.pcorcamitem(pc22_orcamitem)
      ); 

SQL
    );
  }

  public function downEstrutura()
  {
    $this->execute(
      <<<SQL

       --DROP TABLE:
       DROP TABLE IF EXISTS licitacao.liclicitemlances CASCADE;
        --Criando drop sequences
       DROP SEQUENCE IF EXISTS licitacao.liclicitemlances_l49_sequencial_seq;

       ALTER TABLE compras.pcgrupo 
          DROP COLUMN pc03_natureza;
                   
       ALTER TABLE compras.pctipo
       DROP COLUMN pc05_natureza;                 

       ALTER TABLE licitacao.licitaparam 
        DROP COLUMN l12_urlapi;
         
       ALTER TABLE licitacao.licitaparam 
        DROP COLUMN l12_token;  

       DROP TABLE IF EXISTS compras.pcorcamitemresultado;
SQL
    );
  }
}
