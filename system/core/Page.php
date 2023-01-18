<?php

namespace system\core;

class Page
{

    private $controller, $action, $breadcrumb, $path;

    private string $title;
    private string $description;
    private string $keywords;

    public function __construct(
        $controller = false,
        $action = false,
        $breadcrumb = false,
        $path = false
    ) {
        $this->controller = $controller;
        $this->action = $action;
        $this->breadcrumb = $breadcrumb;
        $this->path = $path;
    }

    public function init(array $meta_array) {
        $this->title = $meta_array['title'];
        $this->description = ($meta_array['description'] ?? '');
        $this->keywords = (isset($meta_array['keywords']) ? mb_strtolower($meta_array['keywords']) : '');
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    public function getBreadcrumb() {
        return $this->breadcrumb;
    }

    public function getPath() {
        return $this->path;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getKeywords() {
        return $this->keywords;
    }

}