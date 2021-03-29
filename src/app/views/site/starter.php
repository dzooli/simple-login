    <!DOCTYPE html>

    <?php

    use Framework\Myy;
    use App\Models\User;
    use Framework\Session;

    $flash = Session::getFlash();
    ?>

    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="/assets/css/site.css" />
        <title>
            AuthSample Home
        </title>
    </head>

    <body>

        <!-- Navbar -->
        <div class="w3-bar w3-metro-dark-blue">
            <a class="w3-bar-item w3-button w3-mobile w3-indigo w3-hover w3-hover-indigo" href="/">Random News</a>
            <?php if (!Myy::isGuest()) : ?>
                <a class="w3-bar-item w3-button w3-round w3-metro-red w3-hover-dark-red w3-mobile w3-right" href="user/logout">Logout (<?= User::findById(Myy::$user_id)->getName() ?>)</a>
            <?php endif ?>
        </div>
        <!-- Navbar end -->

        <?php if ($flash) : ?>
            <div class="w3-card w3-<?= $flash['color'] ?> w3-display-container w3-top w3-animate-zoom">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-<?= $flash['color'] ?> w3-display-topright">×</span>
                <div class="w3-container">
                    <p><?= $flash['msg'] ?></p>
                </div>
            </div>
        <?php endif ?>

        <div class="w3-content w3-container">
            <h1>Welcome Home!</h1>
            <div class="w3-container">
                <?php $pagename = __DIR__ . '/' . $page . '.html';
                if (!empty($page) && file_exists($pagename)) : ?>
                    <?php require_once($pagename); ?>
                <?php endif ?>
            </div>
        </div>
    </body>

    </html>