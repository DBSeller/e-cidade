<?php

use Classes\PostgresMigration;

class M18018AjusteTabela18Esocial extends PostgresMigration
{
    public function up () {
        $sql = <<<SQL
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 5: Afastamento/licença prevista em regime próprio (estatuto), sem remuneração' where db18_valor = 'Afastamento/licença prevista em regime próprio (estatuto), sem remuneração' and db18_opcao = '5';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 7: Acompanhamento: Licença para acompanhamento de membro da família enfermo' where db18_valor = 'Acompanhamento: Licença para acompanhamento de membro da família enfermo' and db18_opcao = '7';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 8: Afastamento do empregado para participar de atividade do Conselho Curador do FGTS' where db18_valor = 'Afastamento do empregado para participar de atividade do Conselho Curador do FGTS' and db18_opcao = '8';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 10: Afastamento/licença prevista em regime próprio (estatuto), com remuneração' where db18_valor = 'Afastamento/licença prevista em regime próprio (estatuto), com remuneração' and db18_opcao = '10';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 16: Licença remunerada' where db18_valor = 'Licença remunerada' and db18_opcao = '16';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 12: Candidato a cargo eletivo: Lei 7.664/1988. art. 25°' where db18_valor = 'Candidato a cargo eletivo: Lei 7.664/1988. art. 25°' and db18_opcao = '12';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 13: Candidato a cargo eletivo: Lei Complementar 64/1990. art. 1°' where db18_valor = 'Candidato a cargo eletivo: Lei Complementar 64/1990. art. 1°' and db18_opcao = '13';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 17: Licença Maternidade: 120 dias e suas prorrogações/antecipações' where db18_valor = 'Licença Maternidade: 120 dias e suas prorrogações/antecipações' and db18_opcao = '17';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 18: Licença Maternidade: 120 dias a 180 dias, Lei 11.770/2008 (Empresa Cidadã), inclusive para o cônjuge sobrevivente' where db18_valor = 'Licença Maternidade: 120 dias a 180 dias' and db18_opcao = '18';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 19: Licença Maternidade: Afastamento temporário por motivo de aborto não criminoso' where db18_valor = 'Licença Maternidade: Afastamento temporário por motivo de aborto não criminoso' and db18_opcao = '19';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 20: Licença Maternidade: Afastamento temporário por adoção ou guarda judicial' where db18_valor = 'Licença Maternidade: Afastamento temporário por adoção ou guarda judicial' and db18_opcao = '20';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 33: Licença Maternidade: De 180 dias' where db18_valor = 'Licença Maternidade: De 180 dias' and db18_opcao = '33';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 22: Afastamento temporário para o exercício de mandato eleitoral, sem remuneração' where db18_valor = 'Afastamento temporário para o exercício de mandato eleitoral, sem remuneração' and db18_opcao = '22';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Cód. 23: Afastamento temporário para o exercício de mandato eleitoral, com remuneração' where db18_valor = 'Afastamento temporário para o exercício de mandato eleitoral, com remuneração' and db18_opcao = '23';

        --Acidente/Doença do trabalho - Motivo 1
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '1';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '1', 'Cod. 1: Acidente/Doença do trabalho' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '1');

