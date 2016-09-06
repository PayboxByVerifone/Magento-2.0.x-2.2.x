# Paybox by Verifone - Magento2

Extension Magento2 pour la solution de paiement Paybox by Verifone

## About

En une seule intégration, offrez plusieurs méthodes de paiement, bénéficiez d'une page de paiement personalisée et sécurisée, multi-langues et multi-devises et offrez le paiement à la livraison ou en 3 fois sans frais pour vos clients.

Install
=======

1. Aller dans le dossier racine de Magento2

2. Exécuter la commande suivante pour la récupération du module (vous aurez besoin de vos identifiants Magento2):

    ```bash    
	composer require paybox/epayment:dev-master
    ```
   Attendre que les dépendances soient mises à jour

3. Exécuter les commandes suivantes pour l'installation et l'activation du module :

    ```bash
    php bin/magento setup:upgrade
    php bin/magento cache:clean
    php bin/magento setup:static-content:deploy isocode (ex: php bin/magento  setup:static-content:deploy fr_FR)
    ```

4. Vous pouvez configurer le module via le menu Boutiques \ Configuration \ Paybox
