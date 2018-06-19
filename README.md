Installation

Pull repository
Import cmsinmotion_kinderdagverblijfkiekeboe.sql 

USE PHP 5.4
https://stackoverflow.com/questions/16783558/how-can-i-add-additional-php-versions-to-mamp

Go to CMS/settings.inc.php to change the base dir and Database connection


Login Backend:
http://localhost:8888/cmsinmotion2/cms/docroot

Add user

INSERT INTO `users` (`id`, `voornaam`, `achternaam`, `emailadres`, `wachtwoord`, `group_id`, `cookiekey`, `firstuse`) VALUES
(7, 'admin', 'admin', 'admin@cmsinmotion.com', 'b028737117b44d0ad46ef35626eb15017090e9b3', 3, '', 0);

user: admin@cmsinmotion.com
pasword: rQC2YFHR


Go to site:
cmsinmotion2/activesite/

-todo URL redirecting







