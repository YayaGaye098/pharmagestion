@echo off
title PharmaGestion - Demarrage
echo ===================================================
echo      Demarrage de PharmaGestion (Mode Local/Offline)
echo ===================================================
echo.
echo Verification et lancement des conteneurs Docker...
docker compose up -d
echo.
echo ===================================================
echo   PharmaGestion est pret !
echo ===================================================
echo   - Acces sur ce PC      : http://localhost:8000
echo   - Base de donnees (PMA): http://localhost:8080
echo   - Acces caisses/reseau : http://[IP_DU_SERVEUR]:8000
echo ===================================================
echo.
pause
