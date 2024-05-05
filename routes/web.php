<?php

use App\Http\Controllers\Amenitie;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Agent\AgentController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\CompareController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Agent\AgentPropertyController;
use App\Http\Controllers\Backend\PropertyTypeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User Frontend Route

Route::get('/', [UserController::class, 'index']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/user/profile', [UserController::class, 'UserProfile'])->name('user.profile');
    Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');
    Route::get('/user/change/password', [UserController::class, 'UserChangePassword'])->name('user.change.password');
    Route::post('/user/password/update', [UserController::class, 'UserPasswordUpdate'])->name('user.password.update');
    Route::get('/user/schedule/request', [UserController::class, 'userScheduleRequest'])->name('user.schedule.request');

    //User Live Chat Route
    Route::get('/user/live/chat', [UserController::class, 'liveChat'])->name('live.chat');
   

  //user wishlist route
  Route::get('/user-wishlist', [WishlistController::class, 'userWishList'])->name('user.wishlist');
  Route::get('/get-wishlist-property', [WishlistController::class, 'GetWishListProperty']);
  Route::get('/wishlist-remove/{id}', [WishlistController::class, 'WishListRemove']);

  //User Compare Route
  Route::get('/user/compare', [CompareController::class, 'userCompare'])->name('user.compare');
  Route::get('/get-compare-property', [CompareController::class, 'GetCompareProperty']);
  Route::get('/compare-remove/{id}', [CompareController::class, 'CompareRemove']);


});

require __DIR__.'/auth.php';


