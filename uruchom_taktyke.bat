@echo off

cd C:\taktykapodroznika

start "PHP Server" cmd /min /k "php artisan serve" & start "NPM Dev" cmd /min /k "npm run dev" & 
tasklist /FI "IMAGENAME eq xampp-control.exe" 2>NUL | find /I /N "xampp-control.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo XAMPP jest już uruchomiony.
) else (
    start "XAMPP" "C:\xampp\xampp-control.exe"
)
