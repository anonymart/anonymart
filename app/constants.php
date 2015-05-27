<?PHP

define('PROJECT_NAME','Anonymart');

define('MINIMUM_CONFIRMATIONS',4);
define('BC_SCALE',4);
define('MINING_FEE','0.0001');

define('PGP_PUBLIC_START','-----BEGIN PGP PUBLIC KEY BLOCK-----');
define('PGP_PUBLIC_END','-----END PGP PUBLIC KEY BLOCK-----');
define('PGP_MESSAGE_START','-----BEGIN PGP MESSAGE-----');
define('PGP_MESSAGE_END','-----END PGP MESSAGE-----');
define('MPK_START','xpub');

DEFINE('XPUB_START','xpub661MyMwAqRbc');

define('ERROR_LOG',base_path().'/app/storage/logs/laravel.log');