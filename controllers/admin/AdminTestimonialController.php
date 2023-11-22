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


class AdminTestimonialController extends ModuleAdminController
{

    protected $position_identifier = 'id_testimonial';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'testimonial';
        $this->className = 'testimonialClass';
        $this->identifier = 'id_testimonial';
        $this->_defaultOrderBy = 'position';
        $this->_defaultOrderWay = 'ASC';
        $this->toolbar_btn = null;
        $this->list_no_link = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', [], 'Modules.Testimonials.Admin'),
                'confirm' => $this->trans('Delete selected items?', [], 'Modules.Testimonials.Admin'),
                'icon' => 'icon-trash'
            )
        );

        $this->fields_list = array(
            'id_testimonial' => array(
                'title' => $this->trans('ID', [], 'Modules.Testimonials.Admin'),
                'filter_key' => 'id_testimonial',
            ),
            'title' => array(
                'title' => $this->trans('Title', [], 'Modules.Testimonials.Admin'),
                'filter_key' => 'b!title',
            ),

            'status' => array(
                'title' => $this->trans('status', [], 'Modules.Testimonials.Admin'),
                'type' => 'bool',
                'active' => 'approved',
                'search' => true
            ),
            'position' => array(
                'title' => $this->trans('Position', [], 'Modules.Testimonials.Admin'),
                'filter_key' => 'position',
                'position' => 'position',
                'align' => 'center',
                'class' => 'fixed-width-md',
                'orderby' => true,
            ),
            'date_add' => array(
                'title' => $this->trans('Date add', [], 'Modules.Testimonials.Admin'),
                'type' => 'date',
                'search' => false,
            ),

        );
    }
    protected function fileUpload($file)
    {
        $result = array(
            'error' => array(),
            'image' => '',
        );

        $types = array('Doc', 'jpeg', 'docx', 'png');
        if (isset($_FILES[$file]) && isset($_FILES[$file]['tmp_name']) && !empty($_FILES[$file]['tmp_name']) && ($_FILES[$file]['size'] < 1048576)) {
            $name = str_replace(strrchr($_FILES[$file]['name'], '.'), '', $_FILES[$file]['name']);

            $imageSize = @getimagesize($_FILES[$file]['tmp_name']);
            if (
                !empty($imageSize) &&
                ImageManager::isCorrectImageFileExt($_FILES[$file]['name'], $types)
            ) {
                $fileName = explode('.', $_FILES[$file]['name']);
                $imageExt = $fileName[1];
                $tempName = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $coverImageName = $name . '-' . rand(0, 1000) . '.' . $imageExt;
                if ($upload_error = ImageManager::validateUpload($_FILES[$file])) {
                    $result['error'][] = $upload_error;
                } elseif (!$tempName || !move_uploaded_file($_FILES[$file]['tmp_name'], $tempName)) {
                    $result['error'][] = $this->trans('An error occurred during moving image.', [], 'Modules.Testimonials.Admin');
                } else {
                    $destinationFile = _PS_MODULE_DIR_ . $this->module->name . '/views/img/' . $coverImageName;
                    if (!ImageManager::resize($tempName, $destinationFile, null, null, $imageExt)) {
                        $result['error'][] = $this->trans('An error occurred during the image upload.', [], 'Modules.Testimonials.Admin');
                    }
                }
                if (isset($tempName)) {
                    @unlink($tempName);
                }

                if (!count($result['error'])) {
                    $result['image'] = $coverImageName;
                    $result['width'] = $imageSize[0];
                    $result['height'] = $imageSize[1];
                }
                return $result;
            }
        } else {
            return $result;
        }
    }



    public function postProcess()
    {
        $obj = $this->loadObject(true);
        if ($this->action && $this->action == 'save') {
            $myfile = $this->fileUpload('file');
            if ($myfile['image']) {
                $_POST['file'] = $myfile['image'];
            } else {
                $_POST['file'] = $obj->file;
            }
        }
        return parent::postProcess();
    }

    public function renderForm()
    {

        if (!($obj = $this->loadObject(true))) {
            return;
        }
        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->trans('title', [], 'Modules.Testimonials.Admin'),
                'icon' => 'icon-folder-close'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Modules.Testimonials.Admin'),
                    'name' => 'title',
                    'required' => true,
                    'desc' => $this->trans('Please enter a title', [], 'Modules.Testimonials.Admin'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('customer id', [], 'Modules.Testimonials.Admin'),
                    'name' => 'id_costumer',
                    'required' => true,
                    'desc' => $this->trans('customer', [], 'Modules.Testimonials.Admin'),
                    'options' => array(
                        'query' => Customer::getCustomers(1),
                        'id' => 'id_customer',
                        'name' => 'firstname'
                    ),
                ),

                array(
                    'type' => 'textarea',
                    'label' => $this->trans('message', [], 'Modules.Testimonials.Admin'),
                    'name' => 'message',
                    'required' => true,
                    'autoload_rte' => 'rte',
                    'desc' => $this->trans('Please enter a message', [], 'Modules.Testimonials.Admin'),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->trans('file', [], 'Modules.Testimonials.Admin'),
                    'name' => 'file',
                    'required' => false,
                    'desc' => $this->trans('Please enter a file', [], 'Modules.Testimonials.Admin'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('status', [], 'Modules.Testimonials.Admin'),
                    'name' => 'status',
                    'is_bool' => true,
                    'desc' => $this->trans('Approve the testimonial', [], 'Modules.Testimonials.Admin'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('aproved', [], 'Modules.Testimonials.Admin')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('declined', [], 'Modules.Testimonials.Admin')
                        )
                    ),
                ),




            ),
            'submit' => array(
                'title' => $this->trans('Save', [], 'Modules.Testimonials.Admin'),
                'class' => 'btn btn-default pull-right'
            )
        );
        return parent::renderForm();
    }


    public function ajaxProcessUpdatePositions()
    {
        $way = (int) (Tools::getValue('way'));
        $id_testimonial = (int) (Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int) $pos[2] === $id_testimonial) {
                if ($posi = new TestimonialClass((int) $pos[2])) {
                    if (isset($position) && $posi->updatePosition($way, $position)) {
                        echo 'ok position ' . (int) $position . ' for Testimonial ' . (int) $pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update Testimonial ' . (int) $id_testimonial . ' to position ' . (int) $position . ' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This Tesitimonial (' . (int) $id_testimonial . ') can t be loaded"}';
                }

                break;
            }
        }
    }
}
