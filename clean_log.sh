#!/bin/bash
#
# >>>>>>> !!! Substitute <BASE_PATH> with the right path in your environment: for example /var/www/html !!! <<<<<<
#
rm <BASE_PATH>/Telegram/OpenDistributoriCarburantiBot/cron_log.txt
cp <BASE_PATH>/Telegram/OpenDistributoriCarburantiBot/cron_log_template.txt <BASE_PATH>/Telegram/OpenDistributoriCarburantiBot/cron_log.txt
php -c /etc/php5/apache2 -f <BASE_PATH>/Telegram/OpenDistributoriCarburantiBot/updateDataAccessNumbersTable.php
