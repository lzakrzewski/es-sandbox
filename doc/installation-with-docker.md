# Installation with a docker

## Requirements
- Make
- [Docker](https://docs.docker.com/engine/installation/)

## Installation
1. Clone repository:  
    ```
    git@github.com:lzakrzewski/es-sandbox.git
    ```
2. Go to project directory:  
    ```
    cd es-sandbox
    ```
3. Setup `es-sandbox`  
    ```
    make setup-es-sandbox
    ```
    
It's all! If everything was successful, then you should have given containers running:
- `php`
- `mysql`
- `event-store`

For ensure that necessary containers are running:
```
docker ps
```
