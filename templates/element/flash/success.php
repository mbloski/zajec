<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div>
    <div class="sbox" style="text-align: center;">
        <div class="message success"><?= $message ?></div>
    </div>
</div>
