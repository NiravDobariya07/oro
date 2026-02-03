#!/bin/bash
# Script to drop all tables in oro_commerce database

echo "Dropping all tables in oro_commerce database..."

PGPASSWORD='StrongPassword123!' psql -U oro_user -d oro_commerce -h 127.0.0.1 << 'EOF'
DO $$ DECLARE
    r RECORD;
BEGIN
    FOR r IN (SELECT tablename FROM pg_tables WHERE schemaname = 'public') LOOP
        EXECUTE 'DROP TABLE IF EXISTS ' || quote_ident(r.tablename) || ' CASCADE';
    END LOOP;
END $$;
EOF

echo "All tables dropped successfully!"
