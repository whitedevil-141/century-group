<?php
namespace Src\Controllers;

class PagesController {
    public function home() {
        require __DIR__ . '/../../views/home.php';
    }

    public function about() {
        require __DIR__ . '/../../views/about.php';
    }

    public function contact() {
        require __DIR__ . '/../../views/contact.php';
    }

    public function careers() {
        require __DIR__ . '/../../views/careers.php';
    }

    public function industries() {
        require __DIR__ . '/../../views/industries.php';
    }

    public function projects() {
        require __DIR__ . '/../../views/projects.php';
    }
}
