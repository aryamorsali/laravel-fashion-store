<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Content\AboutController;
use App\Http\Controllers\Admin\Content\BannerController;
use App\Http\Controllers\Admin\Content\CategoryController as ContentCategoryController;
use App\Http\Controllers\Admin\Content\CommentController as ContentCommentController;
use App\Http\Controllers\Admin\Content\FAQController;
use App\Http\Controllers\Admin\Market\HomeBoxController;
use App\Http\Controllers\Admin\Content\MenuController;
use App\Http\Controllers\Admin\Content\PostController;
use App\Http\Controllers\Admin\Content\TagController;
use App\Http\Controllers\Admin\Market\AmazingSaleController;
use App\Http\Controllers\Admin\Market\BrandController;
use App\Http\Controllers\Admin\Market\CategoryController;
use App\Http\Controllers\Admin\Market\CommentController;
use App\Http\Controllers\Admin\Market\CommonDiscountController;
use App\Http\Controllers\Admin\Market\CoupanController;
use App\Http\Controllers\Admin\Market\DeliveryController;
use App\Http\Controllers\Admin\Market\GalleryController;
use App\Http\Controllers\Admin\Market\OrderController;
use App\Http\Controllers\Admin\Market\PaymentController;
use App\Http\Controllers\Admin\Market\ProductColorController;
use App\Http\Controllers\Admin\Market\ProductController;
use App\Http\Controllers\Admin\Market\ProductSizeController;
use App\Http\Controllers\Admin\Market\ProductVariantController;
use App\Http\Controllers\Admin\Market\PropertyController;
use App\Http\Controllers\Admin\Market\PropertyValueController;
use App\Http\Controllers\Admin\Market\WarehouseController;
use App\Http\Controllers\Admin\Market\WarehouseTransactionController;
use App\Http\Controllers\Admin\Market\WarehouseVariantController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Notification\EmailController;
use App\Http\Controllers\Admin\Notification\EmailFileController;
use App\Http\Controllers\Admin\Notification\SMSController;
use App\Http\Controllers\Admin\Ticket\AdminTicketController;
use App\Http\Controllers\Admin\Ticket\TicketCategoryController;
use App\Http\Controllers\Admin\Ticket\TicketController;
use App\Http\Controllers\Admin\Ticket\TicketPriorityController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Admin\User\CustomerController;
use App\Http\Controllers\Admin\User\PermissionController;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Customer\Content\ContentController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\Market\ShopController;
use App\Http\Controllers\Customer\Market\ProductController as MarketProductController;
use App\Http\Controllers\Customer\SalesProcess\AddressController;
use App\Http\Controllers\Customer\SalesProcess\CartController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Customer\SalesProcess\PaymentController as SalesProcessPaymentController;

use Illuminate\Support\Facades\Route;

