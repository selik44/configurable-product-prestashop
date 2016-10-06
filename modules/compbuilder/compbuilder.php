<?php
if (!defined('_PS_VERSION_'))
    exit;

class CompBuilder extends Module
{
    public function __construct()
    {
        $this->name = 'compbuilder';
        $this->tab = 'administration';
        $this->version = '1.0';
        $this->author = 'DarinX';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Computer builder');
        $this->description = $this->l('Compurer builder for bundle product.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('COMPBUILDER'))
            $this->warning = $this->l('No name provided');
    }


    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        return parent::install() &&
        $this->registerHook('actionModuleRegisterHookAfter') &&
        $this->registerHook('actionModuleUnRegisterHookAfter') &&
        $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('COMPBUILDER')
        )
            return false;

        return true;
    }

}