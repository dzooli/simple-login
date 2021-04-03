<?php

namespace Framework;

/**
 * Basic view object
 *
 * Could be usable for advanced views. It contains a template with HTML headers
 * you have to specify the content.
 * Passing the title is not implemented yet but this is a good base for advanced views.
 *
 */
class SimpleView
{
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
     * The content placeholder should be replaced in the template upon @param string $content The page content inside the <body> tags
     * @return void
     * @see render()
     *
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setTitle(string $title = ''): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
