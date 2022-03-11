## EXECUTE DOCKER

docker-compose up -d --build

## UPDATE DATABASE

docker exec -it symfony.wiredbeauty php bin/console d:s:u --force

## INSTALL PACKAGES

docker exec -it symfony.wiredbeauty composer install

## EXECUTE FIXTURES

docker exec -it symfony.wiredbeauty php bin/console d:f:l

## ACCOUNTS 

#### User : user@user.fr => azerty
#### Admin : admin@admin.fr => azerty
