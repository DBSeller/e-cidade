<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20034SAGRESDicionarioDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sSql = <<<SQL
        insert into db_sysarquivo 
        values  (1010862, 'sagresordenadordespesa', 'Cadastro Ordenador de Despesa do SAGRES', 'c139', '2022-02-13', 'Cadastro Ordenador de Despesa do SAGRES', 0, 'f', 'f', 'f', 'f' ),
                (1010863, 'sagresresponsavelunidadeorcamentaria', 'Responsável pela Unidade Orçamentária do SAGRES', 'c140', '2022-02-13', 'Responsável pela Unidade Orçamentária do SAGRES', 0, 'f', 'f', 'f', 'f' ),
                (1010864, 'sagresarquivogerado', 'Arquivos gerados no SAGRES', 'c141', '2022-02-13', 'Arquivos gerados no SAGRES', 0, 'f', 'f', 'f', 'f' );
        
        insert into db_sysarqmod
        values  (32,1010862),
                (32,1010863),
                (32,1010864);
        
        insert into db_syscampo
        values  (1013712,'c139_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
                (1013713,'c139_instit','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
                (1013714,'c139_cgm','int4','CGM','0', 'CGM',10,'f','f','f',1,'text','CGM'),
                (1013715,'c139_cgmsubstituto','int4','CGM Substituto','0', 'CGM Substituto',10,'t','f','f',1,'text','CGM Substituto'),
                (1013716,'c139_principal','bool','Ordenador Principal','f', 'Ordenador Principal',1,'f','f','f',5,'text','Ordenador Principal'),
                (1013717,'c139_substituto','bool','Ordenador Substituto','f', 'Ordenador Substituto',1,'f','f','f',5,'text','Ordenador Substituto'),
                (1013718,'c139_datainicio','date','Data Inicio','null', 'Data Inicio',10,'f','f','f',0,'text','Data Inicio'),
                (1013719,'c139_datafim','date','Data fim','null', 'Data fim',10,'t','f','f',0,'text','Data fim'),
                (1013720,'c139_tipoatojuridico','int4','Tipo ato juridico','0', 'Tipo ato juridico',10,'f','f','f',1,'text','Tipo ato juridico'),
                (1013721,'c139_titulo','varchar(50)','Titulo do Ordenador','', 'Titulo do Ordenador',50,'t','t','f',0,'text','Titulo do Ordenador'),
                (1013722,'c139_ativo','bool','Ativo','f', 'Ativo',1,'f','f','f',5,'text','Ativo'),
                (1013723,'c139_datainatividade','date','Data de inativação','null', 'Data de inativação',10,'t','f','f',0,'text','Data de inativação'),
                (1013724,'c139_idusuario','int4','Identificação do usuário','0', 'Identificação do usuário',10,'t','f','f',1,'text','Identificação do usuário'),
                (1013986,'c139_datainiciosub','date','Data Início Substituto','null', 'Data Início Substituto',10,'t','f','f',0,'text','Data Início Substituto'),
                (1013987,'c139_datafimsub','date','Data Fim Substituto','null', 'Data Fim Substituto',10,'t','f','f',0,'text','Data Fim Substituto'),
                (1013725,'c140_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
                (1013726,'c140_orgao','int4','Código do Órgão','0', 'Código do Órgão',10,'f','f','f',1,'text','Código do Órgão'),
                (1013727,'c140_unidade','int4','Código da Unidade','0', 'Código da Unidade',10,'f','f','f',1,'text','Código da Unidade'),
                (1013728,'c140_cgm','int4','CGM','0', 'CGM',10,'f','f','f',1,'text','CGM'),
                (1013729,'c140_cgmsubstituto','int4','CGM Substituto','0', 'CGM Substituto',10,'t','f','f',1,'text','CGM Substituto'),
                (1013730,'c140_principal','bool','Responsável Principal','f', 'Responsável Principal',1,'f','f','f',5,'text','Responsável Principal'),
                (1013731,'c140_substituto','bool','Responsável Substituto','f', 'Responsável Substituto',1,'f','f','f',5,'text','Responsável Substituto'),
                (1013732,'c140_datainicio','date','Data Inicio','null', 'Data Inicio',10,'f','f','f',0,'text','Data Inicio'),
                (1013733,'c140_datafim','date','Data fim','null', 'Data fim',10,'t','f','f',0,'text','Data fim'),
                (1013734,'c140_tipoatojuridico','int4','Tipo ato juridico','0', 'Tipo ato juridico',10,'f','f','f',1,'text','Tipo ato juridico'),
                (1013735,'c140_ativo','bool','Ativo','f', 'Ativo',1,'f','f','f',5,'text','Ativo'),
                (1013736,'c140_datainatividade','date','Data de inativação','null', 'Data de inativação',10,'t','f','f',0,'text','Data de inativação'),
                (1013737,'c140_idusuario','int4','Identificação do usuário','0', 'Identificação do usuário',10,'t','f','f',1,'text','Identificação do usuário'),
                (1013738,'c140_anousu','int4','Campo anousu','0', 'Campo anousu',10,'f','f','f',1,'text','Campo anousu'),
                (1013983,'c140_instit','int4','Código Instituição','0', 'Código Instituição',10,'f','f','f',1,'text','Código Instituição'),
                (1013984,'c140_datainiciosub','date','Data Inicio Substituto','null', 'Data Inicio Substituto',10,'t','f','f',0,'text','Data Inicio Substituto'),
                (1013985,'c140_datafimsub','date','Data Fim Substituto','null', 'Data Fim Substituto',10,'t','f','f',0,'text','Data Fim Substituto'),
                (1013741,'c141_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
                (1013742,'c141_usuario','int4','Usuário','0', 'Usuário',10,'f','f','f',1,'text','Usuário'),
                (1013743,'c141_data','date','Data de execução','null', 'Data de execução',10,'f','f','f',0,'text','Data de execução'),
                (1013744,'c141_codlayout','int4','Código de identificação do layout','0', 'Código de identificação do layout',10,'f','f','f',1,'text','Código de identificação do layout'),
                (1013745,'c141_nomearquivo','varchar(70)','Nome do arquivo','', 'Nome do arquivo',70,'f','t','f',0,'text','Nome do arquivo'),
                (1013746,'c141_json','text','JSON','', 'JSON',1,'f','f','f',0,'text','JSON');
        
        insert into db_syscampodef
        values  (1013716,'t','SIM'),
                (1013716,'f','NÃO'),
                (1013717,'t','SIM'),
                (1013717,'f','NÃO'),
                (1013722,'t','SIM'),
                (1013722,'f','NÃO'),
                (1013730,'t','SIM'),
                (1013730,'f','NÃO'),
                (1013731,'t','SIM'),
                (1013731,'f','NÃO'),
                (1013735,'t','SIM'),
                (1013735,'f','NÃO');

        insert into db_sysarqcamp
        values  (1010862,1013712,1,0),
                (1010862,1013713,2,0),
                (1010862,1013714,3,0),
                (1010862,1013715,4,0),
                (1010862,1013716,5,0),
                (1010862,1013717,6,0),
                (1010862,1013718,7,0),
                (1010862,1013719,8,0),
                (1010862,1013720,9,0),
                (1010862,1013721,10,0),
                (1010862,1013722,11,0),
                (1010862,1013723,12,0),
                (1010862,1013724,13,0),
                (1010862,1013986,14,0),
                (1010862,1013987,15,0),
                (1010863,1013725,1,0),
                (1010863,1013726,2,0),
                (1010863,1013727,3,0),
                (1010863,1013728,4,0),
                (1010863,1013729,5,0),
                (1010863,1013730,6,0),
                (1010863,1013731,7,0),
                (1010863,1013732,8,0),
                (1010863,1013733,9,0),
                (1010863,1013734,10,0),
                (1010863,1013735,11,0),
                (1010863,1013736,12,0),
                (1010863,1013737,13,0),
                (1010863,1013738,14,0),
                (1010863,1013983,15,0),
                (1010863,1013984,16,0),
                (1010863,1013985,17,0),
                (1010864,1013741,1,0),
                (1010864,1013742,2,0),
                (1010864,1013743,3,0),
                (1010864,1013744,4,0),
                (1010864,1013745,5,0),
                (1010864,1013746,6,0);

        insert into db_sysprikey (codarq,codcam,sequen,camiden)
        values  (1010862,1013712,1,1013714),
                (1010863,1013725,1,1013728),
                (1010864,1013741,1,1013746);
        
        insert into db_sysindices
        values  (1008715,'sagresordenadordespesa_c139_instit_in',1010862,'0'),
                (1008716,'sagresordenadordespesa_c139_cgm_in',1010862,'0'),
                (1008717,'sagresordenadordespesa_c139_cgmsubstituto_in',1010862,'0'),
                (1008718,'sagresordenadordespesa_c139_idusuario_in',1010862,'0'),
                (1008719,'sagresresponsavelunidadeorcamentaria_c140_orgao_in',1010863,'0'),
                (1008720,'sagresresponsavelunidadeorcamentaria_c140_unidade_in',1010863,'0'),
                (1008721,'sagresresponsavelunidadeorcamentaria_c140_cgm_in',1010863,'0'),
                (1008722,'sagresresponsavelunidadeorcamentaria_c140_cgmsubstituto_in',1010863,'0'),
                (1008723,'sagresresponsavelunidadeorcamentaria_c140_idusuario_in',1010863,'0'),
                (1008745,'sagresresponsavelunidadeorcamentaria_c140_instit_in',1010863,'0'),
                (1008726,'sagresarquivogerado_usuario_in',1010864,'0');

        insert into db_syscadind
        values  (1008715,1013713,1),
                (1008716,1013714,1),
                (1008717,1013715,1),
                (1008718,1013724,1),
                (1008719,1013726,1),
                (1008720,1013727,1),
                (1008721,1013728,1),
                (1008722,1013729,1),
                (1008723,1013737,1),
                (1008726,1013742,1),
                (1008745,1013983,1);

        insert into db_sysforkey
        values  (1010862,1013713,1,83,1),
                (1010862,1013714,1,42,0),
                (1010862,1013715,1,42,0),
                (1010862,1013724,1,109,0),
                (1010863,1013738,1,756,0),
                (1010863,1013726,2,756,0),
                (1010863,1013738,1,757,0),
                (1010863,1013726,2,757,0),
                (1010863,1013727,3,757,0),
                (1010863,1013728,1,42,0),
                (1010863,1013729,1,42,0),
                (1010863,1013737,1,109,0),
                (1010863,1013983,1,83,0),
                (1010864,1013742,1,109,0);
        
        insert into db_syssequencia
        values  (1001038, 'sagresordenadordespesa_c139_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                (1001039, 'sagresresponsavelunidadeorcamentaria_c140_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                (1001040, 'sagresarquivogerado_c141_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

        update db_sysarqcamp set codsequencia = 1001038 where codarq = 1010862 and codcam = 1013712;
        update db_sysarqcamp set codsequencia = 1001039 where codarq = 1010863 and codcam = 1013725;
        update db_sysarqcamp set codsequencia = 1001040 where codarq = 1010864 and codcam = 1013741;

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values  ( 228625 ,'TCE/PB' ,'TCE/PB' ,'' ,'1' ,'1' ,'TCE/PB' ,'true' ),
                ( 228626 ,'Responsável pela Unidade Orçamentária' ,'Responsável pela Unidade Orçamentária' ,'con1_sagresresponsavelunidadeorcamentaria001.php' ,'1' ,'1' ,'Responsável pela Unidade Orçamentária' ,'true' ),
                ( 228627 ,'Ordenador de Despesa' ,'Ordenador de Despesa' ,'con1_sagresordenadordespesa001.php' ,'1' ,'1' ,'Ordenador de Despesa' ,'true' ),
                ( 228628 ,'Gerar SAGRES' ,'Gerar SAGRES' ,'con3_gerarsagres001.php' ,'1' ,'1' ,'Gerar SAGRES' ,'true' );

        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
        values  ( 6819 ,228625 ,6 ,209 ),
                ( 228625 ,228626 ,1 ,209 ),
                ( 228625 ,228627 ,2 ,209 ),
                ( 228625 ,228628 ,3 ,209 );
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sSql = <<<SQL
        delete from db_menu where id_item_filho in (228625, 228626, 228627, 228628) and modulo = 209;
        delete from db_itensmenu where id_item in (228625, 228626, 228627, 228628);
        delete from db_syssequencia where codsequencia in (1001038, 1001039, 1001040);
        delete from db_sysforkey where codarq in (1010862, 1010863, 1010864);
        delete from db_syscadind where codind in (1008715, 1008716, 1008717, 1008718, 1008719, 1008720, 1008721, 1008722, 1008723, 1008726);
        delete from db_sysindices where codarq in (1010862, 1010863, 1010864);
        delete from db_sysprikey where codarq in (1010862, 1010863, 1010864);
        delete from db_sysarqcamp where codarq in (1010862, 1010863, 1010864);
        delete from db_syscampodef where codcam in (1013716, 1013717, 1013722, 1013730, 1013731, 1013735);
        delete from db_syscampo where codcam between 1013712 and 1013738 or codcam between 1013741 and 1013746;
        delete from db_sysarqmod where codarq in (1010862, 1010863, 1010864);
        delete from db_sysarquivo where codarq in (1010862, 1010863, 1010864);
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

}
