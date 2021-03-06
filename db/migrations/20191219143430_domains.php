<?php

use Phinx\Migration\AbstractMigration;

class Domains extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('domains');
        $table->addColumn('name_domain', 'string')
            ->addColumn('name_town_cyrilic', 'string')
            ->addColumn('name_diler', 'string')
            ->addColumn('email', 'string')
            ->addColumn('site', 'string')
            ->addColumn('phones', 'string')
            ->addColumn('address', 'string')
            ->addColumn('towns', 'text')
            ->addColumn('map_coordinats', 'string')
            ->addColumn('active', 'integer')
            ->addColumn('content_1', 'text')
            ->addColumn('content_2', 'text')
            ->addColumn('api_foriegn_key', 'integer')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
