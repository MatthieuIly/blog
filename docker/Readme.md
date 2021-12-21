## Créer une image

    docker build -f .\config\dockerfiles\Dockerfile-php8.0 -t sankokai/php8.0 .
    docker build -f .\config\dockerfiles\Dockerfile-php8.1 -t sankokai/php8.1 .

## Démarrer les conteneurs

    docker compose up

## Dump Base de données

# Composer

- Sous PHP 8.0  

    - composer update --ignore-platform-reqs

## Choisir le ficher docker-compose à utiliser
    
    docker compose -f ./docker/docker-compose.yml up

Attention aux ports déjà utilisés par Windows, Docker ne le dira pas !  

## Vhosts Apache

### Sous Windows  
Ajouter "127.0.0.1       url" dans le fichier C:\Windows\System32\drivers\etc\hosts  

Exemple  
127.0.0.1   blog.docker.local  


### WSL

Fichier c:\Users\YourUsername\.wslconfig 

    [wsl2]  
    kernel=C:\\temp\\myCustomKernelWslDocker  
    memory=4GB # Limits VM memory in WSL 2 to 4 GB  
    processors=2 # Makes the WSL 2 VM use two virtual processors

### Remove orphans Containers
    docker compose down --remove-orphans
