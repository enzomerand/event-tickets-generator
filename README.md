# Event Ticket Generator

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/fef0bf5b8dcc45449eb09c0d102bad67)](https://www.codacy.com/app/enzo7337/event-tickets-generator?utm_source=github.com&utm_medium=referral&utm_content=Nyzo/event-tickets-generator&utm_campaign=badger)

Event Ticket Generator vous permet de générer des billets au format PDF, les exporter au format CSV ou importer des billets pour des événements.

Fonctionnalités :
- Générer un ou plusieurs tickets au format PDF, dans un ou plusieurs fichiers et choisir (si un seul fichier) de l'afficher directement dans le navigateur
- Importer des tickets à partir d'un fichier CSV
- Exporter des tickets au format CSV
- Télécharger un ou plusieurs tickets
- Définir une authencité de tickets via une vérification des codes de tickets

Un exemple d'un ticket généré :

![Billet exemple](assets/img/example.PNG?raw=true)

## Pour commencer
Pour initialiser la librairie, il faut créer une instance. Vous pouvez lui indiquer des paramètres comme le montre la documentation.
```php
include('../library/event-ticket/Config.php');
$ticket = new EventTicket\Ticket();
```
Puis ceci pour définir les paramètres globaux du tickets en PDF :
```php
$ticket->setGenerator();
```

## Mises à jour
À venir :
- Ajout des CGV en bas du billets
- Traductions
- Ajout d'un champ site web
- Exportation au format XLSX
- Affichage des prix sous le bon format, nottament avec les nombres décimaux dans l'exportation (fichier excel)
- Suppression d'un ticket

N'hésitez pas à proposer des nouveautés, suggérer, etc...
