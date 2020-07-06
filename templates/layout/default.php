<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        ZAJEC <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('stats.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<?php $icon = 'https://cdn.discordapp.com/icons/'.$guild['id'].'/'.$guild['icon'].'.webp?size=128'; ?>
<div id="header" style="background-image: url(<?= $icon ?>);">
    <h1>
        <?= $this->Html->link($guild['name'], '/') ?>
    </h1>
    <cite>Discord statistics</cite>
    <ul id="headnav">
        <li><?= $this->Html->link('Logs', ['controller' => 'Logs', 'action' => 'index']) ?></li>
    </ul>
</div>

<div id="container">
    <div id="fcontent" style="width:80%">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>
</div>
</body>
</html>
