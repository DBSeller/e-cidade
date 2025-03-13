<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

use Classes\PostgresMigration;

class M8747CadastroAcervo extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function upDicionario()
    {
        $sql = <<<SQL_UP
            insert into db_syscampo values(1010587,'bi06_paginacao','varchar(50)','Quantidade de páginas do acervo.','', 'Paginação',50,'f','t','f',0,'text','Paginação');
            insert into db_syscampo values(1010588,'bi06_tomo','varchar(50)','Tomo do acervo.','', 'Tomo',50,'f','t','f',0,'text','Tomo');
            insert into db_syscampo values(1010590,'bi06_numeroitem','int4','Número do item da coleção do acervo.','0', 'Número do Item',10,'t','f','f',1,'text','Número do Item');
            update db_syscampo set nomecam = 'bi06_volume', conteudo = 'varchar(50)', descricao = 'Volume do Acervo', valorinicial = '', rotulo = 'Volume', nulo = 't', tamanho = 50, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Volume' where codcam = 1008118;
            update db_syscampo set nomecam = 'bi06_paginacao', conteudo = 'varchar(50)', descricao = 'Quantidade de páginas do acervo.', valorinicial = '', rotulo = 'Paginação', nulo = 't', tamanho = 50, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Paginação' where codcam = 1010587;
            update db_syscampo set nomecam = 'bi06_tomo', conteudo = 'varchar(50)', descricao = 'Tomo do acervo.', valorinicial = '', rotulo = 'Tomo', nulo = 't', tamanho = 50, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Tomo' where codcam = 1010588;
            insert into db_sysarqcamp values(1008014,1010588,18,0);
            insert into db_sysarqcamp values(1008014,1010587,19,0);
            insert into db_sysarqcamp values(1008014,1010590,20,0);

            insert into db_syscampo values(1010589,'bi29_quantidade','int4','Quantidade de itens referentes a coleção do acervo.','0', 'Quantidade de Itens',10,'f','f','f',1,'text','Quantidade de Itens');
            insert into db_sysarqcamp values(3584,1010589,4,0);
SQL_UP;

        $this->execute($sql);
    }

    public function upDDL()
    {
        $sql = <<<SQL_UP
alter table acervo add column bi06_paginacao varchar(50);
alter table acervo add column bi06_tomo varchar(50);
alter table acervo alter column bi06_volume type varchar(50);
alter table acervo add column bi06_numeroitem int4;

alter table colecaoacervo add column bi29_quantidade int4;
update colecaoacervo set bi29_quantidade = 1;
alter table colecaoacervo alter column bi29_quantidade set not null;
SQL_UP;

        $this->execute($sql);
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    public function downDicionario()
    {
        $sql = <<<SQL_DOWN
delete from db_sysarqcamp where codcam in(1010587, 1010588, 1010589, 1010590);
delete from db_syscampo where codcam in(1010587, 1010588, 1010589, 1010590);
SQL_DOWN;

        $this->execute($sql);
    }

    public function downDDL()
    {
        $sql = <<<SQL_DOWN
alter table acervo drop column bi06_paginacao;
alter table acervo drop column bi06_tomo;
alter table acervo drop column bi06_numeroitem;
alter table colecaoacervo drop column bi29_quantidade;
SQL_DOWN;

        $this->execute($sql);
    }
}
