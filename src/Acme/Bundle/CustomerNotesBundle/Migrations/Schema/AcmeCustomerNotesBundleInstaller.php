<?php

namespace Acme\Bundle\CustomerNotesBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class AcmeCustomerNotesBundleInstaller implements Installation
{
    /**
     * @inheritDoc
     */
    public function getMigrationVersion(): string
    {
        return 'v1_2';
    }

    /**
     * @inheritDoc
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        /** Tables generation **/
        $this->createAcmeCustomerNoteTable($schema);

        /** Foreign keys generation **/
        $this->addAcmeCustomerNoteForeignKeys($schema);
    }

    /**
     * Create acme_customer_note table
     */
    private function createAcmeCustomerNoteTable(Schema $schema): void
    {
        $table = $schema->createTable('acme_customer_note');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('note', 'text', []);
        $table->addColumn('username', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('createdat', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['owner_id'], 'idx_acme_customer_note_owner_id');
        $table->addIndex(['organization_id'], 'idx_acme_customer_note_organization_id');
    }

    /**
     * Add acme_customer_note foreign keys.
     */
    private function addAcmeCustomerNoteForeignKeys(Schema $schema): void
    {
        $table = $schema->getTable('acme_customer_note');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['owner_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }
}