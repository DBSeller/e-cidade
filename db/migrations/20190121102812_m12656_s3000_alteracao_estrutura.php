<?php

use Classes\PostgresMigration;

class M12656S3000AlteracaoEstrutura extends PostgresMigration
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
            alter table esocial.avaliacaogruporespostatsveinicial drop column eso16_empregador;
            alter table esocial.avaliacaogruporespostatsveinicial drop column eso16_avaliacao; 
        ");

        $this->execute("
            delete from db_sysforkey where codarq = 1010311 and codcam in (1010310, 1010311);
            delete from db_sysarqcamp where codarq = 1010311 and codcam in (1010310, 1010311);
            delete from db_syscampo where codcam in (1010310, 1010311); 
        ");
    }

    private function estrututa()
    {
        $this->execute("
            alter table esocial.avaliacaogruporespostatsveinicial add column eso16_empregador int4;
            alter table esocial.avaliacaogruporespostatsveinicial add column eso16_avaliacao int4; 
            
            alter table esocial.avaliacaogruporespostatsveinicial add constraint avaliacaogruporespostatsveinicial_empregador_fk foreign key (eso16_empregador) references cgm;
            alter table esocial.avaliacaogruporespostatsveinicial add constraint avaliacaogruporespostatsveinicial_avaliacao_fk foreign key (eso16_avaliacao) references avaliacao;
        ");
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_syscampo values(1010310,'eso16_empregador','int4','Empregador','0', 'Empregador',10,'t','f','f',1,'text','Empregador');
            insert into db_syscampo values(1010311,'eso16_avaliacao','int4','Avaliação','0', 'Avaliação',10,'f','f','f',1,'text','Avaliação');
            insert into db_sysarqcamp values(1010311,1010311,4,0);
            insert into db_sysarqcamp values(1010311,1010310,5,0);
            insert into db_sysforkey values(1010311,1010311,1,2980,0);
            insert into db_sysforkey values(1010311,1010310,1,42,0);
        ");
    }

    private function migracao()
    {
        $this->execute("
            WITH sub AS (
              SELECT DISTINCT r70_numcgm AS cgm, eso16_sequencial
              FROM esocial.avaliacaogruporespostatsveinicial
                     JOIN pessoal.rhpessoalmov ON rh02_regist = eso16_rhpessoal
                     JOIN pessoal.rhlota ON r70_codigo = rh02_lota AND r70_instit = rh02_instit
                     join protocolo.cgm on cgm.z01_numcgm = rhlota.r70_numcgm
            )
            UPDATE esocial.avaliacaogruporespostatsveinicial
            SET eso16_empregador = sub.cgm
            FROM sub
            WHERE avaliacaogruporespostatsveinicial.eso16_sequencial = sub.eso16_sequencial;
            
            WITH sub AS (
              SELECT DISTINCT db102_avaliacao AS avalicao, eso16_sequencial
              FROM esocial.avaliacaogruporespostatsveinicial
                     JOIN habitacao.avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = eso16_avaliacaogruporesposta
                     JOIN habitacao.avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                     JOIN habitacao.avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                     JOIN habitacao.avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                     JOIN habitacao.avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
            )
            UPDATE esocial.avaliacaogruporespostatsveinicial
            SET eso16_avaliacao = sub.avalicao
            FROM sub
            WHERE avaliacaogruporespostatsveinicial.eso16_sequencial = sub.eso16_sequencial;
        ");
    }

    private function disableAudit()
    {
        $this->execute("SELECT fc_putsession('__disable_audit__', 'on');");
    }
}
