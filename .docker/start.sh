!/bin/bash

set -e

echo "####################"
echo "# INSTALL COMPOSER #"
echo "####################"
composer install
echo "####################"
echo "#      TESTS       #"
echo "####################"
./vendor/bin/phpunit || true
if [ -f ./report/phpunit.txt ]; then
  cat ./report/phpunit.txt
  rm -rf ./report
fi
echo "####################"
echo "#   CODE SNIFFER   #"
echo "####################"
./vendor/bin/phpcs -p || true

#     cd ~
#     git clone https://github.com/laravel/laravel.git laravel-app

# cd ~/laravel-app
# docker run --rm -v $(pwd):/app composer install
# sudo chown -R $USER:$USER ~/laravel-app
