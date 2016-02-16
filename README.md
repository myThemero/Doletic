# Doletic

Projet d'ERP open source destiné aux Junior-Entreprises.

## ETIC INSA Technologies

L'équipe du pôle DSI de la Junior-Entreprise de l'INSA de Lyon est à l'initiative de ce projet.

## Contributeurs & Junior-Entreprises

 - Paul Dautry (ETIC INSA Technologies)
 - Nicolas Sorin (ETIC INSA Technologies)

## Sources

Vous souhaitez consulter les sources, rendez-vous dans le dossier **src/**

## Mise en garde

Ce dépôt est en évolution constante, la mise en production n'est pas encore envisageable à ce stade.

## Installation 

### Ubuntu/Debian
 
 - Installation des dépendances :

   1. Installez Apache : `sudo apt-get install apache2` puis suivez les instructions.
   2. Installez MySQL : `sudo apt-get install mysql-server mysql-client` puis suivez les instructions.
   3. Installez PHP : `sudo apt-get install php5 php5-curl php5-json php5-mysql` puis suivez les instructions.
   4. [optionnel] Installez phpMyAdmin : `sudo apt-get install phpmyadmin` puis suivez les instructions.

 - Mise en place de Doletic :

   5. Dans mysql créez un utilisateur 'doletic' et une base de données 'doletic' sur laquel l'utilisateur a tous les droits.
   6. Lancez ensuite le script *Setup.php* situé dans **src/kernel/** et suivez les instructions.

 - Configuration Apache :

   7. Ajouter un hote virtuel dans Apache pour Doletic en veillant à respecter `DocumentRoot /path/to/Doletic`

