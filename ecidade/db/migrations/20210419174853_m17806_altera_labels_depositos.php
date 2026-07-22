<?php

use Classes\PostgresMigration;

class M17806AlteraLabelsDepositos extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            update db_syscampo
                set nomecam = 'm91_codigo',
                    conteudo = 'int4',
                    descricao = 'Código de cadastro para o departamento que vai ser depósito.',
                    valorinicial = '0',
                    rotulo = 'Código Depósito',
                    nulo = 'f',
                    tamanho = 6,
                    maiusculo = 'f',
                    autocompl = 'f',
                    aceitatipo = 1,
                    tipoobj = 'text',
                    rotulorel = 'Depósito'
                where codcam = 7164;

            update db_syscampo
                set nomecam = 'm92_codalmox',
                    conteudo = 'int4',
                    descricao = 'Codigo de cadastro para o departamento que vai ser depósito.',
                    valorinicial = '0',
                    rotulo = 'Codigo Depósito',
                    nulo = 'f',
                    tamanho = 6,
                    maiusculo = 'f',
                    autocompl = 'f',
                    aceitatipo = 1,
                    tipoobj = 'text',
                    rotulorel = 'Depósito'
                where codcam = 7166;
                update db_syscampo
                    set nomecam = 'm40_almox',
                        conteudo = 'int4',
                        descricao = 'Codigo de cadastro para o departamento que vai ser almoxarifado.',
                        valorinicial = '0',
                        rotulo = 'Depósito',
                        nulo = 'f',
                        tamanho = 6,
                        maiusculo = 'f',
                        autocompl = 'f',
                        aceitatipo = 1,
                        tipoobj = 'text',
                        rotulorel = 'Depósito'
                where codcam = 9994;
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            update db_syscampo
                set nomecam = 'm91_codigo',
                    conteudo = 'int4',
                    descricao = 'Código de cadastro para o departamento que vai ser almoxarifado.',
                    valorinicial = '0',
                    rotulo = 'Código Almox.',
                    nulo = 'f',
                    tamanho = 6,
                    maiusculo = 'f',
                    autocompl = 'f',
                    aceitatipo = 1,
                    tipoobj = 'text',
                    rotulorel = 'Código Almox.'
                where codcam = 7164;

            update db_syscampo
                set nomecam = 'm92_codalmox',
                    conteudo = 'int4',
                    descricao = 'Codigo de cadastro para o departamento que vai ser almoxarifado.',
                    valorinicial = '0',
                    rotulo = 'Código Almoxarifado',
                    nulo = 'f',
                    tamanho = 6,
                    maiusculo = 'f',
                    autocompl = 'f',
                    aceitatipo = 1,
                    tipoobj = 'text',
                    rotulorel = 'Código Almox.'
                where codcam = 7166;
                update db_syscampo
                    set nomecam = 'm40_almox',
                        conteudo = 'int4',
                        descricao = 'Codigo de cadastro para o departamento que vai ser almoxarifado.',
                        valorinicial = '0',
                        rotulo = 'Almoxarifado',
                        nulo = 'f',
                        tamanho = 6,
                        maiusculo = 'f',
                        autocompl = 'f',
                        aceitatipo = 1,
                        tipoobj = 'text',
                        rotulorel = 'Almoxarifado'
                where codcam = 9994;
sql
            );
    }
}
