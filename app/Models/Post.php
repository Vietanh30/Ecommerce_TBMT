<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=['title','tags','summary','slug','description','photo','quote','post_cat_id','status'];


    public function cat_info(){
        return $this->hasOne('App\Models\PostCategory','id','post_cat_id');
    }

    public function author_info(){
        return $this->hasOne('App\User','id','added_by');
    }
    public static function getAllPost(){
        return Post::with(['cat_info','author_info'])->orderBy('id','DESC')->paginate(10);
    }
    // public function get_comments(){
    //     return $this->hasMany('App\Models\PostComment','post_id','id');
    // }
    public function tag_info(){
        // Tách giá trị trong cột `tags` thành mảng các tag
        $tags = explode(',', $this->tags);
        return $tags;
    }
    public static function getPostBySlug($slug){
        $post = Post::with(['author_info']) // Chỉ eager load mối quan hệ hợp lệ
                    ->where('slug', $slug)
                    ->where('status', 'active')
                    ->first();
    
        if ($post) {
            // Xử lý tag_info thủ công
            $post->tag_info = $post->tag_info(); // Gọi phương thức tag_info để xử lý logic
        }
    
        return $post;
    }

    // public static function getProductByCat($slug){
    //     // dd($slug);
    //     return Category::with('products')->where('slug',$slug)->first();
    //     // return Product::where('cat_id',$id)->where('child_cat_id',null)->paginate(10);
    // }

    // public static function getBlogByCategory($id){
    //     return Post::where('post_cat_id',$id)->paginate(8);
    // }
    public static function getBlogByTag($slug){
        // dd($slug);
        return Post::where('tags',$slug)->paginate(8);
    }

    public static function countActivePost(){
        $data=Post::where('status','active')->count();
        if($data){
            return $data;
        }
        return 0;
    }
}
