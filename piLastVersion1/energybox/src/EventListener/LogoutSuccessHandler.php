<?php
// src/EventListener/LogoutSuccessHandler.php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    private $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function onLogoutSuccess(Request $request)
    {
        // Clear cache
        $finder = new Finder();
        $finder->files()->in($this->cacheDir)->name('*.php');
        foreach ($finder as $file) {
            unlink($file->getRealPath());
        }

        // Redirect user to a different page
        $response = new RedirectResponse('/');
        return $response;
    }
}
