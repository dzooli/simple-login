<?php

namespace Pages;

use \Framework\SimpleView;

class Error404 extends SimpleView
{
    public function __construct()
    {
        $this->title = 'Error Page';
        $this->content = <<<ENDCONTENT
            <div class="w3-panel w3-red w3-round w3-card-4">
            <H1>Whoops!!!</H1>
            <H2>I think this is the beginning of a beautiful friendship...</H2>
            <h3>But not the best place to start. <span>Please visit <a href="/">this</a></span> instead.</h3>
            </div>
ENDCONTENT;
    }
}
