<?php

use Classes\PostgresMigration;

class M10197MelhoriasFormulariosEsocial extends PostgresMigration
{
    public function up()
    {
        $this->execute(
<<<SQL

delete from db_menu where id_item_filho = 10465 AND modulo = 10216;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10475 ,10465 ,2 ,10216 );
update db_itensmenu set id_item = 10488 , descricao = 'Envio de eventos para o eSocial' , help = 'Envio de Arquivos para o eSocial' , funcao = 'eso01_agendamentoenvio.php' , itemativo = '1' , manutencao = '1' , desctec = 'Agendamento dos Eventos para ser enviado ao eSocial.' , libcliente = 'true' where id_item = 10488;
update db_menu set menusequencia = 1 where id_item = 10466 and modulo = 10216 and id_item_filho = 10244;
update db_menu set menusequencia = 2 where id_item = 10466 and modulo = 10216 and id_item_filho = 10426;
update db_menu set menusequencia = 3 where id_item = 10466 and modulo = 10216 and id_item_filho = 10479;
update db_menu set menusequencia = 4 where id_item = 10466 and modulo = 10216 and id_item_filho = 10483;
update db_menu set menusequencia = 5 where id_item = 10466 and modulo = 10216 and id_item_filho = 10484;
update db_menu set menusequencia = 6 where id_item = 10466 and modulo = 10216 and id_item_filho = 10485;
update db_menu set menusequencia = 7 where id_item = 10466 and modulo = 10216 and id_item_filho = 10486;
update db_menu set menusequencia = 8 where id_item = 10466 and modulo = 10216 and id_item_filho = 10493;
update db_menu set menusequencia = 9 where id_item = 10466 and modulo = 10216 and id_item_filho = 10220;
delete from db_menu where id_item_filho = 10493 AND modulo = 10216;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10220 ,10493 ,1 ,10216 );
delete from db_itensmenu where id_item = 10219;
delete from db_menu where id_item_filho = 10219;

update db_menu set menusequencia = 2 where id_item = 10220 and modulo = 10216 and id_item_filho = 10427;

update db_itensmenu set id_item = 10476 , descricao = 'Configuração do Certificado' , help = 'Configuração do certificado' , funcao = 'eso4_enviocertificado001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Configuração do certificado digital' , libcliente = 'true' where id_item = 10476;

update avaliacaopergunta set db103_sequencial = 3000866 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000193 , db103_descricao = 'Selecionar a descrição do FPAS correspondente' , db103_identificador = 'preencher-com-o-codigo-relativo-ao-fpas' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'fpas' where db103_sequencial = 3000866;

update avaliacaopergunta set db103_sequencial = 3000876 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000196 , db103_descricao = 'Informar o nome do empregador.' , db103_identificador = 'informar-o-nome-do-empregador' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'nmRazao' where db103_sequencial = 3000876;
update avaliacaopergunta set db103_sequencial = 3000877 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000196 , db103_descricao = 'Preencher com o código correspondente à classificação tributária do empregador, conforme tabela 8.' , db103_identificador = 'preencher-com-o-codigo-correspondente5a2ac5a3d7d81' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'classTrib' where db103_sequencial = 3000877;
update avaliacaopergunta set db103_sequencial = 3000878 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000196 , db103_descricao = 'Selecionar o código da natureza jurídica do empregador.' , db103_identificador = 'preencher-com-o-codigo-da-natureza-ju5a2ac5a3e8f65' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'natJurid' where db103_sequencial = 3000878;
update avaliacaogrupopergunta set db102_sequencial = 3000210 , db102_avaliacao = 3000015 , db102_descricao = 'Registro que identifica o processo em que houve decisão ou sentença favorável ao empregador - FAP.' , db102_identificador = 'registro-que-identifica-o-processo-em5a2ac5a46b340' , db102_identificadorcampo = 'procAdmJudFap' , db102_ordem = 1 where db102_sequencial = 3000210;

update avaliacaopergunta set db103_sequencial = 3000942 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000231 , db103_descricao = 'Início da validade, no formato AAAA-MM:' , db103_identificador = 'inicio-da-validade' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 4 , db103_tipo = 1 , db103_mascara = '9999-99' , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'iniValid' where db103_sequencial = 3000942;
update avaliacaopergunta set db103_sequencial = 3000943 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000231 , db103_descricao = 'Término da validade, no formato AAAA-MM:' , db103_identificador = 'termino-da-validade' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 5 , db103_tipo = 1 , db103_mascara = '9999-99' , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'fimValid' where db103_sequencial = 3000943;

SQL
        );
    }

    public function down()
    {
        $this->execute(
<<<SQL
delete from db_menu where id_item_filho = 10465 AND modulo = 10216;
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (32, 10465, 491, 10216);
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values (10466, 10493, 9, 10216);

update db_menu set menusequencia = 3 where id_item = 10466 and modulo = 10216 and id_item_filho = 10244;
update db_menu set menusequencia = 2 where id_item = 10466 and modulo = 10216 and id_item_filho = 10426;
update db_menu set menusequencia = 4 where id_item = 10466 and modulo = 10216 and id_item_filho = 10479;
update db_menu set menusequencia = 5 where id_item = 10466 and modulo = 10216 and id_item_filho = 10483;
update db_menu set menusequencia = 6 where id_item = 10466 and modulo = 10216 and id_item_filho = 10484;
update db_menu set menusequencia = 7 where id_item = 10466 and modulo = 10216 and id_item_filho = 10485;
update db_menu set menusequencia = 8 where id_item = 10466 and modulo = 10216 and id_item_filho = 10486;
update db_menu set menusequencia = 9 where id_item = 10466 and modulo = 10216 and id_item_filho = 10493;
update db_menu set menusequencia = 1 where id_item = 10466 and modulo = 10216 and id_item_filho = 10220;

insert into db_itensmenu values (10219, 'Preenchimento', 'Preenchimento', 'eso4_preenchimento001.php', 1, 1, 'Preenche o formulário do e-social', true);
insert into db_menu values(10220, 10219, 2, 10216);
update db_menu set menusequencia = 1 where id_item = 10220 and modulo = 10216 and id_item_filho = 10427;

update db_itensmenu set id_item = 10476 , descricao = 'Envio do Certificado' , help = 'Envio do Certificado' , funcao = 'eso4_enviocertificado001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Envio do certificado digital para a api' , libcliente = 'true' where id_item = 10476;

update avaliacaopergunta set db103_sequencial = 3000866 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000193 , db103_descricao = 'Preencher com o código relativo ao FPAS' , db103_identificador = 'preencher-com-o-codigo-relativo-ao-fpas' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'fpas' where db103_sequencial = 3000866;

update avaliacaopergunta set db103_sequencial = 3000876 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000196 , db103_descricao = 'Informar o nome do contribuinte.' , db103_identificador = 'informar-o-nome-do-contribuinte' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'nmRazao' where db103_sequencial = 3000876;
update avaliacaopergunta set db103_sequencial = 3000876 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000196 , db103_descricao = 'Informar o nome do contribuinte.' , db103_identificador = 'informar-o-nome-do-contribuinte' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'nmRazao' where db103_sequencial = 3000876;
update avaliacaopergunta set db103_sequencial = 3000878 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000196 , db103_descricao = 'Preencher com o código da Natureza Jurídica do Contribuinte.' , db103_identificador = 'preencher-com-o-codigo-da-natureza-ju5a2ac5a3e8f65' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'natJurid' where db103_sequencial = 3000878;
update avaliacaogrupopergunta set db102_sequencial = 3000210 , db102_avaliacao = 3000015 , db102_descricao = 'Registro que identifica o processo em que houve decisão ou sentença favorável ao contribuinte - FAP.' , db102_identificador = 'registro-que-identifica-o-processo-em5a2ac5a46b340' , db102_identificadorcampo = 'procAdmJudFap' , db102_ordem = 1 where db102_sequencial = 3000210;

update avaliacaopergunta set db103_sequencial = 3000942 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000231 , db103_descricao = 'Início da validade:' , db103_identificador = 'inicio-da-validade' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 4 , db103_tipo = 1 , db103_mascara = '9999-99' , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'iniValid' where db103_sequencial = 3000942;
update avaliacaopergunta set db103_sequencial = 3000943 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000231 , db103_descricao = 'Término da validade:' , db103_identificador = 'termino-da-validade' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 5 , db103_tipo = 1 , db103_mascara = '9999-99' , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'fimValid' where db103_sequencial = 3000943;

SQL
        );

    }
}
