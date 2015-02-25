<?php

use yii\db\mysql\Schema;

class m150225_101447_rbac_manger_split extends \rmrevin\yii\rbac\RbacMigration
{

    protected function getNewInheritance()
    {
        return [
            'manager' => [
                'user',
                'backend.settings.access',
                'backend.account.access',
                'backend.account.approve',
                'backend.account.create',
                'backend.account.update',
                'backend.pages.access',
                'backend.pages.create',
                'backend.pages.update',
                'frontend.contract.update',
                'frontend.contract.delete',
                'backend.deal.access',
            ],
        ];
    }

    protected function getOldInheritance()
    {
        return [
            'manager' => [
                'seller',
                'buyer_legal',
                'buyer_individual',
                'backend.settings.access',
                'backend.account.access',
                'backend.account.approve',
                'backend.account.create',
                'backend.account.update',
                'backend.pages.access',
                'backend.pages.create',
                'backend.pages.update',
                'frontend.contract.update',
                'frontend.contract.delete',
                'backend.deal.access',
            ],
        ];
    }
}