<?php

use Classes\PostgresMigration;

class M10657S1299Formulario extends PostgresMigration
{

    public function up()
    {
        $this->formulario();
    }

    private function formulario()
    {
        $this->execute("
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000043 ,5 ,'S-1299 - Fechamento dos Eventos Periódicos' ,'s1299-fechamento-dos-eventos-periodicos' ,'S-1299 - Fechamento dos Eventos Periódicos' ,'true' ,'' ,'true' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000217 ,3000043 ,'Responsável pelas informações.' ,'responsavel-pelas-informacoes5c38bfc3d9a4e' ,'ideRespInf' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000210 ,2 ,4000217 ,'Nome do responsável pelas informações:' ,'nome-do-responsavel-pelas-informacoes5c38bfc3e375b' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmResp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000210;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001167 ,4000210 ,'' ,'5c38bfc3ed0bf' ,'true' ,0 ,'' ,'nmResp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000211 ,2 ,4000217 ,'Preencher com o CPF do responsável:' ,'preencher-com-o-cpf-do-responsavel' ,'false' ,'true' ,2 ,4 ,'' ,0 ,'false' ,'' ,'cpfResp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000211;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001168 ,4000211 ,'' ,'5c38bfc4035b0' ,'true' ,0 ,'' ,'cpfResp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000212 ,2 ,4000217 ,'Informar o número do telefone, com DDD:' ,'informar-o-numero-do-telefone-com-dd5c38bfc406744' ,'false' ,'true' ,3 ,7 ,'' ,0 ,'false' ,'' ,'telefone' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000212;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001169 ,4000212 ,'' ,'5c38bfc40a8da' ,'true' ,0 ,'' ,'telefone' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000213 ,2 ,4000217 ,'Endereço eletrônico:' ,'endereco-eletronico5c38bfc40d872' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'email' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000213;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001170 ,4000213 ,'' ,'5c38bfc411851' ,'true' ,0 ,'' ,'email' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000218 ,3000043 ,'Informações do Fechamento.' ,'informacoes-do-fechamento5c38bfc41456e' ,'infoFech' ,2 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000214 ,1 ,4000218 ,'Possui informações relativas remuneração de trabalhadores no período de apuração?' ,'possui-informacoes-relativas-remuneracao-de-trabal' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'evtRemun' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000214;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001171 ,4000214 ,'Sim' ,'sim5c38bfc41f58a' ,'false' ,0 ,'S' ,'evtRemun_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001172 ,4000214 ,'Não' ,'nao5c38bfc4226a5' ,'false' ,0 ,'N' ,'evtRemun_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000215 ,1 ,4000218 ,'Possui informações de pagamento de rendimentos do trabalho no período de apuração?' ,'possui-informacoes-de-pagamento-de-rendimentos-do-' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'evtPgtos' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000215;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001173 ,4000215 ,'Sim' ,'sim5c38bfc429d0b' ,'false' ,0 ,'S' ,'evtPgtos_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001174 ,4000215 ,'Não' ,'nao5c38bfc42cc2e' ,'false' ,0 ,'N' ,'evtPgtos_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000216 ,1 ,4000218 ,'Possui informações sobre a aquisição de produto rural de pessoas físicas?' ,'possui-informacoes-sobre-a-aquisicao-de-produto-ru' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'evtAqProd' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000216;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001175 ,4000216 ,'Sim' ,'sim5c38bfc433933' ,'false' ,0 ,'S' ,'evtAqProd_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001176 ,4000216 ,'Não' ,'nao5c38bfc4366a0' ,'false' ,0 ,'N' ,'evtAqProd_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000217 ,1 ,4000218 ,'Possui informações de comercialização de produção?' ,'possui-informacoes-de-comercializacao-de-producao' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'evtComProd' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000217;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001177 ,4000217 ,'Sim' ,'sim5c38bfc43cbc6' ,'false' ,0 ,'S' ,'evtComProd_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001178 ,4000217 ,'Não' ,'nao5c38bfc43f69f' ,'false' ,0 ,'N' ,'evtComProd_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000218 ,1 ,4000218 ,'Contratou, por intermédio de sindicato, serviços de trabalhadores avulsos não portuários?' ,'contratou-por-intermedio-de-sindicato-servicos-de-' ,'true' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'evtContratAvNP' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000218;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001179 ,4000218 ,'Sim' ,'sim5c38bfc445d6a' ,'false' ,0 ,'S' ,'evtContratAvNP_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001180 ,4000218 ,'Não' ,'nao5c38bfc448865' ,'false' ,0 ,'N' ,'evtContratAvNP_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000219 ,1 ,4000218 ,'Possui informações de desoneração de folha de pagamento ou, sendo empresa enquadrada no Simples, possui informações sobre a receita obtida em atividades cuja contribuição previdenciária incidente sobre a folha de pagamento é concomitantemente substituída e também não substituída?' ,'possui-informacoes-de-desoneracao-de-folha-de-paga' ,'true' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'' ,'evtInfoComplPer' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000219;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001181 ,4000219 ,'Sim' ,'sim5c38bfc44f0cc' ,'false' ,0 ,'S' ,'evtInfoComplPer_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001182 ,4000219 ,'Não' ,'nao5c38bfc452189' ,'false' ,0 ,'N' ,'evtInfoComplPer_N' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000220 ,2 ,4000218 ,'Informar a primeira competência a partir da qual não houve movimento, cuja situação perdura até a competência atual.' ,'informar-a-primeira-competencia-a-partir-da-qual-n' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'compSemMovto' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000220;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001183 ,4000220 ,'' ,'5c38bfc458efb' ,'true' ,0 ,'' ,'compSemMovto' );
        ");
    }

    public function down()
    {
        $this->execute("        
        create temp table x_avaliacaopergunta as 
              select db103_sequencial 
                from avaliacaopergunta 
               where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000043);
            
            create temp table x_avaliacaoperguntaopcao as 
              select db104_sequencial 
                from avaliacaoperguntaopcao 
               where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaogrupopergunta where db102_avaliacao = 3000043;
            delete from avaliacao where db101_sequencial = 3000043;
            
            drop table x_avaliacaopergunta;
            drop table x_avaliacaoperguntaopcao;
        ");
    }
}
