<?php

use Classes\PostgresMigration;

class M10175AtualizacaoLicitacon extends PostgresMigration
{
    public function up()
    {
        // dicionario
        $this->execute("
            insert into db_syscampo values(1009627,'z09_cidade','varchar(50)','Cidade estrangeira','', 'Cidade',50,'f','t','f',0,'text','Cidade');
            insert into db_syscampo values(1009628,'z09_pais','varchar(100)','País estrangeiro','', 'País',100,'f','t','f',0,'text','País');
            insert into db_sysarqcamp values(3944,1009628,4,0);
            insert into db_sysarqcamp values(3944,1009627,5,0);
        ");

        // estrutura
        $this->execute("
            alter table cgmestrangeiro add column z09_pais varchar(100),
                                       add column z09_cidade varchar(50);
        ");

        $this->execute(
            <<<SQL_UP
            delete from db_layoutcampos where db52_layoutlinha = 792;
            insert into db_layoutcampos values (12759, 792, 'TP_DOCUMENTO', 'TIPO DE DOCUMENTO', 1, 1, '', 1, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12760, 792, 'NR_DOCUMENTO', 'NÚMERO DO DOCUMENTO', 1, 2, '', 14, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12761, 792, 'TP_PESSOA', 'TIPO DE PESSOA', 1, 16, '', 1, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12762, 792, 'NM_PESSOA', 'NOME DA PESSOA', 1, 17, '', 100, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12763, 792, 'DS_OBJETO_SOCIAL', 'DESCRIÇÃO DO OBJETO SOCIAL', 1, 117, '', 60, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12764, 792, 'NR_INSCRICAO_ESTADUAL', 'NÚMERO DE INSCRIÇÃO  ESTADUAL', 1, 177, '', 30, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12765, 792, 'NR_INSCRICAO_MUNICIPAL', 'NÚMERO  DE INSCRIÇÃO  MUNICIPAL', 1, 207, '', 30, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12766, 792, 'CD_TIPO_CONSELHO_PROFISSIONAL', 'CÓDIGO  DO  CONSELHO  REGIONAL', 1, 237, '', 10, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12767, 792, 'NR_CONSELHO_PROFISSIONAL', 'NÚMERO DO  CONSELHO  REGIONAL', 1, 247, '', 20, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12768, 792, 'SG_UF_CONSELHO_PROFISSIONAL', 'SIGLA DA UF DO CONSELHO REGIONAL', 1, 267, '', 2, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12769, 792, 'DS_EMAIL', 'E-MAIL', 1, 269, '', 60, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12770, 792, 'DS_PAGINA_INTERNET', 'ENDEREÇO DO SITE DA PESSOA', 1, 329, '', 100, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12771, 792, 'SG_UF', 'SIGLA DA UF DO ENDEREÇO DA PESSOA', 1, 429, '', 2, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12772, 792, 'CD_MUNICIPIO_IBGE', 'CÓDIGO IBGE DO MUNICÍPIO', 1, 431, '', 10, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12773, 792, 'LOGRADOURO', 'NOME DO LOGRADOURO', 1, 441, '', 100, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12774, 792, 'NR_ENDERECO', 'NÚMERO DO ENDEREÇO DO LOGRADOURO', 1, 541, '', 5, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12775, 792, 'COMPLEMENTO', 'COMPLEMENTO DO ENDEREÇO', 1, 546, '', 150, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12776, 792, 'BAIRRO', 'NOME DO BAIRRO', 1, 696, '', 100, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12777, 792, 'CEP', 'NÚMERO DO CEP', 1, 796, '', 8, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12778, 792, 'TELEFONE', 'TELEFONE', 1, 804, '', 40, false, true, 'd', '', 0);
SQL_UP
        );
    }

    public function down()
    {
        // dicionario
        $this->execute("
            delete from db_sysarqcamp where codcam in (1009628, 1009627);
            delete from db_syscampo where codcam in (1009628, 1009627);

        ");
        // estrutura
        $this->execute("
            alter table cgmestrangeiro drop column z09_pais,
                                       drop column z09_cidade;
        ");


        $this->execute(
            <<<SQL_DOWN
            delete from db_layoutcampos where db52_layoutlinha = 792;
            insert into db_layoutcampos values (12759, 792, 'TP_DOCUMENTO', 'TIPO DE DOCUMENTO', 1, 1, '', 1, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12760, 792, 'NR_DOCUMENTO', 'NÚMERO DO DOCUMENTO', 1, 2, '', 14, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12761, 792, 'TP_PESSOA', 'TIPO DE PESSOA', 1, 16, '', 1, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12762, 792, 'NM_PESSOA', 'NOME DA PESSOA', 1, 17, '', 60, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12763, 792, 'DS_OBJETO_SOCIAL', 'DESCRIÇÃO DO OBJETO SOCIAL', 1, 77, '', 60, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12764, 792, 'NR_INSCRICAO_ESTADUAL', 'NÚMERO DE INSCRIÇÃO  ESTADUAL', 1, 137, '', 30, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12765, 792, 'NR_INSCRICAO_MUNICIPAL', 'NÚMERO  DE INSCRIÇÃO  MUNICIPAL', 1, 167, '', 30, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12766, 792, 'CD_TIPO_CONSELHO_PROFISSIONAL', 'CÓDIGO  DO  CONSELHO  REGIONAL', 1, 197, '', 10, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12767, 792, 'NR_CONSELHO_PROFISSIONAL', 'NÚMERO DO  CONSELHO  REGIONAL', 1, 207, '', 20, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12768, 792, 'SG_UF_CONSELHO_PROFISSIONAL', 'SIGLA DA UF DO CONSELHO REGIONAL', 1, 227, '', 2, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12769, 792, 'DS_EMAIL', 'E-MAIL', 1, 229, '', 60, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12770, 792, 'DS_PAGINA_INTERNET', 'ENDEREÇO DO SITE DA PESSOA', 1, 289, '', 100, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12771, 792, 'SG_UF', 'SIGLA DA UF DO ENDEREÇO DA PESSOA', 1, 389, '', 2, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12772, 792, 'CD_MUNICIPIO_IBGE', 'CÓDIGO IBGE DO MUNICÍPIO', 1, 391, '', 10, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12773, 792, 'LOGRADOURO', 'NOME DO LOGRADOURO', 1, 401, '', 100, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12774, 792, 'NR_ENDERECO', 'NÚMERO DO ENDEREÇO DO LOGRADOURO', 1, 501, '', 5, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12775, 792, 'COMPLEMENTO', 'COMPLEMENTO DO ENDEREÇO', 1, 506, '', 40, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12776, 792, 'BAIRRO', 'NOME DO BAIRRO', 1, 546, '', 40, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12777, 792, 'CEP', 'NÚMERO DO CEP', 1, 586, '', 8, false, true, 'd', '', 0);
            insert into db_layoutcampos values (12778, 792, 'TELEFONE', 'TELEFONE', 1, 594, '', 40, false, true, 'd', '', 0);
SQL_DOWN

        );


    }
}
