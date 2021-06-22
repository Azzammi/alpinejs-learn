<?php

namespace App\Http\Controllers;

use Session;
use DB;
use App\Month;
use App\Volume;
use App\Transaction;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Ramsey\Uuid\v1;



class BatchtransactionController extends Controller
{
    public function viewloading($id){
        $tgltr = date('Y-m-d');
        $edit = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$id)->get();
        return view('viewloading',['loading' => $edit]);
    }
    public function SchedulePerJam($machine_code, $tgldv, $jam){
        if ($jam == 0){
        $schedulebcp = DB::table('delivery_schedule')
        ->where('mach_code','=',$machine_code)->where('schedule_date',$tgldv)->sum('24') ;
        } else{
        $schedulebcp =  DB::table('delivery_schedule')
        ->where('mach_code','=',$machine_code)->where('schedule_date',$tgldv)->sum($jam);
        }
        return $schedulebcp;
    }
    public function ScheduleDay($machine_code, $tgldv){
        $deliveryjatiasih = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$machine_code)->sum('1-24hr');
        return $deliveryjatiasih;
    }
    public function LoadingDay($machine_code, $tgltr){
        $dayjatiasih = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_jt)->sum('batch_vol');
        return $dayjatiasih;
    }
    public function TargetAndCapacityPerMonth($plant){
        $targetjatiasih = Month::where('plant','=',$plant)->first();
        return $targetjatiasih;
    }
    public function SelisihJam($tgltr, $jam, $jamtotal){
        $time1jt = new \DateTime("$tgltr $jam");
        $time2jt = new \DateTime("$tgltr $jamtotal");
        $selisihjam = $time1jt->diff($time2jt)->h;
        return $selisihjam;
    }

    public function batchtransaction(){

    $now = Carbon::now();
    $month = Carbon::now()->format('m');
    $year = Carbon::now()->format('Y');

    $tgltr = date('Y-m-d');
    $tgldv = date('d/m/Y');

    date_default_timezone_set('Asia/Jakarta');
    $jam = date('G');
    $jamtotal = date('H:i:s');
    $jam = $jam == 0 ? 24 : $jam;

    //machinecode
    $mach_jt = 'R01JAT-801' ;
    $mach_byh3 = 'R05BYH-803';
    $mach_bjn = 'R07BJN-801';
    $mach_msp = 'R15MSP-801';
    $mach_lgk = 'R25LGK-801';
    $mach_cmp = 'R28CMP-801';
    $mach_tgd = 'R32TGD-801';
    $mach_bcp = 'R35BCP-801';
    $mach_srp = 'R37SRP-801';
    $mach_tab = 'R38TAB-801';
    $mach_ctp = 'R41CTP-801';
    $mach_csk = 'R42CSK-801';

    //plant
    $jatiasih = 'PLANT JATIASIH 1';
	$byh3 = 'PLANT BAYAH3';
	$bjn = 'PLANT BOJONEGARA';
	$msp = 'PLANT MASPION';
	$lgk = 'PLANT LEGOK';
	$cmp = 'PLANT CEMPAKA PUTIH';
	$tgd = 'PLANT TGD';
	$bcp = 'PLANT BATUCEPER';
	$srp = 'PLANT SERPAN';
	$tab = 'PLANT TAB';
	$csk = 'PLANT CISAUK';
	$ctp = 'PLANT CITEUREUP';


    //loadingday
    $totalloadingtr = Transaction::where('delv_date',$tgltr)->sum('batch_vol');
    $daymsp = Volume::where('tanggal',$tgltr)->sum('volume');
    $loadingday = $totalloadingtr + $daymsp ;

    //schejulday
    $scheduleday = Schedule::where('schedule_date',$tgldv)->sum('1-24hr');

    //loadingmonth
    $loadingmonth = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->sum('batch_vol');
    $msploading = Volume::whereMonth('tanggal','=', $month)->whereYear('tanggal','=', $year)->where('plant','PLANT MASPION')->sum('volume');
    $totalloadingmonth = $loadingmonth + $msploading ;
    $targetmonth = Month::sum('jumlah');

    //scheduleperjam
    $schedulejatiasih = $this->SchedulePerJam($mach_jt, $tgldv, $jam);   //Contoh
    $schedulelgk = DB::table('delivery_schedule');    
    $schedulecmp = DB::table('delivery_schedule');    
    $schedulebjn = DB::table('delivery_schedule');
    $schedulebyh3 = DB::table('delivery_schedule');    
    $scheduletgd = DB::table('delivery_schedule');    
    $schedulesrp = DB::table('delivery_schedule');    
    $schedulebcp = DB::table('delivery_schedule');    
    $schedulemsp = DB::table('delivery_schedule');    
    $scheduletab = DB::table('delivery_schedule');    
    $schedulecsk = DB::table('delivery_schedule');    
    $schedulectp = DB::table('delivery_schedule');

    //scheduleday
    $deliveryjatiasih = $this->ScheduleDay($mach_jt,$tgldv); // Alternatif
    $deliverylegok = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_lgk)->sum('1-24hr');
    $deliverycmp = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_cmp)->sum('1-24hr');
    $deliverybjn = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_bjn)->sum('1-24hr');
    $deliverybyh3 = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_byh3)->sum('1-24hr');
    $deliverytgd = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_tgd)->sum('1-24hr');
    $deliverysrp = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_srp)->sum('1-24hr');
    $deliverybcp = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_bcp)->sum('1-24hr');
    $deliverymsp = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_msp)->sum('1-24hr');
    $deliverytab = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_tab)->sum('1-24hr');
    $deliverycsk = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_csk)->sum('1-24hr');
    $deliveryctp = Schedule::where('schedule_date','=', $tgldv)->where('mach_code','=',$mach_ctp)->sum('1-24hr');

    //loadingday
    $dayjatiasih = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_jt)->sum('batch_vol');
    $daylegok = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_lgk)->sum('batch_vol');
    $daycmp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_cmp)->sum('batch_vol');
    $daybjn = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_bjn)->sum('batch_vol');
    $daybyh3 = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_byh3)->sum('batch_vol');
    $daytgd = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_tgd)->sum('batch_vol');
    $daysrp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_srp)->sum('batch_vol');
    $daybcp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_bcp)->sum('batch_vol');
    $daytab = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_tab)->sum('batch_vol');
    $daycsk = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_csk)->sum('batch_vol');
    $dayctp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_ctp)->sum('batch_vol');

    //target&capacityperbulan
    $targetjatiasih = Month::where('plant','=',$jatiasih)->first();
    $targetlegok = Month::where('plant','=',$lgk)->first();
    $targetcmp = Month::where('plant','=',$cmp)->first();
    $targetbjn = Month::where('plant','=',$bjn)->first();
    $targetbyh3 = Month::where('plant','=',$byh3)->first();
    $targettgd = Month::where('plant','=',$tgd)->first();
    $targetsrp = Month::where('plant','=',$srp)->first();
    $targetbcp = Month::where('plant','=',$bcp)->first();
    $targetmsp = Month::where('plant','=',$msp)->first();
    $targettab = Month::where('plant','=',$tab)->first();
    $targetcsk = Month::where('plant','=',$csk)->first();
    $targetctp = Month::where('plant','=',$ctp)->first();

      //seting
    $jatiasihset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R01JAT-801')->orderBy('id', 'desc')->first();
    $legokset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R25LGK-801')->orderBy('id', 'desc')->first();
    $cmpset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R28CMP-801')->orderBy('id', 'desc')->first();
    $bjnset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R07BJN-801')->orderBy('id', 'desc')->first();
    $byh3set = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R05BYH-803')->orderBy('id', 'desc')->first();
    $tgdset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R32TGD-801')->orderBy('id', 'desc')->first();
    $mspset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R15MSP-801')->orderBy('id', 'desc')->first();
    $srpset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R37SRP-801')->orderBy('id', 'desc')->first();
    $bcpset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R35BCP-801')->orderBy('id', 'desc')->first();
    $tabset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R38TAB-801')->orderBy('id', 'desc')->first();
    $cskset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R42CSK-801')->orderBy('id', 'desc')->first();
    $ctpset = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R41CTP-801')->orderBy('id', 'desc')->first();

    //loadingmonth
    $jatiasihtotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R01JAT-801')->sum('batch_vol');
    $legoktotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R25LGK-801')->sum('batch_vol');
    $cmptotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R28CMP-801')->sum('batch_vol');
    $bjntotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R07BJN-801')->sum('batch_vol');
    $byh3total = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R05BYH-803')->sum('batch_vol');
    $tgdtotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R32TGD-801')->sum('batch_vol');
    $srptotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R37SRP-801')->sum('batch_vol');
    $bcptotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R35BCP-801')->sum('batch_vol');
    $msptotal = Volume::whereMonth('tanggal','=', $month)->whereYear('tanggal','=', $year)->where('plant','PLANT MASPION')->sum('volume');
    $tabtotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R38TAB-801')->sum('batch_vol');
    $csktotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R42CSK-801')->sum('batch_vol');
    $ctptotal = Transaction::whereMonth('delv_date','=', $month)->whereYear('delv_date','=', $year)->where('machine_code','R41CTP-801')->sum('batch_vol');

    //startloading
    //Dicoba bang ini
    $start = Transaction::whereIn('devl_date', array($mach_jt, $mach_lgk, $mach_cmp, $mach_bjn, $mach_byh3, $mach_tgd, $mach_srp, $mach_bcp, $mach_msp, $mach_tab, $mach_csk, $mach_ctp))->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->get();
    dd($start);

    /*
    $startjatiasih = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_jt)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startlegok= Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_lgk)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startcmp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_cmp)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startbjn = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_bjn)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startbyh3 = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_byh3)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $starttgd = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_tgd)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startsrp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_srp)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startbcp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_bcp)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startmsp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_msp)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $starttab = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_tab)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startcsk = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_csk)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    $startctp = Transaction::where('delv_date','=', $tgltr)->where('machine_code','=',$mach_ctp)->where('delv_time' ,'>=','08:00:00')
    ->orderBy('delv_time','ASC')->first();
    */

    //finishloading
    $jatiasihend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R01JAT-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $legokend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R25LGK-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $cmpend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R28CMP-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $bjnend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R07BJN-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $byh3end = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R05BYH-803')->orderBy('delv_time', 'desc')->first('delv_time');
    $tgdend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R32TGD-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $srpend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R37SRP-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $bcpend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R35BCP-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $mspend = Volume::where('tanggal','=', $tgltr)->where('plant','PLANT MASPION')->first();
    $tabend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R38TAB-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $cskend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R42CSK-801')->orderBy('delv_time', 'desc')->first('delv_time');
    $ctpend = Transaction::where('delv_date','=', $tgltr)->where('machine_code','R41CTP-801')->orderBy('delv_time', 'desc')->first('delv_time');

    //selisih jam
    $jamjt = $jatiasihend['delv_time'];
    $jamlegok = $legokend['delv_time'];
    $jamcmp = $cmpend['delv_time'];
    $jambjn = $bjnend['delv_time'];
    $jambyh3 = $byh3end['delv_time'];
    $jamtgd = $tgdend['delv_time'];
    $jamsrp = $srpend['delv_time'];
    $jambcp = $bcpend['delv_time'];
    $jammsp=  $mspend['waktu'];
    $jamtab = $tabend['delv_time'];
    $jamcsk = $cskend['delv_time'];
    $jamctp = $ctpend['delv_time'];

   
    $selisihjamjatiasih = $this->SelisihJam($tgltr, $jamjt, $jamtotal); //Contoh    
    $selisihjamlegok = $this->SelisihJam($tgltr, $jamlegok, $jamtotal); //Contoh 2
    $time1cmp = new \DateTime("$tgltr $jamcmp");
    $time2cmp = new \DateTime("$tgltr $jamtotal");
    $selisihjamcmp = $time1cmp->diff($time2cmp)->h;
    $time1bjn = new \DateTime("$tgltr $jambjn");
    $time2bjn = new \DateTime("$tgltr $jamtotal");
    $selisihjambjn = $time1bjn->diff($time2bjn)->h;
    $time1byh3 = new \DateTime("$tgltr $jambyh3");
    $time2byh3 = new \DateTime("$tgltr $jamtotal");
    $selisihjambyh3 = $time1byh3->diff($time2byh3)->h;
    $time1tgd = new \DateTime("$tgltr $jamtgd");
    $time2tgd = new \DateTime("$tgltr $jamtotal");
    $selisihjamtgd = $time1tgd->diff($time2tgd)->h;
    $time1srp = new \DateTime("$tgltr $jamsrp");
    $time2srp = new \DateTime("$tgltr $jamtotal");
    $selisihjamsrp = $time1srp->diff($time2srp)->h;
    $time1bcp = new \DateTime("$tgltr $jambcp");
    $time2bcp = new \DateTime("$tgltr $jamtotal");
    $selisihjambcp = $time1bcp->diff($time2bcp)->h;
    $time1msp = new \DateTime("$tgltr $jammsp");
    $time2msp = new \DateTime("$tgltr $jamtotal");
    $selisihjammsp = $time1msp->diff($time2msp)->h;
    $time1tab = new \DateTime("$tgltr $jamtab");
    $time2tab = new \DateTime("$tgltr $jamtotal");
    $selisihjamtab = $time1tab->diff($time2tab)->h;
    $time1csk = new \DateTime("$tgltr $jamcsk");
    $time2csk = new \DateTime("$tgltr $jamtotal");
    $selisihjamcsk = $time1csk->diff($time2csk)->h;
    $time1ctp = new \DateTime("$tgltr $jamctp");
    $time2ctp = new \DateTime("$tgltr $jamtotal");
    $selisihjamctp = $time1ctp->diff($time2ctp)->h;

    //CapacityPlant



    return view('batchtransaction', compact('scheduleday', 'loadingday', 'totalloadingmonth','targetmonth','schedulejatiasih','schedulelgk'
                ,'schedulecmp','schedulebjn','schedulebyh3','scheduletgd','schedulesrp','schedulebcp','schedulemsp','scheduletab','schedulecsk','schedulectp',
                'deliveryjatiasih','deliverylegok','deliverycmp','deliverybjn','deliverybyh3','deliverytgd','deliverysrp','deliverybcp',
                'deliverymsp','deliverytab','deliverycsk','deliveryctp','dayjatiasih','daylegok','daycmp','daybjn','daybyh3','daytgd','daytgd',
                'daysrp','daybcp','daymsp','daytab','daycsk','dayctp','targetjatiasih','targetlegok','targetcmp','targetbjn','targetbyh3',
                'targettgd','targetsrp','targetbcp','targetmsp','targettab','targetcsk','targetctp','jatiasihset','legokset','cmpset','bjnset','byh3set',
                'tgdset','srpset','bcpset','mspset','tabset','cskset','ctpset','jatiasihtotal','jatiasihtotal','legoktotal','cmptotal','bjntotal',
                'byh3total','tgdtotal','srptotal','bcptotal','msptotal','tabtotal','csktotal','ctptotal','startjatiasih','startlegok','startcmp',
                'startbjn','startbyh3','starttgd','startsrp','startbcp','starttab','startcsk','startctp','jamjt','jamlegok','jamcmp','jambjn',
                'jambyh3','jamtgd','jamsrp','jambcp','jammsp','jamtab','jamcsk','jamctp','selisihjamjatiasih','selisihjamlegok','selisihjamcmp',
                'selisihjambjn','selisihjambyh3','selisihjamtgd','selisihjamsrp','selisihjambcp','selisihjammsp','selisihjamtab','selisihjamcsk','selisihjamctp',
                'mach_jt','mach_lgk','mach_cmp','mach_bjn','mach_byh3','mach_tgd','mach_srp','mach_bcp','mach_msp','mach_tab','mach_csk','mach_ctp'
            ));
    }
    
    //
}
