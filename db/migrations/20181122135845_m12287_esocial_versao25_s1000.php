<?php

use Classes\PostgresMigration;

class M12287EsocialVersao25S1000 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) VALUES ( 3002360 ,1 ,3000196 ,'Indicativo da opção pelo produtor rural pela forma de tributação da contribuição previdenciária, nos termos do art. 25, §13, da Lei 8.212/1991 e do art. 25, §7°, da Lei 8.870/1994.' ,'indicativo-da-opcao-pelo-produtor-rural-pela-forma' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'indOpcCP' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 4000896 ,3002360 ,'Sobre a comercialização da sua produção' ,'sobre-a-comercializacao-da-sua-producao' ,'false' ,0 ,'1' ,'indOpcCP_1' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) VALUES ( 4000897 ,3002360 ,'Sobre a folha de pagamento.' ,'sobre-a-folha-de-pagamento' ,'false' ,0 ,'2' ,'indOpcCP_2' );
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM avaliacaoperguntaopcao WHERE db104_avaliacaopergunta = 3002360;
            DELETE FROM avaliacaopergunta WHERE db103_sequencial = 3002360;
        ");
    }
}
