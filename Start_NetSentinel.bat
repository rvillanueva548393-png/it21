@echo off
color 0A
title NetSentinel Auto-Boot
echo ========================================================
echo        NetSentinel - Auto Starting All Services
echo ========================================================
echo.

:: Check Python is available
where python >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Python not found! Make sure Python is installed.
    pause
    exit /b
)

echo [1/3] Starting Python Packet Capture Engine...
start "NetSentinel-Engine" cmd /k "cd /d %~dp0python_capture && python capture.py"

echo [2/3] Waiting for engine to boot...
timeout /t 3 >nul

echo [3/3] All services started! Opening Dashboard...
start http://127.0.0.1:8000

echo.
echo ========================================================
echo  NetSentinel is now ONLINE and monitoring your network!
echo  Close the black terminal window to STOP monitoring.
echo ========================================================
