# Noyau de Doletic 

## Dossiers

Les sous-dossiers de ce dossier sont les suivants :

- **interfaces/** : contient toutes les classes abstraites manipulable par des classes du noyau.
- **loaders/** : contient toutes les classes permettant de charger dynamiquement des classes plugin.
- **managers/** : contient toutes les classes chargées de gérer un aspect fonctionnel du noyau. Elles sont instanciées par le noyau lors de sa propre instanciation.
- **objects/** : contient toutes les classes utilitaires liées au noyau.
- **templates/** : contient les fichiers modèles pour les classes héritant des classes abstraites contenues dans le dossier **interface/**.
- **ui/** : contient tout ce qui concerne l'interface utilisateur propre au noyau.

## Scripts

Les scripts importants de ce dossier sont les suivants :

- **DoleticKernel.php** : déclare la classe qui porte le même nom. Cette dernière fait office de façade pour tous les managers situés dans le dossier 


- **DBInit.php** : permet de recréer une base de donnée et de la peupler en utilisant les fonctionnalités du noyau uniquement. 
  - _Usage :_ `$> php5 DBInit.php`
  - _**Mise en garde :** ce script **détruira** la base de données configurée dans le fichier settings.ini !_ 

 
- **Main.php** : point d'entrée de Doletic, c'est ce script qui contient le contrôleur principal.


- **settings.ini** : fichier de configuration du noyau, les paramètres sont décrits à l'intérieur.


- **Tests.php** : script de test de Doletic. Tous les tests sont implémentés dans ce fichier. 
  - _Usage :_ `$> php5 Test.php [test_name]`
  - _**Mise en garde :** ce script **détruira** la base de données configurée dans le fichier settings.ini !_ 


### Note concernant l'interface utilisateur

L'interface utilisateur est entièrement réalisée avec [Semantic UI](http://semantic-ui.com/).

Elle exploite également l'[API Plotly JS](https://plot.ly/javascript/).