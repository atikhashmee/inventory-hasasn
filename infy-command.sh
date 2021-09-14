#admin rollback
# php artisan infyom:rollback WareHouse scaffold
# php artisan infyom:rollback Shop scaffold
# php artisan infyom:rollback Category scaffold
# php artisan infyom:rollback Menufacture scaffold
# php artisan infyom:rollback Supplier scaffold
# php artisan infyom:rollback Brand scaffold
# php artisan infyom:rollback Product scaffold
# php artisan infyom:rollback Stock scaffold
# php artisan infyom:rollback Unit scaffold
# php artisan infyom:rollback Challan scaffold
#php artisan infyom:rollback Quotation scaffold
php artisan infyom:rollback QuotationItem scaffold

#admin adding
# php artisan infyom:scaffold WareHouse --fieldsFile='./public/fields_sample/warehouse.json'
# php artisan infyom:scaffold Shop --fieldsFile='./public/fields_sample/shop.json'
# php artisan infyom:scaffold Category --fieldsFile='./public/fields_sample/category.json'
# php artisan infyom:scaffold Menufacture --fieldsFile='./public/fields_sample/menufacture.json'
# php artisan infyom:scaffold Supplier --fieldsFile='./public/fields_sample/supplier_new.json'
# php artisan infyom:scaffold Brand --fieldsFile='./public/fields_sample/brand.json'
# php artisan infyom:scaffold Product --fieldsFile='./public/fields_sample/product.json'
# php artisan infyom:scaffold Stock --fieldsFile='./public/fields_sample/stock.json'
# php artisan infyom:scaffold Unit --fieldsFile='./public/fields_sample/unit.json'
#php artisan infyom:scaffold Quotation --fieldsFile='./public/fields_sample/quotation.json'
php artisan infyom:model QuotationItem --fieldsFile='./public/fields_sample/quotation_items.json'
php artisan infyom:migration QuotationItem --fieldsFile='./public/fields_sample/quotation_items.json'