#include<stdio.h>
#include<string.h>
#include<errno.h>
#include <unistd.h>
#include <fcntl.h>



int main() {  
  int fd;
  char buf[256];
    
  if((fd = open("/dev/lp0",O_RDWR|O_NOCTTY|O_NDELAY)) == -1) {
    fprintf(stderr,"ERRO: %s\n",strerror(errno));
    exit(-1);
  }
  fcntl(fd, F_SETFL, 0);
  
  //renicializar
  sprintf(buf,"%c%c",0x1B,0x40);
  write(fd,buf,strlen(buf));
  //hab negrito
  sprintf(buf,"%c%c",0x1B,0x45);
  write(fd,buf,strlen(buf));
  //imp
  sprintf(buf,"     PREFEITURA MUNICIPAL DE TESTE\n");
  write(fd,buf,strlen(buf));
  //des neg
  sprintf(buf,"%c%c",0x1B,0x46);
  write(fd,buf,strlen(buf));
  //hab ital
  sprintf(buf,"%c%c",0x1B,0x34);
  write(fd,buf,strlen(buf));
  //imp
  sprintf(buf,"Rua  Adbull Dabi, 590/12  Bairro  Centro\n");
  write(fd,buf,strlen(buf));
  sprintf(buf,"Fone:  (051)3212-2637  Porto Alegre - RS\n");
  write(fd,buf,strlen(buf));
  sprintf(buf,"CNPJ: 93.015.006/0019-42  IE:096/0635920\n");
  write(fd,buf,strlen(buf));
  //des ital
  sprintf(buf,"%c%c",0x1B,0x35);
  write(fd,buf,strlen(buf));
  //hab exp
  sprintf(buf,"%c%c%c",0x1B,0x57,0x01);
  write(fd,buf,strlen(buf));
  //imp
  sprintf(buf,"    CUPOM FISCAL\n");
  write(fd,buf,strlen(buf));
  //des exp  
  sprintf(buf,"%c%c%c",0x1B,0x57,0x00);
  write(fd,buf,strlen(buf) + 1);
  //hab cond
  sprintf(buf,"%c%c",0x1B,0x0F);
  write(fd,buf,strlen(buf));
  //imp
  sprintf(buf,"------------------------------------------------------------\n");
  write(fd,buf,strlen(buf));
  //des cond  
  sprintf(buf,"%c",0x12);
  write(fd,buf,strlen(buf));
  close(fd);    
  return 0;
}
