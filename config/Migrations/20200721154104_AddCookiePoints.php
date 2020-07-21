<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddCookiePoints extends AbstractMigration
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
        $table = $this->table('cookie_points');
        $table->addColumn('author_id', 'integer', ['null' => false]);
        $table->addColumn('target_author_id', 'integer', ['null' => false]);
        $table->addColumn('count', 'integer', ['null' => false, 'default' => null]);
        $table->create();
    }
}
