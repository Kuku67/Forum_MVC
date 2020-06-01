<?php $topics = $result['data']; ?>
<?php if(!App\Session::getUser()): ?>
<div id="home-jumbotron" class="jumbotron h-100">
    <?php if($message = App\Session::getMessage()): ?>
    <p class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></p>
    <?php App\Session::unsetMessage(); ?>
    <?php endif; ?>
  <h1 class="display-4">Bienvenue sur mon forum !</h1>
  <p class="lead">Ce projet a pour but de mettre en pratique tout ce qui a été appris lors de la formation.</p>
  <p class="lead">Il requiert de mettre en place le Design Pattern MVC (Modèle, Vue, Controlleur), à l'aide des technologies suivantes :</p>
  <ul class="lead">
    <li>PHP <span class="italic">(Hypertext Preprocessor)</span></li>
    <li>SQL <span class="italic">(Structured Query Language)</span></li>
    <li>Un SGBD comme PhpMyAdmin ou HeidiSQL <span class="italic">(Système de Gestion de Base de Données)</span></li>
    <li>HTML <span class="italic">(Hypertext Markup Language)</span></li>
    <li>CSS <span class="italic">(Cascading Style Sheets)</span></li>
    <li>JS <span class="italic">(JavaScript)</span></li>
  </ul>
  <p class="lead"><strong>Afin de profiter entièrement des fonctionnalités du forum, vous devez vous <a href="/security/register">inscrire</a>, ou vous <a href="/security/login">connecter</a> si vous êtes déjà inscrit(e).</strong></p>
  <hr class="my-4">
</div>
<?php else: ?>
<div class="container-fluid">
    <div class="container-heading">
        <p class="lead">Forum <span class="separator"> / </span>Sujets récents</p>
        <?php if($message = App\Session::getMessage()): ?>
        <p class="alert alert-<?= $message['type'] ?>"><?= $message['content'] ?></p>
        <?php App\Session::unsetMessage(); ?>
        <?php endif; ?>
        <div class="administation-bundle d-flex justify-content-between align-items-center">
          <i class="fas fa-cogs text-dark"></i>
          <p>
              <a href="/topic/create"><i class="fas fa-plus"></i>Nouveau sujet</a>
          </p>
      </div>
  </div>
  <div class="topic-container">
      <div class="row topic-title">
          <div class="col-lg-8">
              <p class="lead">Sujet</p>
          </div>
          <div class="col-lg-2">
              <p class="lead label-message">Messages</p>
          </div>
          <div class="col-lg-2">
              <p class="lead label-date">Date de création</p>
          </div>
      </div>
      <?php foreach($topics as $topic): ?>
      <div class="row topic <?= $topic->getResolu() == 1 ? "resolved" : null ?>">
          <div class="col-lg-8">
              <p class="lead">
                <?php if($topic->getVerouillage() == 1): ?>
                <span class="text-danger">[CLOSED] </span>
                <?php endif; ?>
                <?php if($topic->getResolu() == 1): ?>
                <span class="text-success">[RESOLVED] </span>
                <?php endif; ?>
                <a href="/topic/view/<?= $topic->getId() ?>">
                <?= $topic->getTitre() ?>
                </a>
              </p>
              <p class="lead italic">créé par <a href="/profile/view/<?= $topic->getUser()->getId() ?>"><?= $topic->getUser()->getPseudo() ?></a></p>
          </div>
          <div class="col-lg-2">
              <p class="lead label-message"><i class="fas fa-comment"></i><?= $topic->getNbMessages() ?></p>
          </div>
          <div class="col-lg-2">
              <p class="lead label-date">
                  Le <?= $topic->getCreation('d-m-Y') ?><br>à <?= $topic->getCreation('H:i') ?>
              </p>
          </div>
      </div>
      <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>