<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddZajec9000 extends AbstractMigration
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
        $table = $this->table('zajec9000');
        $table->addColumn('banned_until', 'datetime', ['null' => false]);
        $table->addColumn('timeout_power', 'integer', ['null' => false]);
        $table->addColumn('author_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('total_bans', 'integer', ['null' => false]);
        $table->addIndex('author_id', ['unique' => true]);
        $table->create();
    }
}
