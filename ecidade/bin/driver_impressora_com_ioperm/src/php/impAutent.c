/* include standard header */
#include "php.h"
#include<stdlib.h>
#include<unistd.h>
#include<sys/types.h>
#include<sys/socket.h>
#include<sys/wait.h>
#include<netdb.h>
#include<netinet/in.h>
#include<locale.h>

int sockfd;

/* declaration of functions to be exported */
ZEND_FUNCTION(im_imp);//imprime com \r
ZEND_FUNCTION(im_impln);//imprime com \n
ZEND_FUNCTION(im_autenticar);//autentica
ZEND_FUNCTION(im_negrito);//hab/desab negrito
ZEND_FUNCTION(im_sublinhado);//hab/desab sublinhado
ZEND_FUNCTION(im_italico);//hab/desab ialico
ZEND_FUNCTION(im_condensado);//hab/desab condensado
ZEND_FUNCTION(im_expandido);//hab/desab expandido
ZEND_FUNCTION(im_fontenormal);//seta fonte normal
ZEND_FUNCTION(im_fonteelite);//seta fonte elite
ZEND_FUNCTION(im_reset);//reinicializa com as configurações default
ZEND_FUNCTION(im_conectar);//conecta
ZEND_FUNCTION(im_verifica);//verifica se a impressora t� pronta para imprimir
ZEND_FUNCTION(im_fechar);//fexha conexao


PHP_MINFO_FUNCTION(impFiscal);
PHP_MINIT_FUNCTION(impFiscal);
/* compiled function list so Zend knows what's in this module */
zend_function_entry impFiscal_functions[] =
{
    ZEND_FE(im_imp, NULL)
    ZEND_FE(im_impln, NULL)
    ZEND_FE(im_conectar, NULL)
    ZEND_FE(im_fechar, NULL)
    ZEND_FE(im_autenticar, NULL)
    ZEND_FE(im_negrito, NULL)
    ZEND_FE(im_sublinhado, NULL)
    ZEND_FE(im_italico, NULL)
    ZEND_FE(im_condensado, NULL)
    ZEND_FE(im_expandido, NULL) 
    ZEND_FE(im_fontenormal, NULL)
    ZEND_FE(im_fonteelite, NULL)   
    ZEND_FE(im_reset, NULL)
    ZEND_FE(im_verifica, NULL)
    ZEND_FALIAS(im_imprimir,im_imp,NULL)
    ZEND_FALIAS(im_imprimirln,im_imp,NULL)
    ZEND_FALIAS(im_autent,im_autenticar,NULL)
    {NULL, NULL, NULL}
};

/* compiled module information */
zend_module_entry impFiscal_module_entry =
{
    STANDARD_MODULE_HEADER,
    "Impressora Fiscal",
    impFiscal_functions,
    PHP_MINIT(impFiscal), 
    NULL, 
    NULL, 
    NULL, 
    PHP_MINFO(impFiscal),
    NO_VERSION_YET,
    STANDARD_MODULE_PROPERTIES
};

/* implement standard "stub" routine to introduce ourselves to Zend */
#if COMPILE_DL_IMPFISCAL
  ZEND_GET_MODULE(impFiscal)
#endif

//ZEND_DECLARE_MODULE_GLOBALS(joao);


PHP_MINFO_FUNCTION(impFiscal) {
  php_info_print_table_start();
  php_info_print_table_header(2, "Modulo Impressora Fiscal", "instalado");
  php_info_print_table_row(2, "Version","1.0" );
  php_info_print_table_end();
 // DISPLAY_INI_ENTRIES();
}



