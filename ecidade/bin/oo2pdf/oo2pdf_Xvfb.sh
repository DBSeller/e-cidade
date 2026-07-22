#!/bin/bash
#http://www.oooninja.com/2008/02/batch-command-line-file-conversion-with.html
#apt-get install xvfb
# Try to autodetect OOFFICE and OOOPYTHON.
OOFFICE=/usr/bin/ooffice
OOOPYTHON=/usr/bin/python


# Set DISPLAY to something besides :1 (because :1 is the standard display). 
DISPLAY=:1000 
# Kill any existing virtual framebuffers. 
killall -u `whoami` Xvfb 
# Start the framebuffer. 
Xvfb $DISPLAY -screen 0 1024x768x24 &

# Kill any running OpenOffice.org processes.
killall -u `whoami` -q soffice

# Download the converter script if necessary.
#test -f DocumentConverter.py || wget http://www.artofsolving.com/files/DocumentConverter.py

# Start OpenOffice.org in listening mode on TCP port 8100.
$OOFFICE "-accept=socket,host=localhost,port=8100;urp;OpenOffice.ServiceManager" -norestore -nologo -headless -display :1000 &

# Wait a few seconds to be sure it has started.
sleep 5s

# Convert as many documents as you want serially (but not concurrently).
# Substitute whichever documents you wish.
#$OOOPYTHON DocumentConverter.py sample.ppt sample.swf
#$OOOPYTHON DocumentConverter.py sample.ppt sample.pdf
$OOOPYTHON DocumentConverter.py sample.odt sample.pdf
# Close OpenOffice.org.
killall -u `whoami` soffice
