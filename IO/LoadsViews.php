<?php
namespace Laranix\Support\IO;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\View\View;

trait LoadsViews
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Load view factory
     */
    protected function loadView()
    {
        $this->view = app()->make('view');
    }

    /**
     * Load global variables available in all views
     *
     * @param \Illuminate\Contracts\Config\Repository       $config
     */
    protected function loadGlobalViewVariables(Config $config)
    {
        $app = app();
        $share = [];

        foreach ($config->get('globalviewvars') as $name => $abstract) {
            $share[$name] = $app->make($abstract);
        }

        $this->share($share);

        if (method_exists($this, 'loadExtraGlobalViewVariables')) {
            $this->loadExtraGlobalViewVariables();
        }
    }

    /**
     * Share a variables with the view
     *
     * @param mixed $share
     * @param mixed $value
     * @return mixed
     */
    protected function share($share, $value = null)
    {
        return $this->view->share($share, $value);
    }

    /**
     * Render error or success page
     *
     * @param array       $data
     * @param bool        $error
     * @param null|string $view
     * @return \Illuminate\Contracts\View\View
     */
    protected function renderStatePage(array $data, bool $error = false, ?string $view = null) : View
    {
        return $error ? $this->renderErrorPage($data, $view) : $this->renderSuccessPage($data, $view);
    }

    /**
     * Render success page
     *
     * @param array       $data
     * @param null|string $view
     * @return \Illuminate\Contracts\View\View
     */
    protected function renderSuccessPage(array $data, ?string $view = null) : View
    {
        return $this->view->make($view ?? 'state.success')->with($data);
    }

    /**
     * Render error page
     *
     * @param array       $data
     * @param null|string $view
     * @return \Illuminate\Contracts\View\View
     */
    protected function renderErrorPage(array $data, ?string $view = null) : View
    {
        return $this->view->make($view ?? 'state.error')->with($data);
    }
}
