<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class Redirect404Listener implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            // Złapany błąd 404
            $request = $event->getRequest();

            $url1 = $request->getUri();

            if (isset($this->links[$url1])) {
                $newUrl = $this->links[$url1];
                $status = 301;
            } else {

                $newUrl = 'https://medorto.pl';
                $status = 303;
            }

            // Przykład przekierowania na inny adres URL
            $response = new RedirectResponse($newUrl, $status);

            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    protected array $links = [
        'https://medorto.pl/pl_PL/taxons/wyposazenie/akcesoria' => 'https://medorto.pl/pl_PL/taxons/drobne-wyposazenie',
        'https://medorto.pl/pl_PL/taxons/sprzet-lazienkowy' => 'https://medorto.pl/pl_PL/taxons/sprzet-toaletowo-prysznicowy',
        'https://medorto.pl/pl_PL/taxons/wyposazenie?page=9' => 'https://medorto.pl/pl_PL/taxons/drobne-wyposazenie',
        'https://medorto.pl/pl_PL/taxons/wyposazenie?page=4' => 'https://medorto.pl/pl_PL/taxons/drobne-wyposazenie',
        'https://medorto.pl/pl_PL/taxons/drobny-sprzet-medyczny?page=1' => 'https://medorto.pl/pl_PL/taxons/sprzet-i-urzadzenia',
        'https://medorto.pl/pl_PL/taxons/separatory-kliny-oslony-palcow' => 'https://medorto.pl/pl_PL/taxons/separatory-palcow',
        'https://medorto.pl/pl_PL/taxons/gazy' => 'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku/materialy-opatrunkowe/gazy',
        'https://medorto.pl/pl_PL/taxons/drobny-sprzet-medyczny/materialy-opatrunkowe?page=2' => 'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku/materialy-opatrunkowe',
        'https://medorto.pl/pl_PL/products/pt0432-ccl1-ponczochy-uciskowe-premium-delicate' => 'https://medorto.pl/pl_PL/taxons/produkty-przeciwzylakowe-kompresja/ponczochy',
        'https://medorto.pl/pl_PL/products/opaska-na-sciegno-achillesa-airheel-asdsadsadsad' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/opaski',
        'https://medorto.pl/pl_PL/taxons/wyposazenie/poduszki-ortopedyczne' => 'https://medorto.pl/pl_PL/taxons/poduszki',
        'https://medorto.pl/pl_PL/products/stalowy-wozek-inwalidzki-reha-fund-cruiser-rf-1' => 'https://medorto.pl/pl_PL/taxons/wozki-reczne',
        'https://medorto.pl/pl_PL/products/pachwinowy-pas-jednostronny-pt109' => 'https://medorto.pl/pl_PL/taxons/pasy-brzuszne',
        'https://medorto.pl/pl_PL/products/orteza-stabilizujaca-staw-skokowy-malleo-dynastab' => 'https://medorto.pl/pl_PL/products/orteza-stabilizuujaca-staw-skokowy-malleo-dynastab',
        'https://medorto.pl/pl_PL/taxons/wozki-dzieciece' => 'https://medorto.pl/pl_PL/taxons/mobilnosc',
        'https://medorto.pl/pl_PL/products/laweczka-pod-prysznic-sophia-rf-854' => 'https://medorto.pl/pl_PL/taxons/taborety-i-krzesla-prysznicowe',
        'https://medorto.pl/pl_PL/taxons/wyposazenie?page=1' => 'https://medorto.pl/pl_PL/taxons/sprzet-toaletowo-prysznicowy',
        'https://medorto.pl/pl_PL/products/laska-inwalidzka-czwornog-niski-z-miekkim-uchwytem' => 'https://medorto.pl/pl_PL/taxons/laski-trojnog-i-czwornog',
        'https://medorto.pl/pl_PL/products/automatyczny-cisnieniomierz-naramienny-omron-m2-basic' => 'https://medorto.pl/pl_PL/taxons/cisnieniomierze',
        'https://medorto.pl/pl_PL/taxons/rehabilitacja-i-cwiczenia//tasmy-do-tapingu' => 'https://medorto.pl/pl_PL/taxons/tape',
        'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku/pojemniki-na-mocz-i-kal/pojemniki-na-odpady-medyczne' => 'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku/pojemniki-na-odpady-medyczne',
        'https://medorto.pl/pl_PL/products/oslona-na-palce-do-docinania-zamknieta-g046' => 'https://medorto.pl/pl_PL/taxons/oslony-na-palce',
        'https://medorto.pl/pl_PL/taxons/opaska-stawu-kolanowego' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/opaski',
        'https://medorto.pl/pl_PL/products/wozek-inwalidzki-at52322' => 'https://medorto.pl/pl_PL/taxons/wozki-reczne',
        'https://medorto.pl/pl_PL/taxons/kosmetyki-i-pielegnacja-ciala?page=2' => 'https://medorto.pl/pl_PL/taxons/higiena-i-pielegnacja',
        'https://medorto.pl/pl_PL/taxons/drobny-sprzet-medyczny?page=3' => 'https://medorto.pl/pl_PL/taxons/sprzet-i-urzadzenia',
        'https://medorto.pl/pl_PL/products/hartmann-molicare-premium-slip-super-plus-m-30-szt' => 'https://medorto.pl/pl_PL/products/hartmann-molicare-premium-slip-extra-plus-m-30-szt',
        'https://medorto.pl/pl_PL/products/kolnierz-ortopedyczny-typu-shanza-o-1800' => 'https://medorto.pl/pl_PL/taxons/kolnierze-ortopedyczne',
        'https://medorto.pl/pl_PL/taxons/drobny-sprzet-medyczny?page=5' => 'https://medorto.pl/pl_PL/taxons/sprzet-i-urzadzenia',
        'https://medorto.pl/pl_PL/products/termometr-na-podczerwien-everchek-swift' => 'https://medorto.pl/pl_PL/taxons/termometry-i-pulsoksymetry',
        'https://medorto.pl/pl_PL/products/krazki-przeciwodlezynowe-40x5' => 'https://medorto.pl/pl_PL/taxons/kategorie/artykuly-przeciwodlezynowe/krazki',
        'https://medorto.pl/pl_PL/products/korektor-postawy' => 'https://medorto.pl/pl_PL/taxons/rehabilitacja-i-cwiczenia//korektory-postawy',
        'https://medorto.pl/pl_PL/products/hartmann-molicare-premium-slip-super-plus-l-30-szt' => 'https://medorto.pl/pl_PL/products/hartmann-molicare-premium-slip-extra-plus-l-30-szt',
        'https://medorto.pl/pl_PL/products/tena-lady-slim-mini-plus-wings' => 'https://medorto.pl/pl_PL/products/tena-lady-slim-ultra-mini-28szt-podpaska-specjalistyczna',
        'https://medorto.pl/pl_PL/taxons/wyposazenie' => 'https://medorto.pl/pl_PL/taxons/drobne-wyposazenie',
        'https://medorto.pl/pl_PL/products/jednorazowa-gabka-nasaczona-zelem-myjacym-dla-dzieci-1szt' => 'https://medorto.pl/pl_PL/taxons/myjki-i-chusteczki',
        'https://medorto.pl/pl_PL/taxons/stabilizatory' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/_stabilizatory',
        'https://medorto.pl/pl_PL/taxons/poduszki-rehabilitacyjne' => 'https://medorto.pl/pl_PL/taxons/poduszki',
        'https://medorto.pl/pl_PL/taxons/drobny-sprzet-medyczny' => 'https://medorto.pl/pl_PL/taxons/sprzet-i-urzadzenia',
        'https://medorto.pl/pl_PL/products/omron-cisnieniomierz-naramienny-m2-basic' => 'https://medorto.pl/pl_PL/taxons/cisnieniomierze',
        'https://medorto.pl/pl_PL/products/wozek-inwalidzki-aluminiowy-ergonomic-ar-300' => 'https://medorto.pl/pl_PL/taxons/wozki-reczne',
        'https://medorto.pl/pl_PL/products/wkladki-ortopedyczne-sampi-15cm' => 'https://medorto.pl/pl_PL/products/wkladki-ortopedyczne-sampi',
        'https://medorto.pl/pl_PL/taxons/sprzet-toaletowy' => 'https://medorto.pl/pl_PL/taxons/sprzet-toaletowo-prysznicowy',
        'https://medorto.pl/pl_PL/taxons/mobilnosc/wozki-inwalidzkie' => 'https://medorto.pl/pl_PL/taxons/wozki-reczne',
        'https://medorto.pl/pl_PL/products/pt0472-ccl1-ponczochy-medyczne-premium' => 'https://medorto.pl/pl_PL/taxons/produkty-przeciwzylakowe-kompresja/ponczochy',
        'https://medorto.pl/pl_PL/taxons/kosmetyki-i-pielegnacja-ciala?_channel_code=FASHION_WEB&page=4' => 'https://medorto.pl/pl_PL/taxons/higiena-i-pielegnacja',
        'https://medorto.pl/pl_PL/taxons/wyposazenie/sprzet-medyczny' => 'https://medorto.pl/pl_PL/taxons/sprzet-i-urzadzenia',
        'https://medorto.pl/pl_PL/products/gabka-z-zelem-myjacym-z-dodatkiem-aloesu-1szt' => 'https://medorto.pl/pl_PL/taxons/higiena-i-pielegnacja',
        'https://medorto.pl/pl_PL/taxons/stabilizatory?page=2' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/_stabilizatory',
        'https://medorto.pl/pl_PL/taxons/kosmetyki-i-pielegnacja-ciala?_channel_code=FASHION_WEB&page=2' => 'https://medorto.pl/pl_PL/taxons/higiena-i-pielegnacja',
        'https://medorto.pl/pl_PL/products/wkladki-zelowe-air-flex' => 'https://medorto.pl/pl_PL/products/wkladki-zelowe-air-flex_g011',
        'https://medorto.pl/pl_PL/products/stabilizator-sciegna-achillesa-up5420' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/_stabilizatory',
        'https://medorto.pl/pl_PL/taxons/mobilnosc/skutery-inwalidzkie-elektryczne' => 'https://medorto.pl/pl_PL/taxons/wozki-elektryczne-i-skutery',
        'https://medorto.pl/pl_PL/products/tena-proskin-pants-night' => 'https://medorto.pl/pl_PL/taxons/wkladki-urologiczne/dla-kobiet',
        'https://medorto.pl/pl_PL/taxons/wyposazenie/akcesoria/do-stop' => 'https://medorto.pl/pl_PL/taxons/wkladki-do-butow',
        'https://medorto.pl/pl_PL/products/pileczka-rehabilitacyjna-z-kolcami' => 'https://medorto.pl/pl_PL/taxons/pilki-rehabilitacyjne',
        'https://medorto.pl/pl_PL/taxons/stabilizatory?page=1' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/_stabilizatory',
        'https://medorto.pl/pl_PL/taxons/drobny-sprzet-medyczny/materialy-opatrunkowe?page=1' => 'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku/materialy-opatrunkowe',
        'https://medorto.pl/pl_PL/products/korytkowe-wkladki-na-plaskostopie-podluzne old' => 'https://medorto.pl/pl_PL/products/korytkowe-wkladki-na-plaskostopie-podluzne-champion',
        'https://medorto.pl/pl_PL/taxons/rehabilitacja-i-cwiczenia//pilki' => 'https://medorto.pl/pl_PL/taxons/pilki-rehabilitacyjne',
        'https://medorto.pl/pl_PL/taxons/stabilizator-nadgarstka-actimove' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/_stabilizatory',
        'https://medorto.pl/pl_PL/products/orteza-nadgarstka-ligaflex-cassic-open-rozm-2' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/Ortezy',
        'https://medorto.pl/pl_PL/products/mankiety-microlife-do-cisnieniomierzy' => 'https://medorto.pl/pl_PL/products/mankiety-mmicrolife-do-cisnieniomierzy',
        'https://medorto.pl/pl_PL/products/krzeslo-prysznicowe-z-oparciem' => 'https://medorto.pl/pl_PL/products/krzeslo-prysznicowe-z-oparciem-ar-203',
        'https://medorto.pl/pl_PL/products/polkule-do-rehabilitacji?_channel_code=FASHION_WEB' => 'https://medorto.pl/pl_PL/taxons/rehabilitacja-i-cwiczenia/',
        'https://medorto.pl/pl_PL/products/pt433-ccl1-podkolanowki-premium-delicate' => 'https://medorto.pl/pl_PL/taxons/produkty-przeciwzylakowe-kompresja/podkolanowki',
        'https://medorto.pl/pl_PL/products/skarpet-na-opadajaca-stope-z-pasem-up5800' => 'https://medorto.pl/pl_PL/taxons/skarpety-i-stopki',
        'https://medorto.pl/pl_PL/products/kolnierz-ortopedyczny-typ-florida-sz-02' => 'https://medorto.pl/pl_PL/products/kolnierz-oortopedyczny-typ-florida-sz-02',
        'https://medorto.pl/pl_PL/taxons/kategorie/produkty-medyczne' => 'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku/materialy-opatrunkowe',
        'https://medorto.pl/pl_PL/products/poduszka-ortopedyczna-separator-pomiedzy-kolana-exclusive-support' => 'https://medorto.pl/pl_PL/products/poduszka-ortopedyczna-separator-pomiedzy-kolana-exclusive-support',
        'https://medorto.pl/pl_PL/taxons/stabilizatory?page=4' => 'https://medorto.pl/pl_PL/taxons/stabilizatory-ortezy-opaski/_stabilizatory',
        'https://medorto.pl/pl_PL/products/orteza-stabilizujaca-staw-skokowy-malleo-dynastab-rozm-3' => 'https://medorto.pl/pl_PL/products/orteza-stabilizuujaca-staw-skokowy-malleo-dynastab',
        'https://medorto.pl/pl_PL/products/wkladki-na-plaskostopie-perfect-old' => 'https://medorto.pl/pl_PL/products/wkladki-na-plaskostopie-perfect',
        'https://medorto.pl/pl_PL/products/krazki-przeciwodlezynowe-15x5' => 'https://medorto.pl/pl_PL/taxons/kategorie/artykuly-przeciwodlezynowe',
        'https://medorto.pl/pl_PL/products/pilki-rehabilitacyjne' => 'https://medorto.pl/pl_PL/taxons/pilki-rehabilitacyjne',
        'https://medorto.pl/pl_PL/products/poduszka-do-siedzenia-bioflote-2plus' => 'https://medorto.pl/pl_PL/taxons/poduszki',
        'https://medorto.pl/pl_PL/products/kula-inwalidzka-advance' => 'https://medorto.pl/pl_PL/taxons/mobilnosc/kule-i-laski',
        'https://medorto.pl/pl_PL/products/gabka-nasaczona-zelem-aktywnym-ph-5-5-dla-dzieci-zapach-brzoskwiniowy' => 'https://medorto.pl/pl_PL/taxons/myjki-i-chusteczki',
        'https://medorto.pl/pl_PL/products/wkladki-ortopedyczne-sampi-24cm' => 'https://medorto.pl/pl_PL/products/wkladki-ortopedyczne-sampi',
        'https://medorto.pl/pl_PL/products/basen-sanitarny-plaski-rf-896' => 'https://medorto.pl/pl_PL/taxons/baseny-sanitarne',
        'https://medorto.pl/pl_PL/products/pt0473-ccl1-podkolanowki-medyczne-premium' => 'https://medorto.pl/pl_PL/taxons/produkty-przeciwzylakowe-kompresja',
        'https://medorto.pl/pl_PL/taxons/kategorie/produkty-medyczne?_channel_code=FASHION_WEB' => 'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku/materialy-opatrunkowe',
        'https://medorto.pl/pl_PL/products/szpatulka-laryngologiczna-drewniana-jalowa' => 'https://medorto.pl/pl_PL/taxons/kategorie/materialy-jednorazowego-uzytku',
        'https://medorto.pl/pl_PL/products/krazki-przeciwodlezynowe-25x7' => 'https://medorto.pl/pl_PL/products/krazki-przeciwodlezynowe',
        'https://medorto.pl/pl_PL/taxons/mikolajkowe-promocje' => 'https://medorto.pl/pl_PL/promotions/',
        'https://medorto.pl/pl_PL/products/kolnierz-sztywny-z-otworem-tracheostomijnym-o-1840' => 'https://medorto.pl/pl_PL/taxons/ortopedia'
    ];
}


