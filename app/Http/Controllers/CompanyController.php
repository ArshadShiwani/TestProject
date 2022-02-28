<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\company;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
         $form = company::findOrFail(1);

        return response()
            ->json(['form' => $form]);

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

        
       
        $company = company::findOrFail(1);
        $request->validate([
            'phone' => 'required',
            'company_img' => 'required',
            'address' => 'required',
            'email'=>'required|email'
        ]);

         $fileName = null;
       if($request->hasFile('company_img'))
       {
           $file = request()->file('company_img');
           $fileName = md5($file->getClientOriginalName()) . "_" . time() . "." . $file->getClientOriginalExtension();
           $file->move('./uploads/', $fileName);
       }

        try{
            $company->update([
                'phone' => $request->phone,
                'company_img' => $request->company_img,
                'address' => $request->address,
                'email'=>$request->email
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
