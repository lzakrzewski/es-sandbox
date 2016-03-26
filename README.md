# Event sourcing sandbox

[![Build Status](https://travis-ci.org/lzakrzewski/es-sandbox.svg?branch=master)](https://travis-ci.org/lzakrzewski/es-sandbox)

This sandbox has the target to test integration between given components: 
- [Symfony framework](http://symfony.com/)
- [SimpleBus](http://simplebus.github.io/)
- [GetEventStore](https://geteventstore.com/)

## Installation

1. Clone repository:  
    ```
    git@github.com:lzakrzewski/es-sandbox.git
    ```
2. Go to project directory:  
    ```
    cd es-sandbox
    ```
3. Download composer and install dependencies with [composer](https://getcomposer.org/):  
    ```
    php composer.phar install
    ```
4. Run script and observe results:  
    ```
    bin/console es_sandbox:basket:simulate-shopping
    ```

### Example
```sh
bin/console es_sandbox:basket:simulate-shopping

Facts about your basket:
 =================== ====================================== ========================== 
  Basket with id:     10701e8e-a03f-47c0-9bf7-5392a5285fe5   was picked up.            
  Product with id:    162931bb-2abb-4721-ba3d-9e7a760e2a2c   was added to basket.      
  Product with id:    f4c83399-28a3-4f49-97d7-33e0c61b3e64   was added to basket.      
  Product with id:    3536f851-8c04-4d59-8d80-a1083ad7e0a2   was added to basket.      
  Product with id:    82b34233-19c3-416a-85f3-25da3b40497b   was added to basket.      
  Product with id:    9b3c113a-d86e-4e92-9b46-8bd6a44a4a7a   was added to basket.      
  Product with id:    8b57035d-dbd5-4cd1-b409-bc491d9e0185   was added to basket.      
  Product with id:    f4c83399-28a3-4f49-97d7-33e0c61b3e64   was removed from basket.  
  Product with id:    87bf46c9-c545-4d94-af0a-71ed0c87f7a7   was added to basket.      
  Product with id:    eac0f2df-e7bb-42e2-bad9-3b4704875a54   was added to basket.      
  Product with id:    31620b62-83fe-4c21-8fe9-0b6c2fb2d1c3   was added to basket.      
  Product with id:    264835fd-3db2-4b6f-a814-89c6743e95ae   was added to basket.      
  Product with id:    8b57035d-dbd5-4cd1-b409-bc491d9e0185   was removed from basket.  
  Product with id:    e699049f-f493-4de6-8fb8-fc349af32f5c   was added to basket.      
  Product with id:    1b95e5f1-aeb3-4870-aea2-26c3e18296ec   was added to basket.      
 =================== ====================================== ========================== 

Your basket contains:
+--------------------------------------+---------+
| productId                            | name    |
+--------------------------------------+---------+
| 162931bb-2abb-4721-ba3d-9e7a760e2a2c | Beer    |
| 3536f851-8c04-4d59-8d80-a1083ad7e0a2 | Juice   |
| 82b34233-19c3-416a-85f3-25da3b40497b | Mango   |
| 9b3c113a-d86e-4e92-9b46-8bd6a44a4a7a | Glass   |
| 87bf46c9-c545-4d94-af0a-71ed0c87f7a7 | Teapot  |
| eac0f2df-e7bb-42e2-bad9-3b4704875a54 | Water   |
| 31620b62-83fe-4c21-8fe9-0b6c2fb2d1c3 | Blender |
| 264835fd-3db2-4b6f-a814-89c6743e95ae | Juice   |
| e699049f-f493-4de6-8fb8-fc349af32f5c | Glass   |
| 1b95e5f1-aeb3-4870-aea2-26c3e18296ec | Glass   |
+--------------------------------------+---------+
```
 
## Todo
- [ ] Prepare Dockerfile for `geteventstore`
- [ ] Create adapter for `geteventstore`

