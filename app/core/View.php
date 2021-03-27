<?php

namespace Gov\Core;

class View
{
    protected $root;

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function make(string $view, array $data = [])
    {
        $content = $this->getViewContent($view, $data);

        extract($data);
        eval(sprintf('?>%s<?php', $content));

        return;
    }

    protected function getViewContent(string $view, array $data): string
    {
        if (($path = $this->getViewPath($view)) !== false) {
            $content = file_get_contents($path);

            // rule replace ment
            $this->replaceRuleViewInclude($content, $data);

            return $content;
        }

        throw new \RuntimeException(
            sprintf("Can't find view with name: %s", $view)
        );
    }

    protected function getViewPath(string $view)
    {
        return realpath(
            sprintf(
                '%s%s.php',
                $this->root,
                str_replace(['.', '/'], DS, $view)
            )
        );
    }

    protected function replaceRuleViewInclude(string &$content, array $data)
    {
        $content = preg_replace_callback(
            '/<x-view-([^\s]+) \/>/i',
            function ($matches) use ($data) {
                return $this->make($matches[1], $data);
            },
            $content
        );
    }
}
