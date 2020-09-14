<?php

declare(strict_types=1);

namespace Core;

final class View
{
    private $viewPath;
    private $useExtension;
    private $globalData;

    public function __construct(string $viewPath, bool $useExtension = false)
    {
        $path = realpath($viewPath);
        if (false === $path) {
            throw new \UnexpectedValueException(sprintf('Директория "%s" не найдена!', $path));
        }
        $this->viewPath = $path.DIRECTORY_SEPARATOR;
    }

    public function addGlobalData(string $key, $data): void
    {
        $this->globalData[$key] = $data;
    }

    public function render(string $template, array $data = []): string
    {
        $template = $this->viewPath . $template . ($this->useExtension ? '' : '.php');
        if (file_exists($template)) {
            $data = array_merge($this->globalData, $data);
            extract($data, EXTR_OVERWRITE);
            ob_start();
            include $template;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        throw new \UnexpectedValueException(sprintf('Шаблон "%s" несуществует', $template));
    }
}
