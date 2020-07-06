<?php if ($reactions->count() > 0): ?>
<div class="sbox">
    <h2 class="text-center">
        Most popular reactions
    </h2>
    <table class="itemtable">
        <tr class="itemrow solid-border">
            <?php foreach ($reactions as $reaction): ?>
                <td class="itemgfx" style="width: 0;">
                    <div><?= $this->Number->format($reaction->count) ?>x</div>
                </td>
                <td class="itemgfx">
                    <span class="emoji emoji-32">
                        <?php
                        $emoji = str_replace(['<:' ,':>'], '', $this->Discord->resolveEmoji($reaction->reaction));
                        echo $this->Twemoji->replace($emoji);
                        ?>
                    </span>
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
</div>
<?php endif; ?>
