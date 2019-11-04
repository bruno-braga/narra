FROM node:12.12.0-buster

ENV HOME=/home/dev

#COPY package.json package-lock.json $HOME
COPY package.json $HOME

WORKDIR $HOME/app

RUN npm install --silent --progress=false

COPY . $HOME

CMD ["node"]
