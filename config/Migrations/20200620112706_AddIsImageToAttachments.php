<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddIsImageToAttachments extends AbstractMigration
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
        $table->addColumn('image', 'boolean', ['null' => false, 'default' => false]);
        $table->update();
    }
}
