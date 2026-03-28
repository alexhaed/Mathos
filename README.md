# Mathos

Site web pour s'entraîner aux calculs mathématiques. Les élèves peuvent s'exercer sur différents types d'opérations, suivre leur progression, et les admins peuvent consulter les statistiques par utilisateur.

## Exercices disponibles

- Addition et soustraction
- Compléments
- Calculs à trous
- Multiplication et division
- Division avec reste
- Priorité des opérations
- Nombres relatifs
- Nombres décimaux
- Double et moitié

## Installation

### Prérequis

- [Docker](https://www.docker.com/) et Docker Compose

### Démarrage

1. Copier le fichier d'environnement et le remplir :

```bash
cp .env.example .env
```

2. Lancer l'application :

```bash
docker compose up --build
```

L'application est accessible sur [http://localhost:8080](http://localhost:8080).

## Stack

- PHP / Apache
- MariaDB 10.11
- Docker Compose
