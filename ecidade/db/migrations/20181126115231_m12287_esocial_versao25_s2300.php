<?php

use Classes\PostgresMigration;

class M12287EsocialVersao25S2300 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) VALUES ( 3000523 ,3000027 ,'Informações de mudança de CPF do trabalhador.' ,'informacoes-de-mudanca-de-cpf-do-trab5bfbddc30ee6a' ,'mudancaCPF' ,34 );
    
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo )
            VALUES ( 3002370 ,2 ,3000523 ,'Preencher com o número do CPF antigo do trabalhador.' ,'preencher-com-o-numero-do-cpf-antigo-5bfbddc30f6ee' ,'false' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'cpfAnt' ),
                   ( 3002371 ,2 ,3000523 ,'Data de alteração do CPF.' ,'data-de-alteracao-do-cpf5bfbddc310dee' ,'false' ,'true' ,2 ,5 ,'' ,0 ,'false' ,'' ,'dtAltCPF' ),
                   ( 3002372 ,2 ,3000523 ,'Observação' ,'observacao5bfbddc314441' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'mudancaCPF_observacao' );
            
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo )
            VALUES ( 4000911 ,3002370 ,'' ,'5bfbddc310509' ,'true' ,0 ,'' ,'cpfAnt' ),
                   ( 4000912 ,3002371 ,'' ,'5bfbddc311b3b' ,'true' ,0 ,'' ,'dtAltCPF' ),
                   ( 4000913 ,3002372 ,'' ,'5bfbddc3155ba' ,'true' ,0 ,'' ,'mudancaCPF_observacao' );
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial IN (4000911, 4000912, 4000913);
            DELETE FROM avaliacaopergunta WHERE db103_sequencial IN (3002370, 3002371, 3002372);
            DELETE FROM avaliacaogrupopergunta WHERE db102_sequencial IN (3000523);
        ");
    }

}
