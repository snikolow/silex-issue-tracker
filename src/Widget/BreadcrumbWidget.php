<?php

namespace App\Widget;

use App\Service\Breadcrumb;

class BreadcrumbWidget extends AbstractWidget {

    /** @var \App\Service\Breadcrumb */
    private $service;

    /**
     * Inject Breadcrumb service so we can get all elements.
     *
     * @param Breadcrumb $service
     */
    public function setBreadcrumbService(Breadcrumb $service) {
        $this->service = $service;
    }

    public function getContent() {
        return $this->render('widget/breadcrumb.twig',
            array(
                'collection' => $this->service->getElements()
            )
        );
    }

}
