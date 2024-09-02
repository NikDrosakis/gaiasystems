# APIFAST v.1.0.0
fastapi runs with uvicorn in venv 

## databases 
* sqlite 
* maria 

## install 
```
sudo apt install python3-pip python3-dev
sudo apt install python3-venv
sudo apt-get install libmariadb-dev
ufw allow 8000
cd /var/www/apifast
chmod +x run.sh
python3 -m venv venv
source venv/bin/activate
pip install 'uvicorn[standard]'
pip install mariadb
pip install fastapi
exit
service apifast start
```
systemd runs run.sh file 
```
#!/bin/bash
#activate virtual enviroment
source /var/www/apifast/venv/bin/activate
# Run Uvicorn command
gunicorn main:app -w 4 -k uvicorn.workers.UvicornWorker
```
## endpoints
currently supports only GET variables
```
GET /apifast/post
GET /apifast/user
GET /apifast/globs
```
## nginx 
```
location /apifast {
   proxy_pass https://0.0.0.0:8000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;

}
```

## TODO
* queries & params maria
* mongo
* redis
* auth
* websocket 