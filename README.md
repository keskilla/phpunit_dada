# phpunit_dada

--TP sur PHPUNIT--
1- installer phpunit via composer
2- Configurer les tests via un fichier de config XML 
2.1- Exécuter les tests depuis notre dossier bin
3- Créer la class de test qui s'étend de PHPUnit_Framework_TestCase
4- Tester via le principe des MOCK : 
    -tester qu'une fonction est bien appelée et avec les bons arguments
    -modifier la fonction pour quelle renvoie tel ou tel résultat

5- créer la classe personne et tester la méthode bonjour
6- écrire une requête SQL puis mockée dans nos tests OU (sans BDD) avec un tableau mocker la fonction getPersonneID de la classe personne

RAS : installer XDEBUG et générer le fichier html pour afficher le coverage : 
// phpunit --coverage-html coverage

0xxx[];;;;;;;;;;;;;;;;;;>
20/04/2022

PHP 7.4.26
PHPUnit 5.7.27
