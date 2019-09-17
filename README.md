## notice
the service is down since Dec. 2017, cause ntu sports center website is upgraded :(
for more informaiton, please visit https://pe.ntu.edu.tw/rent/#/site_inq

## demo
http://140.112.91.208/~iownthegame/court/lookup.php?mon=2017-12

## lookup
lookup.php, lookupm.php

## update
lookup_update.php, fetch page and save to json file

## crontab command
0 */1 * * * cd ntu_sports_court; php lookup_update.php > update_log
