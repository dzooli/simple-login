    <!DOCTYPE html>

    <?php

    use Framework\Myy;
    use App\Models\User;
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

        <div class="w3-content w3-container">
            <h1>Welcome Home!</h1>
        </div>
    </body>

    </html>