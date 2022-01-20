<?php

namespace Tracking\Providers;

use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Plugin\Templates\Twig;
//use IO\Extensions\Constants\ShopUrls;
//use IO\Helper\RouteConfig;

class TrackingCodeProvider
{
    public function call( Twig $twig )
    {
        /** @var FrontendSessionStorageFactoryContract $sessionStorage */
        $sessionStorage = pluginApp(FrontendSessionStorageFactoryContract::class);

        $enableTracking = $sessionStorage->getPlugin()->getValue('GTM_TRACK_ORDER') === 1;

        $sessionStorage->getPlugin()->unsetKey('GTM_TRACK_ORDER');
//        $templateForTracking = self::getTemplateForTracking();

        return $twig->render(
            'ODTracking::TrackingCode',
            [
                'trackOrder' => $enableTracking,
//                'templateForTracking' => $templateForTracking
            ]
        );
    }

//    public function getTemplateForTracking()
//    {
//        $shopUrls = pluginApp(ShopUrls::class);
//        foreach (RouteConfig::ALL as $routeKey) {
//            if ($shopUrls->is($routeKey)) {
//                return $routeKey;
//            }
//        }
//    }
}
