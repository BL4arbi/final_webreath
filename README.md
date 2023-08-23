# Monitoring de Modules IOT

Ce projet vise à développer un site web pour la surveillance des modules IOT. Il a été réalisé dans le cadre d'un test visant à évaluer les compétences en programmation.

## Table des matières
- [Introduction](#introduction)
- [Fonctionnalités](#fonctionnalités)
- [Technologies utilisées](#technologies-utilisées)
- [Installation et utilisation](#installation-et-utilisation)
- [Captures d'écran](#captures-décran)


## Introduction
Face à la prolifération des objets connectés à Internet, disposer d'outils robustes pour surveiller leur fonctionnement et leur disponibilité est devenu primordial. Ce projet répond à cette nécessité en offrant un outil web pour le monitoring des modules IOT.

## Fonctionnalités
- **Base de données des modules**: Elle répertorie les modules, leurs détails et leur historique de fonctionnement.
- **Formulaire d'inscription**: Il permet d'intégrer de nouveaux modules à la base de données.
- **Visualisation de l'état des modules**: Elle montre l'état actuel des modules, y compris la valeur mesurée et l'état de fonctionnement. Elle inclut aussi un graphique pour suivre l'évolution de la valeur mesurée.
- **Script de génération automatique**: Ce script simule l'état des modules, générant aléatoirement des données (telles que température ou vitesse) et les sauvegardant dans l'historique.

## Technologies utilisées
- HTML
- CSS
- PHP
- Bootstrap
- JavaScript
- MariaDB
- Symfony
  
### Installation et utilisation

Git clone du projet
composer install
symfony console doctrine:create:database
symfony console doctrine:migration:migrate
npm install 
symfony serve
npm run dev

#### Captures d'écran

![Pages d'accueil](https://github.com/BL4arbi/final_webreath/assets/142533784/e01f65e8-121b-4641-807b-272ab89bf0fc)

![Pages répertoriant tous les modules](https://github.com/BL4arbi/final_webreath/assets/142533784/426ec831-1529-47ce-909e-8f714913a5b7)

![Détail d'un module en particulier](https://github.com/BL4arbi/final_webreath/assets/142533784/39bdf75e-7b38-4c79-9ab9-0b6be1e3a740)

