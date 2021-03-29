<?php

namespace Framework;

class SimpleView
{
    public static array
        $flashes = [
            'danger' => [
                'color' => 'red',
                'title' => 'Danger!',
            ],
            'warning' => [
                'color' => 'yellow',
                'title' => 'Warning!',
            ],
            'success' => [
                'color' => 'green',
                'title' => '',
            ],
            'info' => [
                'color' => 'blue',
                'title' => 'Info',
            ],
        ];

    protected static array $replace_elements = [
        'title',
        'content',
    ];

    protected static string $template = <<<ENDTEMPLATE
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge" /> -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />        
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />
        <title><<title>></title>
    </head>
    <body>
        <div class="w3-container w3-responsive">
        <<content>>
        </div>
    </body>
</html>
ENDTEMPLATE;

    protected string $content = '';
    protected string $title = '';

    public function __construct(string $content = '', string $title = '')
    {
        $this->content = $content;
        $this->title = $title;
    }

    /**
     * Rendering the page
     *
     * Goes through the @template and replaces the self::$replace_elements (placeholder list) with the
     * according instance variable. Finally template should be filled with the content.
     *
     * @return void
     */
    public function render(): void
    {
        $result = self::$template;
        foreach (self::$replace_elements as $element) {
            $result = str_replace('<<' . $element . '>>', $this->$element, $result);
        }
        echo $result;
    }

    /**
     * Returns the page content
     *
     * This function is usable for building complex pages from page-pieces
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content element of the view.
     *
     * The content placeholder should be replaced in the template upon @see render()
     *
     * @param string $content   The page content inside the <body> tags
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public static function getFlash(): ?array
    {
        $retFlash = [];
        if (Myy::$app && Myy::$app->getSession() && Myy::$app->getSession()->has('flash')) {
            $flash = explode('|', Myy::$app->getSession()->get('flash'));
            Myy::$app->getSession()->remove('flash');
            $retFlash['msg'] = $flash[1] ?? '';
            $retFlash['color'] = self::$flashes[$flash[0]]['color'];
            $retFlash['title'] = self::$flashes[$flash[0]]['title'];
            return $retFlash;
        }
        return null;
    }
}
