<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Notification;
use App\Notifications\StatusNotification;
use App\User;
use App\Models\ProductReview;
use App\Models\Response;
class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews=ProductReview::getAllReview();

        return view('backend.review.index')->with('reviews',$reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    public function showProductReviews($productSlug)
{
    $product = Product::where('slug', $productSlug)->first();
    if (!$product) {
        return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
    }

    // Lấy tất cả các đánh giá cho sản phẩm
    $reviews = ProductReview::where('product_id', $product->id)
                            ->with('responses') // Lấy các phản hồi liên quan
                            ->with(['user_info', 'responses', 'responses.user'])
                            ->get();


    return view('frontend.product_reviews', compact('product', 'reviews'));
}

    public function storeResponse(Request $request, $reviewId)
{
    $request->validate([
        'content' => 'required|string|max:255',
    ]);

    // Tạo phản hồi mới
    $response = Response::create([
        'review_id' => $reviewId,
        'user_id' => auth()->id(),
        'content' => $request->input('content'),
    ]);

    // Cập nhật review với response_id
    $review = ProductReview::find($reviewId);
    $review->response_id = $response->id;
    $review->save();

    return redirect()->back()->with('success', 'Phản hồi của bạn đã được gửi.');
}


    public function updateResponse(Request $request, $reviewId)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'response' => 'required|string|max:255',
        ]);

        // Tìm đánh giá theo ID
        $productReview = ProductReview::findOrFail($reviewId);
        if (auth()->user()->id !== $productReview->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa phản hồi này.');
        }

        // Cập nhật phản hồi
        $productReview->response = $request->input('response');
        $productReview->save();

        // Trả về kết quả
        return redirect()->back()->with('success', 'Phản hồi đánh giá đã được cập nhật.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'rate'=>'required|numeric|min:1'
        ]);
        $product_info=Product::getProductBySlug($request->slug);
        //  return $product_info;
        // return $request->all();
        $data=$request->all();
        $data['product_id']=$product_info->id;
        $data['user_id']=$request->user()->id;
        $data['status']='active';
        // dd($data);
        $status=ProductReview::create($data);

        $user=User::where('role','admin')->get();
        $details=[
            'title'=>'New Product Rating!',
            'actionURL'=>route('product-detail',$product_info->slug),
            'fas'=>'fa-star'
        ];
        Notification::send($user,new StatusNotification($details));
        if($status){
            request()->session()->flash('success','Thank you for your honest review!');
        }
        else{
            request()->session()->flash('error','Something went wrong! Please try again!!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review=ProductReview::find($id);
        // return $review;
        return view('backend.review.edit')->with('review',$review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review=ProductReview::find($id);
        if($review){
            // $product_info=Product::getProductBySlug($request->slug);
            //  return $product_info;
            // return $request->all();
            $data=$request->all();
            $status=$review->fill($data)->update();

            // $user=User::where('role','admin')->get();
            // return $user;
            // $details=[
            //     'title'=>'Update Product Rating!',
            //     'actionURL'=>route('product-detail',$product_info->id),
            //     'fas'=>'fa-star'
            // ];
            // Notification::send($user,new StatusNotification($details));
            if($status){
                request()->session()->flash('success','Review updated');
            }
            else{
                request()->session()->flash('error','Something went wrong! Please try again!!');
            }
        }
        else{
            request()->session()->flash('error','Review not found!!');
        }

        return redirect()->route('review.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review=ProductReview::find($id);
        $status=$review->delete();
        if($status){
            request()->session()->flash('success','Review deleted');
        }
        else{
            request()->session()->flash('error','Something went wrong! Try again');
        }
        return redirect()->route('review.index');
    }
}
