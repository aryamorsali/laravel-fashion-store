<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Content\BannerController;
use App\Http\Controllers\Admin\Content\CategoryController as ContentCategoryController;
use App\Http\Controllers\Admin\Content\CommentController as ContentCommentController;
use App\Http\Controllers\Admin\Content\FAQController;
use App\Http\Controllers\Admin\Content\FaqsController;
use App\Http\Controllers\Admin\Market\HomeBoxController;
use App\Http\Controllers\Admin\Content\MenuController;
use App\Http\Controllers\Admin\Content\PostController;
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
use App\Http\Controllers\Admin\Ticket\AdminTicketController;
use App\Http\Controllers\Admin\Ticket\TicketCategoryController;
use App\Http\Controllers\Admin\Ticket\TicketController;
use App\Http\Controllers\Admin\Ticket\TicketPriorityController;
use App\Http\Controllers\Admin\User\CustomerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// admin
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.home');
    // market
    Route::prefix('market')->group(function () {
        // product_category
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.market.category.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.market.category.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('admin.market.category.store');
            Route::get('/edit/{productCategory}', [CategoryController::class, 'edit'])->name('admin.market.category.edit');
            Route::put('/update/{productCategory}', [CategoryController::class, 'update'])->name('admin.market.category.update');
            Route::delete('/destroy/{productCategory}', [CategoryController::class, 'destroy'])->name('admin.market.category.destroy');
            Route::get('/status/{productCategory}', [CategoryController::class, 'status'])->name('admin.market.category.status');
            Route::get('/show-in-menu/{productCategory}', [CategoryController::class, 'showInMenu'])->name('admin.market.category.show-in-menu');
        });

        // home boxes
        Route::prefix('home-box')->group(function () {
            Route::get('/', [HomeBoxController::class, 'index'])->name('admin.market.home-box.index');
            Route::get('/create', [HomeBoxController::class, 'create'])->name('admin.market.home-box.create');
            Route::post('/store', [HomeBoxController::class, 'store'])->name('admin.market.home-box.store');
            Route::get('/edit/{homeBox}', [HomeBoxController::class, 'edit'])->name('admin.market.home-box.edit');
            Route::put('/update/{homeBox}', [HomeBoxController::class, 'update'])->name('admin.market.home-box.update');
            Route::delete('/destroy/{homeBox}', [HomeBoxController::class, 'destroy'])->name('admin.market.home-box.destroy');
            Route::get('/status/{homeBox}', [HomeBoxController::class, 'status'])->name('admin.market.home-box.status');
        });

        // brands
        Route::prefix('brand')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('admin.market.brand.index');
            Route::get('/create', [BrandController::class, 'create'])->name('admin.market.brand.create');
            Route::post('/store', [BrandController::class, 'store'])->name('admin.market.brand.store');
            Route::get('/edit/{brand}', [BrandController::class, 'edit'])->name('admin.market.brand.edit');
            Route::put('/update/{brand}', [BrandController::class, 'update'])->name('admin.market.brand.update');
            Route::delete('/destroy/{brand}', [BrandController::class, 'destroy'])->name('admin.market.brand.destroy');
            Route::get('/status/{brand}', [BrandController::class, 'status'])->name('admin.market.brand.status');
        });

        // products
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.market.product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('admin.market.product.create');
            Route::post('/store', [ProductController::class, 'store'])->name('admin.market.product.store');
            Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('admin.market.product.edit');
            Route::put('/update/{product}', [ProductController::class, 'update'])->name('admin.market.product.update');
            Route::delete('/destroy/{product}', [ProductController::class, 'destroy'])->name('admin.market.product.destroy');

            // product gallery
            Route::get('/gallery/{product}', [GalleryController::class, 'index'])->name('admin.market.gallery.index');
            Route::get('/gallery/create/{product}', [GalleryController::class, 'create'])->name('admin.market.gallery.create');
            Route::post('/gallery/store/{product}', [GalleryController::class, 'store'])->name('admin.market.gallery.store');
            Route::delete('/gallery/destroy/{product}/{gallery}', [GalleryController::class, 'destroy'])->name('admin.market.gallery.destroy');

            // product variant
            Route::prefix('variant')->group(function () {
                Route::get('/{product}', [ProductVariantController::class, 'index'])->name('admin.market.variant.index');
                Route::get('/create/{product}', [ProductVariantController::class, 'create'])->name('admin.market.variant.create');
                Route::post('/store/{product}', [ProductVariantController::class, 'store'])->name('admin.market.variant.store');
                Route::get('/edit/{product}/{variant}', [ProductVariantController::class, 'edit'])->name('admin.market.variant.edit');
                Route::put('/update/{product}/{variant}', [ProductVariantController::class, 'update'])->name('admin.market.variant.update');
                Route::delete('/destroy/{product}/{variant}', [ProductVariantController::class, 'destroy'])->name('admin.market.variant.destroy');
                Route::delete('/destroyAllVariants/{product}', [ProductVariantController::class, 'destroyAllVariants'])->name('admin.market.variant.destroyAllVariants');
            });
        });


        // product attributes
        Route::prefix('property')->group(function () {
            Route::get('/', [PropertyController::class, 'index'])->name('admin.market.property.index');
            Route::get('/create', [PropertyController::class, 'create'])->name('admin.market.property.create');
            Route::post('/store', [PropertyController::class, 'store'])->name('admin.market.property.store');
            Route::get('/edit/{productAttribute}', [PropertyController::class, 'edit'])->name('admin.market.property.edit');
            Route::put('/update/{productAttribute}', [PropertyController::class, 'update'])->name('admin.market.property.update');
            Route::delete('/destroy/{productAttribute}', [PropertyController::class, 'destroy'])->name('admin.market.property.destroy');


            // product attribute values
            Route::prefix('value')->group(function () {
                Route::get('/{productAttribute}', [PropertyValueController::class, 'index'])->name('admin.market.value.index');
                Route::get('/create/{productAttribute}', [PropertyValueController::class, 'create'])->name('admin.market.value.create');
                Route::post('/store/{productAttribute}', [PropertyValueController::class, 'store'])->name('admin.market.value.store');
                Route::get('/edit/{productAttribute}/{value}', [PropertyValueController::class, 'edit'])->name('admin.market.value.edit');
                Route::put('/update/{productAttribute}/{value}', [PropertyValueController::class, 'update'])->name('admin.market.value.update');
                Route::delete('/destroy/{productAttribute}/{value}', [PropertyValueController::class, 'destroy'])->name('admin.market.value.destroy');
            });
        });

        // colors
        Route::prefix('color')->group(function () {
            Route::get('/', [ProductColorController::class, 'index'])->name('admin.market.color.index');
            Route::get('/create', [ProductColorController::class, 'create'])->name('admin.market.color.create');
            Route::post('/store', [ProductColorController::class, 'store'])->name('admin.market.color.store');
            Route::delete('/destroy/{color}', [ProductColorController::class, 'destroy'])->name('admin.market.color.destroy');
        });

        // sizes
        Route::prefix('size')->group(function () {
            Route::get('/', [ProductSizeController::class, 'index'])->name('admin.market.size.index');
            Route::get('/create', [ProductSizeController::class, 'create'])->name('admin.market.size.create');
            Route::post('/store', [ProductSizeController::class, 'store'])->name('admin.market.size.store');
            Route::delete('/destroy/{size}', [ProductSizeController::class, 'destroy'])->name('admin.market.size.destroy');
        });

        //comments
        Route::prefix('comment')->group(function () {
            Route::get('/', [CommentController::class, 'index'])->name('admin.market.comment.index');
            Route::get('/show/{comment}', [CommentController::class, 'show'])->name('admin.market.comment.show');
            Route::delete('/destroy/{comment}', [CommentController::class, 'destroy'])->name('admin.market.comment.destroy');
            Route::get('/status/{comment}', [CommentController::class, 'status'])->name('admin.market.comment.status');
            Route::get('/approved/{comment}', [CommentController::class, 'approved'])->name('admin.market.comment.approved');
            Route::post('/answer/{comment}', [CommentController::class, 'answer'])->name('admin.market.comment.answer');
        });

        // warehouse
        Route::prefix('warehouse')->group(function () {
            Route::get('/', [WarehouseController::class, 'index'])->name('admin.market.warehouse.index');
            Route::get('/create', [WarehouseController::class, 'create'])->name('admin.market.warehouse.create');
            Route::post('/store', [WarehouseController::class, 'store'])->name('admin.market.warehouse.store');
            Route::get('/edit/{warehouse}', [WarehouseController::class, 'edit'])->name('admin.market.warehouse.edit');
            Route::put('/update/{warehouse}', [WarehouseController::class, 'update'])->name('admin.market.warehouse.update');
            Route::delete('/destroy/{warehouse}', [WarehouseController::class, 'destroy'])->name('admin.market.warehouse.destroy');

            // warehouse_variant
            Route::prefix('{warehouse}/variants')->group(function () {
                Route::get('/', [WarehouseVariantController::class, 'index'])->name('admin.market.warehouse.variant.index');
                Route::get('/create', [WarehouseVariantController::class, 'create'])->name('admin.market.warehouse.variant.create');
                Route::post('/store', [WarehouseVariantController::class, 'store'])->name('admin.market.warehouse.variant.store');
                Route::get('/edit/{warehouseVariant}', [WarehouseVariantController::class, 'edit'])->name('admin.market.warehouse.variant.edit');
                Route::put('/update/{warehouseVariant}', [WarehouseVariantController::class, 'update'])->name('admin.market.warehouse.variant.update');
            });
        });

        // warehouse_transaction
        Route::prefix('/transaction')->group(function () {
            Route::get('/', [WarehouseTransactionController::class, 'index'])->name('admin.market.transaction.index');
        });

        // delivery
        Route::prefix('delivery')->group(function () {
            Route::get('/', [DeliveryController::class, 'index'])->name('admin.market.delivery.index');
            Route::get('/create', [DeliveryController::class, 'create'])->name('admin.market.delivery.create');
            Route::post('/store', [DeliveryController::class, 'store'])->name('admin.market.delivery.store');
            Route::get('/edit/{delivery}', [DeliveryController::class, 'edit'])->name('admin.market.delivery.edit');
            Route::put('/update/{delivery}', [DeliveryController::class, 'update'])->name('admin.market.delivery.update');
            Route::delete('/destroy/{delivery}', [DeliveryController::class, 'destroy'])->name('admin.market.delivery.destroy');
            Route::get('/status/{delivery}', [DeliveryController::class, 'status'])->name('admin.market.delivery.status');
        });

        // order
        Route::prefix('/order')->group(function () {
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
            Route::get('/', [PaymentController::class, 'index'])->name('admin.market.payment.index');
            Route::get('/show/{payment}', [PaymentController::class, 'show'])->name('admin.market.payment.show');
            Route::get('/change-payment-status/{payment}', [PaymentController::class, 'changePaymentStatus'])->name('admin.market.payment.changePaymentStatus');
            Route::get('/filter', [PaymentController::class, 'filter'])->name('admin.market.payment.filter');
        });

        // discount
        Route::prefix('/discount')->group(function () {
            // coupon
            Route::prefix('/coupon')->group(function () {
                Route::get('/', [CoupanController::class, 'index'])->name('admin.market.discount.coupon');
                Route::get('/create', [CoupanController::class, 'create'])->name('admin.market.discount.coupon.create');
                Route::post('/store', [CoupanController::class, 'store'])->name('admin.market.discount.coupon.store');
                Route::get('/edit/{coupon}', [CoupanController::class, 'edit'])->name('admin.market.discount.coupon.edit');
                Route::put('/update/{coupon}', [CoupanController::class, 'update'])->name('admin.market.discount.coupon.update');
                Route::delete('/destroy/{coupon}', [CoupanController::class, 'destroy'])->name('admin.market.discount.coupon.destroy');
            });
            // common_discount
            Route::prefix('/common-discount')->group(function () {
                Route::get('/', [CommonDiscountController::class, 'index'])->name('admin.market.discount.common_discount');
                Route::get('/create', [CommonDiscountController::class, 'create'])->name('admin.market.discount.common_discount.create');
                Route::post('/store', [CommonDiscountController::class, 'store'])->name('admin.market.discount.common_discount.store');
                Route::get('/edit/{common_discount}', [CommonDiscountController::class, 'edit'])->name('admin.market.discount.common_discount.edit');
                Route::put('/update/{common_discount}', [CommonDiscountController::class, 'update'])->name('admin.market.discount.common_discount.update');
                Route::delete('/destroy/{common_discount}', [CommonDiscountController::class, 'destroy'])->name('admin.market.discount.common_discount.destroy');
            });

            // amzing_sale
            Route::prefix('/amazing-sale')->group(function () {
                Route::get('/', [AmazingSaleController::class, 'index'])->name('admin.market.discount.amazingSale');
                Route::get('/create', [AmazingSaleController::class, 'create'])->name('admin.market.discount.amazingSale.create');
                Route::post('/store', [AmazingSaleController::class, 'store'])->name('admin.market.discount.amazingSale.store');
                Route::get('/edit/{amazingSale}', [AmazingSaleController::class, 'edit'])->name('admin.market.discount.amazingSale.edit');
                Route::put('/update/{amazingSale}', [AmazingSaleController::class, 'update'])->name('admin.market.discount.amazingSale.update');
                Route::delete('/destroy/{amazingSale}', [AmazingSaleController::class, 'destroy'])->name('admin.market.discount.amazingSale.destroy');
            });
        });
    });


    // user 
    Route::prefix('user')->group(function () {
        // customer  مشتریان
        Route::prefix('customer')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('admin.user.customer.index');
            Route::get('/create', [CustomerController::class, 'create'])->name('admin.user.customer.create');
            Route::post('/store', [CustomerController::class, 'store'])->name('admin.user.customer.store');
            Route::get('/edit/{customer}', [CustomerController::class, 'edit'])->name('admin.user.customer.edit');
            Route::put('/update/{customer}', [CustomerController::class, 'update'])->name('admin.user.customer.update');
            Route::delete('/destroy/{customer}', [CustomerController::class, 'destroy'])->name('admin.user.customer.destroy');
            Route::get('/status/{customer}', [CustomerController::class, 'status'])->name('admin.user.customer.status');
            Route::get('/activation/{customer}', [CustomerController::class, 'activation'])->name('admin.user.customer.activation');
        });
    });


    // tickets
    Route::prefix('/ticket')->group(function () {
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
            Route::get('/', [ContentCategoryController::class, 'index'])->name('admin.content.category.index');
            Route::get('/create', [ContentCategoryController::class, 'create'])->name('admin.content.category.create');
            Route::post('/store', [ContentCategoryController::class, 'store'])->name('admin.content.category.store');
            Route::get('/edit/{postCategory}', [ContentCategoryController::class, 'edit'])->name('admin.content.category.edit');
            Route::put('/update/{postCategory}', [ContentCategoryController::class, 'update'])->name('admin.content.category.update');
            Route::delete('/destroy/{postCategory}', [ContentCategoryController::class, 'destroy'])->name('admin.content.category.destroy');
            Route::get('/status/{postCategory}', [ContentCategoryController::class, 'status'])->name('admin.content.category.status');
        });

        //post
        Route::prefix('post')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('admin.content.post.index');
            Route::get('/create', [PostController::class, 'create'])->name('admin.content.post.create');
            Route::post('/store', [PostController::class, 'store'])->name('admin.content.post.store');
            Route::get('/edit/{post}', [PostController::class, 'edit'])->name('admin.content.post.edit');
            Route::put('/update/{post}', [PostController::class, 'update'])->name('admin.content.post.update');
            Route::delete('/destroy/{post}', [PostController::class, 'destroy'])->name('admin.content.post.destroy');
            Route::get('/status/{post}', [PostController::class, 'status'])->name('admin.content.post.status');
            Route::get('/commentable/{post}', [PostController::class, 'commentable'])->name('admin.content.post.commentable');
        });


        //menu
        Route::prefix('menu')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('admin.content.menu.index');
            Route::get('/create', [MenuController::class, 'create'])->name('admin.content.menu.create');
            Route::post('/store', [MenuController::class, 'store'])->name('admin.content.menu.store');
            Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('admin.content.menu.edit');
            Route::put('/update/{menu}', [MenuController::class, 'update'])->name('admin.content.menu.update');
            Route::delete('/destroy/{menu}', [MenuController::class, 'destroy'])->name('admin.content.menu.destroy');
            Route::get('/status/{menu}', [MenuController::class, 'status'])->name('admin.content.menu.status');
        });

        //faqs
        Route::prefix('faq')->group(function () {
            Route::get('/', [FAQController::class, 'index'])->name('admin.content.faq.index');
            Route::get('/create', [FAQController::class, 'create'])->name('admin.content.faq.create');
            Route::post('/store', [FAQController::class, 'store'])->name('admin.content.faq.store');
            Route::get('/edit/{faq}', [FAQController::class, 'edit'])->name('admin.content.faq.edit');
            Route::put('/update/{faq}', [FAQController::class, 'update'])->name('admin.content.faq.update');
            Route::delete('/destroy/{faq}', [FAQController::class, 'destroy'])->name('admin.content.faq.destroy');
            Route::get('/status/{faq}', [FAQController::class, 'status'])->name('admin.content.faq.status');
        });

        //comments
        Route::prefix('comment')->group(function () {
            Route::get('/', [ContentCommentController::class, 'index'])->name('admin.content.comment.index');
            Route::get('/show/{comment}', [ContentCommentController::class, 'show'])->name('admin.content.comment.show');
            Route::delete('/destroy/{comment}', [ContentCommentController::class, 'destroy'])->name('admin.content.comment.destroy');
            Route::get('/status/{comment}', [ContentCommentController::class, 'status'])->name('admin.content.comment.status');
            Route::get('/approved/{comment}', [ContentCommentController::class, 'approved'])->name('admin.content.comment.approved');
            Route::post('/answer/{comment}', [ContentCommentController::class, 'answer'])->name('admin.content.comment.answer');
        });
        // banner
        Route::prefix('banner')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('admin.content.banner.index');
            Route::get('/create', [BannerController::class, 'create'])->name('admin.content.banner.create');
            Route::post('/store', [BannerController::class, 'store'])->name('admin.content.banner.store');
            Route::get('/edit/{banner}', [BannerController::class, 'edit'])->name('admin.content.banner.edit');
            Route::put('/update/{banner}', [BannerController::class, 'update'])->name('admin.content.banner.update');
            Route::delete('/destroy/{banner}', [BannerController::class, 'destroy'])->name('admin.content.banner.destroy');
            Route::get('/status/{banner}', [BannerController::class, 'status'])->name('admin.content.banner.status');
        });
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
