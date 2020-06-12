<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddQuotes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('quotes');
        $table->addColumn('name', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('author_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('value', 'text', ['null' => false]);
        $table->addColumn('deleted', 'boolean', ['null' => false, 'default' => false]);
        $table->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('said', 'datetime', ['null' => false]);
        $table->create();
    }
}
