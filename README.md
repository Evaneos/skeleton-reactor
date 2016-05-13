#  Playground for reactor pattern with Silex skeleton and Traefik


**Service discovery via docker**

**Avg response time : ~7ms**

* docker-compose up -d
* docker-compose scale turbine=5
* docker-compose log traefik
* docker-compose log turbine

Visit http://turbine.docker.localhost

Play :)

