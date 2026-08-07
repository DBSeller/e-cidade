<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19822CamposEsocial extends Migration
{
    
    public function upDicionario() {
      DB::connection()->getPdo()->exec(<<<SQL
      insert into configuracoes.db_syscampo values(1013638,'z04_paisnascimento','int4','País onde a pessoa nasceu','0', 'País Nascimento',10,'f','f','f',1,'text','País Nascimento');
      insert into configuracoes.db_syscampo values(1013639,'z04_paisnacionalidade','int4','País Nacionalidade','0', 'País Nacionalidade',10,'f','f','f',1,'text','País Nacionalidade');
      insert into configuracoes.db_syscampo values(1013640,'z04_nomesocial','varchar(70)','Nome Social para atender legislação','', 'Nome Social',70,'f','t','f',0,'text','Nome Social');
      insert into configuracoes.db_sysarqcamp values(2939,1013640,4,0);
      insert into configuracoes.db_sysarqcamp values(2939,1013638,5,0);
      insert into configuracoes.db_sysarqcamp values(2939,1013639,6,0);
      insert into configuracoes.db_sysforkey values(2939,1013638,1,2779,0);
      insert into configuracoes.db_sysforkey values(2939,1013639,1,2779,0);       

      insert into db_sysarquivo values (1010855, 'cgmenderecoexterior', 'Endereço fora do Brasil, inicialmente para atender o e-social', 'z19', '2022-01-26', 'Endereço Exterior', 0, 'f', 'f', 'f', 'f' );
      insert into db_sysarqmod values (4,1010855);
      insert into db_syscampo values(1013642,'z19_pais','int4','País do endereço no exterior','0', 'País',10,'f','f','f',1,'text','País');
      insert into db_syscampo values(1013643,'z19_logradouro','varchar(100)','Logradouro do endereço no exterior','', 'Logradouro',100,'f','t','f',0,'text','Logradouro');
      insert into db_syscampo values(1013644,'z19_numero','int4','Número do logradouro','0', 'Número',10,'t','f','f',1,'text','Número');
      insert into db_syscampo values(1013645,'z19_complemento','varchar(30)','Complemento do endereço','', 'Complemento',30,'t','t','f',0,'text','Complemento');
      insert into db_syscampo values(1013646,'z19_bairro','varchar(90)','Bairro do endereço','', 'Bairro',90,'f','t','f',0,'text','Bairro');
      insert into db_syscampo values(1013647,'z19_cidade','varchar(50)','Cidade do endereço no exterior','', 'Cidade',50,'f','t','f',0,'text','Cidade');
      insert into db_syscampo values(1013648,'z19_codigopostal','varchar(12)','Código Postal','', 'Código Postal',12,'f','t','f',0,'text','Código Postal');
      insert into db_syscampo values(1013649,'z19_numcgm','int4','Vinculo com a tabela cgm','0', 'Código do Cgm',10,'f','f','f',1,'text','Código do Cgm');
      insert into db_syscampo values(1013650,'z19_sequencial','int4','Código Sequencial','0', 'Códidgo Sequencial',10,'f','f','f',1,'text','Códidgo Sequencial');
      insert into db_syssequencia values(1001032, 'cgmenderecoexterior_z19_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
      insert into db_sysarqcamp values(1010855,1013650,1,1001032);
      insert into db_sysarqcamp values(1010855,1013649,2,0);
      insert into db_sysarqcamp values(1010855,1013642,3,0);
      insert into db_sysarqcamp values(1010855,1013643,4,0);
      insert into db_sysarqcamp values(1010855,1013644,5,0);
      insert into db_sysarqcamp values(1010855,1013645,6,0);
      insert into db_sysarqcamp values(1010855,1013646,7,0);
      insert into db_sysarqcamp values(1010855,1013647,8,0);
      insert into db_sysarqcamp values(1010855,1013648,9,0);

      insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010855,1013650,1,1013650);
      insert into db_sysforkey values(1010855,1013642,1,2779,0);
      insert into db_sysforkey values(1010855,1013649,1,42,0);
      insert into db_sysindices values(1008709,'cgmenderecoexterior_pais_in',1010855,'0');
      insert into db_syscadind values(1008709,1013642,1);
      insert into db_sysindices values(1008710,'cgmenderecoexterior_numcgm_in',1010855,'0');
      insert into db_syscadind values(1008710,1013649,1);
SQL
);     
    }

    public function upEstrutura() {
      DB::connection()->getPdo()->exec(<<<SQL
       ALTER TABLE protocolo.cgmfisico 
         ADD column z04_nomesocial varchar(70);
  
       ALTER TABLE protocolo.cgmfisico 
         ADD column z04_paisnascimento integer,
         ADD CONSTRAINT cgmfisico_paisnascimento_fk
        FOREIGN KEY (z04_paisnascimento)
       REFERENCES configuracoes.cadenderpais;;

       ALTER TABLE protocolo.cgmfisico 
         ADD column z04_paisnacionalidade integer, 
         ADD CONSTRAINT cgmfisico_paisnacionalidade_fk
        FOREIGN KEY (z04_paisnacionalidade)
       REFERENCES configuracoes.cadenderpais;

CREATE SEQUENCE cgmenderecoexterior_z19_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE protocolo.cgmenderecoexterior(
z19_sequencial		int4 NOT NULL,
z19_numcgm		int4 NOT NULL,
z19_pais		int4 NOT NULL,
z19_logradouro		varchar(100) NOT NULL,
z19_numero		int4,
z19_complemento		varchar(30),
z19_bairro		varchar(90) NOT NULL,
z19_cidade		varchar(50) NOT NULL,
z19_codigopostal		varchar(12),
CONSTRAINT cgmenderecoexterior_sequ_pk PRIMARY KEY (z19_sequencial));

ALTER TABLE protocolo.cgmenderecoexterior
ADD CONSTRAINT cgmenderecoexterior_numcgm_fk FOREIGN KEY (z19_numcgm)
REFERENCES cgm;

ALTER TABLE protocolo.cgmenderecoexterior
ADD CONSTRAINT cgmenderecoexterior_pais_fk FOREIGN KEY (z19_pais)
REFERENCES cadenderpais;

CREATE  INDEX cgmenderecoexterior_pais_in ON protocolo.cgmenderecoexterior(z19_pais);
CREATE  INDEX cgmenderecoexterior_numcgm_in ON protocolo.cgmenderecoexterior(z19_numcgm);

SQL
);         
    }

    public function downDicionario() {
      DB::connection()->getPdo()->exec(<<<SQL
      delete 
       from configuracoes.db_sysarqcamp
      where codcam in (1013638,1013639,1013640);
     
     delete 
       from configuracoes.db_sysforkey 
      where codcam in (1013638,1013639);

     delete
       from configuracoes.db_syscampo
      where codcam in (1013638,1013639,1013640);
      
      
      delete
        from db_syssequencia 
       where codsequencia = 1001032;
      
      delete 
        from db_sysarqcamp 
       where codarq = 1010855;
      
      delete 
        from db_sysprikey 
       where codarq = 1010855;

      delete
        from db_sysforkey 
       where codarq = 1010855;

      delete 
        from db_sysindices 
       where codind in (1008709, 1008710);

      delete 
        from db_syscadind 
       where codind in (1008709, 1008710);
      
       delete
        from db_sysarqmod 
       where codarq = 1010855;

       delete 
        from db_sysarquivo 
       where codarq = 1010855;
      
      delete
        from db_syscampo 
       where codcam in (1013642,1013643, 1013644,1013645,1013646, 1013647, 1013648,1013649,1013650);
      
SQL
);
          
    }

    public function downEstrutura() {
      DB::connection()->getPdo()->exec(<<<SQL
      alter table protocolo.cgmfisico 
      drop column z04_nomesocial;

     alter table protocolo.cgmfisico 
      drop column z04_paisnascimento;

     alter table protocolo.cgmfisico 
      drop column z04_paisnacionalidade;
     
     DROP TABLE IF EXISTS cgmenderecoexterior CASCADE;
 
     DROP SEQUENCE IF EXISTS cgmenderecoexterior_z19_sequencial_seq;

SQL
);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $this->upDicionario();
      $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      $this->downDicionario();
      $this->downEstrutura();
    }

}
