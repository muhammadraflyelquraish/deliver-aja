<?php

namespace App\Http\Controllers;

use App\Models\Deliver;
use App\Models\DeliverTracking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Response;

class DeliverController extends Controller
{
    function index()
    {
        return view('deliver.index');
    }

    function data()
    {
        $query = Deliver::with(['user', 'service']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $button = '<div class="btn-group pull-right">';
                $button .= '<a class="btn btn-sm btn-info" href="' . route('deliver.show', $row->id) . '"><i class="fa fa-eye"></i></a>';
                // $button .= '<a class="btn btn-sm btn-warning" href="' . route('deliver.edit', $row->id) . '"><i class="fa fa-edit"></i></a>';
                $button .= '<button class="btn btn-sm btn-danger" id="delete" data-integrity="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                $button .= '</div>';
                return $button;
            })
            ->editColumn('date_sent', function ($row) {
                return $row->date_sent ? date('Y-m-d', strtotime($row->date_sent)) : '-';
            })
            ->editColumn('date_received', function ($row) {
                return $row->date_received ? date('Y-m-d', strtotime($row->date_received)) : '-';
            })
            ->editColumn('status_deliver', function ($row) {
                if ($row->status_deliver == 'Pickup') {
                    return '<span class="label label-danger">' . $row->status_deliver . '</span>';
                } else if ($row->status_deliver == 'Waiting') {
                    return '<span class="label label-info">' . $row->status_deliver . '</span>';
                } else if ($row->status_deliver == 'Sent') {
                    return '<span class="label label-warning">' . $row->status_deliver . '</span>';
                } else if ($row->status_deliver == 'Arrived') {
                    return '<span class="label label-primary">' . $row->status_deliver . '</span>';
                } else {
                }
            })
            ->rawColumns(['action', 'status_deliver'])
            ->toJson();
    }

    function search_customer(Request $request)
    {
        $search = $request->searchTerm;
        $customer = User::with('addresses')
            ->orderBy('name', 'asc')
            ->where('role', 'Customer')
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%" . $search . "%");
                $q->orWhere('email', 'like', "%" . $search . "%");
                $q->orWhere('phone_number', 'like', "%" . $search . "%");
            })
            ->get();


        return response()->json([
            'res' => 'success',
            'data' => $customer
        ]);
    }

    function search_address(User $user)
    {
        $data = $user->load('addresses');
        return response()->json([
            'res' => 'success',
            'data' => $data
        ]);
    }

    function create()
    {
        $last_deliver = Deliver::latest()->first();
        $code_deliver = $last_deliver ? sprintf('DA%05s', substr($last_deliver->code_deliver, 2) + 1) : 'DA00001';
        $services = Service::get();
        return view('deliver.create', compact('services', 'code_deliver'));
    }

    function store(Request $request)
    {
        Deliver::create([
            'code_deliver' => $request->code_deliver,
            'type_deliver' => $request->type_deliver,
            'user_id' => $request->user_id,
            'address_id' => $request->address_id,
            'service_id' => $request->service_id,
            'destination_address' => $request->destination_address,
            'kilometer' => $request->kilometer,
            'weight' => $request->weight,
            'total_price' => $request->total_price,
            'date_pickup' => $request->date_pickup,
            'date_sent' => $request->date_sent,
            'status_deliver' => $request->status_deliver,
        ]);
        return redirect()->route('deliver.index');
    }

    function show(Deliver $deliver)
    {
        $deliver = $deliver->load('user')
            ->load('address')
            ->load('service')
            ->load('tracking');

        return view('deliver.detail', compact('deliver'));
    }

    function destroy(Deliver $deliver)
    {
        $deliver->delete();
        return response()->json(['res' => 'success'], Response::HTTP_NO_CONTENT);
    }

    function tracking_store(Deliver $deliver, Request $request)
    {
        if ($request->type_tracking == 'Sampai') {
            $deliver->update([
                'status_deliver' => 'Arrived',
                'date_received' => $request->date_arrived
            ]);
        }
        $deliver->tracking()->create($request->all());

        return response()->json(['res' => 'success', 'msg' => 'Data berhasil ditambahkan'], Response::HTTP_CREATED);
    }

    function tracking_data(Deliver $deliver)
    {
        $query = $deliver->tracking();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return parent::_getActionButton($row->id);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    function tracking_update(Deliver $deliver, DeliverTracking $tracking, Request $request)
    {
        if ($request->type_tracking == 'Sampai') {
            $deliver->update([
                'status_deliver' => 'Arrived',
                'date_received' => $request->date_arrived
            ]);
        }
        $tracking->update($request->all());
        return response()->json(['res' => 'success', 'msg' => 'Data berhasil diubah'], Response::HTTP_ACCEPTED);
    }

    function tracking_destroy(Deliver $deliver, DeliverTracking $tracking)
    {
        $tracking->delete();
        return response()->json(['res' => 'success'], Response::HTTP_NO_CONTENT);
    }
}
