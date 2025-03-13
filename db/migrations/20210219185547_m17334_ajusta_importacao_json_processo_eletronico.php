<?php

use Classes\PostgresMigration;

class M17334AjustaImportacaoJsonProcessoEletronico extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            alter table protocolo.tipoprocessoformulario add column p108_rota text;
            alter table protocolo.tipoprocessoformulario alter column p108_formulario DROP not null;
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            alter table protocolo.tipoprocessoformulario drop column p108_rota;
            alter table protocolo.tipoprocessoformulario alter column p108_formulario set not null;
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1012586,'p108_rota','varchar(255)','A rota quer deve ser redirecionado ao clicar no link do card no processo eletrônico.','', 'Rota',255,'t','f','f',0,'text','Rota');
            insert into db_sysarqcamp values(1010558,1012586,4,0);

            update db_syscampo set nomecam = 'p108_formulario', conteudo = 'text', descricao = 'formulario do tipo de processo', valorinicial = '', rotulo = 'formulario', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'formulario' where codcam = 1011246;

            insert into db_syssequencia values(1000994, 'tipoprocessoformulario_p108_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000994 where codarq = 1010558 and codcam = 1011245;
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam in (
                1012586
            );

            delete from db_syscampo where codcam in (
                1012586
            );

            update db_syscampo set nomecam = 'p108_formulario', conteudo = 'text', descricao = 'formulario do tipo de processo', valorinicial = '', rotulo = 'formulario', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'formulario' where codcam = 1011246;
SQL
        );
    }
}
