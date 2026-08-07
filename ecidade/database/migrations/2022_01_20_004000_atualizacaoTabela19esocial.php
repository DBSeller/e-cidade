<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AtualizacaoTabela19esocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            update habitacao.avaliacaoperguntaopcao set db104_sequencial = 3005524 , db104_avaliacaopergunta = 3001773 , db104_descricao = 'Mudança de CPF' , db104_identificador = 'termino-do-exercicio-do-mandato-eletivo' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '36' , db104_identificadorcampo = 'desligamento_causa_rescisao_36' where db104_sequencial = 3005524;
            update habitacao.avaliacaoperguntaopcao set db104_sequencial = 3005526 , db104_avaliacaopergunta = 3001773 , db104_descricao = 'Aposentadoria, exceto por invalidez' , db104_identificador = 'aposentadoria-de-servidor-estatutario-exceto-por-i' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '38' , db104_identificadorcampo = 'desligamento_causa_rescisao_38' where db104_sequencial = 3005526;

            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001468 ,3001773 ,'Término do exercício do mandato eletivo' ,'termino_exercício_mandato_eletivo' ,'false' ,0 ,'40' ,'desligamento_causa_rescisao_40' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001469 ,3001773 ,'Rescisão do contrato de aprendizagem por desempenho insuficiente ou inadaptação do aprendiz' ,'rescisao_aprendizagem_desempenho_insuficiente' ,'false' ,0 ,'41' ,'desligamento_causa_rescisao_41' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001470 ,3001773 ,'Rescisão do contrato de aprendizagem por ausência injustificada do aprendiz à escola que implique perda do ano letivo' ,'rescisao_aprendizagem_ausencia_injustificada' ,'false' ,0 ,'42' ,'desligamento_causa_rescisao_42' );

            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (3005506, 3005507, 3005508)); 
            delete from habitacao.avaliacaoperguntaopcao where db104_sequencial in (3005506, 3005507, 3005508);

            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (3005516,3005523)); 
            delete from habitacao.avaliacaoperguntaopcao where db104_sequencial in (3005516,3005523);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
            insert into habitacao.avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo) values(3005506, 3001773, 'Aposentadoria Compulsória (somente para categorias de trabalhadores 301 a 309)', 'aposentadoria-compulsoria-somente-para-categorias-', 'false', 0, '18', 'desligamento_causa_rescisao_18');
            insert into habitacao.avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo) values(3005507, 3001773, 'Aposentadoria por idade (somente para categorias de trabalhadores 301 a 309)', 'aposentadoria-por-idade-somente-para-categorias-de', 'false', 0, '19', 'desligamento_causa_rescisao_19');
            insert into habitacao.avaliacaoperguntaopcao (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo) values(3005508, 3001773, 'Aposentadoria por idade e tempo de contribuição (somente categorias 301 a 309)', 'aposentadoria-por-idade-e-tempo-de-contribuicao-so', 'false', 0, '20', 'desligamento_causa_rescisao_20');
    
            delete from habitacao.avaliacaoperguntaopcao where db104_sequencial in (4001468, 4001469, 4001470);

            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3005516 ,3001773 ,'Término da Cessão/Requisição' ,'termino-da-cessaorequisicao' ,'false' ,0 ,'28' ,'desligamento_causa_rescisao_28' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3005523 ,3001773 ,'Extinção do contrato de trabalho intermitente' ,'extincao-do-contrato-de-trabalho-intermitente' ,'false' ,0 ,'35' ,'desligamento_causa_rescisao_35' );

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
