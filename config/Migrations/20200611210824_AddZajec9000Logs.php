<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddZajec9000Logs extends AbstractMigration
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
        $table = $this->table('zajec9000_logs');
        $table->addColumn('message', 'text', ['null' => false]);
        $table->create();
    }
}
