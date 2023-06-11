<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

use App\Models\User;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user.view');
    }


    public function create(): JsonResponse
    {
        $user = User::whereNotIn('id', array(auth()->user()->id));

        return DataTables::of($user)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $button = '<div class="btn-group pull-right">';
                $button .= '<a class="btn btn-sm btn-info" href="' . route('address.index', $row->id) . '"><i class="fa fa-eye"></i></a>';
                $button .= '<button class="btn btn-sm btn-warning" data-mode="edit" data-integrity="' . $row->id . '" data-toggle="modal" data-target="#ModalAddEdit"><i class="fa fa-edit"></i></button>';
                $button .= '<button class="btn btn-sm btn-danger" id="delete" data-integrity="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                $button .= '</div>';
                return $button;
            })
            ->editColumn('role', function ($row) {
                return $row->role == 'Admin'
                    ? '<span class="label label-warning">' . $row->role . '</span>'
                    : '<span class="label label-primary">' . $row->role . '</span>';
            })
            ->rawColumns(['action', 'role'])
            ->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        try {
            $user = User::create($data);
            return response()->json(['res' => 'success', 'msg' => 'Data berhasil ditambahkan'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['res' => 'error', 'msg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            User::findOrFail($id)->update($request->all());
            return response()->json(['res' => 'success', 'msg' => 'Data berhasil diubah'], Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return response()->json(['res' => 'error', 'msg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            User::findOrFail($id)->delete();
            return response()->json(['res' => 'success'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['res' => 'error', 'msg' => $e->getMessage()], Response::HTTP_CONFLICT);
        }
    }

    function address(User $user)
    {
        return view('user.address', compact('user'));
    }

    function address_store(User $user, Request $request)
    {
        $user->addresses()->create([
            'address' => $request->address
        ]);
        return response()->json(['res' => 'success', 'msg' => 'Data berhasil ditambahkan'], Response::HTTP_CREATED);
    }

    function address_data(User $user)
    {
        $query = $user->addresses();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return parent::_getActionButton($row->id);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    function address_update(User $user, Address $address, Request $request)
    {
        $address->update($request->all());
        return response()->json(['res' => 'success', 'msg' => 'Data berhasil diubah'], Response::HTTP_ACCEPTED);
    }

    function address_destroy(User $user, Address $address)
    {
        $address->delete();
        return response()->json(['res' => 'success'], Response::HTTP_NO_CONTENT);
    }
}
