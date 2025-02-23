<?php

namespace Dynamic\Products\Page;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\NumericField;

class ProductCategory extends \Page
{
    /**
     * @var array
     */
    private static $db = [
        'ProductsPerPage' => 'Int',
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'ProductsPerPage' => 12,
    ];

    /**
     * @var string
     */
    private static $table_name = 'ProductCategory';

    /**
     * @var array
     */
    private static $allowed_children = [
        ProductCategory::class,
        Product::class,
    ];

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->addFieldToTab(
                'Root.Main',
                NumericField::create('ProductsPerPage')
                    ->setTitle(_t(__CLASS__ . '.ProductsPerPage', 'Products Per Page')),
                'Content'
            );
        });

        return parent::getCMSFields();
    }

    /**
     * @return mixed
     */
    public function getProductList()
    {
        $categories = ProductCategory::get()->filter('ParentID', $this->data()->ID)->column('ID');
        $categories[] = $this->data()->ID;
        $products = Product::get()
            ->filterAny([
                'ParentID' => $categories,
            ]);

        $this->extend('updateProductList', $products);

        return $products;
    }
}
