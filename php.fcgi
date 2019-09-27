#!/bin/bash

PHP_INI_SCAN_DIR=/home/webdesignvratsa/.sh.phpmanager/php70.d
export PHP_INI_SCAN_DIR

DEFAULTPHPINI=/home/webdesignvratsa/rostar.webdesignvratsa.com/php70-fcgi.ini
exec /opt/cpanel/ea-php70/root/usr/bin/php-cgi -c ${DEFAULTPHPINI}
