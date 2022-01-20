<?php

namespace ODTracking\Providers;

use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\Templates\Twig;
use IO\Services\ItemSearch\Helper\ResultFieldTemplate;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Modules\Order\Events\OrderCreated;
use Plenty\Modules\System\Contracts\WebstoreConfigurationRepositoryContract;
use Plenty\Modules\Webshop\Consent\Contracts\ConsentRepositoryContract;
use Plenty\Plugin\ConfigRepository;
use IO\Extensions\Constants\ShopUrls;
use IO\Services\UrlBuilder\UrlQuery;

class TrackingServiceProvider extends ServiceProvider
{

    const PRIORITY = 0;

    /**
     * Register the service provider.
     */

    public function register()
    {
        /** @var ConsentRepositoryContract $consentRepository */
        $consentRepository = pluginApp(ConsentRepositoryContract::class);

        /** @var ConfigRepository $config */
        $config = pluginApp(ConfigRepository::class);

        $consentRepository->registerConsentGroup(
            'other',
            'ODTracking::CookieConsent.consentGroupOtherLabel',
            [
                'position' => 300,
                'description' => 'ODTracking::CookieConsent.consentGroupOtherDescription'
            ]
        );

        if ($config->get('ODTracking.showProductList') === 'true') {
            $consentRepository->registerConsent(
                'gtmProductList',
                'ODTracking::CookieConsent.consentProductListLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentProductListDescription',
                    'provider' => 'ODTracking::CookieConsent.consentProductListProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentProductListLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentProductListPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupProductList', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentProductListNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentProductListIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentProductListCookieNames')))

                ]
            );
        }

