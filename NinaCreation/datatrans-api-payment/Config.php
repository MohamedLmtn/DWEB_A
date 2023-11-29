<?php
class Config {

    const WEB_ROOT = 'http://localhost/pp-article-code/data-trans/';

    const MERCHANT_ID = '1110014683';

    const PASSWORD = 'AuxX79AAjwN6BlC4';

    const SUCCESS_URL = Config::WEB_ROOT . 'return.php';

    const CANCEL_URL = Config::WEB_ROOT . 'return.php?status=cancelled';

    const ERROR_URL = Config::WEB_ROOT . 'return.php?status=error';
}
?>