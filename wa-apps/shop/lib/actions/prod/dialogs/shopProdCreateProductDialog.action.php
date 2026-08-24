<?php

class shopProdCreateProductDialogAction extends waViewAction
{
    public function execute()
    {
        $type_model = new shopTypeModel();
        $product_types = $type_model->getTypes();
        $active_product_type = reset($product_types);

        $this->view->assign([
            'product'             => $this->createEmptyProduct($product_types, $active_product_type),
            'product_types'       => $product_types,
            'active_product_type' => $active_product_type,
            'categories_tree'     => $this->getCategoriesTree(),
            'currencies'          => $this->getCurrencies(),
            'stocks'              => shopProdSkuAction::getStocks(),
        ]);
    }

    protected function createEmptyProduct(array $product_types, array $active_product_type)
    {
        $product = new shopProduct('new');

        // magic loading of skus
        $product['skus'];

        $product->setData('name', '');
        $product->setData('currency', wa('shop')->getConfig()->getCurrency());
        $product->setData('status', 1);
        $product->setData('type_id', key($product_types));
        $product->setData('order_multiplicity_factor', $active_product_type['order_multiplicity_factor']);
        $product->setData('stock_unit_id', $active_product_type['stock_unit_id']);
        $product->setData('base_unit_id', $active_product_type['base_unit_id']);
        $product->setData('stock_base_ratio', $active_product_type['stock_base_ratio']);
        $product->setData('order_count_min', $active_product_type['order_count_min']);
        $product->setData('order_count_step', $active_product_type['order_count_step']);
        $product_skus_model = new shopProductSkusModel();
        $empty_sku = $product_skus_model->getEmptyRow();
        $empty_sku['id'] = '-1';
        foreach (['price', 'primary_price', 'purchase_price', 'compare_price'] as $field) {
            $empty_sku[$field] = 0.0;
        }
        $product->setData('skus', [-1 => $empty_sku]);

        return $product;
    }

    protected function getCurrencies()
    {
        $model = new shopCurrencyModel();
        $currencies = $model->getCurrencies();
        $result = [];
        foreach ($currencies as $_currency) {
            $result[$_currency["code"]] = [
                "code" => $_currency["code"],
                "title" => $_currency["title"],
                "sign" => $_currency["sign"],
                "sign_html" => $_currency["sign_html"]
            ];
        }

        return $result;
    }
    protected function getCategoriesTree()
    {
        $category_model = new shopCategoryModel();
        $categories = $category_model->getFullTree('id, name, parent_id', true);
        $categories_tree = $category_model->buildNestedTree($categories);

        return $categories_tree;
    }
}
