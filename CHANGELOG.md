# Change Log

## [1.0.5] - 2016-11-15

### Ajouts
- Responsive : Selection du type de la page de paiement , classique ou responsive
- Paypal : variable additionnelle pour garantir une intégration optimale de Paypal

## [1.0.4] - 2016-11-15

### Corrections
- Block Redirect : No cache for redirect block and custom registry key by order id

## [1.0.3] - 2016-11-09

### Corrections
- Observer : Correction des problèmes avec "additional_data" depuis la version 2.0.1 de Magento
- (JS) Redirection :  Modification de la méthode de redirection vers Paybox. Redirection après orderPlaced

## [1.0.2] - 2016-10-26

### Corrections
- Observer : paramètres d'appels obligatoires manquants
- ACL : déclaration BO incorrecte

## [1.0.1] - 2016-10-25
### Ajouts
- Appels Paybox : ajout paramètre PBX_VERSION pour transmettre les versions Magento et du module
- Multi-devise : configuration pour forcer l'utilisation de la devise de base ou celle de la commande

### Modifications
- Traductions

### Corrections
- FO - Paiement : correction pour fonctionnement en sous-dossier ou sous-domaine
