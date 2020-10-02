<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use Gate;

class DynamicPDFController extends Controller
{
    function index(){
      if(!Gate::allows('isAdmin')){
            abort(403, 'Sorry, this page is for system administrator.');
        }
        else{
            $customer_data = $this->get_customer_data();
            return view('pages.table_list')->with('customer_data', $customer_data);
        }
    }
    function get_customer_data(){
    	$customer_data = DB::table('users')
    					->get();

    	return $customer_data;
    }
    function pdf()
    {
        if(!Gate::allows('isAdmin'))
        {
            abort(403, 'Sorry, this page is for system administrator.');
        }
        else{
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($this->convert_customer_data_to_html())->setPaper('a4', 'portrait')->setWarnings(false)->save('myfile.pdf');
            return $pdf->stream();

        }
    }
    function convert_customer_data_to_html(){
    	$customer_data = $this->get_customer_data();
    	$output = '
    		<h3 align=center>Sample PDF</h3>
    		<table class="table" align=center>
                <tr style="background-color: #17468f">
                  <th style="border: 1px solid; padding: 10px 20px; width: 25%; color: #fff">
                    Name
                  </th>
                  <th style="border: 1px solid; padding: 10px 20px; width: 25%; color: #fff">
                    Email
                  </th>
                  <th style="border: 1px solid; padding: 10px 20px; width: 25%; color: #fff">
                    Role
                  </th>
               </tr>
    	';
    	 foreach($customer_data as $customer){
    	 	$output .='
    	 		
                    <tr>
                      <td style="border: 1px solid; padding: 10px 20px; width: 25%">
                        '.$customer->name.
                      '</td>
                      <td style="border: 1px solid; padding: 10px 20px; width: 25%">'
                        .$customer->email.
                      '</td>
                      <td style="border: 1px solid; padding: 10px 20px; width: 25%">'
                        .$customer->user_type.
                      '</td>
                    </tr> 
              
            ';
        } 
        $output .="</table>";
        return $output;
    }
}
