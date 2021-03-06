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

        <div class="w3-third">&nbsp;</div>
        <div class="w3-third">
            <div class="w3-panel">
                <div class="w3-card-4">
                    <div class="w3-row w3-metro-dark-blue">
                        <div class="w3-panel">
                            <h3>Sign In</h3>
                        </div>
                    </div>
                    <form class="w3-container" action="user/login" method="POST" style="padding-top: 48px; padding-bottom: 24px;">
                        <input id="user-email" type="text" class="w3-input w3-round" name="User[email]" required />
                        <label for="#user-email" class="w3-left">E-mail</label>
                        <input id="user-password" type="password" class="w3-input w3-round" name="User[password]" required />
                        <label for="#user-password" class="w3-left">Password</label>
                        <div class="w3-row" style="padding-top: 48px;">
                            <button type="submit" class="w3-button w3-round-large w3-metro-dark-blue w3-hover-indigo">Gimme a Page</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>