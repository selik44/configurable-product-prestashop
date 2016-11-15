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
            !$this->registerHook('displayAdminProductsExtra') OR
            !$this->registerHook('actionProductUpdate') OR
            !$this->registerHook('getContent')
        )
            return false;
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('compbuilder')
        )
            return false;

        return true;
    }



    public function prepareNewTab()
    {
        $query = $this->getContent();
        $base_url = Tools::getHttpHost(true).__PS_BASE_URI__;
        //$js = $this->context->controller->addJs($this->_path . ''.$base_url.'/modules/compbuilder/views/templates/js/main.js');

        $product = new Product((int)Tools::getValue('id_product'));
        // Accessories block
        $accessories = Product::getAccessoriesLight($this->context->language->id, $product->id);

        if ($post_accessories = Tools::getValue('inputAccessories')) {
            $post_accessories_tab = explode('-', $post_accessories);
            foreach ($post_accessories_tab as $accessory_id) {
                if (!$this->haveThisAccessory($accessory_id, $accessories) && $accessory = Product::getAccessoryById($accessory_id)) {
                    $accessories[] = $accessory;
                }
            }
        }


        $smarty = $this->context->smarty->assign(array(
           // 'js' => $js,
            'base_url' => $base_url,
            'id_product' => (int)Tools::getValue('id_product'),
            'accessories' => $accessories,
            'languages' => $this->context->controller->_languages,
            'default_language' => (int)Configuration::get('PS_LANG_DEFAULT')
        ));

        //$smarty->append(array('city' => 'Lincoln', 'state' => 'Nebraska'));
        //return $smarty;
        return $this->display(__FILE__, 'views/templates/admin/sample.tpl');
    }


    public function hookDisplayAdminProductsExtra($params)
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product'))))
        {
            $this->prepareNewTab();
            return $this->display(__FILE__, 'views/templates/admin/sample.tpl');
        }
    }


    public function hookActionProductUpdate($params) {

    }


    public function getContent()
    {


        $product_id = (int)Tools::getValue('id_product');

        //post process part
        if (Tools::isSubmit('saveMyAssociations')) {
            // see the function below, a simple query to delete all the associations on a product
            $this->deleteMyAssociations($product_id);
            if ($associations = Tools::getValue('inputMyAssociations')) {
                $associations_id = array_unique(explode('-', $associations));
                if (count($associations_id)) {
                    array_pop($associations_id);
                    //insert all the association we have made.
                    $this->changeMyAssociations($associations_id, $product_id);

                }
            }
        }

        $my_associations = CompBuilder::getAssociationsLight($this->context->language->id, Tools::getValue('id_product')); //function that will retrieve the array of all the product associated on my module table.

//        $this->context->smarty->assign(array(
//            'my_associations' => $my_associations,
//            'product_id' => (int)Tools::getValue('id_product')
//        ));

        return $my_associations;

    }

//our little function to get the already saved list, for each product we will retrieve id, name and reference with a join on the product/product_lang tables.
    public static function getAssociationsLight($id_lang, $id_product, Context $context = null)
    {
        if (!$context)
            $context = Context::getContext();

        $sql = 'SELECT p.id_product, p.reference, pl.name
                FROM `'._DB_PREFIX_ .'accessory`
                LEFT JOIN `'._DB_PREFIX_ .'product` p ON (p.id_product = id_product_2)
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                    p.id_product = pl.id_product
                    AND pl.id_lang = 2
                )
                WHERE id_product_1 = '.(int)$id_product;

//        $sql = 'SELECT p.`id_product`, p.`reference`, pl.`name`
//                FROM `'._DB_PREFIX_.'accessory`
//                LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (p.`id_product`= `id_product_2`)
//                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
//                    p.`id_product` = pl.`id_product`
//                )
//                WHERE `id_product_1` = ' . (int)$id_product;

        return Db::getInstance()->executeS($sql);
    }

}