<?php

use Classes\PostgresMigration;

class M10927SegmentosCnab240 extends PostgresMigration
{
    public function up()
    {

        $aSql = array();


        // segemnto J
        $aSql[] = "INSERT INTO db_layoutlinha(db51_codigo, db51_layouttxt , db51_descr, db51_tipolinha  , db51_tamlinha, db51_compacta)  VALUES  (2010 , 9, 'REGISTRO - SEGMENTO J', 3, 240,false);";
        // segmento O
        $aSql[] = "INSERT INTO db_layoutlinha(db51_codigo, db51_layouttxt , db51_descr, db51_tipolinha  , db51_tamlinha, db51_compacta)  VALUES  (2011, 9, 'REGISTRO - SEGMENTO O', 3,240,false);";

        //  campos  tipo j
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jCodBanco'        ,'Código do Banco',  1,1,3 ,  'd' ,true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jLoteServico'     ,'Lote de Serviço',2,4,4,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jCodigoRegistro'  ,'Código do registro', 2,8,1,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jNsr'             ,'NRS', 1,9,5,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir,db52_ident,db52_default)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jCodSegmento'     ,'Cod.Segmento', 1,14,1,  'e',true,true,'J');";

        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jTipoMovimento'   ,'Tipo Movimento',1,15,1,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jCodMovimento'    ,'Cód. Movimento',1,16,2,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jBancoDest'       ,'Banco destino',1,18,3,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jCodMoeda'        ,'Cód. Moeda', 1,21,1,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jDvCodBarra'      ,'DV Cód. Barras',1 ,22,1,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jFatorVencimento' ,'Fator de vencimento',1,23,4,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jValorDoc'        ,'Valor do Documento', 1,27,10,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jCampoLivre'      ,'Campo Livre', 1,37,25,  'e',true);";


        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jNomeCedente'     ,'Nome do Cedente',1,62,30,  'e',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jDataVencimento'  ,'Data Vencimento',1,92,8,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jValorTitulo'     ,'Valor do Título', 1,100,15,  'd',true);";

        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jValorDesconto'   ,'Valor do desconto + Abatimento', 1,115, 15,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jValorMora'       ,'Valor da mora + multa', 1,130, 15,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jDtPagamento'     ,'Data do Pagamento', 1,145,8,  'd',true);";

        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jValorPagamento'  ,'Valor Pagamento' , 1,153,15,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jQuantidadeMoeda' ,'Quantidade Moeda', 1,168,15,  'd',true);";

        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jNumeroDocumento' ,'Número documento atribuído pela empresa', 1,183,6,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jFiller'          ,'Filler', 1,189,14,  'e',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jnumeroDocBanco'  ,'Número documento atribuído pelo banco', 1,203,9,  'd',true);";


        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jFiller2'         ,'Filler', 1,212,11,  'e',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jCodigoMoeda'     ,'Código da Moeda', 1, 223,2,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jFebraban'        ,'Uso FEBRABAN',1,225,6,  'e',true);";
        $aSql [] = "INSERT INTO db_layoutcampos(db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir)  VALUES(nextval('db_layoutcampos_db52_codigo_seq') ,2010,'jOcorrencia'      ,'Ocorrências do Retorno' ,1,231,10,  'e',true);";


        // segmento  o

        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oCodBanco','Codigo doBanco',1,1,3,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oLoteServico',' Lote de Serviço',1,4,4,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oCodigoRegistro','Código do registro',1,8,1,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oNsr','NSR',1,9, 5,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir,db52_ident,db52_default) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oCodSegmento','Cód. Segmento',1,14,1,  'e',true,true,'O');";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oTipoMovimento',' Tipo Movimento',1,15,1,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oCodMovimento','Cód. Movimento',1,16,2,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oCodBarras','Código de Barras',1,18,44,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oNomeConcessionaria','Nome da Concessionária',1,62,30,  'e',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oDtVencimento','Data do Vencimento',1,92,8,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oDtPagamento','Data do Pagamento',1,100,8,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oValorPagamento','Valor do Pagamento',3,108,15,  'd',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'onumeroDocumento','Número do Documento A/ pela Empresa ',1,123,20,  'e',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'onumeroDocumentoBanco','Número do Documento A/ pelo Banco ',1,143,20,  'e',true);";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oFebraban','Uso FEBRABAN',1,163,68,  'e',true); ";
        $aSql [] = "INSERT INTO db_layoutcampos (db52_codigo,db52_layoutlinha,db52_nome,db52_descr,db52_layoutformat,db52_posicao,db52_tamanho,db52_alinha,db52_imprimir) values(nextval('db_layoutcampos_db52_codigo_seq') ,2011,'oOcorrencia','Códigos das Ocorrências para Retorno',1,231,10,  'e',true);";

        foreach ($aSql as $sql) {

            $this->execute($sql);
        }
    }

    public function down()
    {
        $aSql = array();

        $aSql []  = "DELETE FROM db_layoutcampos WHERE  db52_layoutlinha = 2010;";
        $aSql []  = "DELETE FROM db_layoutcampos WHERE  db52_layoutlinha = 2011 ;";

        $aSql []  = "DELETE FROM db_layoutlinha WHERE  db51_codigo = 2010;";
        $aSql []  = "DELETE FROM db_layoutlinha WHERE  db51_codigo = 2011;";

        foreach ($aSql as $sql) {

            $this->execute($sql);
        }


    }


}
