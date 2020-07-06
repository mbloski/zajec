<div class="sbox">
    <h2 class="text-center">
        Quotes
        <img src="https://discordapp.com/assets/0b6fc9f58ca3827977d546a6ee0ca3e7.svg" class="emoji" style="height:32px;" alt=":speech_balloon:">
    </h2>
    <table class="itemtable">
        <thead>
        <th>ID</th>
        <th>Author</th>
        <th>Quote</th>
        <th width="170px;">Date</th>
        </thead>
        <tbody>
        <?php foreach ($quotes as $quote): ?>
            <tr class="itemrow">
                <td class="itemdesc">#<?= $quote->id ?></td>
                <td class="itemdesc" style="width:200px;"><?= $quote->author_id? $this->Html->link($this->Discord->getUsernameWithColor($quote->author_id), ['controller' => 'Stats', 'action' => 'user', $quote->author_id], ['escape' => false]) : h($quote->name) ?></td>
                <td class="itemdesc" style="max-width:600px;word-break:break-word;"><?= $this->Log->wrappedRichLine($quote->value) ?></td>
                <td><?= h($quote->created) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
