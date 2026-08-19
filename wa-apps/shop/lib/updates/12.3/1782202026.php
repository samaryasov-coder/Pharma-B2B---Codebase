<?php
$installer = new shopInstaller();
$installer->addColumns('shop_customer', 'id_code');
try {
    (new waModel())->exec("ALTER TABLE `shop_customer` ADD INDEX `id_code` (`id_code`)");
} catch (waException $e) {
    // already exists
}