-- Create database and user for OroCommerce
CREATE DATABASE oro_commerce;
CREATE USER oro_user WITH ENCRYPTED PASSWORD 'StrongPassword123!';
GRANT ALL PRIVILEGES ON DATABASE oro_commerce TO oro_user;

-- Grant schema permissions (required for PostgreSQL)
\c oro_commerce
GRANT ALL ON SCHEMA public TO oro_user;
ALTER DATABASE oro_commerce OWNER TO oro_user;
