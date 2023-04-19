<?php

namespace App\View\Composers;

use Illuminate\View\View;

class VueAppComposer
{
    /**
     * Create new view composer.
     */
    public function __construct(
        protected null|string $userDataJson,
    ) {
    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with('userDataJson', $this->userDataJson);
    }
}
