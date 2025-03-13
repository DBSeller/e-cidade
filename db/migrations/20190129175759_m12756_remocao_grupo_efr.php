<?php

use Classes\PostgresMigration;

class M12756RemocaoGrupoEfr extends PostgresMigration
{
    public function up()
    {
        $sql = "
            CREATE TABLE bkp_tarefa_12756 AS
            SELECT db102_sequencial as grupo,
                   db103_sequencial as pergunta,
                   db104_sequencial as opcao,
                   db106_sequencial as resposta
            FROM avaliacaogrupopergunta
            JOIN avaliacaopergunta on avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
            JOIN avaliacaoperguntaopcao on avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
            LEFT JOIN avaliacaoresposta on avaliacaoresposta.db106_avaliacaoperguntaopcao = avaliacaoperguntaopcao.db104_sequencial
            WHERE db102_avaliacao = 3000034 AND db102_identificadorcampo = 'infoEFR';
            
            DELETE FROM avaliacaogrupoperguntaresposta WHERE db108_avaliacaoresposta IN (SELECT resposta FROM bkp_tarefa_12756);
            DELETE FROM avaliacaoresposta WHERE db106_sequencial IN (SELECT resposta FROM bkp_tarefa_12756);
            DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial IN (SELECT opcao FROM bkp_tarefa_12756);
            DELETE FROM avaliacaopergunta WHERE db103_sequencial IN (SELECT pergunta FROM bkp_tarefa_12756);
            DELETE FROM avaliacaogrupopergunta WHERE db102_sequencial IN (SELECT grupo FROM bkp_tarefa_12756);
            
            DROP TABLE bkp_tarefa_12756;
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000529 ,3000034 ,'Informações de órgãos públicos estaduais e municipais relativas a Ente Federativo Responsável - EFR' ,'informacoes-de-orgaos-publicos-estaduais-e-municip' ,'infoEFR' ,5 );
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002391 ,1 ,3000529 ,'Informar se o Órgão Público é o Ente Federativo Responsável - EFR ou se é uma unidade administrativa autônoma vinculada a um EFR' ,'informar-se-o-orgao-publico-e-o-ente-federativo-re' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'ideEFR' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000942 ,3002391 ,'S - É EFR' ,'s-e-efr' ,'false' ,0 ,'S' ,'ideEFR_s' );
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000943 ,3002391 ,'N - Não é EFR' ,'n-nao-e-efr' ,'false' ,0 ,'N' ,'ideEFR_n' );
        ";
        $this->execute($sql);
    }
}
