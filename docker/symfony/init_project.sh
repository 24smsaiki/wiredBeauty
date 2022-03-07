#!/bin/bash
echo "##### INITIALIZING PROJECT #####"
cd /var/www/html;

echo ""
echo ""
echo ""
echo "+++++ Composer Install +++++"
echo ""
composer install;

echo ""
echo ""
echo ""
echo "+++++ Yarn Install +++++"
echo ""
yarn install;

echo ""
echo ""
echo ""
echo "+++++ Yarn Build +++++"
echo ""
nohup yarn dev --watch &

echo "-- Run Container"
apachectl stop
apachectl -D FOREGROUND