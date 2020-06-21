<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddMentions extends AbstractMigration
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
        $table = $this->table('mentions');
        $table->addColumn('message_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('author_id', 'string', ['limit' => 255, 'null' => false]);
        $table->addColumn('mentioned_id', 'string', ['limit' => 255, 'null' => false]);
        $table->create();

        $logs = \Cake\ORM\TableRegistry::getTableLocator()->get('Logs')->find('all');
        foreach ($logs as $log) {
            $mentions = [];
            preg_match_all('/<@!?(\d*)>/', $log->message ?? '', $mentions);
            if (isset($mentions[1])) {
                foreach ($mentions[1] as $mention) {
                    $table->insert(['message_id' => $log->message_id, 'author_id' => $log->author_id, 'mentioned_id' => $mention]);
                }
            }
        }

        $table->saveData();
    }
}
