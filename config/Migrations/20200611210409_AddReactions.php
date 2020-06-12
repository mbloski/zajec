<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddReactions extends AbstractMigration
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
        $table = $this->table('reactions');
        $table->addColumn('author_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('message_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('reaction', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('reaction_id', 'string', ['limit' => 255, 'null' => true]);
        $table->addColumn('reaction_name', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('reaction_number', 'string', ['limit' => 255, 'null' => true]);
        $table->create();
    }
}
