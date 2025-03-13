#include<stdio.h>
#include<string.h>
#include<errno.h>
#include<stdlib.h>
#include<unistd.h>
#include<sys/types.h>
#include<sys/socket.h>
#include<sys/wait.h>
#include<netdb.h>
#include<netinet/in.h>
#include<sys/io.h>
#include<fcntl.h>
#include<arpa/inet.h>

#define STROBE_OUT   0xFE
#define INIT_OUT     0xFB
#define AUTOFEED_OUT 0xFD
#define SELECT_OUT   0xF7

//reg de controle
#define STROBE       0x01
#define AUTOFEED     0x02
#define INIT         0x04
#define SELECT       0x08

#define BUSY         0x80
#define ACK          0x40
#define PAPEROUT     0x20
#define OFFLINE      0x10
#define ERROR        0x08

int PORTA;
int REGS2;
int REGS3;

//Operacoes
const char SENSOR_PAPEL[3]   = {0x1B,0x38,'\0'};
const char D_SENSOR_PAPEL[3] = {0x1B,0x39,'\0'};
const char REINICIALIZAR[3]  = {0x1B,0x40,'\0'};
//controle de dados
const char CANCELA_LINHA[2]  = {0x18,'\0'};
//tamanho da impressao e largura do caracter
const char CONDENSADO[3]     = {0x1B,0x0F,'\0'};
const char D_CONDENSADO[2]   = {0x12,'\0'};
const char NORMAL[3]         = {0x1B,0x4D,'\0'};
const char ELITE[3]          = {0x1B,0x50,'\0'};//default
const char EXPANDIDO[4]      = {0x1B,0x57,0x01,'\0'};
const char D_EXPANDIDO[4]    = {0x1B,0x57,'0','\0'};
//realces de impressao
const char SUBLINHADO[4]     = {0x1B,0x2D,0x01,'\0'};
const char D_SUBLINHADO[4]   = {0x1B,0x2D,'0','\0'};
const char ENFATIZADO[3]     = {0x1B,0x45,'\0'};
const char D_ENFATIZADO[3]   = {0x1B,0x46,'\0'};
const char ITALICO[3]        = {0x1B,0x34,'\0'};
const char D_ITALICO[3]      = {0x1B,0x35,'\0'};
//modo de autenticacao
const char AUTENT1[4]        = {0x1B,0x7D,0x01,'\0'};
const char D_AUTENT1[4]      = {0x1B,0x7D,'0','\0'};
const char AUTENT2[4]        = {0x1B,0x7E,0x01,'\0'};
const char D_AUTENT2[4]      = {0x1B,0x7E,'0','\0'};
const char AUTENT3[4]        = {0x1B,0x61,0x01,'\0'};
const char D_AUTENT3[4]      = {0x1B,0x61,'0','\0'};

void delay() {
/*
  struct timeval t;
  t.tv_sec = 0;
  t.tv_usec = (unsigned long)1.0;
  select(0,NULL,NULL,NULL,&t);
*/
/*
  struct timespec t,v;
  t.tv_sec = 0;
  t.tv_nsec = 1;
  v.tv_sec = 0;
  v.tv_nsec = 0;
  nanosleep(&t,&v);
*/

  int i,j;
  for(i = 0;i < 100;i++)
    for(j = 0;j < 1000;j++);
}

/* {{{ strobe
 */
void strobe() {
  unsigned char ch;
  ch = inb(REGS3);  
  ch |= STROBE;
  outb(ch,REGS3);    
  delay();
  ch &= STROBE_OUT;
  outb(ch,REGS3);
}
/* }}} */
/* {{{ ack */
int ack() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & ACK) != 0)
    return 1;
  else
    return 0;
}

/* }}} */
/* {{{ desligada 
 */
int desligada() {
  unsigned char ch;  
  ch = inb(REGS2);
  if(ch == 'g' || ch == 'G' || ch == 'W')
    return 1;
  else
    return 0;
}
int semCabo() {
  unsigned char ch;  
  ch = inb(REGS2);
  if(ch == 127)
    return 1;
  else
    return 0;
}
/* }}} */
/* {{{ erro
 */
int erro() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & ERROR) != 0)
    return 0;
  else
    return 1;
}
/* }}} */
/* {{{
 */
int offLine() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & OFFLINE) != 0)
    return 0;
  else
    return 1;
}
/* }}} */
/* {{{ semPapel
 */
int semPapel() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & PAPEROUT) != 0)
    return 1;
  else
    return 0;
}
/* }}} */
/* {{{ ocupada
 */
