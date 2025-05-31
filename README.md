# Todo App

Una aplicación web sencilla para gestionar tareas. Permite crear, listar, actualizar y eliminar tareas usando una base de datos PostgreSQL.

## Dependencias

- PostgreSQL (como motor de base de datos)
- PHP
- pgAdmin (opcional, para gestión visual de PostgreSQL)

## Cómo se ejecuta en local

Utilizo xampp para ejecutar php y apache en local e instalo postgresql para alojar mi bdd tambien en local.
Tras iniciar ambos servicios se puede acceder a la aplicacion a traves del navegador con localhost.

## Despliegue en render

Tras iniciar sesion en render y crear un proyecto he creado una bdd en postre.
He accedido desde mi pgAdmin a la bdd de render y he creado todas las tablas y usuarios necesarios para mi proyecto.
He creado el archivo Dockerfile y el apache-config.conf en mi proyecto de php necesarios para el deploy en render.
He modificado el archivo de conexion a la bdd para que contenga las credenciales de la nueva bdd en render.
He hecho commit a mi repositori de github.
He creado un sevicio web en render a partir del repositorio.
He iniciado el deploy y revisado el resultado final mediante la url.
