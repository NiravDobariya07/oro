<?php

namespace Acme\Bundle\CustomerNotesBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Add owner field to CustomerNote entity
 */
class AddOwnerField implements Migration
{
    /**
     * @inheritDoc
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        $table = $schema->getTable('acme_customer_note');
        
        if (!$table->hasColumn('owner_id')) {
            $table->addColumn('owner_id', 'integer', ['notnull' => false]);
            $table->addIndex(['owner_id'], 'idx_acme_customer_note_owner_id');
            
            $table->addForeignKeyConstraint(
                $schema->getTable('oro_user'),
                ['owner_id'],
                ['id'],
                ['onDelete' => 'SET NULL', 'onUpdate' => null]
            );
        }
    }
}
