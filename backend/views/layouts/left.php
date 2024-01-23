<?php

use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\widgets\Menu;

?>
<aside class="main-sidebar">
    <section class="sidebar">

        <?php
        $menu = [
            ['label' => 'Content', 'options' => ['class' => 'header']],
            [
                'label' => '<span class="fa fa-commenting"></span> ' . 'Reviews',
                'url'   => ['/review']
            ],
            [
                'label' => '<span class="fa fa-adjust"></span> ' . 'Color',
                'url'   => ['/color']
            ],
            ['label' => 'Settings', 'options' => ['class' => 'header']],
            [
                'label' => '<span class="fa fa-dashboard"></span> ' . 'Change own password',
                'url'   => ['/user-management/auth/change-own-password']
            ],
            [
                'label' => '<span class="glyphicon glyphicon-lock"></span> ' . 'Logout',
                'url'   => ['/user-management/auth/logout']
            ],
        ];

        if (User::hasRole(['Admin'])) {
            $menu[] = ['label' => 'Settings User', 'options' => ['class' => 'header']];
            if (User::hasRole(['Superadmin'])) {
                $umm = UserManagementModule::menuItems();
            } else {
                $umm[] = [
                    'label'   => '<span class="fa fa-angle-double-right"></span> Users',
                    'url'     => ['/user-management/user/index'],
                    'visible' => true
                ];
            }
            $menu = array_merge($menu, $umm);
        }
        ?>

        <?= Menu::widget(
            [
                'encodeLabels'    => false,
                //'activateItems' => true,
                'activateParents' => true,
                'options'         => ['class' => 'sidebar-menu'],
                'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
                'items'           => $menu,
            ]
        ) ?>
    </section>
</aside>
