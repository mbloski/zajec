<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddMessageIdToFaces extends AbstractMigration
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
        $table = $this->table('faces');
        $table->addColumn('message_id', 'string', ['limit' => 255, 'null' => false, 'after' => 'author_id']);
        $table->update();
    }
}
