<?php

use function DI\create;
use Elementrify\Base\WidgetBase;
use Elementrify\Admin\AdminAssets;
use Elementrify\Utils\DataProcessor;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Common\WidgetManager;
use Elementrify\Admin\AdminController;
use Elementrify\Widgets\Button\Button;
use Elementrify\Admin\WidgetController;
use Elementrify\Admin\SettingsController;
use Elementrify\Widgets\Accordion\Accordion;

return [
    AdminController::class    => create(AdminController::class),
    AdminAssets::class        => create(AdminAssets::class),
    WidgetManager::class      => create(WidgetManager::class),
    WidgetController::class   => create(WidgetController::class),
    SettingsController::class => create(SettingsController::class),
    OptionsHelper::class      => create(OptionsHelper::class),
    DataProcessor::class      => create(DataProcessor::class),
    Button::class             => create(Button::class),
    Accordion::class          => create(Accordion::class),
    WidgetBase::class         => create(WidgetBase::class)
];
