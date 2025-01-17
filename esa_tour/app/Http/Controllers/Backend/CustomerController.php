<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Province; 
use App\Models\District;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Pelanggan';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.customer.index', compact('title'));
    }

    public function data()
    {
        // dd('masok');
        $data = Customer::orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $provinces  = Province::all();
        $action     = 'Tambah';

        if ($id) {
            $data = Customer::find($id);
        }
                
        return view('backend.customer.form', compact('title', 'action', 'provinces', 'data'));
    }

    public function get_city(Request $request, $province_id)
    {
        $selected_city = 0;

        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            $selected_city = $customer->city_id;
        }

        $data = City::where('province_id', $province_id)->get();
       
        return response()->json([
            'selected_city' => $selected_city,
            'data'          => $data
        ]);
    }

    public function get_district(Request $request, $city_id)
    {
        $selected_district = 0;

        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            $selected_district = $customer->district_id;
        }

        $data = District::where('city_id', $city_id)->get();
       
        return response()->json([
            'selected_district' => $selected_district,
            'data'              => $data
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->customer_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Customer::find($request->customer_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Customer::create($requestData);
            }

            DB::commit();

            $data = Customer::all();

            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.customer.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = Customer::find($id);
            $data->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Dihapus.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal Menyimpan Data, Coba Lagi Kembali',
            ]);
        }
    }
}
