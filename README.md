# Event sourcing sandbox

[![Build Status](https://travis-ci.org/lzakrzewski/es-sandbox.svg?branch=master)](https://travis-ci.org/lzakrzewski/es-sandbox)

`Es-sandbox` is simple `Symfony` and `CLI` application which simulates online shopping.
Model of this application was created with `Event Sourcing` architectural pattern (see [Event Sourcing Basics](http://docs.geteventstore.com/introduction/event-sourcing-basics/)).
`Es-sandbox` was built also with Command–query separation principle (see [CQRS](http://martinfowler.com/bliki/CQRS.html)).
For handling a commands in simple way `es-sandbox` uses `command_bus` from [SimpleBus](http://simplebus.github.io/MessageBus/doc/command_bus.html).

Read more about `es-sandbox` domain model:
- [Domain model](doc/domain-model.md)

This sandbox has also the target to test the integration between given components: 
- [Symfony framework](http://symfony.com/)
- [SimpleBus](http://simplebus.github.io/)
- [EventStore](https://geteventstore.com/)
- [MySQL](https://www.mysql.com/)

Presentations of which I get inspired:
- ["Practical Event Sourcing" by **Mathias Verraes**](http://verraes.net/2014/03/practical-event-sourcing/)
- ["Embrace Events and let CRUD die" by **Source Ministry**](http://www.slideshare.net/sourceministry/embrace-events-and-let-crud-die)

There is a separate lib for communication with `EventStore` via `HTTP`, for more info see:
- [HttpEventStore](https://github.com/lzakrzewski/http-event-store)

## Installation  
There are two way-s to install `es-sandbox`:

1. [Installation with docker](doc/installation-with-docker.md) (recommended)
2. [Installation native](doc/installation-native.md)

## Usage
This application has `CLI` entry points:

1. [Shopping simulation (Recording and writing events to a Stream)](doc/shopping-simulation.md)
2. [Render projection (Renders projection of recorded events)](doc/render-projection.md)

In case when you installed `es-sandbox` with `Docker` then before executing of `CLI` command you should connect to `php` running container:
```
make php
```

### Examples
 