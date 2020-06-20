<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddUsers extends AbstractMigration
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
        $table = $this->table('Users');
        $table->addColumn('user_id', 'string', ['null' => false]);
        $table->addColumn('username', 'string', ['null' => false]);
        $table->addColumn('avatar', 'string', ['null' => false]);
        $table->addColumn('discriminator', 'string', ['null' => false]);
        $table->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP']);
        $table->create();
    }
}
