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

        <div class="w3-bar w3-blue">
            <a class="w3-bar-item w3-button w3-mobile w3-hover-blue" href="/">Random News</a>
            <?php if (!Myy::isGuest()) : ?>
                <span class="w3-bar-item w3-center">Hello '<?= User::findById(Myy::$user_id)->getName() ?>'</span>
                <a class="w3-bar-item w3-button w3-round w3-red w3-hover-pink w3-mobile w3-right" href="user/logout">Logout</a>
            <?php endif ?>
        </div>

        <div class="w3-content w3-container">
            <h1>Welcome Home!</h1>
        </div>
    </body>

    </html>