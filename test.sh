#!/bin/bash
R='\033[0;31m'
G='\033[0;32m'

echo -e "${R}Testing plugin Framework (Unit)..."
echo -e "${G}"
./core/vendor/bin/phpunit --bootstrap ../../../wp-load.php ./core/vendor/jakiboy/vanilleplugin/tests

echo "";
sleep 2

echo -e "${R}Testing plugin Core (Unit)..."
echo -e "${G}"
./core/vendor/bin/phpunit --bootstrap ../../../wp-load.php ./tests

echo "";
sleep 2

echo -e "${R}Test done! press [Enter]"
read PAUSE