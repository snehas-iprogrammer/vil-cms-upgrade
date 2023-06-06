<?php
/**
 * To Admin Auth service provider for Admin module
 * Handling Authentication specific settings and Model binding at Admin Route specific.
 *
 * @author Gaurav Patel <gauravp@iprogrammer.com>
 * @package Admin
 * @since 1.0
 */
namespace Modules\Admin\Providers;

use Illuminate\Routing\Router;
use Modules\Admin\Services\Auth\AdminGuard;
use Modules\Admin\Services\Auth\AdminUserProvider;
use Modules\Admin\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Admin\Repositories\ConfigSettingRepository;
use Modules\Admin\Models\ConfigSetting;

class AdminAuthServiceProvider extends ServiceProvider
{

    public function boot(Router $router)
    {
        parent::boot($router);

        if (!$this->app['request']->is('admin*')) {
            return;
        }

        //database configuration overriding
        $adminDbConnection = $this->app['config']->get('admin.database.connections.mysql');
        $this->app['config']->set('database.connections.mysql', $adminDbConnection);

        $adminUserClass = $this->app['config']->get('admin.auth.admin_model');

        $adminUserTable = $this->app['config']->get('admin.auth.table');
        $adminPasswordEmail = $this->app['config']->get('admin.auth.password.email');

        $this->app['config']->set('auth.driver', 'eloquent.admin');
        $this->app['config']->set('auth.model', $adminUserClass);
        $this->app['config']->set('auth.table', $adminUserTable);
        $this->app['config']->set('auth.password.email', $adminPasswordEmail);

        $fileManagerUrl = $this->app['config']->get('admin.filemanager.url');
        $fileManagerUrlFiles = $this->app['config']->get('admin.filemanager.url-files');
        $this->app['config']->set('filemanager.url', $fileManagerUrl);
        $this->app['config']->set('filemanager.url-files', $fileManagerUrlFiles);

        //Fetch global config settings constants into config
        $this->app['config']->set('settings', $this->fetchConfigConstants());

        $this->app['auth']->extend('eloquent.admin', function () use ($adminUserClass) {
            return new AdminGuard(new AdminUserProvider($this->app['hash'], new $adminUserClass), $this->app['session.store']);
        });
    }

    public function fetchConfigConstants()
    {
        $configSettingRepository = new ConfigSettingRepository(new ConfigSetting);
        $configSetttingData = $configSettingRepository->getSettingsData();
        return($configSetttingData);
    }

    public function map(Router $router)
    {
        parent::map($router);
    }
}
