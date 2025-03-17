<?php
use App\Models\Category;
use App\Models\PostCategory;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Cart;
use App\Models\Post;
// use Auth;
class Helper{
    public static function orderPrice($id, $user_id)
{
    // Lấy tất cả các giỏ hàng của người dùng với order_id tương ứng
    $order = Order::find($id);

    if ($order) {
        // Tính tổng số tiền cho đơn hàng (giả sử order có nhiều sản phẩm, mỗi sản phẩm có amount riêng)
        $totalAmount = 0;

        // Lặp qua tất cả các sản phẩm trong đơn hàng và tính tổng
        foreach ($order->cart_info as $cartItem) {
            $totalAmount += $cartItem->amount; // Lấy giá trị của từng sản phẩm trong giỏ hàng
        }

        return $totalAmount;
    }

    return 0; // Nếu không tìm thấy order thì trả về 0
}

    public static function getAllCategory(){
        $category=new Category();
        $menu=$category->getAllParentWithChild();
        return $menu;
    }

    public static function getHeaderCategory(){
        $category = new Category();
        // dd($category);
        $menu=$category->getAllParentWithChild();

        if($menu){
            ?>

            <li>
            <a href="javascript:void(0);">Loại sản phẩm<i class="ti-angle-down"></i></a>
                <ul class="dropdown border-0 shadow">
                <?php
                    foreach($menu as $cat_info){
                        if($cat_info->child_cat->count()>0){
                            ?>
                            <li><a href="<?php echo route('product-cat',$cat_info->slug); ?>"><?php echo $cat_info->title; ?></a>
                                <ul class="dropdown sub-dropdown border-0 shadow">
                                    <?php
                                    foreach($cat_info->child_cat as $sub_menu){
                                        ?>
                                        <li><a href="<?php echo route('product-sub-cat',[$cat_info->slug,$sub_menu->slug]); ?>"><?php echo $sub_menu->title; ?></a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        }
                        else{
                            ?>
                                <li><a href="<?php echo route('product-cat',$cat_info->slug);?>"><?php echo $cat_info->title; ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </li>
        <?php
        }
    }

    public static function productCategoryList($option='all'){
        if($option='all'){
            return Category::orderBy('id','DESC')->get();
        }
        return Category::has('products')->orderBy('id','DESC')->get();
    }

    public static function postTagList($option = 'all') {
        $tags = Post::select('tags')->pluck('tags')->toArray(); // Lấy tất cả giá trị từ cột `tags`
        $allTags = [];

        foreach ($tags as $tagString) {
            if ($tagString) {
                $allTags = array_merge($allTags, explode(',', $tagString)); // Tách chuỗi tag thành mảng
            }
        }

        $uniqueTags = array_unique($allTags); // Loại bỏ các tag trùng lặp
        sort($uniqueTags); // Sắp xếp danh sách tag theo thứ tự bảng chữ cái
    }

    public static function postCategoryList($option="all"){
        if($option='all'){
            return PostCategory::orderBy('id','DESC')->get();
        }
        return PostCategory::has('posts')->orderBy('id','DESC')->get();
    }
    // Cart Count
    public static function cartCount($user_id=''){

        if(Auth::check()){
            if($user_id=="") $user_id=auth()->user()->id;
            return Cart::where('user_id',$user_id)->where('order_id',null)->sum('quantity');
        }
        else{
            return 0;
        }
    }
    // relationship cart with product


    // Trong lớp Helper (app/Http/Helpers.php)

public static function getAllProductFromCart($user_id = '')
{
    if (Auth::check()) {
        if ($user_id == "") $user_id = auth()->user()->id;

        // Lấy tất cả các sản phẩm trong giỏ hàng của người dùng
        $cartItems = Cart::with('product')->where('user_id', $user_id)->where('order_id', null)->get();

        return $cartItems;
    } else {
        return 0;
    }
}

    // Total amount cart
    public static function totalCartPrice($user_id=''){
        if(Auth::check()){
            if($user_id == "") $user_id = auth()->user()->id;

            // Tính tổng giá trị giỏ hàng (không tính phí vận chuyển)
            return Cart::where('user_id', $user_id)
                       ->where('order_id', null)
                       ->sum('amount');  // Tính tổng số tiền trong giỏ hàng
        } else {
            return 0;
        }
    }


    // Total price with shipping and coupon
    public static function grandPrice($id, $user_id)
    {
        // Gọi phương thức orderPrice để tính tổng giá trị giỏ hàng
        $orderPrice = self::orderPrice($id, $user_id);

        // Trả về tổng giá trị đơn hàng
        return number_format((float)($orderPrice), 2, '.', '');
    }



    // Admin home
    public static function earningPerMonth(){
        $month_data=Order::where('status','delivered')->get();
        // return $month_data;
        $price=0;
        foreach($month_data as $data){
            $price = $data->cart_info->sum('price');
        }
        return number_format((float)($price),2,'.','');
    }


}

?>
