<?php

namespace Pages;

use \Framework\SimpleView;

/**
 * General error page as a simple but complete HTML page
 */
class Error extends SimpleView
{
    public function __construct(int $code, string $message)
    {
        $this->title = 'Error Page';
        $this->content = <<<ENDCONTENT
            <div class="w3-panel w3-red w3-round w3-card-4">
            <H1>Error: {$code}</H1>
            <H2>Message: {$message}</H2>
            </div>
ENDCONTENT;
    }
}
