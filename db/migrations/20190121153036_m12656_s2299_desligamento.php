<?php

use Classes\PostgresMigration;

class M12656S2299Desligamento extends PostgresMigration
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
            alter table esocial.avaliacaogruporespostarhpesrescisao drop column eso15_avaliacao; 
        ");

        $this->execute("
            delete from db_sysforkey where codarq = 1010306 and codcam in (1010314);
            delete from db_sysarqcamp where codarq = 1010306 and codcam in (1010314);
            delete from db_syscampo where codcam in (1010314); 
        ");
    }

    private function estrututa()
    {
        $this->execute("
            alter table esocial.avaliacaogruporespostarhpesrescisao add column eso15_avaliacao int4; 
           
            alter table esocial.avaliacaogruporespostarhpesrescisao add constraint avaliacaogruporespostarhpesrescisao_avaliacao_fk foreign key (eso15_avaliacao) references avaliacao;
        ");
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_syscampo values(1010314,'eso15_avaliacao','int4','Avaliação','0', 'Avaliação',10,'f','f','f',1,'text','Avaliação');
            insert into db_sysarqcamp values(1010306,1010314,6,0);
            insert into db_sysforkey values(1010306,1010314,1,2980,0);
        ");
    }

    private function migracao()
    {
        $this->execute("
            WITH sub AS (
              SELECT DISTINCT db102_avaliacao AS avalicao, eso15_sequencial
              FROM esocial.avaliacaogruporespostarhpesrescisao
                     JOIN habitacao.avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = eso15_avaliacaogruporesposta
                     JOIN habitacao.avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                     JOIN habitacao.avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                     JOIN habitacao.avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                     JOIN habitacao.avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
            )
            UPDATE esocial.avaliacaogruporespostarhpesrescisao
            SET eso15_avaliacao = sub.avalicao
            FROM sub
            WHERE avaliacaogruporespostarhpesrescisao.eso15_sequencial = sub.eso15_sequencial;
        ");
    }

    private function disableAudit()
    {
        $this->execute("SELECT fc_putsession('__disable_audit__', 'on');");
    }
}
