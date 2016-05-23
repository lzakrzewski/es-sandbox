# Event sourcing sandbox

[![Build Status](https://travis-ci.org/lzakrzewski/es-sandbox.svg?branch=master)](https://travis-ci.org/lzakrzewski/es-sandbox)

`Es-sandbox` is simple `Symfony` and `CLI` application which simulates online shopping.
Model of this application was created with `Event Sourcing` architectural pattern (see [Event Sourcing Basics](http://docs.geteventstore.com/introduction/event-sourcing-basics/)).
Read more about `es-sandbox` domain model:
- [Domain model](doc/domain-model.md)

This sandbox has also the target to test the integration between given components: 
- [Symfony framework](http://symfony.com/)
- [SimpleBus](http://simplebus.github.io/)
- [EventStore](https://geteventstore.com/)
- [MySQL](https://www.mysql.com/)

`Es-sandbox` was highly inspired by **Mathias Verraes** article: [Practical Event Sourcing](http://verraes.net/2014/03/practical-event-sourcing/)

## Installation  
There are two way-s to install `es-sandbox`:

1. [Installation with docker](doc/installation-with-docker.md) (recommended)
2. [Installation native](doc/installation-native.md)

## Usage
This application has `CLI` entry points:

1. [Writing to a Stream (Shopping simulation)](doc/writing-to-a-stream.md)
2. [Read recorded events (Event Store projection)](doc/read-recorded-events-event-store.md)
3. [Read recorded events (MySQL projection)](doc/read-recorded-events-mysql.md)

Executing of CLI could be different and it depends on way of `es-sandbox` installation. 
Read more about: [Executing of CLI](doc/executing-of-cli.md) 

### Examples
```sh
bin/console es_sandbox:basket:simulate-shopping
```

Todo: 
- [ ] Update examples
- [ ] Update doc's about projections
- [ ] Create separate lib like `HttpEventStoreClient`
 