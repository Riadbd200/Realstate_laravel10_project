<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\Comment;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function allBlogCategory()
    {
        $category = BlogCategory::latest()->get();
        return view('admin.blog.blogCategory', compact('category'));
    } //End Method

    public function storeCategory(Request $request)
    {
        BlogCategory::insert([
            'category_name' =>$request->category_name,
            'category_slug' =>strtolower(str_replace(' ', '-', $request->category_name)),
        ]);

        $notification = array(
            'message'    =>'Category  Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.blog.category')->with($notification);
    } //End Method


    public function editCategory($id)
    {
        $category  = BlogCategory::findOrFail($id);
        return response()->json($category);

    } //End Method

    public function updateCategory(Request $request)
    {
        $cat_id  = $request->cat_id;

        BlogCategory::findOrFail($cat_id)->update([
            'category_name' =>$request->category_name,
            'category_slug' =>strtolower(str_replace(' ', '-', $request->category_name)),
        ]);

        $notification = array(
            'message'    =>'Category  Updated Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.blog.category')->with($notification);
    } //End Method


    public function deleteCategory($id)
    {
        BlogCategory::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Category  Deleted Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.blog.category')->with($notification);


    } //End Method

    public function allPost()
    {

        $post = BlogPost::latest()->get();

        return view('admin.post.all_post', compact('post'));

    } //End Method

    public function addPost()
    {
        $blogcat = BlogCategory::latest()->get();
        return view('admin.post.add_post', compact('blogcat'));

    } //End Method

    public function storePost(Request $request)
    {
        
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,250)->save('upload/post/'.$name_gen);
        $save_url = 'upload/post/'.$name_gen; 

        BlogPost::insert([
            'blogcat_id'  => $request->blogcat_id,
            'user_id'  => Auth::user()->id,
            'title'  => $request->title,
            'slug'  => strtolower(str_replace(' ', '-', $request->title)),
            'short_desc'  => $request->short_desc,
            'long_desc'  => $request->long_desc,
            'tags'  => $request->tags,
            'image' =>$save_url,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message'    =>'Post Added Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->route('all.post')->with($notification);

    } //End Method


    public function editPost($id)
    {
        $blogcat = BlogCategory::latest()->get();
        $post = BlogPost::findOrFail($id);
        return view('admin.post.edit_post', compact('blogcat','post'));

    } //End Method

    public function updatePost(Request $request)
    {
        $postId = $request->id; 
        $oldImage = $request->old_img;

        if($request->file('image')){

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(370,275)->save('upload/post/'.$name_gen);
            $save_url = 'upload/post/'.$name_gen; 

            if(file_exists($oldImage)){
                unlink($oldImage);
            }

            BlogPost::findOrFail($postId )->update([
                'blogcat_id'  => $request->blogcat_id,
                'user_id'  => Auth::user()->id,
                'title'  => $request->title,
                'slug'  => strtolower(str_replace(' ', '-', $request->title)),
                'short_desc'  => $request->short_desc,
                'long_desc'  => $request->long_desc,
                'tags'  => $request->tags,
                'image' =>$save_url,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message'    =>'Blog Post Updated With Images Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.post')->with($notification);

        }else{
            BlogPost::findOrFail($postId )->update([
                'blogcat_id'  => $request->blogcat_id,
                'user_id'  => Auth::user()->id,
                'title'  => $request->title,
                'slug'  => strtolower(str_replace(' ', '-', $request->title)),
                'short_desc'  => $request->short_desc,
                'long_desc'  => $request->long_desc,
                'tags'  => $request->tags,
                'created_at' => Carbon::now(),
                
            ]);

            $notification = array(
                'message'    =>'Blog Post Updated Without Images Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.post')->with($notification);
        } //End if 

    } //End Method


    public function deletePost($id)
    {
        $deletePost = BlogPost::findOrFail($id);
        $img = $deletePost->image;
        unlink($img);

        BlogPost::findOrFail($id)->delete();

        $notification = array(
            'message'    =>'Post  Deleted Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method

    public function blogDetails($slug)
    {
        $blog =  BlogPost::where('slug', $slug)->first();

        // Fetch all Tags
        $tags = $blog->tags;
        $tags_all = explode(',',$tags);

        //Fetch all category
        $category = BlogCategory::latest()->get();

        //Fetch recent post
        $recentPost = BlogPost::latest()->limit(3)->get();

        $data = [
            'blog' =>$blog,
            'tags_all' =>$tags_all,
            'category' =>$category,
            'recentPost' =>$recentPost,
        ];

        return view('frontend.blog.blog_details', $data);

    } //End Method

    public function blogCatList($id)
    {
        $blog = BlogPost::where('blogcat_id', $id)->get();

        $breadCat = BlogCategory::where('id', $id)->first();

        //Fetch all category
        $category = BlogCategory::latest()->get();

        //Fetch recent post
        $recentPost = BlogPost::latest()->limit(3)->get();

        $data = [
            'blog'       =>$blog,
            'breadCat'   =>$breadCat,
            'category'   =>$category,
            'recentPost' =>$recentPost,
        ];

        return view('frontend.blog.blog_cat_list', $data);

    } //End Method


    public function blogList()
    {

        //Fetch all blog post
        $blog = BlogPost::latest()->get();

        //Fetch all category
        $category = BlogCategory::latest()->get();

        //Fetch recent blog post
        $recentPost = BlogPost::latest()->limit(3)->get();

        $data = [
            'blog'       =>$blog,
            'category'   =>$category,
            'recentPost' =>$recentPost,
        ];

        return view('frontend.blog.blog_list', $data);

    } //End Method

    public function storeComment(Request $request)
    {
        $post_id = $request->post_id;

        Comment::insert([
            'user_id'    => Auth::user()->id,
            'post_id'    => $post_id,
            'parent_id'  => null,
            'subject'    =>$request->subject,
            'message'    =>$request->message,
            'created_at' =>Carbon::now(),

        ]);

        $notification = array(
            'message'    =>'Comment Inserted Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method


    public function adminBlogComment()
    {
        $comment = Comment::where('parent_id', null)->latest()->get();
        return view('admin.comment.comment_all', compact('comment'));

    } //End Method


    public function adminBlogCommentReplay($id)
    {
        $replyComment = Comment::where('id', $id)->first();
        return view('admin.comment.reply_comment', compact('replyComment'));

    } //End Method

    public function adminReplyComment(Request $request)
    {
        $id      = $request->id;
        $user_id = $request->user_id;
        $post_id = $request->post_id;

        Comment::insert([
            'user_id'    => $user_id,
            'post_id'    => $post_id,
            'parent_id'  => $id,
            'subject'    =>$request->subject,
            'message'    =>$request->message,
            'created_at' =>Carbon::now(),

        ]);

        $notification = array(
            'message'    =>'Reply Added Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method

    
}
