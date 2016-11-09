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
        if (!parent::install() OR
            !$this->alterTable('add') OR
            !$this->registerHook('actionAdminControllerSetMedia') OR
            !$this->registerHook('actionProductUpdate') OR
            !$this->registerHook('displayAdminProductsExtra'))
            return false;
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() OR !$this->alterTable('remove'))
            return false;
        return true;
    }


    public function alterTable($method)
    {
        switch ($method) {
            case 'add':
                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_lang ADD `custom_field` TEXT NOT NULL';
                break;

            case 'remove':
                $sql = 'ALTER TABLE ' . _DB_PREFIX_ . 'product_lang DROP COLUMN `custom_field`';
                break;
        }

        if(!Db::getInstance()->Execute($sql))
            return false;
        return true;
    }






    public function prepareNewTab()
    {

        $this->context->smarty->assign(array(
            'custom_field' => '',
            'id_product' => (int)Tools::getValue('id_product'),
            'languages' => $this->context->controller->_languages,
            'default_language' => (int)Configuration::get('PS_LANG_DEFAULT')
        ));

    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product'))))
        {
            $this->prepareNewTab();
            return $this->display(__FILE__, 'newfieldstut.tpl');
        }
    }


    public function hookActionAdminControllerSetMedia($params)
    {

        // add necessary javascript to products back office
        if($this->context->controller->controller_name == 'AdminProducts' && Tools::getValue('id_product'))
        {
            $this->context->controller->addJS($this->_path.'/js/newfieldstut.js');
        }

    }

    public function hookActionProductUpdate($params)
    {
        // get all languages
        // for each of them, store the custom field!

        $id_product = (int)Tools::getValue('id_product');
        $languages = Language::getLanguages(true);
        foreach ($languages as $lang) {
            if(!Db::getInstance()->update('product_lang', array('custom_field'=> pSQL(Tools::getValue('custom_field_'.$lang['id_lang']))) ,'id_lang = ' . $lang['id_lang'] .' AND id_product = ' .$id_product ))
                $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
        }

    }


    public function getComponents(){
        $id_product = (int)Tools::getValue('id_product');

        $sql = 'SELECT prod.id_product, prod.id_category_default, pl.name, c_lang.name
        FROM ps_product prod
        INNER JOIN ps_product_lang pl ON prod.id_product = pl.id_product
        INNER JOIN ps_category pc ON prod.id_category_default = pc.id_category
        INNER JOIN ps_category_lang c_lang ON pc.id_category = c_lang.id_category 
        WHERE prod.id_product = '.$id_product.' AND pc.id_category = 5 AND c_lang.id_lang = 1 AND pl.id_lang = 1';

        $result = Db::getInstance()->getRow($sql);
        foreach ($result as $r){
            $r;
        }



//       $smarty_variable =   $this->context->smarty->assign(array(
//            'custom_field' => '',
//            'id_product' => (int)Tools::getValue('id_product'),
//            'languages' => $this->context->controller->_languages,
//            'default_language' => (int)Configuration::get('PS_LANG_DEFAULT')
//        ));


        return  var_dump($result);
    }

//    public function getCustomField($id_product)
//    {
//        $result = Db::getInstance()->ExecuteS('SELECT custom_field, id_lang FROM '._DB_PREFIX_.'product_lang WHERE id_product = ' . (int)$id_product);
//        if(!$result)
//            return array();
//
//        foreach ($result as $field) {
//            $fields[$field['id_lang']] = $field['custom_field'];
//        }
//
//        return $fields;
//    }

//    public function addComponents(){
//
//    }
//


//    public function getProcessors(){
//        $sql = query('SELECT prod.id_product, prod.id_category_default, pl.name, c_lang.name
//	FROM ps_product prod
//	INNER JOIN ps_product_lang pl ON prod.id_product = pl.id_product
//	INNER JOIN ps_category pc ON prod.id_category_default = pc.id_category
//	INNER JOIN ps_category_lang c_lang ON pc.id_category = c_lang.id_category
//	WHERE prod.id_product = 1 AND pc.id_category = 5 AND c_lang.id_lang = 1 AND pl.id_lang = 1');
//
//        //$id = null;
//
//    }
}