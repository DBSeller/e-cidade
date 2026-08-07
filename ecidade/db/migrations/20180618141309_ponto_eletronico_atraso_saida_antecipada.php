<?php

use Classes\PostgresMigration;

class PontoEletronicoAtrasoSaidaAntecipada extends PostgresMigration
{
    private $tableArquivoData;
    private $tableConfiguracoesGerais;

    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function down()
    {
        $this->downDDL();
        $this->downDicionario();
    }

    private function upDicionario()
    {
        $this->execute(
          <<<SQL_UP
insert into db_syscampo values(1009788,'rh197_horas_atraso','varchar(5)','Guarda as horas de atraso do servidor no dia.','', 'Horas de Atraso',5,'t','t','f',0,'text','Horas de Atraso');
insert into db_syscampo values(1009789,'rh197_horas_saida_antecipada','varchar(5)','Guarda as horas de saída antecipada do servidor no dia.','', 'Horas de Saída Antecipada',5,'t','t','f',0,'text','Horas de Saída Antecipada');
insert into db_syscampo values(1009790,'rh200_tipoasse_atraso','int4','Tipo de assentamento referente as horas de atraso.','0', 'Horas de Atraso',10,'t','f','f',1,'text','Horas de Atraso');
insert into db_sysarqcamp values(4014,1009789,16,0);
insert into db_sysarqcamp values(4014,1009788,17,0);
insert into db_sysarqcamp values(4024,1009790,13,0);
insert into db_sysforkey values(4024,1009790,1,596,0);
SQL_UP
        );
    }

    private function downDicionario()
    {
        $this->execute(
          <<<SQL_DOWN
delete from db_sysforkey where codarq = 4024 and codcam in(1009790);
delete from db_sysarqcamp where codarq = 4014 and codcam in(1009789, 1009788);
delete from db_sysarqcamp where codarq = 4024 and codcam in(1009790);
delete from db_syscampo where codcam in(1009789, 1009788, 1009790);
SQL_DOWN
        );
    }

    private function upDDL()
    {
        $this->tableArquivoData = $this->table('pontoeletronicoarquivodata', array('schema' => 'recursoshumanos'));

        $existColumnAtraso = $this->tableArquivoData->hasColumn('rh197_horas_atraso');

        if($existColumnAtraso) {
            $this->tableArquivoData->removeColumn('rh197_horas_atraso')->save();
        }

        $this->tableArquivoData->addColumn('rh197_horas_atraso', 'string', array('null' => true, 'limit' => 5))
                               ->addColumn('rh197_horas_saida_antecipada', 'string', array('null' => true, 'limit' => 5))
                               ->save();

        $this->tableConfiguracoesGerais = $this->table('pontoeletronicoconfiguracoesgerais', array('schema' => 'recursoshumanos'));
        $this->tableConfiguracoesGerais->addColumn('rh200_tipoasse_atraso', 'integer', array('null' => true))
                                       ->addForeignKey('rh200_tipoasse_atraso', 'recursoshumanos.tipoasse', 'h12_codigo', array('constraint' => 'pontoeletronicoconfiguracoesgerais_tipoasse_atraso_fk'))
                                       ->save();
    }

    private function downDDL()
    {
        $this->tableArquivoData = $this->table('pontoeletronicoarquivodata', array('schema' => 'recursoshumanos'));
        $this->tableArquivoData->removeColumn('rh197_horas_atraso')
                               ->removeColumn('rh197_horas_saida_antecipada')
                               ->save();

        $this->tableConfiguracoesGerais = $this->table('pontoeletronicoconfiguracoesgerais', array('schema' => 'recursoshumanos'));
        $this->tableConfiguracoesGerais->removeColumn('rh200_tipoasse_atraso')
                                       ->dropForeignKey('pontoeletronicoconfiguracoesgerais_tipoasse_atraso_fk')
                                       ->save();
    }
}
