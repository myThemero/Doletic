# Norme de numérotation des versions

Ce fichier contient les instructions relative à la numérotation des versions des composants de Doletic. Merci de suivre ces instruction à chaque montée de version.

Un composant qui n'est pas encore apte au passage en production sera toujours numéroté "1.0dev".


## Versions de l'application

La version de départ après la mise en production porte le numéro 2.0, en référence à l'ancien ERP d'Etic INSA Technologies.

### Premier digit
Le premier digit de la version doit être changé en cas d'ajout d'un module entier.

### Second digit
Le second digit de la version doit être changé en cas de montée de version (premier digit) du kernel.

### Troisième digit
Le troisième digit de la version doit être changé en cas de montée de version (premier digit) d'un module.


## Versions du kernel

### Premier digit
Le premier digit de la version doit être changé en cas d'ajout de nouvelle fonctionnalité majeure, telle qu'un nouvel objet du kernel, une nouvelle interface, un nouveau Manager, etc.

### Second digit
Le second digit de la version doit être changé en cas d'ajout de fonctionnalité dans un composant déjà existant. Par exemple, l'ajout d'attributs et de méthode dans un objet ou une interface. Les mises à jour de ressources externes (framework, bibliothèque)  sont également concernées.

### Troisième digit
Le troisième digit de la version doit être changé en cas de changement mineur, tel qu'une résolution de bug non critique.


## Versions d'un module

### Premier digit
Le premier digit de la version doit être changé en cas d'ajout de fonctionnalité majeure, impliquant par exemple un ou des nouveaux objets, un nouvel onglet de vue, une nouvelle vue, un nouveau DBService.
 
### Second digit
Le second digit de la version doit être changé en cas d'ajout de fonctionnalité dans un composant déjà existant. Par exemple, l'ajout d'attributs et de méthode dans un objet.
 
### Troisième digit
Le troisième digit de la version doit être changé en cas de changement mineur, tel qu'une résolution de bug non critique.