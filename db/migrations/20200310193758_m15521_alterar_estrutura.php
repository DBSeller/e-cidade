<?php

use Classes\PostgresMigration;

class M15521AlterarEstrutura extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    public function dicionarioUp()
    {
        $this->execute("
            update db_syscampo set nomecam = 'ed158_ordem', conteudo = 'int4', descricao = 'Ordem do elemento no procedimento', valorinicial = '0', rotulo = 'Ordem do Elemento', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Ordem do Elemento' where codcam = 1011101;
            update db_syscampo set nomecam = 'ed158_peso', conteudo = 'int4', descricao = 'O peso é utilizado apenas em avaliações onde a forma de cálculo é Média Ponderada (MO)', valorinicial = '0', rotulo = 'Peso da Avaliação', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Peso da Avaliação' where codcam = 1011100;

            delete from db_sysarqcamp where codcam in (1011107, 1011108);
            delete from db_syscampo where codcam in (1011107, 1011108);
        ");
    }

    public function estruturaUp()
    {
        $this->execute("
            alter table areaprocedimentoavaliacao alter column ed158_ordem drop not null;
            alter table areaprocedimentoavaliacao alter column ed158_peso drop not null;
            alter table areaprocedimentoresultado drop column ed159_ordem;
            alter table areaprocedimentoresultado drop column ed159_peso;

            alter table areaprocedimentocomposicaoresultado
                drop constraint areaprocedimentocomposicaoresultado_areaprocedimentoavaliacao_f,
                add constraint areaprocedimentocomposicaoresultado_areaprocedimentoavaliacao_f
                    foreign key (ed160_areaprocedimentoavaliacao) references areaprocedimentoavaliacao on delete cascade;

            alter table diarioareaavaliacao
                drop constraint diarioareaavaliacao_areaprocedimentoavaliacao_fk,
                add constraint diarioareaavaliacao_areaprocedimentoavaliacao_fk
                    foreign key (ed163_areaprocedimentoavaliacao) references areaprocedimentoavaliacao on delete cascade;
        ");
    }

    public function dicionarioDown()
    {
        $this->execute("
            update db_syscampo set nomecam = 'ed158_ordem', conteudo = 'int4', descricao = 'Ordem do elemento no procedimento', valorinicial = '0', rotulo = 'Ordem do Elemento', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Ordem do Elemento' where codcam = 1011101;
            update db_syscampo set nomecam = 'ed158_peso', conteudo = 'int4', descricao = 'O peso é utilizado apenas em avaliações onde a forma de cálculo é Média Ponderada (MO)', valorinicial = '0', rotulo = 'Peso da Avaliação', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Peso da Avaliação' where codcam = 1011100;

            insert into db_syscampo
                values  (1011107,'ed159_peso','int4','O peso é utilizado apenas em avaliações onde a forma de cálculo é Média Ponderada (MO)','1', 'Peso da Avaliação',10,'f','f','f',1,'text','Peso da Avaliação'),
                        (1011108,'ed159_ordem','int4','Ordem do elemento no procedimento','0', 'Ordem do Elemento',10,'f','f','f',1,'text','Ordem do Elemento');
            insert into db_sysarqcamp
                values  (1010535,1011107,6,0),
                        (1010535,1011108,7,0);
        ");
    }

    public function estruturaDown()
    {
        $this->execute("
            alter table areaprocedimentoavaliacao alter column ed158_ordem set not null;
            alter table areaprocedimentoavaliacao alter column ed158_peso set not null;
            alter table areaprocedimentoresultado add column ed159_ordem int4 not null;
            alter table areaprocedimentoresultado add column ed159_peso int4 not null;

        ");
    }
}
