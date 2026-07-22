<?php

use Classes\PostgresMigration;

class M15862CampoComplemento extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL
insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1011286 ,'o15_complemento' ,'int4' ,'Complemento do recurso ' ,'' ,'Complemento' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Complemento' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 749 ,1011286 ,13 ,0 );

alter table orctiporec add o15_complemento integer;
SQL
        );
        $this->execute("drop view  if exists vs_planosistema;");
        $this->execute("drop view if exists vs_planocontas;");

        $this->execute(<<<SQL
alter table orctiporec alter o15_loaespecificacao type varchar;
alter table orctiporec alter o15_db_estruturavalor drop not null;

SQL
        );
        $this->execute(<<<SQL
create view vs_planosistema
            (c64_codpla, c64_estrut, c64_descr, c65_codcon, c65_codpla, c60_codcon, c60_anousu, c60_estrut, c60_descr,
             c60_finali, c60_codsis, c60_codcla, c60_consistemaconta, c60_identificadorfinanceiro, c60_naturezasaldo,
             c60_funcao, c61_codcon, c61_anousu, c61_reduz, c61_instit, c61_codigo, c61_contrapartida, c62_anousu,
             c62_reduz, c62_codrec, c62_vlrcre, c62_vlrdeb, o15_codigo, o15_descr, o15_codtri, o15_finali, o15_tipo,
             o15_datalimite, o15_db_estruturavalor, o15_codigosiconfi, o15_loaidentificadoruso, o15_loatipo,
             o15_loagrupo, o15_loaespecificacao, c63_codcon, c63_anousu, c63_banco, c63_agencia, c63_conta, c63_dvconta,
             c63_dvagencia, c63_identificador, c63_codigooperacao, c63_tipoconta, c63_reduz)
as
SELECT conplanosis.c64_codpla,
       conplanosis.c64_estrut,
       conplanosis.c64_descr,
       conplanoref.c65_codcon,
       conplanoref.c65_codpla,
       conplano.c60_codcon,
       conplano.c60_anousu,
       conplano.c60_estrut,
       conplano.c60_descr,
       conplano.c60_finali,
       conplano.c60_codsis,
       conplano.c60_codcla,
       conplano.c60_consistemaconta,
       conplano.c60_identificadorfinanceiro,
       conplano.c60_naturezasaldo,
       conplano.c60_funcao,
       conplanoreduz.c61_codcon,
       conplanoreduz.c61_anousu,
       conplanoreduz.c61_reduz,
       conplanoreduz.c61_instit,
       conplanoreduz.c61_codigo,
       conplanoreduz.c61_contrapartida,
       conplanoexe.c62_anousu,
       conplanoexe.c62_reduz,
       conplanoexe.c62_codrec,
       conplanoexe.c62_vlrcre,
       conplanoexe.c62_vlrdeb,
       orctiporec.o15_codigo,
       orctiporec.o15_descr,
       orctiporec.o15_codtri,
       orctiporec.o15_finali,
       orctiporec.o15_tipo,
       orctiporec.o15_datalimite,
       orctiporec.o15_db_estruturavalor,
       orctiporec.o15_codigosiconfi,
       orctiporec.o15_loaidentificadoruso,
       orctiporec.o15_loatipo,
       orctiporec.o15_loagrupo,
       orctiporec.o15_loaespecificacao,
       conplanoconta.c63_codcon,
       conplanoconta.c63_anousu,
       conplanoconta.c63_banco,
       conplanoconta.c63_agencia,
       conplanoconta.c63_conta,
       conplanoconta.c63_dvconta,
       conplanoconta.c63_dvagencia,
       conplanoconta.c63_identificador,
       conplanoconta.c63_codigooperacao,
       conplanoconta.c63_tipoconta,
       conplanoconta.c63_reduz
FROM conplanosis
         JOIN conplanoref ON conplanoref.c65_codpla = conplanosis.c64_codpla
         JOIN conplano ON conplano.c60_codcon = conplanoref.c65_codcon
         JOIN conplanoreduz ON conplanoreduz.c61_codcon = conplano.c60_codcon
         JOIN conplanoexe ON conplanoreduz.c61_reduz = conplanoexe.c62_reduz
         JOIN orctiporec ON conplanoreduz.c61_codigo = orctiporec.o15_codigo
         LEFT JOIN conplanoconta ON conplano.c60_codcon = conplanoconta.c63_codcon;

alter table vs_planosistema
    owner to postgres;
SQL
        );

        $this->execute(<<<SQL

DROP VIEW if exists vs_planocontas;

create view vs_planocontas as
        SELECT *
        FROM CONPLANO
     	 INNER JOIN CONSISTEMA             ON C60_CODSIS = C52_CODSIS
   	     INNER JOIN CONCLASS               ON C60_CODCLA = C51_CODCLA
			 LEFT JOIN CONPLANOREDUZ           ON C60_CODCON = C61_CODCON and C60_ANOUSU =C61_ANOUSU
			 LEFT  JOIN CONPLANOCONTA          ON c63_ANOUSU = C60_ANOUSU
																				and C61_REDUZ = C63_REDUZ
  	     LEFT JOIN CONPLANOEXE             ON C61_ANOUSU = C62_ANOUSU and C61_REDUZ  = C62_REDUZ
	     LEFT JOIN ORCTIPOREC              ON C61_CODIGO = O15_CODIGO
	     LEFT JOIN DB_CONFIG               ON CODIGO     = CONPLANOREDUZ.C61_INSTIT

;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_sysarqcamp where codcam = 1011286;
delete from db_syscampo where codcam = 1011286;
alter table orctiporec drop o15_complemento;
SQL
        );
    }
}
