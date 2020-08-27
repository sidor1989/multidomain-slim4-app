<?php

use Phinx\Migration\AbstractMigration;

class Courses extends AbstractMigration
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
        $table = $this->table('courses');
        $table->addColumn('domain_id', 'integer')
            ->addColumn('title', 'string')
            ->addColumn('lectors', 'string')
            ->addColumn('city', 'string')
            ->addColumn('img', 'string')
            ->addColumn('date1', 'datetime', ['null' => true])
            ->addColumn('date2', 'datetime', ['null' => true])
            ->addColumn('tags', 'string')
            ->addColumn('size', 'string')
            ->addColumn('price', 'string', ['null' => true])
            ->addColumn('alias', 'string', ['null' => true])
            ->addColumn('active', 'integer')
            ->addColumn('data', 'text')
            ->addColumn('api_foriegn_key', 'integer', ['null' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
