<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('admin.customer.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:customers',
            'phone' => 'required|unique:customers|max:11',
            'address' => 'required',
            'username' => 'required|unique:customers',
            'password' => 'required',
            'profile_picture' => 'required'
        ]);

        $customer = new Customer();
        $code = 'C'.$this->generateCode('Customer');
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->area_id = $request->area_id;
        $customer->username = $request->username;
        $customer->password = Hash::make($request->password);
        $customer->ip_address = $request->ip();
        $customer->code = $code;
        $customer->save_by = 2;
        $customer->updated_by = 2;
        // $customer->profile_picture = $this->imageUpload($request, 'profile_picture', 'uploads/customer');
        
        $Image = $request->file('profile_picture');
        $newImage = rand(0000,9999).$Image->getClientOriginalName();
        $thumb_image = rand(0000,9999).$Image->getClientOriginalName();
        Image::make($Image)->save('uploads/customer/'.$newImage);
        Image::make($Image)->resize(100,100)->save('uploads/customer/'.$thumb_image);
        $customer->profile_picture = $newImage;
        $customer->thum_picture = $thumb_image;
        $customer->save();  

        $data['status'] = "data Insert Successfully";
        $data['data_table'] = '';
      
        $customers = Customer::latest()->get();
        $sl = 1;
        foreach($customers as $customer){
            if($customer->status == 'a'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }

            $edit_url = 'customer/edit/'.$customer['id'];
            $delete_url = 'customer/delete/'.$customer['id'];
            $csrf = '{{ csrf_token() }}';
            $method = "@method('DELETE')";
            $id = $customer->id;
            $a_form = '
            <a href="'.$edit_url.'" class="btn btn-edit edit-btn"><i class="fas fa-pencil-alt"></i></a>
                                    <button type="submit" class="btn btn-delete" onclick="deleteUser('.$id.')"><i class="far fa-trash-alt"></i></button>
                                        <form id="delete-form-{{ '.$id.' }}" action="'.$delete_url.'"
                                            method="POST" style="display: none;">
                                            '.$csrf.'
                                            '.$method.'
                                        </form>';
            $data['data_table'] .= 
            '<tr  class="text-center">
                <td>'.$sl.'</td>
                <td>'.$customer->name.'</td>
                <td>'.$customer->email.'</td>
                <td>'.$customer->code.'</td>
                <td>'.$status.'</td>
                <td>'.$customer->username.'</td>
                <td>'.$customer->phone.'</td>
                <td class="text-center">
                    '.$a_form.'
                </td>
            </tr>';
        }


        // return redirect()->route('customer.index')->with('success','Customer Created successfully.');
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
        
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
        $customer = Customer::find($id)->toArray();
        $customer['profile_picture'] = asset('uploads/customer/'.$customer['profile_picture']);
        $url = route('customer.update', $customer['id']);
        return response()->json([
            'success' => true,
            'customer' => $customer,
            'url' => $url,
        ]);
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
        $this->validate($request,[
            'name' => 'required',
            'phone' => 'required|unique:customers,id',
            'address' => 'required',
        ]);
        $customerImage = '';
        $customer = Customer::find($id);
        if($request->hasFile('profile_picture')){
            $image_path = public_path('uploads/customer/'.$customer->profile_picture);
            $image_path_thumb = public_path('uploads/customer/'.$customer->thum_picture);
            if (file_exists($image_path)) {
                @unlink($image_path);
                $Image = $request->file('profile_picture');
                $newImage = rand(0000,9999).$Image->getClientOriginalName();
                Image::make($Image)->save('uploads/customer/'.$newImage);
                $customer->profile_picture = $newImage;
            }
            if (file_exists($image_path_thumb)) {
                @unlink($image_path_thumb); 
                $Image = $request->file('profile_picture');
                $newImage = rand(0000,9999).$Image->getClientOriginalName();
                Image::make($Image)->save('uploads/customer/'.$newImage);
                $customer->thum_picture = $newImage; 
            }
        }
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->area_id = $request->area_id;
        $customer->save();

        $data['status'] = "data Updated Successfully";
        $data['data_table'] = '';

        $customers = Customer::latest()->get();
        $sl = 1;
        foreach($customers as $customer){
            if($customer->status == 'a'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            $edit_url = 'customer/edit/'.$customer['id'];
            $delete_url = 'customer/delete/'.$customer['id'];
            $csrf = '{{ csrf_token() }}';
            $method = "@method('DELETE')";
            $id = $customer->id;
            $a_form = '
            <a href="'.$edit_url.'" class="btn btn-edit edit-btn"><i class="fas fa-pencil-alt"></i></a>
                                    <button type="submit" class="btn btn-delete" onclick="deleteUser('.$id.')"><i class="far fa-trash-alt"></i></button>
                                        <form id="delete-form-{{ '.$id.' }}" action="'.$delete_url.'"
                                            method="POST" style="display: none;">
                                            '.$csrf.'
                                            '.$method.'
                                        </form>';
            $data['data_table'] .= 
            '<tr  class="text-center">
                <td>'.$sl.'</td>
                <td>'.$customer->name.'</td>
                <td>'.$customer->email.'</td>
                <td>'.$customer->code.'</td>
                <td>'.$status.'</td>
                <td>'.$customer->username.'</td>
                <td>'.$customer->phone.'</td>
                <td class="text-center">
                '.$a_form.'
                </td>
            </tr>';
        }


        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $image_path = public_path('uploads/customer/'.$customer->profile_picture);
        $image_path_thumb = public_path('uploads/customer/'.$customer->thum_picture);
        if (file_exists($image_path)) {
            @unlink($image_path);
        }
        if (file_exists($image_path_thumb)) {
            @unlink($image_path_thumb);  
        }
            $customer->delete(); 
            return redirect()->route('customer.index')->with('success','Customer deleted successfully.');
        

    }
}
