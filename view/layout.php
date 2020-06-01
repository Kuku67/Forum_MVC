<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.tiny.cloud/1/6j2delsof82utovpo2i98u6iojbzom1w1q3zuigq6z2trm4i/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/<?= CSS_PATH ?>style.css">
    <title><?= $result['title'] ?></title>
</head>
<body>
    <div id="wrapper">
    <header>
            <nav id="main-nav">
                <div id="left-nav">
                    <a href="/" class="main-link"><i class="fas fa-home"></i>Accueil</a>
                    <?php if(App\Session::getUser()): ?>
                    <a href="/topic/create" class="border-left">Ouvrir un sujet</a>
                    <?php endif; ?>
                </div>
                <div id="secondary-nav">
                    <?php if(App\Session::getUser()): ?>
                    <a href="/profile/view/<?= App\Session::getUser()->getId() ?>"><i class="fas fa-user"></i><?= App\Session::getUser()->getPseudo() ?></a>
                    <a href="/security/logout" class="border-left">Déconnexion</a>
                    <?php else: ?>
                    <a href="/security/login">Connexion</a>
                    <a href="/security/register">Inscription</a>
                    <?php endif; ?>
                </div>
            </nav>
        </header>
        <main>
            <div id="page">
               <?= $page ?>
            </div>
        </main>
    </div>
    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script>
    tinymce.init({
      selector: 'textarea',
      mode: 'exact',
      menubar: false,
      plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount codesample'
        ],
        toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor forecolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help | link image | codesample | table',
        content_css: '//www.tiny.cloud/css/codepen.min.css'
    });
  </script>
</body>
</html>