//Admin route
Route::middleware(['auth','roles:admin'])->group(function(){

    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');

    Route::get('/admin/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'adminUpdatePassword'])->name('admin.update.password');

    //Admin Property Type route

    Route::controller(PropertyTypeController::class)->group(function(){

        Route::get('/all/type', 'AllType')->name('all.type');
        Route::get('/add/type', 'AddType')->name('add.type');
        Route::post('/store/type', 'StoreType')->name('store.type');
        Route::get('/edit/type/{id}', 'EditType')->name('edit.type');
        Route::post('/update/type', 'UpdateType')->name('update.type');
        Route::get('/delete/type/{id}', 'DeleteType')->name('delete.type');
    });

    //Admin Amenitie route

    Route::controller(Amenitie::class)->group(function(){

        Route::get('/all/amenitie', 'AllAmenitie')->name('all.amenitie');
        Route::get('/add/amenitie', 'AddAmenitie')->name('add.amenitie');
        Route::post('/store/amenitie', 'StoreAmenitie')->name('store.amenitie');
        Route::get('/edit/amenitie/{id}', 'EditAmenitie')->name('edit.amenitie');
        Route::post('/update/amenitie', 'UpdateAmenitie')->name('update.amenitie');
        Route::get('/delete/amenitie/{id}', 'DeleteAmenitie')->name('delete.amenitie');
    
    });


    //Admin Property route

    Route::controller(PropertyController::class)->group(function(){

        Route::get('/all/property', 'allProperty')->name('all.property');
        Route::get('/add/property', 'addProperty')->name('add.property');
        Route::post('/store/property', 'storeProperty')->name('store.property');
        Route::get('/edit/property/{id}', 'editProperty')->name('edit.property');
        Route::post('/update/property', 'updateProperty')->name('update.property');
        
        Route::post('/update/property/thumbnail', 'updatePropertyThumbnail')->name('update.property.thumbnail');
        Route::post('/update/property/multiimage', 'updatePropertyMultiimage')->name('update.property.multiimage');
        Route::get('/property/multiimg/delete/{id}', 'PropertyMultiimageDelete')->name('property.multiimg.delete');
        Route::post('/store/new/multiimg', 'StoreNewMultiImage')->name('store.new.multiimage');
        Route::post('/update/property/facility', 'UpdatePropertyFacility')->name('update.property.facility');

        Route::get('/delete/property/{id}', 'DeleteProperty')->name('delete.property');
        Route::get('/details/property/{id}', 'DetailsProperty')->name('details.property');
        Route::post('/inactive/property', 'InactiveProperty')->name('inactive.property');
        Route::post('/active/property', 'ActiveProperty')->name('active.property');

        Route::get('/admin/package/history', 'AdminPackageHistory')->name('admins.package.history');
        Route::get('/package/invoice/{id}', 'PackageInvoice')->name('package.invoice');
        Route::get('/package/invoice/{id}', 'PackageInvoice')->name('package.invoice');

        Route::get('/admin/property/message', 'adminPropertyMessage')->name('admin.property.message');
        Route::get('/admin/message/details/{id}', 'adminMessageDetails')->name('admin.message.details');
        
    });


    //Agent all route from admin
    Route::controller(AdminController::class)->group(function(){
        Route::get('/all/agent', 'allAgent')->name('all.agent');
        Route::get('/add/agent', 'addAgent')->name('add.agent');
        Route::post('/store/agent', 'storeAgent')->name('store.agent');
        Route::get('/edit/agent/{id}', 'editAgent')->name('edit.agent');
        Route::post('/update/agent', 'updateAgent')->name('update.agent');
        Route::get('/delete/agent/{id}', 'deleteAgent')->name('delete.agent');
        Route::get('/changeStatus', 'changeStatus');
    });

     // State all route from admin
     Route::controller(StateController::class)->group(function(){
        Route::get('/all/state', 'allState')->name('all.state');
        Route::get('/add/state', 'addState')->name('add.state');
        Route::post('/store/state', 'storeState')->name('store.state');
        Route::get('/edit/state/{id}', 'editState')->name('edit.state');
        Route::post('/update/state', 'updateState')->name('update.state');
        Route::get('/delete/state/{id}', 'deleteState')->name('delete.state');
    });

      // Testimonial all route from admin
      Route::controller(TestimonialController::class)->group(function(){
        Route::get('/all/testimonial', 'allTestimonial')->name('all.testimonial');
        Route::get('/add/testimonial', 'addTestimonial')->name('add.testimonial');
        Route::post('/store/testimonial', 'storeTestimonial')->name('store.testimonial');
        Route::get('/edit/testimonial/{id}', 'editTestimonial')->name('edit.testimonial');
        Route::post('/update/testimonial', 'updateTestimonial')->name('update.testimonial');
        Route::get('/delete/testimonial/{id}', 'deleteTestimonial')->name('delete.testimonial');
    });


      // Blog Category all route from admin
      Route::controller(BlogController::class)->group(function(){
        Route::get('/all/blog/category', 'allBlogCategory')->name('all.blog.category');
        Route::post('/store/category', 'storeCategory')->name('store.category');
        Route::get('/blog/category/{id}', 'editCategory');
        Route::post('/update/category', 'updateCategory')->name('update.category');

        Route::get('/delete/category/{id}', 'deleteCategory')->name('delete.category');
    });

    // Post all route from admin
    Route::controller(BlogController::class)->group(function(){
    Route::get('/all/post', 'allPost')->name('all.post');
    Route::get('/add/post', 'addPost')->name('add.post');
    Route::post('/store/post', 'storePost')->name('store.post');
    Route::get('/edit/post/{id}', 'editPost')->name('edit.post');

    Route::post('/update/post', 'updatePost')->name('update.post');
    Route::get('/delete/post/{id}', 'deletePost')->name('delete.post');
    });

     // SMTP Setting all route from admin
     Route::controller(SettingController::class)->group(function(){
        Route::get('/smtp/setting', 'smtpSetting')->name('smtp.setting');  
        Route::post('/update/smtp/setting', 'updateSmtpSetting')->name('update.smtp.setting');  
    });

    // Site Setting all route from admin
    Route::controller(SettingController::class)->group(function(){
        Route::get('/site/setting', 'siteSetting')->name('site.setting');  
        Route::post('/update/site/setting', 'updateSiteSetting')->name('update.site.setting');  
    });

    // Permission all route from admin
    Route::controller(RoleController::class)->group(function(){
        Route::get('/all/permission', 'allPermission')->name('all.permission');  
        Route::get('/add/permission', 'addPermission')->name('add.permission');    
        Route::post('/store/permission', 'storePermission')->name('store.permission');    
        Route::get('/edit/permission/{id}', 'editPermission')->name('edit.permission'); 
        Route::post('/update/permission', 'updatePermission')->name('update.permission'); 
        Route::get('/delete/permission/{id}', 'deletePermission')->name('delete.permission');  

        Route::get('/import/permission', 'importPermission')->name('import.permission');    
        Route::get('/export', 'export')->name('export');    
        Route::post('/import', 'import')->name('import');    

    });

     // Roles all route from admin
     Route::controller(RoleController::class)->group(function(){
        Route::get('/all/roles', 'allRoles')->name('all.roles');  
        Route::get('/add/roles', 'addRoles')->name('add.roles');    
        Route::post('/store/roles', 'storeRoles')->name('store.roles');    
        Route::get('/edit/roles/{id}', 'editRoles')->name('edit.roles'); 
        Route::post('/update/roles', 'updateRoles')->name('update.roles'); 
        Route::get('/delete/roles/{id}', 'deleteRoles')->name('delete.roles');  


        ////// Add Roles In Permission 
        Route::get('/add/roles/permission', 'addRolesPermission')->name('add.role.permission');    
        Route::post('/store/roles/permission', 'storeRolesPermission')->name('role.permission.store');    
        Route::get('/all/roles/permission', 'allRolesPermission')->name('all.role.permission');    
        Route::get('/edit/roles/permission/{id}', 'editRolesPermission')->name('admin.edit.roles');    
        Route::post('/update/roles/permission/{id}', 'updateRolesPermission')->name('admin.roles.update');    
        Route::get('/delete/roles/permission/{id}', 'deleteRolesPermission')->name('admin.delete.roles');    

    });

    // admin user all route
    Route::controller(AdminController::class)->group(function(){
        Route::get('/all/admin', 'allAdmin')->name('all.admin');   
        Route::get('/add/admin', 'addAdmin')->name('add.admin');   
        Route::post('/store/admin', 'storeAdmin')->name('store.admin');  
        Route::get('/edit/admin/{id}', 'editAdmin')->name('edit.admin');    
        Route::post('/update/admin/{id}', 'updateAdmin')->name('update.admin');    
        Route::get('/delete/admin/{id}', 'deleteAdmin')->name('delete.admin');    
    });


});  //Admin Group Middleware Route End



//Agent route
Route::middleware(['auth','roles:agent'])->group(function(){
    Route::get('/agent/dashboard', [AgentController::class, 'agentDashboard'])->name('agent.dashboard');
    Route::get('/agent/logout', [AgentController::class, 'agentLogout'])->name('agent.logout');
    Route::get('/agent/profile', [AgentController::class, 'agentProfile'])->name('agent.profile');
    Route::post('/agent/profile/store', [AgentController::class, 'agentProfileStore'])->name('agent.profile.store');
    Route::get('/agent/change/password', [AgentController::class, 'agentChangePassword'])->name('agent.change.password');
    Route::post('/agent/update/password', [AgentController::class, 'agentUpdatePassword'])->name('agent.update.password');

    Route::post('/agent/update/password', [AgentController::class, 'agentUpdatePassword'])->name('agent.update.password');
    
});


//Agent Login / Register
Route::get('/agent/login', [AgentController::class, 'AgentLogin'])->name('agent.login')
->middleware(RedirectIfAuthenticated::class);

Route::post('/agent/register', [AgentController::class, 'AgentRegister'])->name('agent.register');


//Admin login route
Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login')
->middleware(RedirectIfAuthenticated::class);

//Agent Property route
Route::middleware(['auth','roles:agent'])->group(function(){

    Route::controller(AgentPropertyController::class)->group(function(){
        Route::get('/agent/all/property', 'AgentAllProperty')->name('agent.all.property');
        Route::get('/agent/add/property', 'AgentAddProperty')->name('agent.add.property');
        Route::post('/agent/store/property', 'AgentStoreProperty')->name('agent.store.property');
        Route::get('/agent/edit/property/{id}', 'AgentEditProperty')->name('agent.edit.property');
        Route::post('/agent/update/property', 'AgentUpdateProperty')->name('agent.update.property');
        Route::post('/agent/update/property/thumbnail', 'AgentUpdatePropertyThumbnail')->name('agent.update.property.thumbnail');
        Route::post('/agent/update/property/multiimage', 'AgentUpdatePropertyMultiImage')->name('agent.update.property.multiimage');

        Route::get('/agent/property/delete/{id}', 'AgentDeletePropertyMultiimage')->name('agent.property.multiimg.delete');

        Route::post('/agent/store/property/multiimage', 'AgentStoreNewPropertyMultiImage')->name('agent.store.new.multiimage');
        Route::post('/agent/update/property/facility', 'AgentUpdatePropertyFacility')->name('agent.update.property.facility');

        Route::get('/agent/details/property/{id}', 'AgentDetailsProperty')->name('agent.details.property');
        Route::get('/agent/delete/property/{id}', 'AgentDeleteProperty')->name('agent.delete.property');
        Route::get('/agent/property/message', 'agentPropertyMessage')->name('agent.property.message');
        Route::get('/agent/message/details/{id}', 'agentMessageDetails')->name('agent.message.details');

        //Schedule Request Route
        Route::get('/agent/schedule/request', 'agentScheduleRequest')->name('agent.schedule.request');
        Route::get('/agent/schedule/details/{id}', 'agentScheduleDetails')->name('agent.details.schedule');
        Route::post('/agent/update/schedule', 'agentUpdateSchedule')->name('agent.update.schedule');
    });

    //Agent Buy Package Route
    Route::controller(AgentPropertyController::class)->group(function(){
        Route::get('/buy/package', 'BuyPackage')->name('buy.package');
        Route::get('/buy/business/plan', 'BuyBusinessPlan')->name('buy.business.plan');
        Route::post('/store/business/plan', 'StoreBusinessPlan')->name('store.business.plan');
        Route::get('/buy/professional/plan', 'BuyProfessionalPlan')->name('buy.professional.plan');
        Route::post('/store/professional/plan', 'StoreProfessionalPlan')->name('store.professional.plan');
        Route::get('/package/history', 'PackageHistory')->name('package.history');
        Route::get('/agent/package/invoice/{id}', 'AgentPackageInvoice')->name('agent.package.invoice');
    });
    
});  //End Agent Route


//FrontEnd Route
Route::get('property/details/{id}/{slug}',[IndexController::class, 'PropertyDetails']);


//Wishlist Add Route
Route::post('/add-to-wishlist/{property_id}', [WishlistController::class, 'addWishList']);

//Add to compare
Route::post('/add-to-compare/{property_id}', [CompareController::class, 'addCompare']);

//Send message from property details page
Route::post('/property/message', [IndexController::class, 'PropertyMessage'])->name('property.message');

//Agent details in frontend
Route::get('/agent/details/{id}', [IndexController::class, 'agentDetails'])->name('agent.details');

//Send message from Agent details page
Route::post('/agent/details/message', [IndexController::class, 'agentDetailsMessage'])->name('agent.details.message');

//Get All Rent Property
Route::get('/rent/property', [IndexController::class, 'rentProperty'])->name('rent.property');

//Get All Buy Property
Route::get('/buy/property', [IndexController::class, 'buyProperty'])->name('buy.property');

//Get All  Property Type
Route::get('/property/type/{id}', [IndexController::class, 'propertyType'])->name('property.type');


//Get State Details  Data
Route::get('/state/details/{id}', [IndexController::class, 'stateDetails'])->name('state.details');

//Home Page Buy Search Route 
Route::post('/buy/property/search', [IndexController::class, 'buyPropertySearch'])->name('buy.property.search');

//Home Page Rent Search Route 
Route::post('/rent/property/search', [IndexController::class, 'rentPropertySearch'])->name('rent.property.search');

//All Property search in  Rent Property 
Route::post('/all/property/search', [IndexController::class, 'allPropertySearch'])->name('all.property.search');

//Schedule Route in Property  Details
Route::post('/store/schedule', [IndexController::class, 'storeSchedule'])->name('store.schedule');


// Blog Details Route
Route::get('/blog/details/{slug}', [BlogController::class, 'blogDetails']);
//Blog display category wise
Route::get('blog/category/list/{id}', [BlogController::class, 'blogCatList']);

//All Blog display
Route::get('blog/list', [BlogController::class, 'blogList'])->name('blog.list');

//Comment Route
Route::post('/store/comment', [BlogController::class, 'storeComment'])->name('store.comment');

//Admin Blog Comment Route
Route::get('/blog/comment', [BlogController::class, 'adminBlogComment'])->name('admin.blog.comment');

Route::get('/admin/comment/reply/{id}', [BlogController::class, 'adminBlogCommentReplay'])->name('admin.comment.reply');

Route::post('/admin/reply/comment', [BlogController::class, 'adminReplyComment'])->name('admin.reply.comment');


// Chat Route
Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('chat.msg');
Route::get('/user-all', [ChatController::class, 'usersAll']);
Route::get('/user-message/{id}', [ChatController::class, 'userMsgById']);

//Agent Live Chat
Route::get('/agent/live/chat', [ChatController::class, 'agentLiveChat'])->name('agent.live.chat');
