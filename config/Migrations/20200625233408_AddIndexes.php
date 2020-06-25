<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddIndexes extends AbstractMigration
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
        $table = $this->table('attachments');
        $table->addIndex('author_id');
        $table->addIndex('message_id');
        $table->update();

        $table = $this->table('message_edit_history');
        $table->addIndex('message_id');
        $table->update();

        $table = $this->table('messages');
        $table->addIndex('author_id');
        $table->addIndex('message_id');
        $table->update();
    }
}
