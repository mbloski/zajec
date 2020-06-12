<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddMessageEditHistory extends AbstractMigration
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
        $table = $this->table('message_edit_history');
        $table->addColumn('message_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('message', 'text', ['null' => true]);
        $table->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP']);
        $table->create();
    }
}
