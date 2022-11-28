# Rat-Manager-Docker
Docker RatManager Installation and Image Building

1. Clone Repo `git clone https://github.com/louisvarley/RatManager
2. `cd RatManager`
3. Copy Env File `mv env.sample .env`
4. Edit Env File `nano .env`
5. Build the image `sudo docker build --no-cache -t rat-manager-web .` 
6. Build the container `sudo docker-compose up -d --build
7. http://0.0.0.0:9093 to Open the Application
