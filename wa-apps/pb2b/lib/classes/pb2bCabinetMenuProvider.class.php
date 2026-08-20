<?php
class pb2bCabinetMenuProvider
{
    private const CABINET_MENU_KEY = 'cabinet_menu';
    private const SIDEBAR_MENU_KEY = 'sidebar';
    private const HEADER_MENU_KEY = 'header';


    public static function sidebar(string $role): array
    {
        return pb2bWaproHelper::getConfigOption(self::CABINET_MENU_KEY)[self::SIDEBAR_MENU_KEY][$role] ?? [];
    }

    public static function header(): array
    {
        return pb2bWaproHelper::getConfigOption(self::CABINET_MENU_KEY)[self::HEADER_MENU_KEY] ?? [];
    }
}