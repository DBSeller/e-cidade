#!/usr/bin/env bash

sudo umount /dados/www/hom.ecidade.portovelho.ro.gov.br/ecidade/integracao_externa/luckman/entrada > /dev/null 2>&1

sudo umount /dados/www/hom.ecidade.portovelho.ro.gov.br/ecidade/integracao_externa/luckman/saida > /dev/null 2>&1

mkdir -p /dados/www/hom.ecidade.portovelho.ro.gov.br/ecidade/integracao_externa/luckman/saida > /dev/null 2>&1

mkdir -p /dados/www/hom.ecidade.portovelho.ro.gov.br/ecidade/integracao_externa/luckman/entrada > /dev/null 2>&1

sudo mount -t cifs -o vers=1.0,guest,uid=1001,gid=33,file_mode=0664,dir_mode=0775 '//10.101.1.31/INTERFACE/pedidos' /dados/www/hom.ecidade.portovelho.ro.gov.br/ecidade/integracao_externa/luckman/saida

sudo mount -t cifs -o vers=1.0,guest,uid=1001,gid=33,file_mode=0664,dir_mode=0775 '//10.101.1.31/INTERFACE/resultgrav/ecidade' /dados/www/hom.ecidade.portovelho.ro.gov.br/ecidade/integracao_externa/luckman/entrada

