@echo off
title Al-Shifa Hospital Management System - Launcher
cd /d "D:\hospital-management-system"

echo ==========================================================
echo    AL-SHIFA INTERNATIONAL HOSPITAL MANAGEMENT SYSTEM
echo ==========================================================
echo Starting Laravel Server on http://127.0.0.1:8000 ...
echo.

start http://127.0.0.1:8000
php artisan serve --port=8000
pause
