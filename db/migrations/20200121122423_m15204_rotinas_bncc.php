<?php

use Classes\PostgresMigration;

class M15204RotinasBncc extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values (228203 ,'BNCC' ,'BNCC' ,'' ,'1' ,'1' ,'Rotinas de configuração da BNCC' ,'true' ),
               (228204 ,'De Para' ,'De Para' ,'' ,'1' ,'1' ,'Configura os de para do e-cidade com a BNCC' ,'true' ),
               (228205 ,'Disciplinas/Componentes Curriculares' ,'Disciplinas/Componentes Curriculares' ,'edu1_bncc_depara_disciplinas001.php' ,'1' ,'1' ,'De/Para para as Disciplinas/Componentes Curriculares ' ,'true' ),
               (228206 ,'Etapas' ,'Etapas' ,'edu1_bncc_depara_etapas001.php' ,'1' ,'1' ,'De Para das etapas' ,'true' ),
               (228207 ,'Importar Tabela de Habilidades' ,'Importar Tabela de Habilidades' ,'edu1_bncc_importar_habilidade001.php' ,'1' ,'1' ,'Importar Tabela de Habilidades' ,'false' );

        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
         values ( 3470 ,228203 ,44 ,7159 ),
                ( 228203 ,228204 ,1 ,7159 ),
                ( 228204 ,228206 ,1 ,7159 ),
                ( 228204 ,228205 ,2 ,7159 ),
                ( 228203 ,228207 ,2 ,7159 );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho in (228203, 228204, 228205, 228206, 228207) AND modulo = 7159;
            delete from db_itensmenu where id_item in (228203, 228204, 228205, 228206, 228207);
        ");
    }
}
