#!/bin/bash
R='\033[0;31m'
G='\033[0;32m'
B='\033[0;96m'

PLUGIN='winamaz-woocommerce' # slug
PREFIX='WinamazWoocommerce' # StudlyCaps

if [ -d "${PLUGIN}" ]; then
    rm -rf "${PLUGIN}"
fi
rm -f *.zip

# Activate plugin
echo -e "${G}Activating plugin..."
wp plugin activate "${PLUGIN}"
sleep 3
clear

# Unit testing
bash test.sh
sleep 3
clear

# Cloning plugin parts
echo -e "${G}Cloning plugin parts..."
mkdir "${PLUGIN}"
mkdir "${PLUGIN}/core"
cp "${PLUGIN}.php" "${PLUGIN}/${PLUGIN}.php"
cp "index.php" "${PLUGIN}/index.php"
cp "uninstall.php" "${PLUGIN}/uninstall.php"
cp "license.txt" "${PLUGIN}/license.txt"
cp "vanilleplugin.json" "${PLUGIN}/composer.json" # Used by packager
cp "core/changelog.txt" "${PLUGIN}/core/changelog.txt"
cp "core/Framework.php" "${PLUGIN}/core/Framework.php"
cp -r "core/assets" "${PLUGIN}/core/assets"
cp -r "core/storage" "${PLUGIN}/core/storage"
cp -r "core/system" "${PLUGIN}/core/system"
cp -r "core/view" "${PLUGIN}/core/view"
cp -r "languages" "${PLUGIN}/languages"
sleep 2
clear

# Composer update (Production)
echo -e "${G}Composer update (Production)..."
cd "${PLUGIN}"
composer clearcache
composer validate
composer update --no-dev -o
sleep 2
clear

# Packager init
echo -e "${G}VanillePlugin Packager init..."
if ! vanilleplugin --version vanilleplugin &> /dev/null
then
	echo -e "${B}Installing global VanillePlugin Packager..."
    composer global require --dev jakiboy/vanilleplugin-packager
fi

# Packaging
vanilleplugin -reg "\!${PREFIX}"
sleep 2
clear

# Test Packaging
vanilleplugin -test "\!${PREFIX}"
sleep 2
clear

# Removing development vendors
echo -e "${R}Removing development vendors..."

# Dynamic
find . -name "*.sh" -type f -delete
find . -name "*.md" -type f -delete
find . -name "phpunit.*" -type f -delete
find . -name ".gitignore" -type f -delete
find . -name ".editorconfig" -type f -delete
find . -name ".gitattributes" -type f -delete
find . -name ".codeclimate.yml" -type f -delete
find . -name ".travis.yml" -type f -delete
find . -name "composer.json" -type f -delete
find . -name "composer.lock" -type f -delete
find . -name "CHANGELOG" -type f -delete
find . -name "README" -type f -delete
find . -name "README.rst" -type f -delete
find . -name "LICENSE.txt" -type f -delete
find . -name "LICENSE" -type f -delete
find . -name "LICENCE" -type f -delete
find . -name "installed.json" -type f -delete
find . -name "_config.yml" -type f -delete
find . -name ".php_cs.dist" -type f -delete
find . -name ".php-cs-fixer.dist.php" -type f -delete
find "./core/vendor/" -type d -name ".github" -exec rm -rf {} +
find "./core/vendor/" -type d -name "bin" -exec rm -rf {} +
find "./core/vendor/" -type d -name "dist" -exec rm -rf {} +
find "./core/vendor/" -type d -name "tests" -exec rm -rf {} +
find "./core/vendor/" -type d -name "docs" -exec rm -rf {} +
find "./core/vendor/" -type d -name "examples" -exec rm -rf {} +
find "./core/vendor/" -type d -name "example" -exec rm -rf {} +
find "./core/vendor/" -type d -name "demo" -exec rm -rf {} +

# Custom
rm "core/vendor/jakiboy/vanilleplugin/logo.png"
sleep 2
clear

echo -e "${G}Restricting vendor directory..."
echo "deny from all" > "core/vendor/.htaccess"
sleep 2
clear

# Installing globals
if ! uglifyjs -v uglifyjs &> /dev/null
then
	echo -e "${B}Installing global uglify-js package..."
    npm install uglify-js -g
fi

if ! uglifycss --version uglifycss &> /dev/null
then
	echo -e "${B}Installing global uglify-css package..."
    npm install uglifycss -g
fi

# Minify CSS files
echo -e "${G}Minify CSS files..."
uglifycss "core/assets/front/css/style.css" --output "core/assets/front/css/style.css"
sleep 2
clear

# Minify JS files
echo -e "${G}Minify JS files..."
uglifyjs "core/assets/front/js/main.js" -c -m --output "core/assets/front/js/main.js"
sleep 2
clear

# Compile language files
echo -e "${G}Compile language files [FR]..."
msgfmt -o "languages/${PLUGIN}-fr_FR.mo" "languages/${PLUGIN}-fr_FR.po"
sleep 2
clear

# Remove plugin cache, temp & logs
echo -e "${R}Remove plugin cache, temp & logs..."
rm -rf "core/storage/cache"
rm -rf "core/storage/temp"
rm -rf "core/storage/logs"
sleep 2
clear

# Creating plugin storage directories
echo -e "${G}Creating plugin storage directories..."
mkdir "core/storage/cache"
mkdir "core/storage/temp"
mkdir "core/storage/logs"
sleep 2
clear

# Update plugin config & disable debug mode
echo -e "${G}Update plugin config & disable debug mode..."
rm "core/storage/config/global.json.example"
DEBUGON='"debug": true'
DEBUGOFF='"debug": false'
cp "core/storage/config/global.json" "core/storage/config/global.tmp"
sed "s/${DEBUGON}/${DEBUGOFF}/g" "core/storage/config/global.tmp" > "core/storage/config/global.json"
rm "core/storage/config/global.tmp"
sleep 2
clear

if ! json --version json &> /dev/null
then
	echo -e "${B}Installing global Json Parser..."
    npm install -g json
fi
echo -e "${G}Parsing version from global.json..."
VERSION=`cat core/storage/config/global.json`
VERSION=`echo ${VERSION} | json version`
sleep 2
clear

cd ..

# Generating language archive
echo -e "${G}Generating language [FR] archive [${VERSION}]..."
zip "${PLUGIN}-${VERSION}-fr_FR.zip" -r -j "languages/${PLUGIN}-fr_FR.mo" "languages/${PLUGIN}-fr_FR.po"
sleep 2
clear

# Generating plugin production archive
echo -e "${G}Generating plugin production archive [${VERSION}]..."
zip "${PLUGIN}-${VERSION}.zip" -r "${PLUGIN}"
rm -rf "${PLUGIN}"
sleep 2
clear

# Pushing plugin production files
echo "Done :D"
