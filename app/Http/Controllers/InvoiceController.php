<?php

namespace App\Http\Controllers;

use App\Counter;
use App\Invoice;
use App\InvoiceAmount;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;
class InvoiceController extends Controller
{




    public function index()
    {
        $invoices = Invoice::where('status', 'pending')->with(['customer', 'lastInvoiceAmount'])->paginate(10);
        $out_standing = Invoice::where('status', 'pending')->with(['customer', 'lastInvoiceAmount'])->get();


        return response()
            ->json(['results' => $invoices, 'out_standing' => $out_standing]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter = Counter::where('key', 'invoice')->first();

        $form = [
            'number' => $counter->prefix . $counter->value,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'terms_and_conditions' => 'Default Terms',
            'items' => [
                [
                    'product' => null,
                    'unit_price' => 0,
                    'qty' => 1
                ]
            ]
        ];

        return response()
            ->json(['form' => $form]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'reference' => 'nullable|max:100',
            'discount' => 'required|numeric|min:0',
            'terms_and_conditions' => 'required|max:2000',
            'items' => 'required|array|min:1',
            // 'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        $invoice = new Invoice;
        $invoice->fill($request->except('items'));

        $invoice->sub_total = collect($request->items)->sum(function($item) {
            return $item['qty'] * $item['unit_price'];
        });

        $invoice = DB::transaction(function() use ($invoice, $request) {
            $counter = Counter::where('key', 'invoice')->first();
            $invoice->number = $counter->prefix . $counter->value;

            // custom method from app/Helper/HasManyRelation
            $invoice->storeHasMany([
                'items' => $request->items
            ]);

            $counter->increment('value');

            return $invoice;
        });

        return response()
            ->json(['saved' => true, 'id' => $invoice->id]);

    }


    public function show($id)
    {

        $model = Invoice::with(['customer','items'])
            // $model = Invoice::with(['customer','items'])
            ->findOrFail($id);
        // $results=Invoice::where('number',$id)->with(['InvoiceAmount'])->get();
        // $invoice_amounts=InvoiceAmount::where('number',$id)->get();

        return response()
            ->json(['model' => $model]);

        // return view('invoice.detail',compact('invoice','invoice_amounts'));

    }


    public function edit($id)
    {
        $form = Invoice::with(['customer', 'items'])
            ->findOrFail($id);

        return response()
            ->json(['form' => $form]);


    }


    public function update(Request $request, $id)
    {
        


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->items()->delete();
        $invoice->amounts()->delete();
        $invoice->delete();

        return response()
            ->json(['deleted' => true]);
    }

   




}