        --Acidente/Doença não relacionada ao trabalho - Motivo 3
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '3';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '3', 'Cod. 3: Acidente/Doença não relacionada ao trabalho' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '3');

        --Aposentadoria por invalidez - Motivo 6
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '6';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '6', 'Cod. 6: Aposentadoria por invalidez' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '6');

        --Cárcere - Motivo 11
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '11';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '11', 'Cod. 11: Cárcere' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '11');

        --Cessão/Requisição - Motivo 14
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '14';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '14', 'Cod. 14: Cessão/Requisição' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '14');

        --Gozo de férias - Motivo 15
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '15';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '15', 'Cod. 15: Gozo de férias ou recesso' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '15');

        --Mandado Sindical - Motivo 24
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '24';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '24', 'Cod. 24: Mandato Sindical: Afastamento temporário para exercício de mandato sindical' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '24');

        --Mulher vítima de violência - Motivo 25
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '25';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '25', 'Cod. 25: Mulher vítima de violência: Lei 11.340/2006, art. 9º §2o, II: Lei Maria da Penha' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '25');

        --Participação de empregado no Conselho Nacional de Previdência - Motivo 26
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '26';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '26', 'Cod. 26: Participação de empregado no Conselho Nacional de Previdência Social-CNPS (art. 3º, Lei 8.213/1991)' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '26');

        --Qualificação  Afastamento por suspensão do contrato de acordo - Motivo 27
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '27';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '27', 'Cod. 27: Qualificação: Afastamento por suspensão do contrato de acordo com o art 476A da CLT' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '27');

        --Representante Sindical - Motivo 28
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '28';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '28', 'Cod. 28: Representante Sindical' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '28');

        --Serviço Militar - Motivo 29
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '29';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '29', 'Cod. 29: Serviço Militar: Afastamento temporário para prestar serviço militar obrigatório' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '29');

        --Suspensão disciplinar - Motivo 30
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '30';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '30', 'Cod. 30: Suspensão disciplinar - CLT, art. 474' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '30');

        --Servidor Público em Disponibilidade - Motivo 31
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '31';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '31', 'Cod. 31: Servidor Público em Disponibilidade' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '31');

        --Inatividade do trabalhador avulso por período superior a 90 dias - Motivo 34
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '34';
        insert into db_cadattdinamicoatributosopcoes (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),  db109_sequencial, '34', 'Cod. 34: Inatividade do trabalhador avulso (portuário ou não portuário) por período superior a 90 dias' from db_cadattdinamicoatributos where  db109_nome = 'motivo_esocial' and db109_valordefault = '34');

