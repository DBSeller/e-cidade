<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19987AdicionaS1299S10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            -- Formulario
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 4000106 ,5 ,'S1299 - Fechamento dos Eventos Periodicos S1.0' ,'s1299-fechamento-dos-eventos-periodicos-s10' ,'S1299 - Fechamento dos Eventos Periodicos S1.0' ,'true' ,'' ,'true' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000243 ,4000106 ,'Informações do fechamento' ,'informacoes-do-fechamento5c38bfc41456es10' ,'infoFech' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000353 ,1 ,4000243 ,'Possui informações relativas a remuneração de trabalhadores ou provento/pensão de beneficiários no período de apuração?' ,'evtRemun-s10' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'evtRemun' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000353;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001471 ,4000353 ,'Sim' ,'sim5c38bfc41f58as10' ,'false' ,0 ,'S' ,'evtRemun_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001472 ,4000353 ,'Não' ,'nao5c38bfc4226a5s10' ,'false' ,0 ,'N' ,'evtRemun_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000354 ,1 ,4000243 ,'Possui informações de comercialização de produção?' ,'evtComProd-s10' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'evtComProd' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000354;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001473 ,4000354 ,'Sim' ,'sim5c38bfc43cbc6s10' ,'false' ,0 ,'S' ,'evtComProd_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001474 ,4000354 ,'Não' ,'nao5c38bfc43f69fs10' ,'false' ,0 ,'N' ,'evtComProd_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000355 ,1 ,4000243 ,'Contratou, por intermédio de sindicato, serviços de trabalhadores avulsos não portuários?' ,'evtContratAvNP-s10' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'evtContratAvNP' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000355;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001475 ,4000355 ,'Sim' ,'sim5c38bfc445d6as10' ,'false' ,0 ,'S' ,'evtContratAvNP_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001476 ,4000355 ,'Não' ,'nao5c38bfc448865s10' ,'false' ,0 ,'N' ,'evtContratAvNP_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000356 ,1 ,4000243 ,'Possui informações de desoneração de folha de pagamento ou, sendo empresa enquadrada no Simples, possui informações sobre a receita obtida em atividades cuja contribuição previdenciária incidente sobre a folha de pagamento é concomitantemente substituída e não substituída?' ,'evtInfoComplPer-s10' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'evtInfoComplPer' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000356;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001477 ,4000356 ,'Sim' ,'sim5c38bfc44f0ccs10' ,'false' ,0 ,'S' ,'evtInfoComplPer_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001478 ,4000356 ,'Não' ,'nao5c38bfc452189s10' ,'false' ,0 ,'N' ,'evtInfoComplPer_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000357 ,1 ,4000243 ,'Indicativo de exclusão de apuração das aquisições de produção rural (eventos S-1250) do período de apuração.' ,'indExcApur1250-s10' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'indExcApur1250' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000357;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001479 ,4000357 ,'Sim' ,'sim5c38123bfc44f0ccs10' ,'false' ,0 ,'S' ,'indExcApur1250_S' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000358 ,1 ,4000243 ,'Solicitação de transmissão imediata da DCTFWeb.' ,'transDCTFWeb-s10' ,'false' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'' ,'transDCTFWeb' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000358;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001481 ,4000358 ,'Sim' ,'sim5c38bfqrqc44f0ccs10' ,'false' ,0 ,'S' ,'transDCTFWeb_S' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000359 ,1 ,4000243 ,'Indicativo de não validação das regras de fechamento, para que os grandes contribuintes possam reduzir o tempo de processamento do evento. O preenchimento deste campo implica a não execução da REGRA_VALIDA_FECHAMENTO_FOPAG.' ,'naoValid-s10' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'naoValid' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000359;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001483 ,4000359 ,'Sim' ,'sim5c3f3ghds8bfc44f0ccs10' ,'false' ,0 ,'S' ,'naoValid_S' );

            -- Atualizando a versao do formulario S1.0
            update recursoshumanos.esocialversaoformulario set rh211_avaliacao = 4000106
                where rh211_esocialformulariotipo = 31 and rh211_versao = 'S1.0';
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
            -- Retornando a versao do formulario S1.0
            update recursoshumanos.esocialversaoformulario set rh211_avaliacao = 3000043
                where rh211_esocialformulariotipo = 31 and rh211_versao = 'S1.0';

            -- Formulario
            delete from habitacao.avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from habitacao.avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from habitacao.avaliacaogrupopergunta where db102_avaliacao in (4000106)));
            delete from habitacao.avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from habitacao.avaliacaogrupopergunta where db102_avaliacao in (4000106));
            delete from habitacao.avaliacaogrupopergunta where db102_avaliacao in (4000106);
            delete from habitacao.avaliacao where db101_sequencial in (4000106);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
