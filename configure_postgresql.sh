#!/bin/bash
# PostgreSQL Authentication Configuration for OroCommerce

echo "Backing up pg_hba.conf..."
sudo cp /etc/postgresql/14/main/pg_hba.conf /etc/postgresql/14/main/pg_hba.conf.backup

echo "Updating PostgreSQL authentication to allow password-based login..."
sudo sed -i 's/^local\s\+all\s\+all\s\+peer/local   all             all                                     md5/' /etc/postgresql/14/main/pg_hba.conf
sudo sed -i 's/^host\s\+all\s\+all\s\+127\.0\.0\.1\/32\s\+ident/host    all             all             127.0.0.1\/32            md5/' /etc/postgresql/14/main/pg_hba.conf
sudo sed -i 's/^host\s\+all\s\+all\s\+::1\/128\s\+ident/host    all             all             ::1\/128                 md5/' /etc/postgresql/14/main/pg_hba.conf

echo "Restarting PostgreSQL service..."
sudo systemctl restart postgresql

echo "Setting password for oro_user..."
sudo -u postgres psql -c "ALTER USER oro_user WITH PASSWORD 'StrongPassword123!';"

echo "Testing connection..."
PGPASSWORD='StrongPassword123!' psql -U oro_user -d oro_commerce -h 127.0.0.1 -c "SELECT 1;" && echo "✓ Connection successful!" || echo "✗ Connection failed"

echo "Done! PostgreSQL is now configured for password authentication."
