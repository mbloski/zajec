<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddAttachments extends AbstractMigration
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
        $table->addColumn('author_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('message_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('url', 'text', ['null' => false]);
        $table->create();
    }
}