        if ($config->get('ODTracking.showGoogleAnalytics') === 'true') {
            $consentRepository->registerConsent(
                'gtmGoogleAnalytics',
                'ODTracking::CookieConsent.consentGoogleAnalyticsLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentGoogleAnalyticsDescription',
                    'provider' => 'ODTracking::CookieConsent.consentGoogleAnalyticsProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentGoogleAnalyticsLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentGoogleAnalyticsPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupGoogleAnalytics', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentGoogleAnalyticsNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentGoogleAnalyticsIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentGoogleAnalyticsCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showGoogleAds') === 'true') {
            $consentRepository->registerConsent(
                'gtmGoogleAds',
                'ODTracking::CookieConsent.consentGoogleAdsLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentGoogleAdsDescription',
                    'provider' => 'ODTracking::CookieConsent.consentGoogleAdsProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentGoogleAdsLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentGoogleAdsPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupGoogleAds', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentGoogleAdsNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentGoogleAdsIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentGoogleAdsCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showFacebook') === 'true') {
            $consentRepository->registerConsent(
                'gtmFacebook',
                'ODTracking::CookieConsent.consentFacebookLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentFacebookDescription',
                    'provider' => 'ODTracking::CookieConsent.consentFacebookProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentFacebookLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentFacebookPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupFacebook', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentFacebookNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentFacebookIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentFacebookCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showPinterest') === 'true') {
            $consentRepository->registerConsent(
                'gtmPinterest',
                'ODTracking::CookieConsent.consentPinterestLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentPinterestDescription',
                    'provider' => 'ODTracking::CookieConsent.consentPinterestProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentPinterestLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentPinterestPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupPinterest', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentPinterestNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentPinterestIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentPinterestCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showBilligerDe') === 'true') {
            $consentRepository->registerConsent(
                'gtmBilligerDe',
                'ODTracking::CookieConsent.consentBilligerDeLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentBilligerDeDescription',
                    'provider' => 'ODTracking::CookieConsent.consentBilligerDeProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentBilligerDeLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentBilligerDePolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupBilligerDe', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentBilligerDeNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentBilligerDeIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentBilligerDeCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showKelkoo') === 'true') {
            $consentRepository->registerConsent(
                'gtmKelkoo',
                'ODTracking::CookieConsent.consentKelkooLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentKelkooDescription',
                    'provider' => 'ODTracking::CookieConsent.consentKelkooProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentKelkooLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentKelkooPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupKelkoo', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentKelkooNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentKelkooIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentKelkooCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showPaypal') === 'true') {
            $consentRepository->registerConsent(
                'gtmPaypal',
                'ODTracking::CookieConsent.consentPaypalLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentPaypalDescription',
                    'provider' => 'ODTracking::CookieConsent.consentPaypalProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentPaypalLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentPaypalPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupPaypal', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentPaypalNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentPaypalIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentPaypalCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showAwin') === 'true') {
            $consentRepository->registerConsent(
                'gtmAwin',
                'ODTracking::CookieConsent.consentAwinLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentAwinDescription',
                    'provider' => 'ODTracking::CookieConsent.consentAwinProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentAwinLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentAwinPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupAwin', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentAwinNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentAwinIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentAwinCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showWebgains') === 'true') {
            $consentRepository->registerConsent(
                'gtmWebgains',
                'ODTracking::CookieConsent.consentWebgainsLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentWebgainsDescription',
                    'provider' => 'ODTracking::CookieConsent.consentWebgainsProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentWebgainsLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentWebgainsPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupWebgains', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentWebgainsNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentWebgainsIsOptOut') === 'true',
                    'cookieNames' => array_map('trim', explode(',', $config->get('ODTracking.consentWebgainsCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieOne') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieOne',
                'ODTracking::CookieConsent.consentCustomCookieOneLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieOneDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieOneProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieOneLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieOnePolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieOne', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieOneNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieOneIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieOneCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieTwo') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieTwo',
                'ODTracking::CookieConsent.consentCustomCookieTwoLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieTwoDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieTwoProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieTwoLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieTwoPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieTwo', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieTwoNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieTwoIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieTwoCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieThree') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieThree',
                'ODTracking::CookieConsent.consentCustomCookieThreeLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieThreeDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieThreeProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieThreeLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieThreePolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieThree', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieThreeNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieThreeIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieThreeCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieFour') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieFour',
                'ODTracking::CookieConsent.consentCustomCookieFourLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieFourDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieFourProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieFourLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieFourPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieFour', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieFourNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieFourIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieFourCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieFive') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieFive',
                'ODTracking::CookieConsent.consentCustomCookieFiveLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieFiveDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieFiveProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieFiveLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieFivePolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieFive', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieFiveNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieFiveIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieFiveCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieSix') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieSix',
                'ODTracking::CookieConsent.consentCustomCookieSixLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieSixDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieSixProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieSixLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieSixPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieSix', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieSixNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieSixIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieSixCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieSeven') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieSeven',
                'ODTracking::CookieConsent.consentCustomCookieSevenLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieSevenDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieSevenProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieSevenLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieSevenPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieSeven', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieSevenNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieSevenIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieSevenCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieEight') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieEight',
                'ODTracking::CookieConsent.consentCustomCookieEightLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieEightDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieEightProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieEightLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieEightPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieEight', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieEightNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieEightIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieEightCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieNine') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieNine',
                'ODTracking::CookieConsent.consentCustomCookieNineLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieNineDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieNineProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieNineLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieNinePolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieNine', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieNineNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieNineIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieNineCookieNames')))
                ]
            );
        }

        if ($config->get('ODTracking.showCustomCookieTen') === 'true') {
            $consentRepository->registerConsent(
                'gtmCustomCookieTen',
                'ODTracking::CookieConsent.consentCustomCookieTenLabel',
                [
                    'description' => 'ODTracking::CookieConsent.consentCustomCookieTenDescription',
                    'provider' => 'ODTracking::CookieConsent.consentCustomCookieTenProvider',
                    'lifespan' => 'ODTracking::CookieConsent.consentCustomCookieTenLifespan',
                    'policyUrl' => 'ODTracking::CookieConsent.consentCustomCookieTenPolicyUrl',
                    'group' => $config->get('ODTracking.consentGroupCustomCookieTen', 'tracking'),
                    'necessary' => $config->get('ODTracking.consentCustomCookieTenNecessary') === 'true',
                    'isOptOut' => $config->get('ODTracking.consentCustomCookieTenIsOptOut') === 'true',
                    'cookieNames' =>  array_map('trim', explode(',', $config->get('ODTracking.consentCustomCookieTenCookieNames')))
                ]
            );
        }

    }

    /**
     * boot twig extensions and services
     * @param Twig $twig
     * @param Dispatcher $dispatcher
     */
    public function boot(Twig $twig, Dispatcher $dispatcher)
    {
        $dispatcher->listen(OrderCreated::class, function ($event) {
            /** @var FrontendSessionStorageFactoryContract $sessionStorage */
            $sessionStorage = pluginApp(FrontendSessionStorageFactoryContract::class);
            $sessionStorage->getPlugin()->setValue('GTM_TRACK_ORDER', 1);
        }, 0);
    }

}
