<?php

// Migration/Schema/v1_0/AcmeCustomerNotesBundleInstaller.php
namespace Acme\Bundle\CustomerNotesBundle\Migration\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class AcmeCustomerNotesBundleInstaller implements Installation
{
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    public function up(Schema $schema, QueryBag $queries)
    {
        $table = $schema->createTable('acme_customer_note');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('note', 'text');
        $table->addColumn('username', 'string', ['length' => 255, 'notnull' => false]);
        $table->setPrimaryKey(['id']);
    }
}

?>