<?php

use Classes\PostgresMigration;

class M17955AlteraRegiaoAdmCenso2021 extends PostgresMigration
{

    public function up()
    {
        $this->novosNomesRegistros();
    }

    public function down()
    {
        $this->antigosNomesRegistros();
    }

    private function novosNomesRegistros() {
        $sql = "update censoregiao set ed174_nome = 'RA - PP - Plano Piloto' where ed174_codigo = 1;
            update censoregiao set ed174_nome = 'RA - GAMA - Gama' where ed174_codigo = 2;
            update censoregiao set ed174_nome = 'RA - TAG - Taguatinga' where ed174_codigo = 3;
            update censoregiao set ed174_nome = 'RA - BRAZ -  Brazlândia' where ed174_codigo = 4;
            update censoregiao set ed174_nome = 'RA - SOBR - Sobradinho' where ed174_codigo = 5;
            update censoregiao set ed174_nome = 'RA - PLAN - Planaltina' where ed174_codigo = 6;
            update censoregiao set ed174_nome = 'RA - PAR - Paranoá' where ed174_codigo = 7;
            update censoregiao set ed174_nome = 'RA - NB - Núcleo Bandeirante' where ed174_codigo = 8;
            update censoregiao set ed174_nome = 'RA - CEIL - Ceilândia' where ed174_codigo = 9;
            update censoregiao set ed174_nome = 'RA - GUAR - Guará' where ed174_codigo = 10;
            update censoregiao set ed174_nome = 'RA - CRUZ - Cruzeiro' where ed174_codigo = 11;
            update censoregiao set ed174_nome = 'RA - SAM - Samambaia' where ed174_codigo = 12;
            update censoregiao set ed174_nome = 'RA - SANT - Santa Maria' where ed174_codigo = 13;
            update censoregiao set ed174_nome = 'RA - SAO - São Sebastião' where ed174_codigo = 14;
            update censoregiao set ed174_nome = 'RA - REC - Recanto das Emas' where ed174_codigo = 15;
            update censoregiao set ed174_nome = 'RA - LS - Lago Sul' where ed174_codigo = 16;
            update censoregiao set ed174_nome = 'RA - RFI - Riacho Fundo' where ed174_codigo = 17;
            update censoregiao set ed174_nome = 'RA - LN - Lago Norte' where ed174_codigo = 18;
            update censoregiao set ed174_nome = 'RA - CAND - Candangolândia' where ed174_codigo = 19;
            update censoregiao set ed174_nome = 'RA - AC - Águas Claras' where ed174_codigo = 20;
            update censoregiao set ed174_nome = 'RA - RFII - Riacho Fundo II' where ed174_codigo = 21;
            update censoregiao set ed174_nome = 'RA - SUDO - Sudoeste e Octogonal' where ed174_codigo = 22;
            update censoregiao set ed174_nome = 'RA - VARJ Varjão' where ed174_codigo = 23;
            update censoregiao set ed174_nome = 'RA - PW - Park Way' where ed174_codigo = 24;
            update censoregiao set ed174_nome = 'RA - SCIA -  Scia e Estrutural' where ed174_codigo = 25;
            update censoregiao set ed174_nome = 'RA - SOBRII - Sobradinho II' where ed174_codigo = 26;
            update censoregiao set ed174_nome = 'RA - JB - Jardim Botânico' where ed174_codigo = 27;
            update censoregiao set ed174_nome = 'RA - ITAP - Itapoã' where ed174_codigo = 28;
            update censoregiao set ed174_nome = 'RA - SIA - SIA' where ed174_codigo = 29;
            update censoregiao set ed174_nome = 'RA - VP - Vicente Pires' where ed174_codigo = 30;
            update censoregiao set ed174_nome = 'RA - FERC - Fercal' where ed174_codigo = 31;
            update censoregiao set ed174_nome = 'RA - SOL - Sol Nascente/Pôr do Sol' where ed174_codigo = 32;
            update censoregiao set ed174_nome = 'RA - ARNQ - Arniqueira' where ed174_codigo = 33;";

        $this->execute($sql);
    }

    private function antigosNomesRegistros() {
    $sql = "update censoregiao set ed174_nome = 'RA I Plano Piloto' where ed174_codigo = 1;
            update censoregiao set ed174_nome = 'RA II Gama' where ed174_codigo = 2;
            update censoregiao set ed174_nome = 'RA III Taguatinga' where ed174_codigo = 3;
            update censoregiao set ed174_nome = 'RA IV Brazlândia' where ed174_codigo = 4;
            update censoregiao set ed174_nome = 'RA V Sobradinho' where ed174_codigo = 5;
            update censoregiao set ed174_nome = 'RA VI Planaltina' where ed174_codigo = 6;
            update censoregiao set ed174_nome = 'RA VII Paranoá' where ed174_codigo = 7;
            update censoregiao set ed174_nome = 'RA VIII Núcleo Bandeirante' where ed174_codigo = 8;
            update censoregiao set ed174_nome = 'RA IX Ceilândia' where ed174_codigo = 9;
            update censoregiao set ed174_nome = 'RA X Guará' where ed174_codigo = 10;
            update censoregiao set ed174_nome = 'RA XI Cruzeiro' where ed174_codigo = 11;
            update censoregiao set ed174_nome = 'RA XII Samambaia' where ed174_codigo = 12;
            update censoregiao set ed174_nome = 'RA XIII Santa Maria' where ed174_codigo = 13;
            update censoregiao set ed174_nome = 'RA XIV São Sebastião' where ed174_codigo = 14;
            update censoregiao set ed174_nome = 'RA XV Recanto das Emas' where ed174_codigo = 15;
            update censoregiao set ed174_nome = 'RA XVI Lago Sul' where ed174_codigo = 16;
            update censoregiao set ed174_nome = 'RA XVII Riacho Fundo' where ed174_codigo = 17;
            update censoregiao set ed174_nome = 'RA XVIII Lago Norte' where ed174_codigo = 18;
            update censoregiao set ed174_nome = 'RA XIX Candangolândia' where ed174_codigo = 19;
            update censoregiao set ed174_nome = 'RA XX Águas Claras' where ed174_codigo = 20;
            update censoregiao set ed174_nome = 'RA XXI Riacho Fundo II' where ed174_codigo = 21;
            update censoregiao set ed174_nome = 'RA XXII Sudoeste / Octogonal' where ed174_codigo = 22;
            update censoregiao set ed174_nome = 'RA XXIII Varjão' where ed174_codigo = 23;
            update censoregiao set ed174_nome = 'RA XXIV Park Way' where ed174_codigo = 24;
            update censoregiao set ed174_nome = 'RA XXV SCIA' where ed174_codigo = 25;
            update censoregiao set ed174_nome = 'RA XXVI Sobradinho II' where ed174_codigo = 26;
            update censoregiao set ed174_nome = 'RA XXVII Jardim Botânico' where ed174_codigo = 27;
            update censoregiao set ed174_nome = 'RA XXVIII Itapoã' where ed174_codigo = 28;
            update censoregiao set ed174_nome = 'RA XXIX SIA' where ed174_codigo = 29;
            update censoregiao set ed174_nome = 'RA XXX Vicente Pires' where ed174_codigo = 30;
            update censoregiao set ed174_nome = 'RA XXXI Fercal' where ed174_codigo = 31;
            update censoregiao set ed174_nome = 'RA XXXII Sol Nascente/Pôr do Sol' where ed174_codigo = 32;
            update censoregiao set ed174_nome = 'RA XXXIII Arniqueira' where ed174_codigo = 33;";

        $this->execute($sql);
    }
}
