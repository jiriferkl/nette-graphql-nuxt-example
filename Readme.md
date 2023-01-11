# Nette - Graphql - Nuxt example

This is an example project so feel free to explore it. But keep in mind that some things are simplified or unfinished on purpose.

## What can you find here
### Grapghql
https://graphql.org/

GraphQL is a query language for API, which provides a complete and understandable description of the data in your API, gives clients the power to ask for exactly what they need and nothing more and makes it easier to evolve API over time.

### Nuxt
https://nuxt.com/

Nuxt is frontend meta framework that makes web development simple and powerful by combining frontend technologies together and setting them up to work right away.

### Tailwindcss
https://tailwindcss.com/

A utility-first CSS framework packed with a lots of classes.

### Vue 3 + I18n module for translations
https://vuejs.org/

Simple to use JavaScript Framework.

### Elasticsearch
https://www.elastic.co/

Elasticsearch is search and analytics engine designed for handling large amounts of data and providing real-time search capabilities.

### RabbitMq
https://www.rabbitmq.com/

RabbitMQ is an open-source message broker that is used for communication between various applications and systems.

### Mysql
https://www.mysql.com/

MySQL is an open-source relational database management system used for storing and retrieving data in tables, and performing various operations on the data.

### Maxwell
https://maxwells-daemon.io/

Maxwell is an application that reads changes in the mysql database and continues to process them. In this example, it reads changes in the product tables and then indexes them into elastica using the rabbitmq queue.

### Symfony console
https://symfony.com/doc/current/components/console.html

The Console component allows you to create command-line commands. Your console commands can be used for any recurring task, such as cronjobs, imports, or other batch jobs. This example includes commands for working with the database, elastica, and generators.

### Apitte
https://contributte.org/apitte/#global-layout

Modern PHP/PSR7 API framework build on top of Nette Framework.

### Others
More things like code generator, docker and simple nginx load balancer with PHP FPM. More about it later!

## How simple is development process
Very! In this example I am using schema.graphql that is used for api specification. A schema.graphql file is a text file that defines the types, fields, and queries of a GraphQL API. This means we can also use it to pre-generate php code with generator that I wrote. For example:

1. We can define new endpoint like this
```schema.graphql
type Query {
  product(id: ID!): Product!
}
```

1. Then run command `make generate` that creates interfaces, requests etc..
1. Then register interface as service and provide the main logic that is mostly only sql query.

Sometimes you can forget to implement some service. In that case I wrote PHPStan rule that checks if every service is registered.

## How can you run this app
Everything is inside a docker container, so you only need to clone this and run `make up`. But don't forget to create folders required by Nette and backend:
- backend/temp
- backend/log
- .docker/temp/elastic (this one is required for elastica to work and chmod is also important)

this folders must be readable so set chmod to `777`. Then run `make exec` and inside it `composer install` to download php dependencies.

Then go outside docker console and run `make init` that creates db structure and indexes products in to elastica.

Now just to be sure you can delete contains of `backend/temp` folder. Now everything is set up, and you can type `localhost` in your browser.

### Bonus
If you run `make status` than you can se that services `maxwell` and `consumer` are probably not running. This is due to slow startup of `rabbitmq` service.

These services check for changes in products tables and send updates to elastica. So if you want to try it out just run again `make up` and rest of the services will go online.

### Shutdown
To shut down the app you can run `make down`.
