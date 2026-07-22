#!/bin/bash
# Try to autodetect OOFFICE and OOOPYTHON.
OOFFICE=/usr/bin/soffice
OOOPYTHON=/usr/bin/python
BINPYTHONSCRIPT=bin/oo2pdf/

# Kill any running OpenOffice.org processes.
#killall -u `whoami` -q soffice

# Download the converter script if necessary.
# test -f DocumentConverter.py || wget http://www.artofsolving.com/files/DocumentConverter.py

OOPID=`pidof soffice.bin`

if [ "$OOPID" = "" ]
then
  # Start OpenOffice.org in listening mode on TCP port 8100.
  echo "Inciando OpenOffice como servico ..."
  
  # Comentado pois não estava funcionando com OO 2.4
  #$OOFFICE "-accept=socket,host=localhost,port=8100;urp;OpenOffice.ServiceManager" -norestore -nologo -headless &
  
  # Retirado do Site http://www.artofsolving.com/node/10
  $OOFFICE -accept="socket,host=localhost,port=8100;urp;" -nofirststartwizard -headless &

  # Wait a few seconds to be sure it has started.
  sleep 5s
fi

$OOOPYTHON ${BINPYTHONSCRIPT}DocumentConverter.py $1 $2

# Close OpenOffice.org.
#killall -u `whoami` soffice
