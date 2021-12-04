<?php

namespace App\Handler;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

interface HandlerInterface
{
    public function handle(Request $request, object $originalData, array $options = []): bool;

    public function createView(): FormView;
}
