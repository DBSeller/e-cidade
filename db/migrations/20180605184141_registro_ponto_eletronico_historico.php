<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

class RegistroPontoEletronicoHistorico extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDados();
        $this->upDDL();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downDDL();
    }

    private function upDicionarioDados()
    {
        $this->execute(
          <<<SQL_UP
insert into db_sysarquivo values (1010285, 'registrapontoeletronicohistorico', 'Guarda o histórico de alterações da opção \"Registra Ponto Eletrônico\" do servidor.', 'rh215', '2018-06-05', 'Registra Ponto Eletrônico Histórico', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (28,1010285);
insert into db_syscampo values(1009760,'rh215_sequencial','int8','Sequencial da tabela registrapontoeletronicohistorico.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1009761,'rh215_matricula','int8','Matrícula referente a alteração de registro do ponto eletrônico no cadastro do servidor.','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
insert into db_syscampo values(1009762,'rh215_registrapontoeletronico','bool','Opção selecionada em registra ponto eletrônico.','f', 'Registra Ponto Eletrônico',1,'f','f','f',5,'text','Registra Ponto Eletrônico');
insert into db_syscampo values(1009763,'rh215_data','date','Data da alteração da opção registra ponto eletrônico no cadastro do servidor.','null', 'Data da Alteração',10,'f','f','f',1,'text','Data da Alteração');
insert into db_sysarqcamp values(1010285,1009760,1,0);
insert into db_sysarqcamp values(1010285,1009761,2,0);
insert into db_sysarqcamp values(1010285,1009762,3,0);
insert into db_sysarqcamp values(1010285,1009763,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010285,1009760,1,1009761);
insert into db_sysforkey values(1010285,1009761,1,1153,0);
insert into db_sysindices values(1008285,'registrapontoeletronicohistorico_matricula_in',1010285,'0');
insert into db_syssequencia values(1000736, 'registrapontoeletronicohistorico_rh215_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000736 where codarq = 1010285 and codcam = 1009760;
SQL_UP
        );
    }

    private function downDicionarioDados()
    {
        $this->execute(
          <<<SQL_DOWN
delete from db_syssequencia where codsequencia = 1000736;
delete from db_sysindices where codind = 1008285;
delete from db_sysforkey where codarq = 1010285;
delete from db_sysprikey where codarq = 1010285 and codcam = 1009760 and sequen = 1 and camiden = 1009761;
delete from db_sysarqcamp where codarq = 1010285;
delete from db_syscampo where codcam in(1009760, 1009761, 1009762, 1009763);
delete from db_sysarqmod where codmod = 28 and codarq = 1010285;
delete from db_sysarquivo where codarq = 1010285;
SQL_DOWN
        );
    }

    private function upDDL()
    {
        $dadosTabela = array(
          'id' => false,
          'schema' => 'pessoal',
          'primary_key' => 'rh215_sequencial',
          'constraint' => 'registrapontoeletronicohistorico_sequ_pk'
        );

        $this->execute('CREATE SEQUENCE registrapontoeletronicohistorico_rh215_sequencial_seq');
        $this->table('registrapontoeletronicohistorico', $dadosTabela)
             ->addColumn('rh215_sequencial',               'integer', array('null' => false))
             ->addColumn('rh215_matricula',                'integer', array('null' => false))
             ->addColumn('rh215_registrapontoeletronico',  'boolean', array('null' => false))
             ->addColumn('rh215_data',                     'date',    array('null' => false))
             ->addForeignKey('rh215_matricula', 'pessoal.rhpessoal', 'rh01_regist', array('constraint' => 'registrapontoeletronicohistorico_matricula_fk'))
             ->addIndex('rh215_matricula', array('name' => 'registrapontoeletronicohistorico_matricula_in'))
        ->save();
    }

    private function downDDL()
    {
        $this->table('registrapontoeletronicohistorico', array('schema' => 'pessoal'))->drop();
        $this->execute('DROP SEQUENCE IF EXISTS registrapontoeletronicohistorico_rh215_sequencial_seq');
    }
}
