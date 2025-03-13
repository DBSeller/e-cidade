<?php

use Classes\PostgresMigration;

class M12656S2399TrabalhadorSemVinculoTermino extends PostgresMigration
{
    public function up()
    {
        $this->disableAudit();
        $this->estrututa();
        $this->dicionario();
        $this->migracao();
    }

    public function down()
    {
        $this->execute("
            alter table esocial.avaliacaogruporespostatertrabasemvinc drop column eso24_avaliacao; 
        ");

        $this->execute("
            delete from db_sysforkey where codarq = 1010321 and codcam in (1010315);
            delete from db_sysarqcamp where codarq = 1010321 and codcam in (1010315);
            delete from db_syscampo where codcam in (1010315); 
        ");
    }

    private function estrututa()
    {
        $this->execute("
            alter table esocial.avaliacaogruporespostatertrabasemvinc add column eso24_avaliacao int4; 
            alter table esocial.avaliacaogruporespostatertrabasemvinc add constraint avaliacaogruporespostatertrabasemvinc_avaliacao_fk foreign key (eso24_avaliacao) references avaliacao;
        ");
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_syscampo values(1010315,'eso24_avaliacao','int4','Avaliação','0', 'Avaliação',10,'f','f','f',1,'text','Avaliação');
            insert into db_sysarqcamp values(1010321,1010315,6,0);
            insert into db_sysforkey values(1010321,1010315,1,2980,0);
        ");
    }

    private function migracao()
    {
        $this->execute("
            WITH sub AS (
              SELECT DISTINCT db102_avaliacao AS avalicao, eso24_sequencial
              FROM esocial.avaliacaogruporespostatertrabasemvinc
                     JOIN habitacao.avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = eso24_avaliacaogruporesposta
                     JOIN habitacao.avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                     JOIN habitacao.avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                     JOIN habitacao.avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                     JOIN habitacao.avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
            )
            UPDATE esocial.avaliacaogruporespostatertrabasemvinc
            SET eso24_avaliacao = sub.avalicao
            FROM sub
            WHERE avaliacaogruporespostatertrabasemvinc.eso24_sequencial = sub.eso24_sequencial;
        ");
    }

    private function disableAudit()
    {
        $this->execute("SELECT fc_putsession('__disable_audit__', 'on');");
    }
}
