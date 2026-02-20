<?php

use function DI\create;
use Elementrify\Base\WidgetBase;
use Elementrify\Admin\AdminAssets;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Admin\AdminController;
use Elementrify\Widgets\Button\Button;
use Elementrify\Common\WidgetController;
use Elementrify\Admin\Widgets\WidgetManager;
use Elementrify\Widgets\Accordion\Accordion;

return [
    AdminController::class  => create(AdminController::class),
    AdminAssets::class      => create(AdminAssets::class),
    WidgetController::class => create(WidgetController::class),
    WidgetManager::class    => create(WidgetManager::class),
    OptionsHelper::class    => create(OptionsHelper::class),
    Button::class           => create(Button::class),
    Accordion::class        => create(Accordion::class),
    WidgetBase::class       => create(WidgetBase::class)
];
