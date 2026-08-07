<?php

use Classes\PostgresMigration;

class M12312Segmentok extends PostgresMigration
{
    public function up()
    {
        // segmento k
        $aSql[] = "INSERT INTO db_layoutlinha(db51_codigo, db51_layouttxt , db51_descr, db51_tipolinha  , db51_tamlinha, db51_compacta)  VALUES  (2012, 9, 'REGISTRO - SEGMENTO K', 3,240,false);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oCodBanco','Codigo doBanco',1,1,3,  'd', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oLoteServico',' Lote de Serviço',1,4,4,  'd', true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oCodigoRegistro','Código do registro',1,8,1,  'd', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oNsr','NSR',1,9, 5,  'd', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir,db52_ident,db52_default) 
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oCodSegmento','Cód. Segmento',1,14,1,  'e', true, true,'K');";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oTipoMovimento',' Tipo Movimento',1,15,1,  'd', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oCodInstrucao','Código Instrução',1,16,2,  'd', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oCodBarras','Código de Barras',1,18,44,  'd', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oFiller1','Filler',1,62,12,  'e', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oDocumentoEmpresa','Número do documento na Empresa',1,74, 6,  'e', true);";


        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oFiller2','Filler',1,80,14,  'e', true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oDtLacamento','Data Lançamento',1,94,8,  'e', true);";


        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq'), 2012,'oTipoMoeda','Tipo da Moeda',1, 102, 3,  'e', true);";


        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oQuantidadeMoeda','Quantidade de Moeda',1,105,15,  'e',true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oValorLancamento','Valor Lançamento',1,120,13,  'e',true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oNumeroDocumentoBanco','Número Documento no banco',1,135,10,  'e',true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oFiller3','Filler',1,144,11,  'e',true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oDtEfetivacao','Data da efetivação',1,155,8,  'e',true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oValorEfetivacao','Valor da efetivação',1,163,15,  'e',true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oOutrasInformacoes','Outras informações',1,178,40,  'e',true); ";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oFebraban','Uso FEBRABAN',1,218,12,  'e',true); ";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oFavorecido','Aviso ao favorecido',1,230,1,  'e',true);";

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) 
                    values(nextval('db_layoutcampos_db52_codigo_seq') ,2012,'oOcorrencia','Ocorrências para retorno',1,231,10,  'e',true);";

        foreach ($aSql as $sql) {

            $this->execute($sql);
        }
    }

    public function down()
    {

        $aSql = array();

        $aSql []  = "DELETE FROM db_layoutcampos WHERE  db52_layoutlinha = 2012 ;";

        $aSql []  = "DELETE FROM db_layoutlinha WHERE  db51_codigo = 2012;";

        foreach ($aSql as $sql) {

            $this->execute($sql);
        }

    }


}
