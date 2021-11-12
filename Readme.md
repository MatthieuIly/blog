# Blog

## Installation

Dans un premier temps, cloner le repository :
```
git clone https://github.com/MatthieuIly/blog
cd blog
```

Installer les dépendances et préparer l'environnement de développement :
```
make install db_user=root db_password=sqlsql env=dev
```

## Tests
Lancer la suite de tests :
```
make tests
```

## Base de données et fixtures
```
make prepare env=dev
```

## Analyse du code
Dans un premier temps, pensez à éxecuter la commande qui permet de nettoyer le code :
```
make fix
```

Lancer les outils d'analyse statique :
```
make analyse
```