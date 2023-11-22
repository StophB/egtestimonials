<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once("classes/EgTestimonialClass.php");

class EgTestimonials extends Module
{

    public function __construct()
    {
        $this->name = 'egtestimonials';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'MST';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans('Eg Testimonials', [], 'Modules.Egtestimonials.Egtestimonials');

        $this->description = $this->trans('Egio Testimonials Module', [], 'Modules.Egtestimonials.Egtestimonials');

        $this->confirmUninstall = $this->trans('Are you Sure ?', [], 'Modules.Egtestimonials.Egtestimonials');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }



    public function createTabs()
    {
        $idParent = (int) Tab::getIdFromClassName('AdminEgDigital');
        if (empty($idParent)) {
            $parent_tab = new Tab();
            $parent_tab->name = [];
            foreach (Language::getLanguages(true) as $lang) {
                $parent_tab->name[$lang['id_lang']] = $this->trans('EGIO Modules', [], $this->domain);
            }
            $parent_tab->class_name = 'AdminEgDigital';
            $parent_tab->id_parent = 0;
            $parent_tab->module = $this->name;
            $parent_tab->icon = 'library_books';
            $parent_tab->add();
        }

        $tab = new Tab();
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('EG Testimonial', [], 'Modules.T
            Egtestimonials.Egtestimonials');
        }
        $tab->class_name = 'AdminEgTestimonialsGeneral';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgDigital');
        $tab->module = $this->name;
        $tab->icon = 'library_books';
        $tab->add();

        // Manage Testimonials
        $tab = new Tab();
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('Manage Testimonials', [], 'Modules.Egtestimonials.Egtestimonials');
        }
        $tab->class_name = 'AdminEgTestimonials';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminEgTestimonialsGeneral');
        $tab->module = $this->name;
        $tab->add();

        return true;
    }

    public function removeTabs($class_name)
    {
        if ($tab_id = (int)Tab::getIdFromClassName($class_name)) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install()
            && $this->createTabs()
            && $this->registerHook('displayCustomerAccount')
            && $this->registerHook('displayHome')
            && $this->registerHook('Header');
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function uninstall()
    {

        include(dirname(__FILE__) . '/sql/uninstall.php');

        $this->removeTabs('AdminEgTestimonials');
        $this->removeTabs('AdminEgTestimonialsGeneral');

        return parent::uninstall();
    }

    public function hookDisplayCustomerAccount()
    {

        $this->context->smarty->assign([

            'testomonial_url' => Context::getContext()->link->getModuleLink($this->name, 'view', [], true)
        ]);
        return $this->display(__FILE__, 'views/templates/hook/testimonial_costumer_account.tpl');
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . '/views/css/testimonials.css');
        $this->context->controller->addJS($this->_path . '/views/js/app.js');
    }
    public function hookDisplayHome()
    {

        $datas = TestimonialClass::getTestimonialByStatus();
        $this->context->smarty->assign([

            'datas' => $datas,
            'testomonial_url' => Context::getContext()->link->getModuleLink($this->name, 'view', [], true),

        ]);
        return $this->display(__FILE__, 'views/templates/hook/display_home_testimonials.tpl');
    }
}
