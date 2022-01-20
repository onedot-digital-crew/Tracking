<?php

namespace ODTracking\Providers;

use Plenty\Plugin\Templates\Twig;

class GtmProviderBody
{
    public function call(Twig $twig)
    {
        return $twig->render('ODTracking::GtmBody');
    }
}
