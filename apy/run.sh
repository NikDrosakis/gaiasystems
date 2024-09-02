#!/bin/bash
#activate virtual enviroment
#source /var/www/apifast/venv/bin/activate
#tree -I '__pycache__|newenv' > tree.txt
# Run Uvicorn command
#gunicorn main:app -w 4 -k uvicorn.workers.UvicornWorker
source /path/to/your/virtualenv/bin/activate
uvicorn main:app --host 0.0.0.0 --port 3006 --reload --log-level debug
# uvicorn main:app --port=8000 -ssl-keyfile /etc/letsencrypt/live/vivalibro.com/fullchain.pem --ssl-certfile /etc/letsencrypt/live/vivalibro.com/privkey.pem --reload