<?php

use rmrevin\yii\rbac\RbacFactory;

class m141222_104853_rbac_first_step extends \rmrevin\yii\rbac\RbacMigration
{

    protected function getNewInheritance()
    {
        return [
            'admin' => [
                'manager',
                'backend.account.delete',
                'backend.pages.delete',
            ],
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
            'seller' => [
                'user',
                'frontend.deal.buy',
                'frontend.deal.sell',
                'frontend.contract.import',
                'frontend.contract.create',
                'frontend.contract.update.own',
                'frontend.contract.delete.own',
            ],
            'buyer_legal' => [
                'user',
                'frontend.deal.buy',
                'frontend.deal.create',
            ],
            'buyer_individual' => [
                'user',
                'frontend.deal.buy',
                'frontend.deal.create',
            ],
            'user' => [
                'frontend.access',
                'frontend.contract.access',
                'frontend.pages.view',
            ],
        ];
    }

    protected function getOldInheritance()
    {
        return [];
    }

    protected function getNewRules()
    {
        return [
            RbacFactory::Rule('\rmrevin\yii\rbac\examples\ItsMyContract', 'frontend.contract.its-my'),
        ];
    }

    protected function getNewRoles()
    {
        return [
            RbacFactory::Role('admin', 'Администратор'),
            RbacFactory::Role('manager', 'Менеджер'),
            RbacFactory::Role('seller', 'Продавец'),
            RbacFactory::Role('buyer_legal', 'Покупатель (юридическое лицо)'),
            RbacFactory::Role('buyer_individual', 'Покупатель (физическое лицо)'),
            RbacFactory::Role('user', 'Пользователь'),
        ];
    }

    protected function getNewPermissions()
    {
        return [
            RbacFactory::Permission('frontend.access', 'Имеет доступ к системе'),
            /** Account module */
            RbacFactory::Permission('backend.account.access', 'Имеет доступ к модулю пользователей'),
            RbacFactory::Permission('backend.account.approve', 'Может подтверждать юр.лица'),
            RbacFactory::Permission('backend.account.create', 'Может создавать пользователей'),
            RbacFactory::Permission('backend.account.update', 'Может обновлять пользователей'),
            RbacFactory::Permission('backend.account.delete', 'Может удалять пользователей'),
            /** Contract module */
            RbacFactory::Permission('frontend.contract.access', 'Имеет доступ к модулю контрактов'),
            RbacFactory::Permission('frontend.contract.import', 'Имеет доступ к импорту контрактов'),
            RbacFactory::Permission('frontend.contract.create', 'Может создавать контракты'),
            RbacFactory::Permission('frontend.contract.update', 'Может обновлять все контракты'),
            RbacFactory::Permission('frontend.contract.update.own', 'Может обновлять свои контракты', 'frontend.contract.its-my'),
            RbacFactory::Permission('frontend.contract.delete', 'Может удалять все контракты'),
            RbacFactory::Permission('frontend.contract.delete.own', 'Может удалять свои контракты', 'frontend.contract.its-my'),
            /** Deal module */
            RbacFactory::Permission('backend.deal.access', 'Может управлять всеми сделками'),
            RbacFactory::Permission('frontend.deal.create', 'Может создавать сделки'),
            RbacFactory::Permission('frontend.deal.buy', 'Может просматривать свои исходящие сделки (покупка)'),
            RbacFactory::Permission('frontend.deal.sell', 'Может просматривать свои входящие сделки (продажа)'),
            /** Pages module */
            RbacFactory::Permission('backend.pages.access', 'Имеет доступ к модулю статических страниц'),
            RbacFactory::Permission('backend.pages.create', 'Может создавать статические страницы'),
            RbacFactory::Permission('backend.pages.update', 'Может обновлять статические страницы'),
            RbacFactory::Permission('backend.pages.delete', 'Может удалять статические страницы'),
            RbacFactory::Permission('frontend.pages.view', 'Может просматривать статические страницы'),
            /** Settings module */
            RbacFactory::Permission('backend.settings.access', 'Имеет доступ к модулю настроек'),
        ];
    }
}