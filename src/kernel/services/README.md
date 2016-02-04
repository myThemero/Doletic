# Services

## Dossiers

Les sous-dossiers de ce dossier sont les suivants :

- **php/** : contient toutes les classes représentant les Objets Métier manipulés par Doletic. Chaque OM est composé de trois sous-classes, par exemple pour un utilisateur :
	- User : cette classe représente l'objet en lui même. Il est sérialisable en JSON et sera retourné en réponse pour les services de consultation.
	- UserServices : cette classe représente l'interface qui implémente l'ensemble des services liés à l'OM. Elle hérite de l'interface **AbstractObjectServices** définie dans le noyau
	- UserDBObject : cette classe représente l'OM. Elle hérite de l'interface **AbstractDBObject** définie dans le noyau.
- **js/** : contient tous les scripts qui définissent les services pour la vue.

**Note concernant les OM :** les OM  représentent des concepts concrets ou abstraits manipulés dans les processus métier de la Junior-Entreprise et peuvent donc se traduire par plusieurs tables en base de données.

## Scripts

Les scripts importants de ce dossier sont les suivants :

- **Services.php** : déclare la classe qui porte le même nom. Cette dernière est exploitée par le contrôleur principal et nécessite une instance du noyau pour fonctionner.
