# Paybox by Verifone - Magento2

Extension Magento2 pour la solution de paiement Paybox by Verifone

## A propos

En une seule intégration, offrez plusieurs méthodes de paiement, bénéficiez d'une page de paiement personalisée et sécurisée, multi-langues et multi-devises et offrez le paiement à la livraison ou en 3 fois sans frais pour vos clients.

## Installation

1. Aller dans le dossier racine de Magento2 en ligne de commande

2. Exécuter la commande suivante pour la récupération des fichiers du module dans le dossier `<your Magento install dir>/vendor` (vous aurez besoin de vos identifiants Magento2) :

    ```sh    
	composer require paybox/epayment
    ```

3. Exécuter les commandes suivantes pour l'installation, l'activation et le déploiement du module :

    ```sh
    # Installation du module
    php bin/magento setup:upgrade
    # Nettoyage du cache
    php bin/magento cache:clean
    # Déploiement des ressources Front Office pour l'étape de paiement du tunnel de commande pour chaque langue du site (l'option <lang> est une liste séparée par des espaces de codes langue au format ISO-636, la liste est disponible en lançant la commande php bin/magento info:language:list)
    php bin/magento setup:static-content:deploy <lang> (exemple : fr_FR)
    ```

4. Vous pouvez alors configurer le module dans votre Back Office via le nouvel onglet Paybox du menu Boutiques \ Configuration

## Configuration

Le paramétrage par défaut correpond à l'environnement de test Paybox où tous les modes de paiement sont disponibles et où toutes les cartes sont activables, aucun paiement réel ne sera effectué.

Pour utiliser le module en réel en environnement de production, vous devez avoir souscrit un contrat auprès de Paybox, disposer de vos identifiants, configurer les options, modes de paiement et cartes en fonction de votre contrat.