@echo off
cd ..
cd portable_php
php.exe -S 0.0.0.0:8000 -t D:\xampp\htdocs router.php
pause