// admin
Route::prefix('admin')->middleware(['auth', 'can:access-admin-panel'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.home');
    // market
    Route::prefix('market')->group(function () {
        // product_category
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.market.category.index')->middleware('can:view-product-category');
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.market.category.create')->middleware('can:create-product-category');
            Route::post('/store', [CategoryController::class, 'store'])->name('admin.market.category.store')->middleware('can:create-product-category');
            Route::get('/edit/{productCategory}', [CategoryController::class, 'edit'])->name('admin.market.category.edit')->middleware('can:update-product-category');
            Route::put('/update/{productCategory}', [CategoryController::class, 'update'])->name('admin.market.category.update')->middleware('can:update-product-category');
            Route::delete('/destroy/{productCategory}', [CategoryController::class, 'destroy'])->name('admin.market.category.destroy')->middleware('can:delete-product-category');
            Route::get('/status/{productCategory}', [CategoryController::class, 'status'])->name('admin.market.category.status')->middleware('can:update-product-category');
            Route::get('/show-in-menu/{productCategory}', [CategoryController::class, 'showInMenu'])->name('admin.market.category.show-in-menu')->middleware('can:update-product-category');
        });

        // home boxes
        Route::prefix('home-box')->group(function () {
            Route::get('/', [HomeBoxController::class, 'index'])->name('admin.market.home-box.index')->middleware('can:view-home-box');
            Route::get('/create', [HomeBoxController::class, 'create'])->name('admin.market.home-box.create')->middleware('can:create-home-box');
            Route::post('/store', [HomeBoxController::class, 'store'])->name('admin.market.home-box.store')->middleware('can:create-home-box');
            Route::get('/edit/{homeBox}', [HomeBoxController::class, 'edit'])->name('admin.market.home-box.edit')->middleware('can:update-home-box');
            Route::put('/update/{homeBox}', [HomeBoxController::class, 'update'])->name('admin.market.home-box.update')->middleware('can:update-home-box');
            Route::delete('/destroy/{homeBox}', [HomeBoxController::class, 'destroy'])->name('admin.market.home-box.destroy')->middleware('can:delete-home-box');
            Route::get('/status/{homeBox}', [HomeBoxController::class, 'status'])->name('admin.market.home-box.status')->middleware('can:update-home-box');
        });

        // brands
        Route::prefix('brand')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('admin.market.brand.index')->middleware('can:view-brand');
            Route::get('/create', [BrandController::class, 'create'])->name('admin.market.brand.create')->middleware('can:create-brand');
            Route::post('/store', [BrandController::class, 'store'])->name('admin.market.brand.store')->middleware('can:create-brand');
            Route::get('/edit/{brand}', [BrandController::class, 'edit'])->name('admin.market.brand.edit')->middleware('can:update-brand');
            Route::put('/update/{brand}', [BrandController::class, 'update'])->name('admin.market.brand.update')->middleware('can:update-brand');
            Route::delete('/destroy/{brand}', [BrandController::class, 'destroy'])->name('admin.market.brand.destroy')->middleware('can:delete-brand');
            Route::get('/status/{brand}', [BrandController::class, 'status'])->name('admin.market.brand.status')->middleware('can:update-brand');
        });

        // products
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.market.product.index')->middleware('can:view-product');
            Route::get('/create', [ProductController::class, 'create'])->name('admin.market.product.create')->middleware('can:create-product');
            Route::post('/store', [ProductController::class, 'store'])->name('admin.market.product.store')->middleware('can:create-product');
            Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('admin.market.product.edit')->middleware('can:update-product');
            Route::put('/update/{product}', [ProductController::class, 'update'])->name('admin.market.product.update')->middleware('can:update-product');
            Route::delete('/destroy/{product}', [ProductController::class, 'destroy'])->name('admin.market.product.destroy')->middleware('can:delete-product');

            // product gallery
            Route::prefix('/gallery')->middleware('can:manage-product-gallery')->group(function () {
                Route::get('/{product}', [GalleryController::class, 'index'])->name('admin.market.gallery.index');
                Route::get('/create/{product}', [GalleryController::class, 'create'])->name('admin.market.gallery.create');
                Route::post('/store/{product}', [GalleryController::class, 'store'])->name('admin.market.gallery.store');
                Route::delete('/destroy/{product}/{gallery}', [GalleryController::class, 'destroy'])->name('admin.market.gallery.destroy');
            });


            // product variant
            Route::prefix('variant')->group(function () {
                Route::get('/{product}', [ProductVariantController::class, 'index'])->name('admin.market.variant.index')->middleware('can:view-product-variant');
                Route::get('/create/{product}', [ProductVariantController::class, 'create'])->name('admin.market.variant.create')->middleware('can:create-product-variant');
                Route::post('/store/{product}', [ProductVariantController::class, 'store'])->name('admin.market.variant.store')->middleware('can:create-product-variant');
                Route::get('/edit/{product}/{variant}', [ProductVariantController::class, 'edit'])->name('admin.market.variant.edit')->middleware('can:update-product-variant');
                Route::put('/update/{product}/{variant}', [ProductVariantController::class, 'update'])->name('admin.market.variant.update')->middleware('can:update-product-variant');
                Route::delete('/destroy/{product}/{variant}', [ProductVariantController::class, 'destroy'])->name('admin.market.variant.destroy')->middleware('can:delete-product-variant');
                Route::delete('/destroyAllVariants/{product}', [ProductVariantController::class, 'destroyAllVariants'])->name('admin.market.variant.destroyAllVariants')->middleware('can:delete-product-variant');
            });
        });


        // product attributes
        Route::prefix('property')->group(function () {
            Route::get('/', [PropertyController::class, 'index'])->name('admin.market.property.index')->middleware('can:view-product-attribute');
            Route::get('/create', [PropertyController::class, 'create'])->name('admin.market.property.create')->middleware('can:create-product-attribute');
            Route::post('/store', [PropertyController::class, 'store'])->name('admin.market.property.store')->middleware('can:create-product-attribute');
            Route::get('/edit/{productAttribute}', [PropertyController::class, 'edit'])->name('admin.market.property.edit')->middleware('can:update-product-attribute');
            Route::put('/update/{productAttribute}', [PropertyController::class, 'update'])->name('admin.market.property.update')->middleware('can:update-product-attribute');
            Route::delete('/destroy/{productAttribute}', [PropertyController::class, 'destroy'])->name('admin.market.property.destroy')->middleware('can:delete-product-attribute');


            // product attribute values
            Route::prefix('value')->group(function () {
                Route::get('/{productAttribute}', [PropertyValueController::class, 'index'])->name('admin.market.value.index')->middleware('can:view-product-attribute-value');
                Route::get('/create/{productAttribute}', [PropertyValueController::class, 'create'])->name('admin.market.value.create')->middleware('can:create-product-attribute-value');
                Route::post('/store/{productAttribute}', [PropertyValueController::class, 'store'])->name('admin.market.value.store')->middleware('can:create-product-attribute-value');
                Route::get('/edit/{productAttribute}/{value}', [PropertyValueController::class, 'edit'])->name('admin.market.value.edit')->middleware('can:update-product-attribute-value');
                Route::put('/update/{productAttribute}/{value}', [PropertyValueController::class, 'update'])->name('admin.market.value.update')->middleware('can:update-product-attribute-value');
                Route::delete('/destroy/{productAttribute}/{value}', [PropertyValueController::class, 'destroy'])->name('admin.market.value.destroy')->middleware('can:delete-product-attribute-value');
            });
        });

        // colors
        Route::prefix('color')->group(function () {
            Route::get('/', [ProductColorController::class, 'index'])->name('admin.market.color.index')->middleware('can:view-color');
            Route::get('/create', [ProductColorController::class, 'create'])->name('admin.market.color.create')->middleware('can:create-color');
            Route::post('/store', [ProductColorController::class, 'store'])->name('admin.market.color.store')->middleware('can:create-color');
            Route::delete('/destroy/{color}', [ProductColorController::class, 'destroy'])->name('admin.market.color.destroy')->middleware('can:delete-color');
        });

        // sizes
        Route::prefix('size')->group(function () {
            Route::get('/', [ProductSizeController::class, 'index'])->name('admin.market.size.index')->middleware('can:view-size');
            Route::get('/create', [ProductSizeController::class, 'create'])->name('admin.market.size.create')->middleware('can:create-size');
            Route::post('/store', [ProductSizeController::class, 'store'])->name('admin.market.size.store')->middleware('can:create-size');
            Route::delete('/destroy/{size}', [ProductSizeController::class, 'destroy'])->name('admin.market.size.destroy')->middleware('can:delete-size');
        });

        //comments
        Route::prefix('comment')->middleware('can:manage-product-comments')->group(function () {
            Route::get('/', [CommentController::class, 'index'])->name('admin.market.comment.index');
            Route::get('/show/{comment}', [CommentController::class, 'show'])->name('admin.market.comment.show');
            Route::delete('/destroy/{comment}', [CommentController::class, 'destroy'])->name('admin.market.comment.destroy');
            Route::get('/approved/{comment}', [CommentController::class, 'approved'])->name('admin.market.comment.approved');
            Route::post('/answer/{comment}', [CommentController::class, 'answer'])->name('admin.market.comment.answer');
        });

        // warehouse
        Route::prefix('warehouse')->group(function () {
            Route::get('/', [WarehouseController::class, 'index'])->name('admin.market.warehouse.index')->middleware('can:view-warehouse');
            Route::get('/create', [WarehouseController::class, 'create'])->name('admin.market.warehouse.create')->middleware('can:create-warehouse');
            Route::post('/store', [WarehouseController::class, 'store'])->name('admin.market.warehouse.store')->middleware('can:create-warehouse');
            Route::get('/edit/{warehouse}', [WarehouseController::class, 'edit'])->name('admin.market.warehouse.edit')->middleware('can:update-warehouse');
            Route::put('/update/{warehouse}', [WarehouseController::class, 'update'])->name('admin.market.warehouse.update')->middleware('can:update-warehouse');
            Route::delete('/destroy/{warehouse}', [WarehouseController::class, 'destroy'])->name('admin.market.warehouse.destroy')->middleware('can:delete-warehouse');

            // warehouse_variant
            Route::prefix('{warehouse}/variants')->group(function () {
                Route::get('/', [WarehouseVariantController::class, 'index'])->name('admin.market.warehouse.variant.index')->middleware('can:view-inventory');
                Route::get('/create', [WarehouseVariantController::class, 'create'])->name('admin.market.warehouse.variant.create')->middleware('can:create-inventory');
                Route::post('/store', [WarehouseVariantController::class, 'store'])->name('admin.market.warehouse.variant.store')->middleware('can:create-inventory');
                Route::get('/edit/{warehouseVariant}', [WarehouseVariantController::class, 'edit'])->name('admin.market.warehouse.variant.edit')->middleware('can:update-inventory');
                Route::put('/update/{warehouseVariant}', [WarehouseVariantController::class, 'update'])->name('admin.market.warehouse.variant.update')->middleware('can:update-inventory');
            });
        });

        // warehouse_transaction
        Route::prefix('/transaction')->middleware('can:view-warehouse-transaction')->group(function () {
            Route::get('/', [WarehouseTransactionController::class, 'index'])->name('admin.market.transaction.index');
        });

        // delivery
        Route::prefix('delivery')->group(function () {
            Route::get('/', [DeliveryController::class, 'index'])->name('admin.market.delivery.index')->middleware('can:view-delivery');
            Route::get('/create', [DeliveryController::class, 'create'])->name('admin.market.delivery.create')->middleware('can:create-delivery');
            Route::post('/store', [DeliveryController::class, 'store'])->name('admin.market.delivery.store')->middleware('can:create-delivery');
            Route::get('/edit/{delivery}', [DeliveryController::class, 'edit'])->name('admin.market.delivery.edit')->middleware('can:update-delivery');
            Route::put('/update/{delivery}', [DeliveryController::class, 'update'])->name('admin.market.delivery.update')->middleware('can:update-delivery');
            Route::delete('/destroy/{delivery}', [DeliveryController::class, 'destroy'])->name('admin.market.delivery.destroy')->middleware('can:delete-delivery');
            Route::get('/status/{delivery}', [DeliveryController::class, 'status'])->name('admin.market.delivery.status')->middleware('can:update-delivery');
        });

        // order
        Route::prefix('/order')->middleware('can:manage-orders')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('admin.market.order.index');
            Route::get('/show/{order}', [OrderController::class, 'show'])->name('admin.market.order.show');
            Route::get('/new-order', [OrderController::class, 'newOrder'])->name('admin.market.order.newOrder');
            Route::get('/sending', [OrderController::class, 'sending'])->name('admin.market.order.sending');
            Route::get('/unpaid', [OrderController::class, 'unpaid'])->name('admin.market.order.unpaid');
            Route::get('/canceled', [OrderController::class, 'canceled'])->name('admin.market.order.canceled');
            Route::get('/returned', [OrderController::class, 'returned'])->name('admin.market.order.returned');
            Route::get('/show/{order}/detail', [OrderController::class, 'detail'])->name('admin.market.order.show.detail');

            Route::get('/change-send-status/{order}', [OrderController::class, 'changeSendStatus'])->name('admin.market.order.changeSendStatus');
            Route::get('/change-order-status/{order}', [OrderController::class, 'changeOrderStatus'])->name('admin.market.order.changeOrderStatus');
            Route::get('/cancel-order/{order}', [OrderController::class, 'cancelOrder'])->name('admin.market.order.cancelOrder');
        });
        // payment
        Route::prefix('payment')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('admin.market.payment.index')->middleware('can:manage-payments');
            Route::get('/show/{payment}', [PaymentController::class, 'show'])->name('admin.market.payment.show')->middleware('can:manage-payments');
            Route::get('/change-payment-status/{payment}', [PaymentController::class, 'changePaymentStatus'])->name('admin.market.payment.changePaymentStatus')->middleware('can:manage-payments');
            Route::get('/filter', [PaymentController::class, 'filter'])->name('admin.market.payment.filter')->middleware('can:manage-payments');
        });

        // discount
        Route::prefix('/discount')->group(function () {
            // coupon
            Route::prefix('/coupon')->group(function () {
                Route::get('/', [CoupanController::class, 'index'])->name('admin.market.discount.coupon')->middleware('can:view-coupon');
                Route::get('/create', [CoupanController::class, 'create'])->name('admin.market.discount.coupon.create')->middleware('can:create-coupon');
                Route::post('/store', [CoupanController::class, 'store'])->name('admin.market.discount.coupon.store')->middleware('can:create-coupon');
                Route::get('/edit/{coupon}', [CoupanController::class, 'edit'])->name('admin.market.discount.coupon.edit')->middleware('can:update-coupon');
                Route::put('/update/{coupon}', [CoupanController::class, 'update'])->name('admin.market.discount.coupon.update')->middleware('can:update-coupon');
                Route::delete('/destroy/{coupon}', [CoupanController::class, 'destroy'])->name('admin.market.discount.coupon.destroy')->middleware('can:delete-coupon');
            });
            // common_discount
            Route::prefix('/common-discount')->group(function () {
                Route::get('/', [CommonDiscountController::class, 'index'])->name('admin.market.discount.common_discount')->middleware('can:view-common-discount');
                Route::get('/create', [CommonDiscountController::class, 'create'])->name('admin.market.discount.common_discount.create')->middleware('can:create-common-discount');
                Route::post('/store', [CommonDiscountController::class, 'store'])->name('admin.market.discount.common_discount.store')->middleware('can:create-common-discount');
                Route::get('/edit/{common_discount}', [CommonDiscountController::class, 'edit'])->name('admin.market.discount.common_discount.edit')->middleware('can:update-common-discount');
                Route::put('/update/{common_discount}', [CommonDiscountController::class, 'update'])->name('admin.market.discount.common_discount.update')->middleware('can:update-common-discount');
                Route::delete('/destroy/{common_discount}', [CommonDiscountController::class, 'destroy'])->name('admin.market.discount.common_discount.destroy')->middleware('can:delete-common-discount');
            });

            // amzing_sale
            Route::prefix('/amazing-sale')->group(function () {
                Route::get('/', [AmazingSaleController::class, 'index'])->name('admin.market.discount.amazingSale')->middleware('can:view-amazing-sale');
                Route::get('/create', [AmazingSaleController::class, 'create'])->name('admin.market.discount.amazingSale.create')->middleware('can:create-amazing-sale');
                Route::post('/store', [AmazingSaleController::class, 'store'])->name('admin.market.discount.amazingSale.store')->middleware('can:create-amazing-sale');
                Route::get('/edit/{amazingSale}', [AmazingSaleController::class, 'edit'])->name('admin.market.discount.amazingSale.edit')->middleware('can:update-amazing-sale');
                Route::put('/update/{amazingSale}', [AmazingSaleController::class, 'update'])->name('admin.market.discount.amazingSale.update')->middleware('can:update-amazing-sale');
                Route::delete('/destroy/{amazingSale}', [AmazingSaleController::class, 'destroy'])->name('admin.market.discount.amazingSale.destroy')->middleware('can:delete-amazing-sale');
            });
        });
    });


    // user 
    Route::prefix('user')->middleware('owner')->group(function () {
        // customer  مشتریان
        Route::prefix('customer')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('admin.user.customer.index');
            Route::get('/create', [CustomerController::class, 'create'])->name('admin.user.customer.create');
            Route::post('/store', [CustomerController::class, 'store'])->name('admin.user.customer.store');
            Route::get('/edit/{customer}', [CustomerController::class, 'edit'])->name('admin.user.customer.edit');
            Route::put('/update/{customer}', [CustomerController::class, 'update'])->name('admin.user.customer.update');
            Route::delete('/destroy/{customer}', [CustomerController::class, 'destroy'])->name('admin.user.customer.destroy');
            Route::get('/activation/{customer}', [CustomerController::class, 'activation'])->name('admin.user.customer.activation');
        });
        // admin users
        Route::prefix('admin-user')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('admin.user.admin.index');
            Route::get('/create', [AdminUserController::class, 'create'])->name('admin.user.admin.create');
            Route::post('/store', [AdminUserController::class, 'store'])->name('admin.user.admin.store');
            Route::get('/edit/{admin}', [AdminUserController::class, 'edit'])->name('admin.user.admin.edit');
            Route::put('/update/{admin}', [AdminUserController::class, 'update'])->name('admin.user.admin.update');
            Route::delete('/destroy/{admin}', [AdminUserController::class, 'destroy'])->name('admin.user.admin.destroy');

            Route::get('/add-admin', [AdminUserController::class, 'addAdmin'])->name('admin.user.admin.add');
            Route::post('/add-admin', [AdminUserController::class, 'storeAddAdmin'])->name('admin.user.admin.add.store');

            Route::get('/activation/{admin}', [AdminUserController::class, 'activation'])->name('admin.user.admin.activation');
            Route::get('/role/{admin}', [AdminUserController::class, 'role'])->name('admin.user.admin.role');
            Route::post('/role/{admin}/store', [AdminUserController::class, 'roleStore'])->name('admin.user.admin.role.store');
            Route::get('/add-permission/{admin}', [AdminUserController::class, 'addPermission'])->name('admin.user.admin.add.permission');
            Route::post('/add-permission/{admin}/store', [AdminUserController::class, 'addPermissionStore'])->name('admin.user.admin.add.permission.store');
            Route::get('/permission/{admin}', [AdminUserController::class, 'permission'])->name('admin.user.admin.permission');
            Route::get('/revoke/{admin}', [AdminUserController::class, 'revokeAdmin'])->name('admin.user.admin.revokeAdmin');
        });

        // permission
        Route::prefix('permission')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('admin.user.permission.index');
            Route::get('/status/{permission}', [PermissionController::class, 'status'])->name('admin.user.permission.status');
        });

        // role
        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('admin.user.role.index');
            Route::get('/create', [RoleController::class, 'create'])->name('admin.user.role.create');
            Route::post('/store', [RoleController::class, 'store'])->name('admin.user.role.store');
            Route::get('/edit/{role}', [RoleController::class, 'edit'])->name('admin.user.role.edit');
            Route::put('/update/{role}', [RoleController::class, 'update'])->name('admin.user.role.update');
            Route::delete('/destroy/{role}', [RoleController::class, 'destroy'])->name('admin.user.role.destroy');
            Route::get('/status/{role}', [RoleController::class, 'status'])->name('admin.user.role.status');
            Route::get('/{role}/permission-form', [RoleController::class, 'permissionForm'])->name('admin.user.role.permission-form');
            Route::post('/{role}/permission/update', [RoleController::class, 'permissionUpdate'])->name('admin.user.role.permission.update');
        });
    });

    // notification 
    Route::prefix('notification')->group(function () {
        // email
        Route::prefix('email')->group(function () {

            Route::get('/', [EmailController::class, 'index'])->name('admin.notification.email.index')->middleware('can:view-email-notification');
            Route::get('/create', [EmailController::class, 'create'])->name('admin.notification.email.create')->middleware('can:create-email-notification');
            Route::post('/store', [EmailController::class, 'store'])->name('admin.notification.email.store')->middleware('can:create-email-notification');
            Route::get('/{email}/edit', [EmailController::class, 'edit'])->name('admin.notification.email.edit')->middleware('can:update-email-notification');
            Route::put('/{email}/update', [EmailController::class, 'update'])->name('admin.notification.email.update')->middleware('can:update-email-notification');
            Route::delete('/{email}/destroy', [EmailController::class, 'destroy'])->name('admin.notification.email.destroy')->middleware('can:delete-email-notification');
            Route::get('/{email}/send', [EmailController::class, 'send'])->name('admin.notification.email.send')->middleware('can:send-email-notification');

            // email files
            Route::prefix('{email}/file')->middleware('can:manage-email-notification-file')->group(function () {
                Route::get('/', [EmailFileController::class, 'index'])->name('admin.notification.email.file.index');
                Route::get('/create', [EmailFileController::class, 'create'])->name('admin.notification.email.file.create');
                Route::post('/store', [EmailFileController::class, 'store'])->name('admin.notification.email.file.store');
                Route::get('/{file}/edit', [EmailFileController::class, 'edit'])->name('admin.notification.email.file.edit');
                Route::put('/{file}/update', [EmailFileController::class, 'update'])->name('admin.notification.email.file.update');
                Route::delete('/{file}/destroy', [EmailFileController::class, 'destroy'])->name('admin.notification.email.file.destroy');
            });
        });
        // sms
        Route::prefix('sms')->group(function () {

            Route::get('/', [SMSController::class, 'index'])->name('admin.notification.sms.index')->middleware('can:view-sms-notification');
            Route::get('/create', [SMSController::class, 'create'])->name('admin.notification.sms.create')->middleware('can:create-sms-notification');
            Route::post('/store', [SMSController::class, 'store'])->name('admin.notification.sms.store')->middleware('can:create-sms-notification');
            Route::get('/{sms}/edit', [SMSController::class, 'edit'])->name('admin.notification.sms.edit')->middleware('can:update-sms-notification');
            Route::put('/{sms}/update', [SMSController::class, 'update'])->name('admin.notification.sms.update')->middleware('can:update-sms-notification');
            Route::delete('/{sms}/destroy', [SMSController::class, 'destroy'])->name('admin.notification.sms.destroy')->middleware('can:delete-sms-notification');
            Route::get('/{sms}/send', [SMSController::class, 'send'])->name('admin.notification.sms.send')->middleware('can:send-sms-notification');
        });
    });


    // tickets
    Route::prefix('/ticket')->middleware('can:manage-tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('admin.ticket.index');
        Route::get('/filter', [TicketController::class, 'filter'])->name('admin.ticket.filter');
        Route::get('/show/{ticket}', [TicketController::class, 'show'])->name('admin.ticket.show');
        Route::post('/answer/{ticket}', [TicketController::class, 'answer'])->name('admin.ticket.answer');
        Route::get('/change/{ticket}', [TicketController::class, 'change'])->name('admin.ticket.change');

        // ticket category
        Route::prefix('/category')->group(function () {
            Route::get('/', [TicketCategoryController::class, 'index'])->name('admin.ticket.category.index');
            Route::get('/create', [TicketCategoryController::class, 'create'])->name('admin.ticket.category.create');
            Route::post('/store', [TicketCategoryController::class, 'store'])->name('admin.ticket.category.store');
            Route::get('/edit/{category}', [TicketCategoryController::class, 'edit'])->name('admin.ticket.category.edit');
            Route::put('/update/{category}', [TicketCategoryController::class, 'update'])->name('admin.ticket.category.update');
            Route::delete('/destroy/{category}', [TicketCategoryController::class, 'destroy'])->name('admin.ticket.category.destroy');
            Route::get('/status/{category}', [TicketCategoryController::class, 'status'])->name('admin.ticket.category.status');
        });

        // ticket priority
        Route::prefix('/priority')->group(function () {
            Route::get('/', [TicketPriorityController::class, 'index'])->name('admin.ticket.priority.index');
            Route::get('/create', [TicketPriorityController::class, 'create'])->name('admin.ticket.priority.create');
            Route::post('/store', [TicketPriorityController::class, 'store'])->name('admin.ticket.priority.store');
            Route::get('/edit/{priority}', [TicketPriorityController::class, 'edit'])->name('admin.ticket.priority.edit');
            Route::put('/update/{priority}', [TicketPriorityController::class, 'update'])->name('admin.ticket.priority.update');
            Route::delete('/destroy/{priority}', [TicketPriorityController::class, 'destroy'])->name('admin.ticket.priority.destroy');
            Route::get('/status/{priority}', [TicketPriorityController::class, 'status'])->name('admin.ticket.priority.status');
        });

        // ticket admin
        Route::prefix('/admin')->group(function () {
            Route::get('/', [AdminTicketController::class, 'index'])->name('admin.ticket.admin.index');
            Route::get('/create', [AdminTicketController::class, 'create'])->name('admin.ticket.admin.create');
            Route::post('/store', [AdminTicketController::class, 'store'])->name('admin.ticket.admin.store');
            Route::get('/edit/{adminTicket}', [AdminTicketController::class, 'edit'])->name('admin.ticket.admin.edit');
            Route::put('/update/{adminTicket}', [AdminTicketController::class, 'update'])->name('admin.ticket.admin.update');
            Route::delete('/destroy/{adminTicket}', [AdminTicketController::class, 'destroy'])->name('admin.ticket.admin.destroy');
        });
    });

    // content
    Route::prefix('content')->group(function () {
        // category
        Route::prefix('category')->group(function () {
            Route::get('/', [ContentCategoryController::class, 'index'])->name('admin.content.category.index')->middleware('can:view-post-category');
            Route::get('/create', [ContentCategoryController::class, 'create'])->name('admin.content.category.create')->middleware('can:create-post-category');
            Route::post('/store', [ContentCategoryController::class, 'store'])->name('admin.content.category.store')->middleware('can:create-post-category');
            Route::get('/edit/{postCategory}', [ContentCategoryController::class, 'edit'])->name('admin.content.category.edit')->middleware('can:update-post-category');
            Route::put('/update/{postCategory}', [ContentCategoryController::class, 'update'])->name('admin.content.category.update')->middleware('can:update-post-category');
            Route::delete('/destroy/{postCategory}', [ContentCategoryController::class, 'destroy'])->name('admin.content.category.destroy')->middleware('can:delete-post-category');
            Route::get('/status/{postCategory}', [ContentCategoryController::class, 'status'])->name('admin.content.category.status')->middleware('can:update-post-category');
        });

        //post
        Route::prefix('post')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('admin.content.post.index')->middleware('can:view-post');
            Route::get('/create', [PostController::class, 'create'])->name('admin.content.post.create')->middleware('can:create-post');
            Route::post('/store', [PostController::class, 'store'])->name('admin.content.post.store')->middleware('can:create-post');
            Route::get('/edit/{post}', [PostController::class, 'edit'])->name('admin.content.post.edit')->middleware('can:update-post');
            Route::put('/update/{post}', [PostController::class, 'update'])->name('admin.content.post.update')->middleware('can:update-post');
            Route::delete('/destroy/{post}', [PostController::class, 'destroy'])->name('admin.content.post.destroy')->middleware('can:delete-post');
            Route::get('/status/{post}', [PostController::class, 'status'])->name('admin.content.post.status')->middleware('can:update-post');
            Route::get('/commentable/{post}', [PostController::class, 'commentable'])->name('admin.content.post.commentable')->middleware('can:update-post');
        });

        //tags
        Route::prefix('tag')->group(function () {
            Route::get('/', [TagController::class, 'index'])->name('admin.content.tag.index')->middleware('can:view-tag');
            Route::get('/create', [TagController::class, 'create'])->name('admin.content.tag.create')->middleware('can:create-tag');
            Route::post('/store', [TagController::class, 'store'])->name('admin.content.tag.store')->middleware('can:create-tag');
            Route::get('/edit/{tag}', [TagController::class, 'edit'])->name('admin.content.tag.edit')->middleware('can:update-tag');
            Route::put('/update/{tag}', [TagController::class, 'update'])->name('admin.content.tag.update')->middleware('can:update-tag');
            Route::delete('/destroy/{tag}', [TagController::class, 'destroy'])->name('admin.content.tag.destroy')->middleware('can:delete-tag');
        });


        //menu
        Route::prefix('menu')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('admin.content.menu.index')->middleware('can:view-menu');
            Route::get('/create', [MenuController::class, 'create'])->name('admin.content.menu.create')->middleware('can:create-menu');
            Route::post('/store', [MenuController::class, 'store'])->name('admin.content.menu.store')->middleware('can:create-menu');
            Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('admin.content.menu.edit')->middleware('can:update-menu');
            Route::put('/update/{menu}', [MenuController::class, 'update'])->name('admin.content.menu.update')->middleware('can:update-menu');
            Route::delete('/destroy/{menu}', [MenuController::class, 'destroy'])->name('admin.content.menu.destroy')->middleware('can:delete-menu');
            Route::get('/status/{menu}', [MenuController::class, 'status'])->name('admin.content.menu.status')->middleware('can:update-menu');
        });

        //faqs
        Route::prefix('faq')->group(function () {
            Route::get('/', [FAQController::class, 'index'])->name('admin.content.faq.index')->middleware('can:view-faq');
            Route::get('/create', [FAQController::class, 'create'])->name('admin.content.faq.create')->middleware('can:create-faq');
            Route::post('/store', [FAQController::class, 'store'])->name('admin.content.faq.store')->middleware('can:create-faq');
            Route::get('/edit/{faq}', [FAQController::class, 'edit'])->name('admin.content.faq.edit')->middleware('can:update-faq');
            Route::put('/update/{faq}', [FAQController::class, 'update'])->name('admin.content.faq.update')->middleware('can:update-faq');
            Route::delete('/destroy/{faq}', [FAQController::class, 'destroy'])->name('admin.content.faq.destroy')->middleware('can:delete-faq');
            Route::get('/status/{faq}', [FAQController::class, 'status'])->name('admin.content.faq.status')->middleware('can:update-faq');
        });

        //comments
        Route::prefix('comment')->middleware('can:manage-post-comments')->group(function () {
            Route::get('/', [ContentCommentController::class, 'index'])->name('admin.content.comment.index');
            Route::get('/show/{comment}', [ContentCommentController::class, 'show'])->name('admin.content.comment.show');
            Route::delete('/destroy/{comment}', [ContentCommentController::class, 'destroy'])->name('admin.content.comment.destroy');
            Route::get('/status/{comment}', [ContentCommentController::class, 'status'])->name('admin.content.comment.status');
            Route::get('/approved/{comment}', [ContentCommentController::class, 'approved'])->name('admin.content.comment.approved');
            Route::post('/answer/{comment}', [ContentCommentController::class, 'answer'])->name('admin.content.comment.answer');
        });
        // banner
        Route::prefix('banner')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('admin.content.banner.index')->middleware('can:view-banner');
            Route::get('/create', [BannerController::class, 'create'])->name('admin.content.banner.create')->middleware('can:create-banner');
            Route::post('/store', [BannerController::class, 'store'])->name('admin.content.banner.store')->middleware('can:create-banner');
            Route::get('/edit/{banner}', [BannerController::class, 'edit'])->name('admin.content.banner.edit')->middleware('can:update-banner');
            Route::put('/update/{banner}', [BannerController::class, 'update'])->name('admin.content.banner.update')->middleware('can:update-banner');
            Route::delete('/destroy/{banner}', [BannerController::class, 'destroy'])->name('admin.content.banner.destroy')->middleware('can:delete-banner');
            Route::get('/status/{banner}', [BannerController::class, 'status'])->name('admin.content.banner.status')->middleware('can:update-banner');
        });
        // about
        Route::prefix('about')->group(function () {
            Route::get('/', [AboutController::class, 'index'])->name('admin.content.about.index');
            Route::get('/edit/{about}', [AboutController::class, 'edit'])->name('admin.content.about.edit');
            Route::put('/update/{about}', [AboutController::class, 'update'])->name('admin.content.about.update');
        });
    });

    // settings
    Route::prefix('settings')->middleware('owner')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('admin.setting.index');
        Route::get('/edit/{setting}', [SettingController::class, 'edit'])->name('admin.setting.edit');
        Route::put('/update/{setting}', [SettingController::class, 'update'])->name('admin.setting.update');
        Route::get('/status/{setting}', [SettingController::class, 'status'])->name('admin.setting.status');
    });
});

