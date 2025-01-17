<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\CategoryNote;
use App\Models\CreditDebit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryNoteController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Kategori Note';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.category_note.index', compact('title'));
    }

    public function data()
    {
        $data = CategoryNote::orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';

        if ($id) {
            $data = CategoryNote::find($id);
        }
                
        return view('backend.category_note.form', compact('title', 'action', 'data'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->category_note_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = CategoryNote::find($request->category_note_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = CategoryNote::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.category_note.index');
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
            $data = CategoryNote::find($id);
            $check = CreditDebit::where('category_note_id', $id)->get();

            if (count($check) > 0) {
                DB::rollBack();

                return response()->json([
                    'status'    => 400,
                    'message'   => 'Tidak Bisa Menghapus Data, Hapus Data Credit Debit Terlebih Dahulu !!',
                ]);
            }else{
                $data->delete();

                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Data Berhasil Dihapus.',
                ]);
            }


    
            // dd($data);

     
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal Menyimpan Data, Coba Lagi Kembali',
            ]);
        }
    }
}
