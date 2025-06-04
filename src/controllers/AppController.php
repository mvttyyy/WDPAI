<?php
class AppController {

     public function __construct()
    {
    }

    protected array $messages = [];

    protected function render(string $template = "", array $variables = []) {
        $templatePath = "public/views/".$template.".php";
        $output = "File not found: ".$templatePath;

        if (file_exists($templatePath)) {
            $variables['messages'] = array_merge($this->messages, $variables['messages'] ?? []);
            extract($variables, EXTR_SKIP);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }
}
