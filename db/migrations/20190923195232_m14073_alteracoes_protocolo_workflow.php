<?php

use Classes\PostgresMigration;

class M14073AlteracoesProtocoloWorkflow extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDados();
        $this->upDDL();
    }
        
    public function upDDL()
    {
        return $this->execute("
            ALTER TABLE configuracoes.acao ALTER COLUMN db176_descricao TYPE varchar;
            ALTER TABLE protocolo.protprocessodocumento ALTER COLUMN p01_documento DROP NOT NULL;
            ALTER TABLE protocolo.protprocessodocumento ADD COLUMN p01_estorage boolean DEFAULT false;
        ");
    }

    public function upDicionarioDados()
    {
        return $this->execute("
            UPDATE db_syscampo SET tamanho = 1000 WHERE codcam = 1010747;
            INSERT INTO db_syscampo VALUES (1010752,'p01_estorage','bool','Informa se o documento está no e-Storage ou não, caso esse campo seja verdadeiro então o campo p01_nomedocumento contem a referência do documento no e-Storage, o valor default do campo é false para retrocompatibilidade','f', 'e-Storage',1,'f','f','f',5,'text','e-Storage');
            INSERT INTO db_syscampodef VALUES (1010752,'false','');
            INSERT INTO db_sysarqcamp VALUES (3649,1010752,9,0);
            UPDATE db_syscampo SET nomecam = 'p01_documento', nulo = 't' WHERE codcam = 20299;

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228164 ,'Solicitações WEB' ,'Solicitações WEB' ,'ouv4_solicitacaoprocessoeletronico.php' ,'1' ,'1' ,'Solicitações de processo eletrônico cadastradas pela internet, ouvidoria, solicitação de alvará entre outras' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7845 ,228164 ,12 ,7837 );
        ");
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downDDL();
    }

    public function downDicionarioDados()
    {
        return $this->execute("
            DELETE FROM db_menu WHERE id_item_filho = 228164 AND modulo = 7837;
            DELETE FROM db_itensmenu WHERE id_item_filho = 228164;

            UPDATE db_syscampo SET nomecam = 'p01_documento', nulo = 'f' WHERE codcam = 20299;
            DELETE FROM db_sysarqcamp WHERE codarq = 3649 AND codcam = 1010752;
            DELETE FROM db_syscampo WHERE codcam = 1010752;
            DELETE FROM db_syscampodep WHERE codcam = 1010747;
            DELETE FROM db_syscampodef WHERE codcam = 1010747;
        ");
    }

    public function downDDL()
    {
        return $this->execute("
            ALTER TABLE protocolo.protprocessodocumento ALTER COLUMN p01_documento SET NOT NULL;
            ALTER TABLE protocolo.protprocessodocumento DROP COLUMN p01_estorage;
        ");
    }

}
