@echo off
setlocal enabledelayedexpansion

REM Check if PHP is installed
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo PHP is not installed. Attempting to install...
    
    REM Download PHP
    powershell -Command "Invoke-WebRequest -Uri 'https://windows.php.net/downloads/releases/php-8.1.18-nts-Win32-vs16-x64.zip' -OutFile 'php.zip'"
    
    REM Extract PHP
    powershell -Command "Expand-Archive -Path 'php.zip' -DestinationPath 'C:\php' -Force"
    
    REM Add PHP to PATH
    setx PATH "%PATH%;C:\php" /M
    
    REM Refresh environment variables
    call refreshenv
    
    echo PHP has been installed to C:\php
) else (
    echo PHP is already installed.
)

REM Check SQLite extension
php -r "if(extension_loaded('sqlite3')) { echo 'SQLite3 extension is loaded.'; } else { echo 'SQLite3 extension is not loaded.'; exit(1); }"
if %errorlevel% neq 0 (
    echo Activating SQLite extension...
    
    REM Find php.ini location
    for /f "tokens=*" %%i in ('php --ini ^| find "Loaded Configuration File"') do set phpini=%%i
    set phpini=!phpini:~27!
    
    REM Activate SQLite extensions
    echo extension=sqlite3 >> "!phpini!"
    echo extension=pdo_sqlite >> "!phpini!"
    
    echo SQLite extensions have been activated in !phpini!
    echo Please restart your command prompt and run this script again.
    pause
    exit
) else (
    echo SQLite extension is already active.
)

REM Navigate to project directory
cd /d "D:\Kerjaan\sistock"

REM Get local IP address
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /R /C:"IPv4 Address"') do set local_ip=%%a
set local_ip=%local_ip:~1%

REM Start Laravel server
echo Starting Laravel server on http://%local_ip%:8000
php artisan serve --host=%local_ip% --port=8000

pause