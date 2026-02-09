<?php

namespace Acme\Bundle\CustomerNotesBundle\Migrations\Schema\v1_3;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityBundle\EntityConfig\DatagridScope;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareTrait;

class AddEmployeeIdToUser implements Migration, ExtendExtensionAwareInterface
{
    use ExtendExtensionAwareTrait;

    public function up(Schema $schema, QueryBag $queries): void
    {
        $table = $schema->getTable('oro_user');
        $table->addColumn(
            'employee_id',
            'string',
            [
                'oro_options' => [
                    'entity' => ['label' => 'Employee ID'],
                    'extend' => ['is_extend' => true, 'owner' => ExtendScope::OWNER_CUSTOM],
                    'datagrid' => ['is_visible' => DatagridScope::IS_VISIBLE_TRUE],
                    'form' => ['is_enabled' => true],
                    'view' => ['is_displayable' => true],
                    'importexport' => ['identity' => true]
                ],
                'length' => 255,
                'notnull' => false
            ]
        );
    }
}
