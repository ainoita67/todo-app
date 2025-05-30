-- Crear base de datos (ejecutar como superusuario)
CREATE DATABASE todo_app;
CREATE USER todo_user WITH PASSWORD 'todo_password';
GRANT ALL PRIVILEGES ON DATABASE todo_app TO todo_user;

-- Conectarse a la base de datos todo_app
\c todo_app;

-- Crear tabla de tareas
CREATE TABLE tasks (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar algunas tareas de ejemplo
INSERT INTO tasks (title, description) VALUES 
('Aprender PHP', 'Estudiar los fundamentos de PHP y bases de datos'),
('Configurar PostgreSQL', 'Instalar y configurar PostgreSQL en el sistema'),
('Crear aplicación web', 'Desarrollar una aplicación de lista de tareas');

-- Dar permisos al usuario
GRANT ALL PRIVILEGES ON TABLE tasks TO todo_user;
GRANT USAGE, SELECT ON SEQUENCE tasks_id_seq TO todo_user;