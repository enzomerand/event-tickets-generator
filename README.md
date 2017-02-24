# Event Ticket Generator
Event Ticket Generator vous permet de générer des billets au format PDF, les exporter au format CSV ou importer des billets pour des événements.

Fonctionnalités :
- Générer un ou plusieurs tickets au format PDF, dans un ou plusieurs fichiers et choisir (si un seul fichier) de l'afficher directement dans le naviguateur
- Importer des tickets à partir d'un fichier CSV
- Exporter des tickets au format CSV
- Télécharger un ou plusiquers tickets
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
- Lien QrCode
- Encodage automatique avant de générer le PDF
- Ajout de l'heure et de la date de l'événement sur le billet, correctement formattés
- Ajout d'erreurs formattées (retour d'un texte uniquement)
- Ajout de codes d'erreurs
- Ajout d'un champ site web

Bugs actuels :
- Exportation de la date
- Le téléchargement renvoi des fichiers corrompus
- Mettre le type de ticket en majuscule automatiquement

N'hésitez pas à proposer des nouveautés, suggérer, etc...