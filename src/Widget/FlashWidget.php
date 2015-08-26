<?php

namespace App\Widget;

class FlashWidget extends AbstractWidget {

    public function getContent() {
        return $this->render('widget/flash.twig');
    }

}
