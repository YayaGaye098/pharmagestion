@echo off
title PharmaGestion - Arret
echo ===================================================
echo             Arret de PharmaGestion
echo ===================================================
echo.
docker compose down
echo.
echo PharmaGestion a ete arrete proprement. Les donnees restent sauvegardees.
echo.
pause