SQL;
        $this->execute($sql);
    }

    public function down() {
        $sql = <<<SQL
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Afastamento/licença prevista em regime próprio (estatuto), sem remuneração' where db18_valor = 'Cód. 5: Afastamento/licença prevista em regime próprio (estatuto), sem remuneração' and db18_opcao = '5';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Acompanhamento: Licença para acompanhamento de membro da família enfermo' where db18_valor = 'Cód. 7: Acompanhamento: Licença para acompanhamento de membro da família enfermo' and db18_opcao = '7';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Afastamento do empregado para participar de atividade do Conselho Curador do FGTS' where db18_valor = 'Cód. 8: Afastamento do empregado para participar de atividade do Conselho Curador do FGTS' and db18_opcao = '8';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Afastamento/licença prevista em regime próprio (estatuto), com remuneração' where db18_valor = 'Cód. 10: Afastamento/licença prevista em regime próprio (estatuto), com remuneração' and db18_opcao = '10';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Licença remunerada' where db18_valor = 'Cód. 16: Licença remunerada' and db18_opcao = '16';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Candidato a cargo eletivo: Lei 7.664/1988. art. 25°' where db18_valor = 'Cód. 12: Candidato a cargo eletivo: Lei 7.664/1988. art. 25°' and db18_opcao = '12';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Candidato a cargo eletivo: Lei Complementar 64/1990. art. 1°' where db18_valor = 'Cód. 13: Candidato a cargo eletivo: Lei Complementar 64/1990. art. 1°' and db18_opcao = '13';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Licença Maternidade: 120 dias e suas prorrogações/antecipações' where db18_valor = 'Cód. 17: Licença Maternidade: 120 dias e suas prorrogações/antecipações' and db18_opcao = '17';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Licença Maternidade: 120 dias a 180 dias' where db18_valor = 'Cód. 18: Licença Maternidade: 120 dias a 180 dias, Lei 11.770/2008 (Empresa Cidadã), inclusive para o cônjuge sobrevivente' and db18_opcao = '18';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Licença Maternidade: Afastamento temporário por motivo de aborto não criminoso' where db18_valor = 'Cód. 19: Licença Maternidade: Afastamento temporário por motivo de aborto não criminoso' and db18_opcao = '19';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Licença Maternidade: Afastamento temporário por adoção ou guarda judicial' where db18_valor = 'Cód. 20: Licença Maternidade: Afastamento temporário por adoção ou guarda judicial' and db18_opcao = '20';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Licença Maternidade: De 180 dias' where db18_valor = 'Cód. 33: Licença Maternidade: De 180 dias' and db18_opcao = '33';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Afastamento temporário para o exercício de mandato eleitoral, sem remuneração' where db18_valor = 'Cód. 22: Afastamento temporário para o exercício de mandato eleitoral, sem remuneração' and db18_opcao = '22';
        update db_cadattdinamicoatributosopcoes set db18_valor = 'Afastamento temporário para o exercício de mandato eleitoral, com remuneração' where db18_valor = 'Cód. 23: Afastamento temporário para o exercício de mandato eleitoral, com remuneração' and db18_opcao = '23';

        --Acidente/Doença do trabalho - Motivo 1
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '1';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 1: Acidente/Doença do trabalho' and db18_opcao = '1';

        --Acidente/Doença não relacionada ao trabalho - Motivo 3
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '3';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 3: Acidente/Doença não relacionada ao trabalho' and db18_opcao = '3';

        --Aposentadoria por invalidez - Motivo 6
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '6';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 6: Aposentadoria por invalidez' and db18_opcao = '6';

        --Cárcere - Motivo 11
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '11';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 11: Cárcere' and db18_opcao = '11';

        --Cessão/Requisição - Motivo 14
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '14';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 14: Cessão/Requisição' and db18_opcao = '14';

        --Gozo de férias - Motivo 15
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '15';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 15: Gozo de férias ou recesso' and db18_opcao = '15';

        --Mandado Sindical - Motivo 24
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '24';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 24: Mandato Sindical: Afastamento temporário para exercício de mandato sindical' and db18_opcao = '24';

        --Mulher vítima de violência - Motivo 25
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '25';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 25: Mulher vítima de violência: Lei 11.340/2006, art. 9º §2o, II: Lei Maria da Penha' and db18_opcao = '25';

        --Participação de empregado no Conselho Nacional de Previdência - Motivo 26
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '26';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 26: Participação de empregado no Conselho Nacional de Previdência Social-CNPS (art. 3º, Lei 8.213/1991)' and db18_opcao = '26';

        --Qualificação  Afastamento por suspensão do contrato de acordo - Motivo 27
        update db_cadattdinamicoatributos set db109_tipo = 7 where db109_nome = 'motivo_esocial' and db109_valordefault = '27';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 27: Qualificação: Afastamento por suspensão do contrato de acordo com o art 476A da CLT' and db18_opcao = '27';

        --Representante Sindical - Motivo 28
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '28';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 28: Representante Sindical' and db18_opcao = '28';

        --Serviço Militar - Motivo 29
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '29';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 29: Serviço Militar: Afastamento temporário para prestar serviço militar obrigatório' and db18_opcao = '29';

        --Suspensão disciplinar - Motivo 30
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '30';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 30: Suspensão disciplinar - CLT, art. 474' and db18_opcao = '30';

        --Servidor Público em Disponibilidade - Motivo 31
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '31';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 31: Servidor Público em Disponibilidade' and db18_opcao = '31';

        --Inatividade do trabalhador avulso por período superior a 90 dias - Motivo 34
        update db_cadattdinamicoatributos set db109_tipo = 6 where db109_nome = 'motivo_esocial' and db109_valordefault = '34';
        delete from db_cadattdinamicoatributosopcoes where db18_valor = 'Cod. 34: Inatividade do trabalhador avulso (portuário ou não portuário) por período superior a 90 dias' and db18_opcao = '34';

SQL;
        $this->execute($sql);

    }
}
