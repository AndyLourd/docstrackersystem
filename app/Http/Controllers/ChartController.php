<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PRequest;
use App\POrder;
use App\TOrder;
use App\Voucher;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            if($_GET['type'] == "Admin Charts")
            {
                $janpr = PRequest::whereMonth('created_at', '01')->count();
                $febpr = PRequest::whereMonth('created_at', '02')->count();
                $marpr = PRequest::whereMonth('created_at', '03')->count();
                $aprpr = PRequest::whereMonth('created_at', '04')->count();
                $maypr = PRequest::whereMonth('created_at', '05')->count();
                $junpr = PRequest::whereMonth('created_at', '06')->count();
                $julpr = PRequest::whereMonth('created_at', '07')->count();
                $augpr = PRequest::whereMonth('created_at', '08')->count();
                $septpr = PRequest::whereMonth('created_at', '09')->count();
                $octpr = PRequest::whereMonth('created_at', '10')->count();
                $novpr = PRequest::whereMonth('created_at', '11')->count();
                $decpr = PRequest::whereMonth('created_at', '12')->count();
                $maxpr = PRequest::count();
                $completepr = PRequest::where('status','Attached PO')->count();
                $incompletepr = PRequest::where('status','!=','Attached PO')->count();

                $janpo = POrder::whereMonth('created_at', '01')->count();
                $febpo = POrder::whereMonth('created_at', '02')->count();
                $marpo = POrder::whereMonth('created_at', '03')->count();
                $aprpo = POrder::whereMonth('created_at', '04')->count();
                $maypo = POrder::whereMonth('created_at', '05')->count();
                $junpo = POrder::whereMonth('created_at', '06')->count();
                $julpo = POrder::whereMonth('created_at', '07')->count();
                $augpo = POrder::whereMonth('created_at', '08')->count();
                $septpo = POrder::whereMonth('created_at', '09')->count();
                $octpo = POrder::whereMonth('created_at', '10')->count();
                $novpo = POrder::whereMonth('created_at', '11')->count();
                $decpo = POrder::whereMonth('created_at', '12')->count();
                $maxpo = POrder::count();
                $completepo = POrder::where('status','Attached DV')->count();
                $incompletepo = POrder::where('status','!=','Attached DV')->count();

                $janto = TOrder::whereMonth('created_at', '01')->count();
                $febto = TOrder::whereMonth('created_at', '02')->count();
                $marto = TOrder::whereMonth('created_at', '03')->count();
                $aprto = TOrder::whereMonth('created_at', '04')->count();
                $mayto = TOrder::whereMonth('created_at', '05')->count();
                $junto = TOrder::whereMonth('created_at', '06')->count();
                $julto = TOrder::whereMonth('created_at', '07')->count();
                $augto = TOrder::whereMonth('created_at', '08')->count();
                $septto = TOrder::whereMonth('created_at', '09')->count();
                $octto = TOrder::whereMonth('created_at', '10')->count();
                $novto = TOrder::whereMonth('created_at', '11')->count();
                $decto = TOrder::whereMonth('created_at', '12')->count();
                $maxto = TOrder::count();
                $completeto = TOrder::where('status','Received by HRMO / Completed')
                    ->orWhere('status','For HRMO')
                    ->count();
                $incompleteto = TOrder::where([
                    ['status','!=','Received by HRMO / Completed'],
                    ['status','!=','For HRMO'],
                ])->count();

                $jandv = Voucher::whereMonth('created_at', '01')->count();
                $febdv = Voucher::whereMonth('created_at', '02')->count();
                $mardv = Voucher::whereMonth('created_at', '03')->count();
                $aprdv = Voucher::whereMonth('created_at', '04')->count();
                $maydv = Voucher::whereMonth('created_at', '05')->count();
                $jundv = Voucher::whereMonth('created_at', '06')->count();
                $juldv = Voucher::whereMonth('created_at', '07')->count();
                $augdv = Voucher::whereMonth('created_at', '08')->count();
                $septdv = Voucher::whereMonth('created_at', '09')->count();
                $octdv = Voucher::whereMonth('created_at', '10')->count();
                $novdv = Voucher::whereMonth('created_at', '11')->count();
                $decdv = Voucher::whereMonth('created_at', '12')->count();
                $maxdv = Voucher::count();
                $completedv = Voucher::where('status','Completed')->count();
                $incompletedv = Voucher::where('status','!=','Completed')->count();

                $charts = array(
                    'janpr' => $janpr,
                    'febpr' => $febpr,
                    'marpr' => $marpr,
                    'aprpr' => $aprpr,
                    'maypr' => $maypr,
                    'junpr' => $junpr,
                    'julpr' => $julpr,
                    'augpr' => $augpr,
                    'septpr' => $septpr,
                    'octpr' => $octpr,
                    'novpr' => $novpr,
                    'decpr' => $decpr,
                    'maxpr' => $maxpr,
                    'completepr' => $completepr,
                    'incompletepr' => $incompletepr,
                    'janpo' => $janpo,
                    'febpo' => $febpo,
                    'marpo' => $marpo,
                    'aprpo' => $aprpo,
                    'maypo' => $maypo,
                    'junpo' => $junpo,
                    'julpo' => $julpo,
                    'augpo' => $augpo,
                    'septpo' => $septpo,
                    'octpo' => $octpo,
                    'novpo' => $novpo,
                    'decpo' => $decpo,
                    'maxpo' => $maxpo,
                    'completepo' => $completepo,
                    'incompletepo' => $incompletepo,
                    'janto' => $janto,
                    'febto' => $febto,
                    'marto' => $marto,
                    'aprto' => $aprto,
                    'mayto' => $mayto,
                    'junto' => $junto,
                    'julto' => $julto,
                    'augto' => $augto,
                    'septto' => $septto,
                    'octto' => $octto,
                    'novto' => $novto,
                    'decto' => $decto,
                    'maxto' => $maxto,
                    'completeto' => $completeto,
                    'incompleteto' => $incompleteto,
                    'jandv' => $jandv,
                    'febdv' => $febdv,
                    'mardv' => $mardv,
                    'aprdv' => $aprdv,
                    'maydv' => $maydv,
                    'jundv' => $jundv,
                    'juldv' => $juldv,
                    'augdv' => $augdv,
                    'septdv' => $septdv,
                    'octdv' => $octdv,
                    'novdv' => $novdv,
                    'decdv' => $decdv,
                    'maxdv' => $maxdv,
                    'completedv' => $completedv,
                    'incompletedv' => $incompletedv,
                );
                return $charts; 
            }
            else if($_GET['type'] == "Enduser Charts")
            {
                $enduser = auth()->user();
                $janpr = PRequest::whereMonth('created_at', '01')->where('user_id',$enduser->id)->count();
                $febpr = PRequest::whereMonth('created_at', '02')->where('user_id',$enduser->id)->count();
                $marpr = PRequest::whereMonth('created_at', '03')->where('user_id',$enduser->id)->count();
                $aprpr = PRequest::whereMonth('created_at', '04')->where('user_id',$enduser->id)->count();
                $maypr = PRequest::whereMonth('created_at', '05')->where('user_id',$enduser->id)->count();
                $junpr = PRequest::whereMonth('created_at', '06')->where('user_id',$enduser->id)->count();
                $julpr = PRequest::whereMonth('created_at', '07')->where('user_id',$enduser->id)->count();
                $augpr = PRequest::whereMonth('created_at', '08')->where('user_id',$enduser->id)->count();
                $septpr = PRequest::whereMonth('created_at', '09')->where('user_id',$enduser->id)->count();
                $octpr = PRequest::whereMonth('created_at', '10')->where('user_id',$enduser->id)->count();
                $novpr = PRequest::whereMonth('created_at', '11')->where('user_id',$enduser->id)->count();
                $decpr = PRequest::whereMonth('created_at', '12')->where('user_id',$enduser->id)->count();
                $maxpr = PRequest::where('user_id',$enduser->id)->count();
                $completepr = PRequest::where([
                    ['status','Attached PO'],
                    ['user_id',$enduser->id]
                ])->count();
                $incompletepr = PRequest::where([
                    ['status','!=','Attached PO'],
                    ['user_id',$enduser->id]
                ])->count();

                $janpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '01')
                    ->where('user_id',$enduser->id)
                    ->count();
                $febpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '02')
                    ->where('user_id',$enduser->id)
                    ->count();
                $marpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '03')
                    ->where('user_id',$enduser->id)
                    ->count();
                $aprpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '04')
                    ->where('user_id',$enduser->id)
                    ->count();
                $maypo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '05')
                    ->where('user_id',$enduser->id)
                    ->count();
                $junpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '06')
                    ->where('user_id',$enduser->id)
                    ->count();
                $julpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '07')
                    ->where('user_id',$enduser->id)
                    ->count();
                $augpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '08')
                    ->where('user_id',$enduser->id)
                    ->count();
                $septpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '09')
                    ->where('user_id',$enduser->id)
                    ->count();
                $octpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '10')
                    ->where('user_id',$enduser->id)
                    ->count();
                $novpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '11')
                    ->where('user_id',$enduser->id)
                    ->count();
                $decpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('p_orders.created_at', '12')
                    ->where('user_id',$enduser->id)
                    ->count();
                $maxpo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->where('user_id',$enduser->id)
                    ->count();
                $completepo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->where([
                        ['p_orders.status','Attached DV'],
                        ['user_id',$enduser->id]
                    ])->count();
                $incompletepo = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->where([
                        ['p_orders.status','!=','Attached DV'],
                        ['user_id',$enduser->id]
                    ])->count();

                $janto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '01')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $febto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '02')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $marto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '03')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $aprto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '04')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $mayto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '05')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $junto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '06')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $julto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '07')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $augto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '08')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $septto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '09')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $octto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '10')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $novto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '11')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $decto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('t_orders.created_at', '12')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $maxto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $completeto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->where([
                        ['t_orders.status','Received by HRMO / Completed'],
                        ['t_o_users.user_id',$enduser->id]
                    ])
                    ->orWhere([
                        ['t_orders.status','For HRMO'],
                        ['t_o_users.user_id',$enduser->id]
                    ])
                    ->count();
                $incompleteto = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->where([
                        ['t_orders.status','!=','Received by HRMO / Completed'],
                        ['t_orders.status','!=','For HRMO'],
                        ['t_o_users.user_id',$enduser->id]
                    ])->count();

                $jandvforsalary = Voucher::whereMonth('created_at', '01')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $jandvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '01')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $jandvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '01')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $jandv = $jandvforsalary + $jandvforpo + $jandvforto;

                $febdvforsalary = Voucher::whereMonth('created_at', '02')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $febdvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '02')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $febdvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '02')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $febdv = $febdvforsalary + $febdvforpo + $febdvforto;

                $mardvforsalary = Voucher::whereMonth('created_at', '3')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $mardvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '3')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $mardvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '3')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $mardv = $mardvforsalary + $mardvforpo + $mardvforto;

                $aprdvforsalary = Voucher::whereMonth('created_at', '4')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $aprdvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '4')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $aprdvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '4')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $aprdv = $aprdvforsalary + $aprdvforpo + $aprdvforto;

                $maydvforsalary = Voucher::whereMonth('created_at', '5')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $maydvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '5')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $maydvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '5')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $maydv = $maydvforsalary + $maydvforpo + $maydvforto;

                $jundvforsalary = Voucher::whereMonth('created_at', '6')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $jundvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '6')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $jundvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '6')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $jundv = $jundvforsalary + $jundvforpo + $jundvforto;

                $juldvforsalary = Voucher::whereMonth('created_at', '7')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $juldvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '7')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $juldvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '7')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $juldv = $juldvforsalary + $juldvforpo + $juldvforto;

                $augdvforsalary = Voucher::whereMonth('created_at', '8')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $augdvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '8')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $augdvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '8')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $augdv = $augdvforsalary + $augdvforpo + $augdvforto;

                $septdvforsalary = Voucher::whereMonth('created_at', '9')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $septdvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '9')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $septdvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '9')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $septdv = $septdvforsalary + $septdvforpo + $septdvforto;

                $octdvforsalary = Voucher::whereMonth('created_at', '10')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $octdvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '10')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $octdvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '10')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $octdv = $octdvforsalary + $octdvforpo + $octdvforto;

                $novdvforsalary = Voucher::whereMonth('created_at', '11')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $novdvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '11')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $novdvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '11')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $novdv = $novdvforsalary + $novdvforpo + $novdvforto;

                $decdvforsalary = Voucher::whereMonth('created_at', '12')
                    ->where([
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $decdvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->whereMonth('vouchers.created_at', '12')
                    ->where('p_requests.user_id',$enduser->id)
                    ->count();
                $decdvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->whereMonth('vouchers.created_at', '12')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->count();
                $decdv = $decdvforsalary + $decdvforpo + $decdvforto;

                $completedvforsalary = Voucher::where([
                        ['status','Completed'],
                        ['user_id',$enduser->id],
                        ['type','DV for Salary']
                    ])
                    ->count();
                $completedvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->where([
                        ['vouchers.status','Completed'],
                        ['p_requests.user_id',$enduser->id]
                    ])
                    ->count();
                $completedvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                    ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->where([
                        ['vouchers.status','Completed'],
                        ['t_o_users.user_id',$enduser->id]
                    ])
                    ->count();
                $completedv = $completedvforsalary + $completedvforpo + $completedvforto;

                $incompletedvforsalary = Voucher::where([
                    ['status','!=','Completed'],
                    ['user_id',$enduser->id],
                    ['type','DV for Salary']
                ])->count();
                $incompletedvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->where([
                        ['vouchers.status','!=','Completed'],
                        ['p_requests.user_id',$enduser->id]
                    ])
                    ->count();
                $incompletedvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                    ->join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->where([
                        ['vouchers.status','!=','Completed'],
                        ['t_o_users.user_id',$enduser->id]
                    ])
                    ->count();
                $incompletedv = $incompletedvforsalary + $incompletedvforpo + $incompletedvforto;

                $maxdv = Voucher::count();

                $charts = array(
                    'janpr' => $janpr,
                    'febpr' => $febpr,
                    'marpr' => $marpr,
                    'aprpr' => $aprpr,
                    'maypr' => $maypr,
                    'junpr' => $junpr,
                    'julpr' => $julpr,
                    'augpr' => $augpr,
                    'septpr' => $septpr,
                    'octpr' => $octpr,
                    'novpr' => $novpr,
                    'decpr' => $decpr,
                    'maxpr' => $maxpr,
                    'completepr' => $completepr,
                    'incompletepr' => $incompletepr,
                    'janpo' => $janpo,
                    'febpo' => $febpo,
                    'marpo' => $marpo,
                    'aprpo' => $aprpo,
                    'maypo' => $maypo,
                    'junpo' => $junpo,
                    'julpo' => $julpo,
                    'augpo' => $augpo,
                    'septpo' => $septpo,
                    'octpo' => $octpo,
                    'novpo' => $novpo,
                    'decpo' => $decpo,
                    'maxpo' => $maxpo,
                    'completepo' => $completepo,
                    'incompletepo' => $incompletepo,
                    'janto' => $janto,
                    'febto' => $febto,
                    'marto' => $marto,
                    'aprto' => $aprto,
                    'mayto' => $mayto,
                    'junto' => $junto,
                    'julto' => $julto,
                    'augto' => $augto,
                    'septto' => $septto,
                    'octto' => $octto,
                    'novto' => $novto,
                    'decto' => $decto,
                    'maxto' => $maxto,
                    'completeto' => $completeto,
                    'incompleteto' => $incompleteto,
                    'jandv' => $jandv,
                    'febdv' => $febdv,
                    'mardv' => $mardv,
                    'aprdv' => $aprdv,
                    'maydv' => $maydv,
                    'jundv' => $jundv,
                    'juldv' => $juldv,
                    'augdv' => $augdv,
                    'septdv' => $septdv,
                    'octdv' => $octdv,
                    'novdv' => $novdv,
                    'decdv' => $decdv,
                    'maxdv' => $maxdv,
                    'completedv' => $completedv,
                    'incompletedv' => $incompletedv,
                );
                return $charts;
            }
            else if($_GET['type'] == "Canvasser Charts")
            {
                $janpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '01')->count();
                $febpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '02')->count();
                $marpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '03')->count();
                $aprpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '04')->count();
                $maypr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '05')->count();
                $junpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '06')->count();
                $julpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '07')->count();
                $augpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '08')->count();
                $septpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '09')->count();
                $octpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '10')->count();
                $novpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '11')->count();
                $decpr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->whereMonth('canvasses.created_at', '12')->count();
                $maxpr = PRequest::count();
                $completepr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->where('canvasses.status','Completed')->count();
                $incompletepr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->where('canvasses.status','!=','Completed')->count();

                $charts = array(
                    'janpr' => $janpr,
                    'febpr' => $febpr,
                    'marpr' => $marpr,
                    'aprpr' => $aprpr,
                    'maypr' => $maypr,
                    'junpr' => $junpr,
                    'julpr' => $julpr,
                    'augpr' => $augpr,
                    'septpr' => $septpr,
                    'octpr' => $octpr,
                    'novpr' => $novpr,
                    'decpr' => $decpr,
                    'maxpr' => $maxpr,
                    'completepr' => $completepr,
                    'incompletepr' => $incompletepr,
                );
                return $charts;
            }
            else if($_GET['type'] == "TO Charts")
            {
                $janto = TOrder::whereMonth('created_at', '01')->count();
                $febto = TOrder::whereMonth('created_at', '02')->count();
                $marto = TOrder::whereMonth('created_at', '03')->count();
                $aprto = TOrder::whereMonth('created_at', '04')->count();
                $mayto = TOrder::whereMonth('created_at', '05')->count();
                $junto = TOrder::whereMonth('created_at', '06')->count();
                $julto = TOrder::whereMonth('created_at', '07')->count();
                $augto = TOrder::whereMonth('created_at', '08')->count();
                $septto = TOrder::whereMonth('created_at', '09')->count();
                $octto = TOrder::whereMonth('created_at', '10')->count();
                $novto = TOrder::whereMonth('created_at', '11')->count();
                $decto = TOrder::whereMonth('created_at', '12')->count();
                $maxto = TOrder::count();
                $completeto = TOrder::where('status','Received by HRMO / Completed')
                    ->orWhere('status','For HRMO')
                    ->count();
                $incompleteto = TOrder::where([
                    ['status','!=','Received by HRMO / Completed'],
                    ['status','!=','For HRMO'],
                ])->count();

                $jandv = Voucher::whereMonth('created_at', '01')->count();
                $febdv = Voucher::whereMonth('created_at', '02')->count();
                $mardv = Voucher::whereMonth('created_at', '03')->count();
                $aprdv = Voucher::whereMonth('created_at', '04')->count();
                $maydv = Voucher::whereMonth('created_at', '05')->count();
                $jundv = Voucher::whereMonth('created_at', '06')->count();
                $juldv = Voucher::whereMonth('created_at', '07')->count();
                $augdv = Voucher::whereMonth('created_at', '08')->count();
                $septdv = Voucher::whereMonth('created_at', '09')->count();
                $octdv = Voucher::whereMonth('created_at', '10')->count();
                $novdv = Voucher::whereMonth('created_at', '11')->count();
                $decdv = Voucher::whereMonth('created_at', '12')->count();
                $maxdv = Voucher::count();
                $completedv = Voucher::where('status','Completed')->count();
                $incompletedv = Voucher::where('status','!=','Completed')->count();

                $charts = array(
                    'janto' => $janto,
                    'febto' => $febto,
                    'marto' => $marto,
                    'aprto' => $aprto,
                    'mayto' => $mayto,
                    'junto' => $junto,
                    'julto' => $julto,
                    'augto' => $augto,
                    'septto' => $septto,
                    'octto' => $octto,
                    'novto' => $novto,
                    'decto' => $decto,
                    'maxto' => $maxto,
                    'completeto' => $completeto,
                    'incompleteto' => $incompleteto,
                    'jandv' => $jandv,
                    'febdv' => $febdv,
                    'mardv' => $mardv,
                    'aprdv' => $aprdv,
                    'maydv' => $maydv,
                    'jundv' => $jundv,
                    'juldv' => $juldv,
                    'augdv' => $augdv,
                    'septdv' => $septdv,
                    'octdv' => $octdv,
                    'novdv' => $novdv,
                    'decdv' => $decdv,
                    'maxdv' => $maxdv,
                    'completedv' => $completedv,
                    'incompletedv' => $incompletedv,
                );
                return $charts;
            }
        }
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
        //
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
        //
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
