<?php

use Phinx\Migration\AbstractMigration;

class AddIsMainLinkFlagToServiceLink extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('service_link');
        $table->addColumn('is_main_link', 'integer', ['null' => false, 'default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'precision' => 3, 'after' => 'type']);
        $table->update();
    }
}
