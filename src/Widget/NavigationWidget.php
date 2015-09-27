<?php

namespace Tracker\Widget;

class NavigationWidget extends AbstractWidget {

    public function getContent() {
        return $this->render('widget/navigation.twig');
    }

}
