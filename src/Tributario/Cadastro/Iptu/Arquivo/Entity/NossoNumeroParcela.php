<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class NossoNumeroParcela extends Entity
{
    
}

// fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sNumero,$iTamNossoNumero," ",STR_PAD_LEFT),$iTamNossoNumero);
// fputs($clabre_arquivo->arquivo, str_pad($oNossoNumero->sDigito,1               ," ",STR_PAD_LEFT),1);

// 362   | NOSSO_NUMERO_PARC1             | NOSSO NUMERO PARCELA 1                                                           | 0010 | 6496 | 6505
// 363   | DG_NOSSO_NUMERO_PARC1          | DIGITO DO NOSSO NUMERO PARCELA 1                                                 | 0001 | 6506 | 6506
