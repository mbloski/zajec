<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->css('style') ?>
</head>
<body>
<div class="error-container">
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</div>
</body>
</html>
