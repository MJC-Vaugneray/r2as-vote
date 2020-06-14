# R2AS VOTE

Un outil simple et stupide pour gérer des votes en ligne sans inscription.

Créé pour les besoins du réseau R2AS, codé en PHP avec Symfony; à l'arrache et en quelques heures.

## Todo

- Retirer tout ce qui est salement hardcodé
- Ajouter d'autres modes de vote
- Ajouter un Dockerfile

PR bienvenues.

## Install

- `composer install`
- Renseigner `MAILER_DSN` et `DATABASE_URL` dans le `.env.local`
- Faire les migrations en DB `php bin/console doctrine:migrations:migrate`

## Licence

Logiciel libre publié sous licence WTFPLv2.
