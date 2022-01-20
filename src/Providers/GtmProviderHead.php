<?php

namespace ODTracking\Providers;

use Plenty\Plugin\Templates\Twig;

class GtmProviderHead
{
    public function call(Twig $twig)
    {
        return $twig->render('ODODTracking::GtmHead');
    }
}
