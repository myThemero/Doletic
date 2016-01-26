# Services

## Dossiers

Les sous-dossiers de ce dossier sont les suivants :

- **objects/** : contient toutes les classes représentant les Objets Métier manipulés par Doletic. Chaque OM est composé de trois sous-classes, par exemple pour un ticket de support utilisateur :
	- Ticket : cette classe représente l'objet en lui même. Il est sérialisable en JSON et sera retourné en réponse pour les services de consultation.
	- TicketServices : cette classe représente l'interface qui implémente l'ensemble des services liés à l'OM. Elle hérite de l'interface **AbstractObjectServices** définie dans le noyau
	- TicketDBObject : cette classe représente l'OM. Elle hérite de l'interface **AbstractDBObject** définie dans le noyau.

**Note concernant les OM :** les OM  représentent des concepts concrets ou abstraits manipulés dans les processus métier de la Junior-Entreprise et peuvent donc se traduire par plusieurs tables en base de données.

## Scripts

Les scripts importants de ce dossier sont les suivants :

- **Services.php** : déclare la classe qui porte le même nom. Cette dernière est exploitée par le contrôleur principal et nécessite une instance du noyau pour fonctionner.
