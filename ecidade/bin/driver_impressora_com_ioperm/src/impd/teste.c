/*
#define DelayTime 500

double GetCPUSpeed(void)
{
DWORD TimerHi, TimerLo;
int PriorityClass, Priority;

PriorityClass = GetPriorityClass(GetCurrentProcess);
Priority = GetThreadPriority(GetCurrentThread);

SetPriorityClass(GetCurrentProcess,REALTIME_PRIORITY_CLASS);
SetThreadPriority(GetCurrentThread,THREAD_PRIORITY_TIME_CRITICAL);

Sleep(10);
__asm {
dw 310Fh
mov TimerLo, eax
mov TimerHi, edx
}
Sleep(DelayTime);
__asm {
dw 310Fh
sub eax, TimerLo
sbb edx, TimerHi
mov TimerLo, eax
mov TimerHi, edx
}

SetThreadPriority(GetCurrentThread, Priority);
SetPriorityClass(GetCurrentProcess, PriorityClass);

return(TimerLo / (1000 * DelayTime));
}

*/
#include<iostream.h>
#include<stdio.h>
#include<stdlib.h>
#include<sys/io.h>
#include<unistd.h>
#include<malloc.h>
#include<string.h>
#include<errno.h>
#include<ctype.h>
#include<time.h>

#define DELAY 0.5
#define PORTA 0x378 //dados
#define REGS2 (PORTA + 1) //status
#define REGS3 (REGS2 + 1) //comandos

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

void strobe() {
  unsigned char ch;
  ch = inb(REGS3);  
  ch |= STROBE;
  outb(ch,REGS3);    
  usleep(DELAY); 
  ch &= STROBE_OUT;
  outb(ch,REGS3);
}

int ack() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & ACK) != 0)
    return 1;
  else
    return 0;
}
int desligada() {
  unsigned char ch;  
  ch = inb(REGS2);
  if(ch == 'g' || ch == 'G' || ch == 'W')
    return 1;
  else
    return 0;
}
int erro() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & ERROR) != 0)
    return 0;
  else
    return 1;
}
int offLine() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & OFFLINE) != 0)
    return 0;
  else
    return 1;
}
int semPapel() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & PAPEROUT) != 0)
    return 1;
  else
    return 0;
}
int ocupada() {
  unsigned char ch;  
  ch = inb(REGS2);
  if((ch & BUSY) != 0)
    return 0;
  else
    return 1;
}
int imprimir(const char *str) {
  int ret;
  //printf("\nOC: %i",ocupada());
  if(!offLine() && !erro() && !semPapel()) {    
    while(ocupada())
      usleep(10000);
    while(*str) {
      outb(*(str++),PORTA);
      usleep(DELAY);
      strobe();          
    }    
    ret = 1;
  } else
    ret = 0;
  outb(0x03,PORTA);
  usleep(DELAY);
  strobe();
  return ret;
}

