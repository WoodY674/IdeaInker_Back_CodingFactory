# TatooProject

### install
Open the projet in IDE and lunch this command
```
docker-compose -f docker-compose.dev.yml up
```
When Docker is ON you can run
```
composer install
```
```
symfony console d:s:u --force
```
if the database is not create you can make this command before d:s:u
```
symfony console d:d:c
```