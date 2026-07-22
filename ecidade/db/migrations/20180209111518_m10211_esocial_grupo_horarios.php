<?php

use Classes\PostgresMigration;

class M10211EsocialGrupoHorarios extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000247 ,3000013 ,'Horários' ,'horarios' ,'horario' ,40 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001032 ,2 ,3000247 ,'Código da Jornada na Segunda-Feira' ,'codigo-da-jornada-na-segundafeira' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004042 ,3001032 ,'' ,'5a7dc82a2337e' ,'true' ,0 ,'' ,'horario_codHorContrat_1' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001033 ,2 ,3000247 ,'Código da Jornada na Terça-Feira' ,'codigo-da-jornada-na-tercafeira' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004043 ,3001033 ,'' ,'5a7dc82a249ae' ,'true' ,0 ,'' ,'horario_codHorContrat_2' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001034 ,2 ,3000247 ,'Código da Jornada na Quarta-Feira' ,'codigo-da-jornada-na-quartafeira' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_3' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004044 ,3001034 ,'' ,'5a7dc82a25eb9' ,'true' ,0 ,'' ,'horario_codHorContrat_3' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001035 ,2 ,3000247 ,'Código da Jornada na Quinta-Feira' ,'codigo-da-jornada-na-quintafeira' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_4' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004045 ,3001035 ,'' ,'5a7dc82a27343' ,'true' ,0 ,'' ,'horario_codHorContrat_4' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001036 ,2 ,3000247 ,'Código da Jornada na Sexta-Feira' ,'codigo-da-jornada-na-sextafeira' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_5' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004046 ,3001036 ,'' ,'5a7dc82a2888d' ,'true' ,0 ,'' ,'horario_codHorContrat_5' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001037 ,2 ,3000247 ,'Código da Jornada no Sábado' ,'codigo-da-jornada-no-sabado' ,'false' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_6' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004047 ,3001037 ,'' ,'5a7dc82a29dc8' ,'true' ,0 ,'' ,'horario_codHorContrat_6' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001038 ,2 ,3000247 ,'Código da Jornada no Domingo' ,'codigo-da-jornada-no-domingo' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_7' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004048 ,3001038 ,'' ,'5a7dc82a2b189' ,'true' ,0 ,'' ,'horario_codHorContrat_7' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001039 ,2 ,3000247 ,'Código da Jornada em Dia Variável' ,'codigo-da-jornada-em-dia-variavel' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'horario_codHorContrat_8' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004049 ,3001039 ,'' ,'5a7dc82a2c71d' ,'true' ,0 ,'' ,'horario_codHorContrat_8' );
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            delete from avaliacaoperguntaopcao where db104_sequencial in (3004042, 3004043, 3004044, 3004045, 3004046, 3004047, 3004048, 3004049);
            delete from avaliacaopergunta where db103_sequencial in (3001032,3001033,3001034,3001035,3001036,3001037,3001038,3001039);
            delete from avaliacaogrupopergunta where db102_sequencial in (3000247);
SQL;
        $this->execute($sql);
    }
}