int main(int argc,char **argv) {
  int result;  
  char op;
  unsigned char str[100];
  clock_t ini,fim;
  
  //abre as portas
  if(ioperm(PORTA,3,1)) {
    fprintf(stderr,"ERRO abrindo endereco $s: %s\n",PORTA,strerror(errno));
    exit(-1);
  }
  ini = 99;
  fim = 88;
 ini = clock();
  //imprimir("joao da silva\n");
  strobe();  
  fim = clock();
  cout << ini << " " << fim << endl;
/*
  do {
//    printf("1) Habilita sensor de Papel\n"); 
//    printf("2) Desabilita sensor de Papel\n"); 
    printf("3) Reinicializar\n"); 
//    printf("4) Cancela Linha\n"); 
    printf("5) Condensado\n"); 
    printf("6) Desabilita Condensado\n"); 
    printf("7) Normal\n"); 
    printf("8) Elite(default)\n"); 
    printf("9) Expandido\n"); 
    printf("A) Desabilita Expandido\n"); 
    printf("B) Sublinhado\n"); 
    printf("C) Desabilita Sublinhado\n"); 
    printf("D) Enfatizado\n"); 
    printf("E) Desabilita Enfatizado\n"); 
    printf("F) Itálico\n"); 
    printf("G) Desabilita Itálico\n"); 
    printf("H) Imprimir sem avanço de linha\n"); 
    printf("I) Imprimir com avanço de linha\n"); 
    printf("J) Testar imprimir 2 vezes\n");
    printf("K) Autenticar 1\n");
    printf("L) Autenticar 2\n");
    printf("M) Autenticar 3\n");
    scanf("%c",&op);
    getchar();
    result = 1;
    switch(toupper(op)) {
      case '1':
        result = imprimir(SENSOR_PAPEL);
	break;
      case '2':
        result = imprimir(D_SENSOR_PAPEL);
	break;	
      case '3':
        result = imprimir(REINICIALIZAR);
	break;	
      case '4':
        result = imprimir(CANCELA_LINHA);
        break;
      case '5':
        result = imprimir(CONDENSADO);
	break;
      case '6':
        result = imprimir(D_CONDENSADO);
	break;	
      case '7':
        result = imprimir(NORMAL);
	break;	
      case '8':
        result = imprimir(ELITE);
        break;
      case '9':
        result = imprimir(EXPANDIDO);
	break;
      case 'A':
        result = imprimir(D_EXPANDIDO);
	break;	
      case 'B':
        result = imprimir(SUBLINHADO);
	break;	
      case 'C':
        result = imprimir(D_SUBLINHADO);
        break;
      case 'D':
        result = imprimir(ENFATIZADO);
	break;
      case 'E':
        result = imprimir(D_ENFATIZADO);
	break;	
      case 'F':
        result = imprimir(ITALICO);
	break;	
      case 'G':
        result = imprimir(D_ITALICO);
        break;
      case 'H':
        printf("Imprimir sem avanço\n");
        scanf("%s",str);
        getchar();
        strcat(str,"\r");
        result = imprimir(str);
	break;
      case 'I':
        printf("Imprimir com avanço\n");
        scanf("%s",str);
        getchar();
        strcat(str,"\n");
        result = imprimir(str);
	break;	
      case 'J':
        result = imprimir("Imprimindo UMA vez!\n");
        //while(ocupada())
        //  usleep(10000);
        result = imprimir("Tentando de NOVO!\n");
	break;	
      case 'K':
        if((result = imprimir(AUTENT1))) {//habilita autent 1       
          do {
            usleep(10000);
          } while(!semPapel());
          result = imprimir("ESTA VAI NO DOCUMENTO(MODO 1)\r");//imprime no documento
          imprimir(D_AUTENT1);//desabilita autenticacao 2
          result = imprimir("ESTA VAI NA BOBINA(MODO 1)\n");//imprime na bobina
        }
        break;
      case 'L':
        if((result = imprimir(AUTENT2))) {//hab auten 2
          do {
            usleep(10000);          
          } while(offLine()); 
          result = imprimir("ESTA VAI NO DOCUMENTO\r");//imprime no documento
          imprimir(D_AUTENT2);//desabilita autenticacao 2
          result = imprimir("ESTA VAI NA BOBINA\n");//imprime na bobina
        }
        break;
      case 'M':
        result = imprimir(AUTENT2);
        break;
    }
    if(!result) {
      printf("\nOCORREU UM ERRO\n");
      if(desligada())
        printf("Impressora Desligada\n");
      if(ocupada())
        printf("Impressora Ocupada\n");
      if(ack())
        printf("ACK retornou UM\n");
      if(semPapel())
        printf("Impressora sem Papel\n");
      if(offLine())
        printf("Impressora OffLine\n");
      if(erro())
        printf("Ocorreu um erro:\n");
    }
    if(!result) {
      printf("ORDENADO\n");
      if(semPapel())
        printf("SEM PAPEL\n");
      else if(desligada())
        printf("DESLIGADA\n");
      else if(offLine())
        printf("OFF LINE\n");
    }
  } while(op != '0');
  */
  if(ioperm(PORTA,3,0)) {
    fprintf(stderr,"ERRO abrindo endereco $s: %s\n",PORTA,strerror(errno));
    exit(-1);
  }
  return 0;
}


