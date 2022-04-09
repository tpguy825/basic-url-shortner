@echo off
WHERE git >nul 2>nul
IF %ERRORLEVEL% NEQ 0 GOTO nogit 

set %directory%=%~dp0

echo WARNING: this will delete every file in the folder
echo Select: [1] Delete all files/folders and install latest version, [2] Install to a different folder, [3] Cancel update
set /p gitoption="Type a number: "
if %gitoption%==1 goto install
if %gitoption%==2 goto installtodifferentfolder
else exit

:nogit
echo You do not have git installed
echo Please install git to update this.
echo Select: [1] Open download page, [2] Close.
set /p download="Type a number: "
if %download%==1 cmd /c "start https://git-scm.com/download/win"
exit

:install
echo Confirming to update in directory %directory%
echo Select: [1] Yes, [2] No
set /p confirmupdate="Type a number: "
if %confirmupdate%==1 goto doupdate
if %confirmupdate%==2 goto changedirectory
exit

:changedirectory
set /p directory="Enter the directory to install update to: "
goto install

:doupdate
echo "cd %% && del /q * && git clone -q https://github.com/tpguy825/basic-url-shortner . && echo Update complete && pause && exit" > C:\update-short.bat
cmd /c C:\update-short.bat
del C:\update-short.bat
