<?php


/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql_queries = [];

$sql_queries[] = 'CREATE TABLE IF NOT EXISTS`' . _DB_PREFIX_ . 'testimonial` (
        `id_testimonial` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `id_costumer` int(10) unsigned NOT NULL,
        `title` varchar(60) NOT NULL,
        `message` varchar(300) NOT NULL,
        `file` varchar(255),
        `position` int(10) unsigned NOT NULL,
        `status`  tinyint(1) unsigned not null default 0,
        `date_add` datetime NOT NULL,
        `date_upd` datetime NOT NULL,
        PRIMARY KEY (`id_testimonial`)
    )ENGINE =' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

foreach ($sql_queries as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
