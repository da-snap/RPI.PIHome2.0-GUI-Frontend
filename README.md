# PiHome

PiHome fork to use Nginx and Sqlite3.
More Infos at: [http://pihome.harkemedia.de](http://pihome.harkemedia.de)


#### PiHome 2.0 ####
User: `admin`
Pass: `pihome`

#### PiHome CronJobs #### ( sudo crontab -e )
```
*/5 * * * * php /home/www/cron/weather.php
* * * * * php /home/www/cron/sunrise_sunset.php
* * * * * php /home/www/cron/gcal.php
* * * * * php /home/www/cron/caldav.php
```

### Nginx Example Config (FIXME)###
```
server {
    listen 80;
    server_name 192.168.0.100;
    root /var/www/pihome/;
    location / {
        index index.php;
    }

  # Add headers to serve security related headers
  add_header Strict-Transport-Security "max-age=15768000; includeSubDomains; preload;";
  add_header X-Content-Type-Options nosniff;
  add_header X-Frame-Options "SAMEORIGIN";
  add_header X-XSS-Protection "1; mode=block";
  add_header X-Robots-Tag none;

  # Disable gzip to avoid the removal of the ETag header
  gzip off;
  location ~ \.php(?:$|/) {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param PATH_INFO $fastcgi_path_info;
    fastcgi_param modHeadersAvailable true; #Avoid sending the security headers twice
    fastcgi_pass php-handler;
    fastcgi_intercept_errors on;
  }

  # Adding the cache control header for js and css files
  # Make sure it is BELOW the location ~ \.php(?:$|/) { block
  location ~* \.(?:css|js)$ {
    add_header Cache-Control "public, max-age=7200";
    # Add headers to serve security related headers
    add_header Strict-Transport-Security "max-age=15768000; includeSubDomains; preload;";
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Robots-Tag none;
    # Optional: Don't log access to assets
    access_log off;
  }

  rewrite ^/([-0-9a-z]*)/([-0-9a-z]*)/([-0-9]*)/$ /index.php?c=$1&a=$2&id=$3 last;
  rewrite ^/([-0-9a-z]*)/([-0-9a-z]*)/$ /index.php?c=$1&a=$2 last;
  rewrite ^/([-0-9a-z]*)/$ /index.php?c=$1 last;

}
```
