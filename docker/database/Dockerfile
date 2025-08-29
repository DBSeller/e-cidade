FROM postgres:16-bookworm

LABEL maintainer="equipe.infraestrutura@dbseller.com.br"

ENV DEBIAN_FRONTEND=noninteractive

RUN echo "Acquire::http {No-Cache=True;};" > /etc/apt/apt.conf.d/no-cache

RUN apt-get update

RUN apt-get install -y --no-install-recommends tzdata \
  locales \
  myspell-pt \
  myspell-pt-br \
  wbrazilian \
  wportuguese

RUN echo 'America/Sao_Paulo' > /etc/timezone && \
  rm /etc/localtime && \
  ln -s /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
  echo "tzdata tzdata/Areas select America" | debconf-set-selections && \
  echo "tzdata tzdata/Zones/America select Sao_Paulo" | debconf-set-selections && \
  dpkg-reconfigure -f noninteractive tzdata

RUN sed -i "s|copy \"iso14651_t1\"|copy \"iso14651_t1\"\nreorder-after <U00A0>\n<U0020><CAP>;<CAP>;<CAP>;<U0020>\nreorder-end|" /usr/share/i18n/locales/pt_BR && \
  sed -i "s|^#\ pt_BR ISO-8859-1|pt_BR ISO-8859-1|g" /etc/locale.gen && \
  sed -i "s|^#\ pt_BR.UTF-8 UTF-8|pt_BR.UTF-8 UTF-8|g" /etc/locale.gen

RUN localedef -i pt_BR -c -f ISO-8859-1 -A /usr/share/locale/locale.alias pt_BR && \
  locale-gen pt_BR

RUN echo 'LANG="pt_BR.UTF-8"'>/etc/default/locale && \
dpkg-reconfigure --frontend=noninteractive locales && \
update-locale LANG=pt_BR.UTF-8

ENV LANG=pt_BR.UTF-8
ENV LANGUAGE=pt_BR:pt
ENV LC_ALL=pt_BR.UTF-8
