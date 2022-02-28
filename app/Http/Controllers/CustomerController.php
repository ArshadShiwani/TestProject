<?php

namespace App\Http\Controllers;

use App\Customer;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    public function AllInvoice($id)
    {
        if (request('startDate') && request('EndDate')) {
            $model = Customer::with(['invoices' => function ($query) {
                $query->when(request('startDate'), function ($query) {
                    $query->whereBetween('date', [request('startDate'), request('EndDate')]);
                }
                );
            },
            ])->find($id);
            return response()
                ->json(['model' => $model]);
        } elseif (request('startDate')) {
            $model = Customer::with(['invoices' => function ($query) {
                $query->where('date', [request('startDate')]);
            }
            ])->find($id);
            return response()
                ->json(['model' => $model]);
        } elseif (request('EndDate')) {
            $model = Customer::with(['invoices' => function ($query) {
                $query->where('date', request('EndDate'));
            }
            ])->find($id);
            return response()
                ->json(['model' => $model]);
        } else {
            $model = Customer::with(['invoices'])->find($id);
            return response()
                ->json(['model' => $model]);
        }
    }

    public function search()
    {

        if (request('type')) {
            $results = Customer::orderBy('client')
                ->where([
                        ['client', 'like', '%' . request('q') . '%'],
                        
                    ])
                        ->orWhere([
                            ['email', 'like', '%' . request('q') . '%'],
                            
                        ])
                        
                ->limit(10)
                ->get();

            return response()
                ->json(['results' => $results]);
        } else if(request('id')){

          $results=Customer::where('id',request('id'))->get();
            return response()
                ->json(['results' => $results]);

        }

    }

    public function index()
    {
        


        if(request('q'))
        {
            $results = Customer::orderBy('client')
            ->when(request('q'), function($query) {
                $query->where('client', 'like', '%'.request('q').'%')
                    ->orWhere('phone', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();

        return response()
            ->json(['results' => $results]);


        } 
        elseif(request('search'))
        {
            
            $customer=request('search');
            $results = Customer::where([
                ['client', 'like', '%' . $customer . '%'],
                ['status',1],

            ])
            ->orwhere([
                ['phone', 'like', '%' . $customer . '%'],
                ['status',1],
            ])
            ->paginate(15);
            return response()
                ->json(['results' => $results]);

        } 
        else{
            $where = [
                'status' => 1,
            ];
            $results = Customer::where($where)->latest()->paginate(25);
            return response()
                ->json(['results' => $results]);

        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $form = [
            'client' => "",
            'phone' => "",
            'email' => "",
        ];
        return response()
            ->json(['form' => $form]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return\Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'client' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email|max:100',
        ]);

        try{
            Customer::create([
                'client' => $request->client,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);
            return response()
                ->json(['saved' => true,'msg'=>""]);


        }   catch (Throwable $e){
            return response()
                ->json(['saved' => false,'msg'=>$e]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $form = Customer::findOrFail($id);

        return response()
            ->json(['form' => $form]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([
            'client' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email|max:100',
        ]);

        try{
            $customer->update([
                'client' => $request->client,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);
            return response()
                ->json(['saved' => true,'msg'=>""]);


        }   catch (Throwable $e){
            return response()
                ->json(['saved' => false,'msg'=>$e]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $Customer = Customer::findOrFail($id);
        if ($Customer->status == 1) {
            $Customer->update([
                'status' => 0
            ]);
        }

        return response()
            ->json(['deleted' => true]);

    }
}