require __DIR__ . '/auth.php';


// -------------------------------------------------------------------------
Route::namespace('customer')->middleware('CheckMaintenanceMode')->group(function () {

    // view shop
    Route::get('/', [HomeController::class, 'home'])->name('customer.home');
    Route::get('/shop/{category:slug?}', [ShopController::class, 'shop'])->name('customer.market.shop');


    // product detail
    Route::prefix('/product')->group(function () {
        Route::get('/{product:slug}', [MarketProductController::class, 'product'])->name('customer.market.product');
        Route::post('/{product:slug}/add-comment', [MarketProductController::class, 'addComment'])->name('customer.market.add-comment')->middleware(['auth', 'throttle:add-comment']);
    });

    // sales process
    Route::namespace('SalesProcess')->group(function () {
        Route::middleware('auth')->group(function () {

            //cart
            Route::get('/shoping-cart', [CartController::class, 'shopingCart'])->name('customer.sales-process.shoping-cart');
            Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('customer.sales-process.add-to-cart')->middleware('throttle:cart');
            Route::get('/remove-from-cart/{cartItem}', [CartController::class, 'removeFromCart'])->name('customer.sales-process.remove-from-cart')->middleware('throttle:cart');
            Route::post('/shoping-cart/update', [CartController::class, 'updateCart'])->name('customer.sales-process.update-shoping-cart');
            Route::post('/shoping-cart/coupon', [CartController::class, 'coupon'])->name('customer.sales-process.coupon')->middleware('throttle:coupon');
            Route::get('/update-header-cart', [CartController::class, 'updateHeaderCart'])->name('customer.sales-process.update-header-cart');

            //address
            Route::get('/address-and-delivery', [AddressController::class, 'addressAndDelivery'])->name('customer.sales-process.address-and-delivery');
            Route::post('/store-address', [AddressController::class, 'storeAddress'])->name('customer.sales-process.store-address')->middleware('throttle:address');
            Route::put('/update-address/{address}', [AddressController::class, 'updateAddress'])->name('customer.sales-process.update-address')->middleware('throttle:address');
            Route::get('/provinces/{province}/cities', [AddressController::class, 'getCities']);

            // payment
            Route::post('/payment', [SalesProcessPaymentController::class, 'payment'])->name('customer.sales-process.payment')->middleware('throttle:payment');
        });

        Route::get('/payment-callback/{order}/{payment}', [SalesProcessPaymentController::class, 'paymentCallBack'])->name('customer.sales-process.payment-call-back');
    });


    // like
    Route::post('/like/{type}/{id}', [LikeController::class, 'toggle'])->name('like.toggle')->middleware(['auth', 'throttle:like',]);

    // content
    Route::prefix('content')->group(function () {
        Route::get('/about', [ContentController::class, 'about'])->name('customer.content.about');
        Route::get('/contact', [ContentController::class, 'contact'])->name('customer.content.contact');
        Route::get('/blogs/{category:slug?}', [ContentController::class, 'blogs'])->name('customer.content.blogs');
        Route::get('/blog-detail/{post:slug}', [ContentController::class, 'blogDetail'])->name('customer.content.blog-detail');
        Route::post('blog-detail/{post:slug}/add-comment', [ContentController::class, 'addComment'])->name('customer.content.blog-detail.add-comment')->middleware(['auth', 'throttle:add-comment']);
    });
});
