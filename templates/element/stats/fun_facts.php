<div class="sbox">
    <h2 class="text-center">
        Fun facts
    </h2>
    <ul>
        <?php if (!empty($topQuestions)): ?>
            <?php $authors = array_keys($topQuestions); ?>
            <li>
                Is <b><?= $this->Discord->getUserById($authors[0], 'user.username') ?? $authors[0] ?></b> stupid or just asking too many questions? <?= $this->Number->format($topQuestions[$authors[0]], ['precision' => 2]) ?>% lines contained a question.<br>
                <?php if (count($topQuestions) > 1): ?>
                    <small><b><?= $this->Discord->getUserById($authors[1], 'user.username') ?? $authors[1] ?></b> didn't know that much either. <?= $this->Number->format($topQuestions[$authors[1]], ['precision' => 2]) ?>% lines were questions.</small>
                <?php endif; ?>
                <br><br></li>
        <?php endif; ?>
        <?php if (!empty($topBadwords)): ?>
            <?php $authors = array_keys($topBadwords); ?>
            <li>
                <b><?= $this->Discord->getUserById($authors[0], 'user.username') ?? $authors[0] ?></b> has quite a potty mouth. <?= $this->Number->format($topBadwords[$authors[0]], ['precision' => 2]) ?>% lines contained foul language.<br>
                <?php if ($foulLine): ?>
                    <b>For example, like this:</b><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->Log->wrappedRichLine($foulLine->message) ?>
                <?php endif; ?>
                <?php if (count($topBadwords) > 1): ?>
                    <br>
                    <small><b><?= $this->Discord->getUserById($authors[1], 'user.username') ?? $authors[1] ?></b> also makes sailors blush, <?= $this->Number->format($topBadwords[$authors[1]], ['precision' => 2]) ?>% of the time.</small>
                <?php endif; ?>
                <br><br></li>
        <?php endif; ?>
        <?php if (!empty($topAngry)): ?>
            <?php $authors = array_keys($topAngry); ?>
            <li>
                <b><?= $this->Discord->getUserById($authors[0], 'user.username') ?? $authors[0] ?></b> seems to be furious. <?= $this->Number->format($topAngry[$authors[0]], ['precision' => 2]) ?>% lines contained angry faces.<br>
                <?php if ($angryLine): ?>
                    <b>For instance:</b><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->Log->wrappedRichLine($angryLine->message) ?>
                <?php endif; ?>
                <?php if (count($topAngry) > 1): ?>
                    <br>
                    <small><b><?= $this->Discord->getUserById($authors[1], 'user.username') ?? $authors[1] ?></b> also tends to be mad, <?= $this->Number->format($topAngry[$authors[1]], ['precision' => 2]) ?>% of the time.</small>
                <?php endif; ?>
                <br><br></li>
        <?php endif; ?>
        <?php if (!empty($longestLines)): ?>
            <?php $authors = array_keys($longestLines); ?>
            <li>
                <b><?= $this->Discord->getUserById($authors[0], 'user.username') ?? $authors[0] ?></b> wrote the longest lines, averaging <?= $this->Number->format($longestLines[$authors[0]], ['precision' => 0]) ?> characters in length.<br>
                <?php if (count($longestLines) > 1): ?>
                    <small><b><?= $this->Discord->getUserById($authors[1], 'user.username') ?? $authors[1] ?></b> is a good orator as well, with approximately <?= $this->Number->format($longestLines[$authors[1]], ['precision' => 0]) ?> characters per line.</small>
                <?php endif; ?>
                <br><br></li>
        <?php endif; ?>
        <?php if (!empty($shortestLines)): ?>
            <?php $authors = array_keys($shortestLines); ?>
            <li>
                <b><?= $this->Discord->getUserById($authors[0], 'user.username') ?? $authors[0] ?></b> wrote the shortest lines, averaging <?= $this->Number->format($shortestLines[$authors[0]], ['precision' => 0]) ?> characters in length.<br>
                <?php if (count($shortestLines) > 1): ?>
                    <small><b><?= $this->Discord->getUserById($authors[1], 'user.username') ?? $authors[1] ?></b> was tight-lipped, too, averaging <?= $this->Number->format($shortestLines[$authors[1]], ['precision' => 0]) ?> characters.</small>
                <?php endif; ?>
                <br><br></li>
        <?php endif; ?>
        <?php if (!empty($mostMentionedUsers)): ?>
            <?php $authors = array_keys($mostMentionedUsers); ?>
            <li>
                <b><?= $this->Discord->getUserById($authors[0], 'user.username') ?? $authors[0] ?></b> is quite popular, having people mention them <?= $this->Number->format($mostMentionedUsers[$authors[0]], ['precision' => 0]) ?> times.<br>
                <?php if (count($mostMentionedUsers) > 1): ?>
                    <small><b><?= $this->Discord->getUserById($authors[1], 'user.username') ?? $authors[1] ?></b> is also liked on this server, getting <?= $this->Number->format($mostMentionedUsers[$authors[1]], ['precision' => 0]) ?> mentions.</small>
                <?php endif; ?>
                <br><br></li>
        <?php endif; ?>
        <?php if (!empty($mostCommonBadwords)): ?>
            <?php $words = array_keys($mostCommonBadwords); ?>
            <li>
                The most common curse is <b><?= $words[0] ?></b>.
                <br><br></li>
        <?php endif; ?>
        <?php foreach ($wordOccurences as $word => $count): ?>
            <li>
                <b><?= $word ?></b> was mentioned <?= $count ?> <?= $count == 1? 'time' : 'times' ?>.
            </li>
        <?php endforeach; ?>
    </ul>
</div>
