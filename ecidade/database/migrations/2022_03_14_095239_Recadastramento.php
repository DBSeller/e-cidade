<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recadastramento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_syscampo values(1013775,'rh16_data_emissao_cnh','date','Data de Emissão da CNH','null', 'Data de Emissão da CNH',10,'t','f','f',1,'text','Data de Emissão da CNH');
        insert into db_syscampo values(1013776,'rh16_orgao_classe','varchar(30)','Registro de Órgão de Classe','', 'Registro de Órgão de Classe',30,'t','t','f',0,'text','Registro de Órgão de Classe');
        insert into db_syscampo values(1013777,'rh16_data_orgao_classe','date','Data de Órgão de Classe','null', 'Data de Órgão de Classe',10,'t','f','f',1,'text','Data de Órgão de Classe');
        insert into db_syscampo values(1013778,'rh16_orgao_emissor_classe','varchar(15)','Órgão Emissor da Classe','', 'Órgão Emissor da Classe',15,'t','t','f',0,'text','Órgão Emissor da Classe');
        insert into db_syscampo values(1013779,'rh16_orgao_emissor_rne','varchar(15)','Órgão Emissor da RNE','', 'Órgão Emissor da RNE',15,'t','t','f',0,'text','Órgão Emissor da RNE');
        insert into db_syscampo values(1013780,'rh16_data_emissao_rne','date','Data de Emissão da RNE','null', 'Data de Emissão da RNE',10,'t','f','f',1,'text','Data de Emissão da RNE');
        insert into db_syscampo values(1013781,'rh16_data_entrada_rne','date','Data de Entrada no País','null', 'Data de Entrada no País',10,'t','f','f',1,'text','Data de Entrada no País');
        insert into db_syscampo values(1013782,'rh16_data_validade_rne','date','Data de Validade da RNE','null', 'Data de Validade da RNE',10,'t','f','f',1,'text','Data de Validade da RNE');
        insert into db_syscampo values(1013792,'rh16_uf_cnh','varchar(2)','Unidade Federativa da CNH','', 'UF da CNH',2,'t','t','f',0,'text','UF da CNH');
        insert into db_syscampo values(1013793,'rh16_data_validade_orgao_classe','date','Data de Validade do Órgão de Classe','Data de Validade do Órgão de Classe', 'Data de Validade do Órgão de Classe',10,'t','f','f',1,'text','Data de Validade do Órgão de Classe');
        insert into db_syscampo values(1013794,'rh16_registro_rne','varchar(15)','Registro da RNE','Registro da RNE', 'Registro da RNE',15,'t','t','f',0,'text','Registro da RNE');
        insert into db_syscampo values(1013801,'z01_genero','varchar(20)','Gênero','Gênero', 'Gênero',20,'t','t','f',0,'text','Gênero');

        insert into db_sysarqcamp values(1168,1013775,16,0);
        insert into db_sysarqcamp values(1168,1013776,17,0);
        insert into db_sysarqcamp values(1168,1013777,18,0);
        insert into db_sysarqcamp values(1168,1013778,19,0);
        insert into db_sysarqcamp values(1168,1013779,20,0);
        insert into db_sysarqcamp values(1168,1013780,21,0);
        insert into db_sysarqcamp values(1168,1013781,22,0);
        insert into db_sysarqcamp values(1168,1013782,23,0);
        insert into db_sysarqcamp values(1168,1013792,17,0);
        insert into db_sysarqcamp values(1168,1013793,20,0);
        insert into db_sysarqcamp values(1168,1013794,23,0);
        insert into db_sysarqcamp values(42,1013801,59,0);

        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_data_emissao_cnh date;
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_uf_cnh varchar(2);
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_orgao_classe varchar(15);
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_data_orgao_classe date;
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_data_validade_orgao_classe date;
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_orgao_emissor_classe varchar(15);
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_registro_rne varchar(15);
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_orgao_emissor_rne varchar(15);
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_data_emissao_rne date;
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_data_entrada_rne date;
        alter table pessoal.rhpesdoc add column IF NOT EXISTS rh16_data_validade_rne date;
        alter table protocolo.cgm add column IF NOT EXISTS z01_genero varchar(20);
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
        DB::connection()->getPdo()->exec(<<<SQL
        delete from db_sysarqcamp where codcam = 1013775;
        delete from db_sysarqcamp where codcam = 1013776;
        delete from db_sysarqcamp where codcam = 1013777;
        delete from db_sysarqcamp where codcam = 1013778;
        delete from db_sysarqcamp where codcam = 1013779;
        delete from db_sysarqcamp where codcam = 1013780;
        delete from db_sysarqcamp where codcam = 1013781;
        delete from db_sysarqcamp where codcam = 1013782;
        delete from db_sysarqcamp where codcam = 1013792;
        delete from db_sysarqcamp where codcam = 1013793;
        delete from db_sysarqcamp where codcam = 1013794;
        delete from db_sysarqcamp where codcam = 1013801;

        delete from db_syscampo where codcam = 1013775; 
        delete from db_syscampo where codcam = 1013776; 
        delete from db_syscampo where codcam = 1013777; 
        delete from db_syscampo where codcam = 1013778; 
        delete from db_syscampo where codcam = 1013779; 
        delete from db_syscampo where codcam = 1013780; 
        delete from db_syscampo where codcam = 1013781; 
        delete from db_syscampo where codcam = 1013782;
        delete from db_syscampo where codcam = 1013792;
        delete from db_syscampo where codcam = 1013793;
        delete from db_syscampo where codcam = 1013794;
        delete from db_syscampo where codcam = 1013801;
        
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_data_emissao_cnh;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_uf_cnh;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_orgao_classe;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_data_orgao_classe;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_data_validade_orgao_classe;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_orgao_emissor_classe;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_registro_rne;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_orgao_emissor_rne;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_data_emissao_rne;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_data_entrada_rne;
        alter table pessoal.rhpesdoc drop column IF EXISTS rh16_data_validade_rne;
        alter table protocolo.cgm drop column IF EXISTS z01_genero;

SQL
        );
    }
}
