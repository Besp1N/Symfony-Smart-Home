
# Symfony smart home

Symfony Smart Home is a modern, efficient smart home management system built using the Symfony framework. It leverages MQTT protocol for communication with ESP8266 microcontrollers that control LED lights. The application features OAuth2 and email login options, along with a comprehensive dashboard for managing devices within different rooms.




## Authors

- [@Kacper Karabinowski](https://github.com/Besp1N)


## Installation

Install my project with

```bash
  git clone https://github.com/Besp1N/Symfony-Smart-Home.git
  symfony server:start
```

You can also use this nginx config, but you have to set your directory

```bash
 #user  nobody;
worker_processes  1;

#error_log  logs/error.log;
#error_log  logs/error.log  notice;
#error_log  logs/error.log  info;

#pid        logs/nginx.pid;


events {
    worker_connections  1024;
}


http {
    include       mime.types;
    default_type  application/octet-stream;

    #log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
    #                  '$status $body_bytes_sent "$http_referer" '
    #                  '"$http_user_agent" "$http_x_forwarded_for"';

    #access_log  logs/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    #keepalive_timeout  0;
    keepalive_timeout  65;

    #gzip  on;

    server {
        listen       8000;
        server_name  localhost 192.168.0.2;
      	root /Users/kacperkarabinowski/PhpstormProjects/Shop/public;

        #charset koi8-r;

        #access_log  logs/host.access.log  main;

        location / {
            try_files $uri /index.php$is_args$args;
        }

        #error_page  404              /404.html;

        # redirect server error pages to the static page /50x.html
        #
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }

        # proxy the PHP scripts to Apache listening on 127.0.0.1:80
        #
        #location ~ \.php$ {
        #    proxy_pass   http://127.0.0.1;
        #}

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        #location ~ /\.ht {
        #    deny  all;
        #}
    }


    # another virtual host using mix of IP-, name-, and port-based configuration
    #
    #server {
    #    listen       8000;
    #    listen       somename:8080;
    #    server_name  somename  alias  another.alias;

    #    location / {
    #        root   html;
    #        index  index.html index.htm;
    #    }
    #}


    # HTTPS server
    #
    #server {
    #    listen       443 ssl;
    #    server_name  localhost;

    #    ssl_certificate      cert.pem;
    #    ssl_certificate_key  cert.key;

    #    ssl_session_cache    shared:SSL:1m;
    #    ssl_session_timeout  5m;

    #    ssl_ciphers  HIGH:!aNULL:!MD5;
    #    ssl_prefer_server_ciphers  on;

    #    location / {
    #        root   html;
    #        index  index.html index.htm;
    #    }
    #}
    include servers/*;
}
```

To load fixtures use

```bash
symfony console doctrine:fixtures:load
```


## Documentation

#### Controllers


| Controller name | Description |
| :-------- | :------------------------- |
| HomeController | Landing page Controller |
| HomeConfigController| Home Controller CRUD |
| DeviceController| Device CRUD + enable and disable functions |
| HomeController | Landing page Controller |
| RoomController | Rooms CRUD |
| RegistationController | Used to register a new user |
| LoginController | Used to authorise user |

#### OAuth2

You can generate your own tokens to try OAuth2 login on console google and paste them in .env file

| ./Security | Description |
| :-------- | :------------------------- |
| AbstractOAuthAuthenticator | Abstract class for GoogleAuthenticator |
| GoogleAuthenticator| extends abstract class to implement OAuth2 login |
| OAuthRegistrationService| Supports new Google users |
| LoginFormAuthenticator | My own auth class |


#### Services and interfaces
Used to separate logic from controllers

| Service | Description |
| :-------- | :------------------------- |
| HomeConfigService / HouseConfigInterface | Supports HomeConfigController |
| RoomService / RoomInterface| Supports RoomController |
| DeviceService / DeviceInterface| Supports DeviceController |


#### AccessDeinedHandler

 In scr/Security there is a AccessDeniedHandler.php to redirect users without permissions.