int ocupada() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & BUSY) != 0)
    return 0;
  else
    return 1;
}
/* }}} */
/* {{{ imprimir
 */

 //UM
int imprimir(const char *str) {
  int ret;  
  if(!offLine() && !erro() && !semPapel()) {    
    while(ocupada())
      delay();      
    while(*str) {
      outb(*(str++),PORTA);      
      delay();
      strobe();          
    }    
    ret = 1;
  } else
    ret = 0;
  outb(0x03,PORTA);  
  delay();
  strobe();
  return ret;
}
/* }}} */
int verificaErro() {
  /*
  if(semCabo())
    return 1;
  else if(desligada())
    return 2;
  else */
  if(semPapel())
    return 3;
  else if(offLine())
    return 4;
  else if(erro())
    return 5;
  return -1;
}
void trim(char *str) {
  char *aux;
  char *aux2;
  char *str2;
  
  aux = malloc(strlen(str) * sizeof(char) + 1);
  aux2 = aux;
  str2 = str;
  while(*str2) {
    if(*str2 != ' ' && *str2 != '\n')
      *(aux++) = *str2;
    str2++;
  }
  *aux = '\0';
  strcpy(str,aux2);
  free(aux2);
}
/* {{{ char substr
 */
char *substr(char *str,const char ch) {
  while(*str) {
    if(*str == ch)
      break;
    str++;
  }
  str++; 
  trim(str); 
  return str;
}
/* }}} */
int main(void) {  
  struct hostent *end1;
  int sockfd, nova_fd;  // escutar em fd, novas conexes em  new_fd
  struct sockaddr_in meu_end;    // minha informa�o de endere�
  struct sockaddr_in outro_end; // informa�o de endere� externo
  int sin_size;
  int yes = 1;
  FILE *fp;  
  char portaTcp[256];
  char portaParalela[256];
  char ipServidorPHP[256];
  char comando[2];
  char str[256];
  int aux;  
//  fd_set mestre;

  if((fp = fopen("impd.conf","r")) == NULL) {
    fprintf(stderr,"Erro abrindo arquivo: %s\n",strerror(errno));
    exit(-1);
  }
  while(!feof(fp)) {
    fgets(str,255,fp);
    if(!strchr(str,'#')) {
      if(strstr(str,"PORTA_TCPIP"))
        strcpy(portaTcp,substr(str,'='));
      else if(strstr(str,"PORTA_PARLELA"))
        strcpy(portaParalela,substr(str,'='));
      else if(strstr(str,"IP_SERVIDOR_PHP"))
        strcpy(ipServidorPHP,substr(str,'='));      
    }    
  }
  fclose(fp);
  strcpy(portaParalela,substr(portaParalela,'x'));
  //PORTA = atoi(portaParalela);
  PORTA = 0x378;
  REGS2 = (PORTA + 1);
  REGS3 = (REGS2 + 1);
  
  /*
  printf("SA: '%s'\n",portaTcp);
  printf("SA: '%s'\n",portaParalela);
  printf("SA: '%s'\n",ipServidorPHP);  
  */
  if((sockfd = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
    fprintf(stderr,"Erro em socket: %s\n",strerror(errno));
    exit(-1);
  }
  
  //fcntl(sockfd,F_SETFL,O_NONBLOCK);
  //FD_ZERO(&mestre);
  //FD_SET(sockfd,&mestre);
  if(setsockopt(sockfd,SOL_SOCKET,SO_REUSEADDR,&yes,sizeof(int)) == -1) {
    fprintf(stderr,"Erro em setsockopt: %s\n",strerror(errno));
    exit(-1);
  }
  meu_end.sin_family = AF_INET;         // host byte order
  meu_end.sin_port = htons(atoi(portaTcp)); // converte para short, network byte order
  meu_end.sin_addr.s_addr = INADDR_ANY; //automaticamente preenche com meu endere� IP
  bzero(&(meu_end.sin_zero), 8);        // zera o resto da estrutura
  if(bind(sockfd, (struct sockaddr *)&meu_end,sizeof(struct sockaddr))  == -1) {
    fprintf(stderr,"Erro em bind: %s\n",strerror(errno));
    exit(-1);
  }
  if(listen(sockfd,1) == -1) {
    fprintf(stderr,"Erro em listen: %s\n",strerror(errno));
    exit(-1);
  }
  while(1) {  // loop principal accept()
    sin_size = sizeof(struct sockaddr_in);    
    if((nova_fd = accept(sockfd, (struct sockaddr*)&outro_end, &sin_size)) == -1) {
      fprintf(stderr,"Erro em accept: %s\n",strerror(errno));
      exit(-1);
    }          
    //printf("SAIDA: '%s' == '%s'\n",(char *)gethostbyname((char *)ipServidorPHP),(char *)gethostbyname((char *)inet_ntoa(outro_end.sin_addr)));
    end1 = gethostbyname(ipServidorPHP);
    //end2 = gethostbyname(inet_ntoa(outro_end.sin_addr));
    //printf("SAIDA: '%s' == '%s'\n",inet_ntoa(outro_end.sin_addr),ipServidorPHP);
    if(strcmp(ipServidorPHP,inet_ntoa(outro_end.sin_addr))) {    
      fprintf(stderr,"Voce não tem permissao para acessar esta porta!");
      close(nova_fd);
    } else {
      //abre 3 portas a partir do endereco porta
      if(ioperm(PORTA,3,1)) {
        fprintf(stderr,"ERRO abrindo endereco: %s\n",strerror(errno));
        exit(-1);
      }         
      while(1) {
        if((aux = recv(nova_fd,comando,1,0)) != 0) {
          comando[1] = '\0';
	  aux = recv(nova_fd,str,256,0);
	  str[aux] = '\0';	
	  switch(comando[0]) {
  	    case '0'://impln
	      if(!imprimir(str)) {
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	      } else {
	        send(nova_fd, "0", 1,0);
	      }
	      break;
            case '1'://imp
	      if(!imprimir(str)) {
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	      } else {
	        send(nova_fd, "0", 1,0);
	      }
	      break;
	    case '2'://autenticar	    	    
  	      if(!imprimir(AUTENT2)) {//hab modo de auten 2
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	        break;
	      }	    
              do {
                usleep(10000);          
              } while(offLine());	    
	      strcat(str,"\r");
              if(!imprimir(str)) {//imprime no documento	    
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	        break;
	      }	    
              if(!imprimir(D_AUTENT2)) {//desabilita autenticacao 2
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	        break;
	      }	    
              send(nova_fd, "0", 1,0);	    
	      break;
	    case '3'://negrito
  	      if(str[0] == '1') {
	        if(!imprimir(ENFATIZADO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      } else {
	        if(!imprimir(D_ENFATIZADO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      }
	      send(nova_fd, "0", 1,0);	    
	      break;	
	    case '4'://sublinhado
 	      if(str[0] == '1') {
	        if(!imprimir(SUBLINHADO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      } else {
	        if(!imprimir(D_SUBLINHADO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      }
	      send(nova_fd, "0", 1,0);	    
	      break;
	    case '5'://italico
	      if(str[0] == '1') {
	        if(!imprimir(ITALICO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      } else {
	        if(!imprimir(D_ITALICO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      }
	      send(nova_fd, "0", 1,0);	    
	      break;	
	    case '6'://condensado
	      if(str[0] == '1') {
	        if(!imprimir(CONDENSADO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      } else {
	        if(!imprimir(D_CONDENSADO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      }
	      send(nova_fd, "0", 1,0);	    
	      break;	
	    case '7'://expandido
	      if(str[0] == '1') {
	        if(!imprimir(EXPANDIDO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      } else {
	        if(!imprimir(D_EXPANDIDO)) {
	          sprintf(str,"%i",verificaErro());
	          send(nova_fd, str, strlen(str),0);
		  break;
	        }
	      }
	      send(nova_fd, "0", 1,0);	    
	      break;	
	    case '8'://fonte normal	    
	      if(!imprimir(NORMAL)) {
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	        break;
	      }	    
	      send(nova_fd, "0", 1,0);	    
	      break;	
	    case '9'://fonte elite
  	      if(!imprimir(ELITE)) {
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	        break;
	      }	    
	      send(nova_fd, "0", 1,0);	    
	      break;
	    case 'A'://fonte elite
	      if(!imprimir(REINICIALIZAR)) {
	        sprintf(str,"%i",verificaErro());
	        send(nova_fd, str, strlen(str),0);
	        break;
	      }	    
	      send(nova_fd, "0", 1,0);	    
	      break;
	    case 'B'://verificaErro
	      sprintf(str,"%i",verificaErro());
              send(nova_fd, str, strlen(str),0);
              break;
	  }	
        } else {
          close(nova_fd);
          break;
        }
      }
    } //if(!strcmp(inet_ntoa(*((struct in_addr *)end1->h_addr_list[0],inet_ntoa(outro_end.sin_addr)) )) ) {
    /**/
    //fecha as portas    
    if(ioperm(PORTA,3,1)) {
      fprintf(stderr,"ERRO fechando endereco: %s\n",strerror(errno));
      exit(-1);
    }   
    //close(nova_fd);
  }
  close(sockfd);
  return 0;
}

