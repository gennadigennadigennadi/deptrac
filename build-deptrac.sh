#!/usr/bin/env bash

DEPTRAC_DIR="${DEPTRAC_DIR:-../deptrac}"
BUILD_DIR=build
BUILD_TMP=${BUILD_DIR}/tmp
PHP='docker compose exec -u 1000 php php -d memory_limit=-1'
SCOPER=$BUILD_DIR/php-scoper.phar

info()
{
    MESSAGE=$1;

    printf "\n";
    echo "######### $MESSAGE ########";
    printf "\n";
}

info "Start build deptrac"

info "Install composer"
$PHP /usr/bin/composer install -a --no-dev

info "Scope deptrac"
$PHP $SCOPER add-prefix --force --config scoper.inc.php --working-dir . --output-dir $BUILD_TMP

info "Dump Composer Autoloader"
$PHP /usr/bin/composer dump-autoload --working-dir $BUILD_TMP -a --no-dev

info "Copy package templates"
cp -v -R $BUILD_DIR/template/* *.md mkdocs.yml .github docs -t $BUILD_TMP

info "Copy build into deptrac distrubtion repository"
cp -v -a $BUILD_TMP/. $DEPTRAC_DIR

info "Git commit changes"
echo "Update $(date)" > git_commit_message.txt
echo "" >> git_commit_message.txt
git log $(git describe --tags --abbre=0)..HEAD --oneline >> git_commit_message.txt

git -C $DEPTRAC_DIR add .
mv git_commit_message.txt $DEPTRAC_DIR
git -C $DEPTRAC_DIR commit  -F git_commit_message.txt
git -C $DEPTRAC_DIR reset --hard
git -C $DEPTRAC_DIR clean -fd

info "Build done!"

