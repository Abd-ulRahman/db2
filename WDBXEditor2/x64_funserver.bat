@ECHO OFF 
CLS 
ECHO ArcEmu WoW Server Started %time:~0,5% %date:~1% 
:SERVERLOOP 
WDBXEditor2.exe
ECHO Restarted %time:~0,5% %date:~1% 
ECHO. 
GOTO SERVERLOOP 
:END