ZEND_FUNCTION(im_impln) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char *str;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(string str) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_STRING) {
    zend_error(E_ERROR, "%s() parametro tem que ser uma  string!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  str = Z_STRVAL_PP(param);  
  
  //envia o comando imprimirln
  if(send(sockfd, "0", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string
  strcat(str,"\n");
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}


ZEND_FUNCTION(im_imp) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char *str;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(string str) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_STRING) {
    zend_error(E_ERROR, "%s() parametro tem que ser uma  string!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  str = Z_STRVAL_PP(param);  
  
  //envia o comando imprimir
  if(send(sockfd, "1", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string
  strcat(str,"\r");
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}

ZEND_FUNCTION(im_autenticar) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char *str;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(string str) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_STRING) {
    zend_error(E_ERROR, "%s() parametro tem que ser uma  string!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  str = Z_STRVAL_PP(param);  
  
  //envia o comando imprimir
  if(send(sockfd, "2", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}

ZEND_FUNCTION(im_verifica) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char str[2];
  unsigned short int aux;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //envia o comando imprimir
  if(send(sockfd, "B", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}
ZEND_FUNCTION(im_negrito) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char str[2];
  unsigned short int aux;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(bool b) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_BOOL) {
    zend_error(E_ERROR, "%s() parametro tem que ser true ou false!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  convert_to_long_ex(param);
  aux = (unsigned short int)Z_LVAL_PP(param);  
  sprintf(str,"%i",aux);  
  //envia o comando imprimir
  if(send(sockfd, "3", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}
ZEND_FUNCTION(im_sublinhado) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char str[2];
  unsigned short int aux;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(bool) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_BOOL) {
    zend_error(E_ERROR, "%s() parametro tem que ser true ou false!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  convert_to_long_ex(param);
  aux = (unsigned short int)Z_LVAL_PP(param);  
  sprintf(str,"%i",aux);  
  //envia o comando imprimir
  if(send(sockfd, "4", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}

ZEND_FUNCTION(im_italico) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char str[2];
  unsigned short int aux;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(bool) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_BOOL) {
    zend_error(E_ERROR, "%s() parametro tem que ser true ou false!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  convert_to_long_ex(param);
  aux = (unsigned short int)Z_LVAL_PP(param);  
  sprintf(str,"%i",aux);  
  //envia o comando imprimir
  if(send(sockfd, "5", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}

ZEND_FUNCTION(im_condensado) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char str[2];
  unsigned short int aux;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(bool b) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_BOOL) {
    zend_error(E_ERROR, "%s() parametro tem que ser true ou false!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  convert_to_long_ex(param);
  aux = (unsigned short int)Z_LVAL_PP(param);  
  sprintf(str,"%i",aux);  
  //envia o comando imprimir
  if(send(sockfd, "6", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}

ZEND_FUNCTION(im_expandido) {
  zval **param;    
  char retorno[5];
  int numbytes;
  char str[2];
  unsigned short int aux;
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 1 || zend_get_parameters_ex(1,&param) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 1 parametro: %s(bool b) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos
  if((*param)->type != IS_BOOL) {
    zend_error(E_ERROR, "%s() parametro tem que ser true ou false!",get_active_function_name(TSRMLS_C));
  }   
  //pega o parametro  
  convert_to_long_ex(param);
  aux = (unsigned short int)Z_LVAL_PP(param);  
  sprintf(str,"%i",aux);  
  //envia o comando imprimir
  if(send(sockfd, "7", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, str, strlen(str),0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}
ZEND_FUNCTION(im_fontenormal) {  
  char retorno[5];
  int numbytes;  
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");  
  //envia o comando imprimir
  if(send(sockfd, "8", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, "nada", 4,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}

ZEND_FUNCTION(im_fonteelite) {  
  char retorno[5];
  int numbytes;  
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");  
  //envia o comando imprimir
  if(send(sockfd, "9", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, "nada", 4,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}
ZEND_FUNCTION(im_reset) {  
  char retorno[5];
  int numbytes;  
  
   //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");  
  //envia o comando imprimir
  if(send(sockfd, "A", 1,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  //envia a string  
  if(send(sockfd, "nada", 4,0) == -1) {    
    php_error(E_ERROR, "%s() ERRO em send: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }  
  if((numbytes=recv(sockfd, retorno, 3, 0)) == -1) {
    php_error(E_ERROR, "%s() ERRO em recv: %s",get_active_function_name(TSRMLS_C),strerror(errno));
  }
  retorno[numbytes] = '\0';  
  //if(!strcmp(retorno,"OK")) {
  //  RETURN_LONG(0);  
  //} else {
  RETURN_LONG(atol(retorno));
 // }
}
//tipo de imressora *macro
//ip                *string
//porta             *int
ZEND_FUNCTION(im_conectar) {    
  struct hostent *he;
  struct sockaddr_in outro_end; // informa�o do endere� externo  
  unsigned short int portaImpressora;
  char *ipImpressora = NULL;  
  pval **args[2];
  int arg_count=ARG_COUNT(ht);
 
  //seta a linguagem portugues 
  setlocale(LC_ALL,"pt_BR.UTF-8");
  //verifica se esta passando 3 parametros, e pega os argumentos
  if(ZEND_NUM_ARGS() != 2 || zend_get_parameters_array_ex(arg_count,args) == FAILURE) {
    //WRONG_PARAM_COUNT;
    php_error(E_ERROR, "%s() funcao espera 2 parametros: %s(string ip,long porta) ",get_active_function_name(TSRMLS_C),get_active_function_name(TSRMLS_C));
    return;
  }
  //verifica se os parametros passados são validos  
  if((*args)[0]->type != IS_STRING) {
    zend_error(E_ERROR, "%s() O primeiro parametro tem que ser string!",get_active_function_name(TSRMLS_C));
  }
  if((*args)[1]->type != IS_LONG) {
    zend_error(E_ERROR, "%s() O segundo parametro tem que ser inteiro!",get_active_function_name(TSRMLS_C));
  }
  //pega os parametros  
  ipImpressora = Z_STRVAL_PP(args[0]);
  portaImpressora = (unsigned short int)Z_LVAL_PP(args[1]);  
  
  //atribui o ip em he
  if((he = gethostbyname(ipImpressora)) == NULL) {  // pega informa�o do host
    php_error(E_ERROR, "%s() erro em gethostbyname: %s",get_active_function_name(TSRMLS_C),strerror(errno));    
  }
  //cria o socket
  if((sockfd = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
    php_error(E_ERROR, "%s() erro em socket: %s",get_active_function_name(TSRMLS_C),strerror(errno));    
  }
  outro_end.sin_family = AF_INET;    // host byte order
  outro_end.sin_port = htons(portaImpressora);  // converte em short, network byte order
  outro_end.sin_addr = *((struct in_addr *)he->h_addr_list[0]);
  //printf("Endere� IP  : %s\n", inet_ntoa(*((struct in_addr *)he->h_addr_list[0] )));
  //outro_end.sin_addr = *((struct in_addr*)he-&gt;h_addr);
  //outro_end.sin_addr.s_addr = inet_addr(argv[1]);
  bzero(&(outro_end.sin_zero), 8);   // zera o resto da estrutura
  //faz a conexao
  if(connect(sockfd, (struct sockaddr *)&outro_end,sizeof(struct sockaddr)) == -1) {
    //php_error(E_ERROR, "%s() erro em connect: %s",get_active_function_name(TSRMLS_C),strerror(errno));    
    RETURN_LONG(0);
  } else
    RETURN_LONG(1);
}
/* }}} */
ZEND_FUNCTION(im_fechar) {
  close(sockfd);
}
 
PHP_MINIT_FUNCTION(impFiscal) {
  REGISTER_LONG_CONSTANT("SEM_CABO", 1, CONST_CS | CONST_PERSISTENT);
  REGISTER_LONG_CONSTANT("DESLIGADA", 2, CONST_CS | CONST_PERSISTENT);
  REGISTER_LONG_CONSTANT("SEM_PAPEL", 3, CONST_CS | CONST_PERSISTENT);  
  REGISTER_LONG_CONSTANT("OFFLINE", 4, CONST_CS | CONST_PERSISTENT);
  REGISTER_LONG_CONSTANT("ERRO", 5, CONST_CS | CONST_PERSISTENT);
  return SUCCESS;
}


