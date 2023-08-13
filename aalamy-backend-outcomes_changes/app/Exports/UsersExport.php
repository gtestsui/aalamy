<?php

namespace App\Exports;

use App\Models\BusinessPlan;
use App\Models\User;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromView
{
    use Exportable;


    public $businessPlan;
    public function __construct($businessPlan){
        $this->businessPlan = $businessPlan;
    }

    public function view(): View
    {
        return view('export-business-plan', [
            'businessPlan' => $this->businessPlan,
            'headerArray' => $this->headings(),
            'headingOfExecutionPlan' => $this->headingOfExecutionPlan(),
            'headingOfExpectedRevenues' => $this->headingOfExpectedRevenues()
        ]);
    }

//    public function query(){
//        return BusinessPlan::query()->where('id',24)
//            ->with(['ExpectedRevenues','ExecutionPlan','BusinessType','Country','State','City']);
//    }

    public function headings(): array
    {
        return [
            'Business plan start name',
            'Idea name',
            'Business type name',
            'Vision',
            'Mission',
            'The legal structure',
            'Business formation history',
            'Location in EN',
            'Location in AR',
            'Market analysis',
            'Product analysis',
            'Competitor analysis',
            'Client analysis',
            'Sales and marketing plan',
            'Financial plan',
            'Created at'
        ];
    }

    public function headingOfExecutionPlan(){
        return [
            'action_to_be_done',
            'title',
            'responsibilities',
            'powers',
            'who report to him',
            'start date',
            'end date',
            'expected result',
            'cost',
            'Years Of Exp',
            'Special Skills',
            'Knowledge About Specific Items',
        ];
    }

    public function headingOfExpectedRevenues(){
        return [
            'Year num',
            'Revenues',
            'Currency'
        ];
    }

//    public function map($businessPlan): array
//    {
//        $executionPlan = [];
//        foreach($businessPlan->ExecutionPlan as $executionPlan){
//            $executionPlan [] = 'title: '.$executionPlan->title.
//                'responsibilities: '.$executionPlan->responsibilities.
//                'powers: '.$executionPlan->powers.
//                'who report to him: '.$executionPlan->who_report_to_him.
//                'start date: '.$executionPlan->start_date.
//                'end date: '.$executionPlan->end_date.
//                'expected result: '.$executionPlan->expected_result.
//                'cost: '.$executionPlan->cost.'|';
//        }
//        return [
////            $executionPlan,
//            $businessPlan->start_name,
//            $businessPlan->Idea->name,
//            $businessPlan->BusinessType->name_en.'('.$businessPlan->BusinessType->name_ar.')',
//            $businessPlan->vision,
//            $businessPlan->mission,
//            $businessPlan->the_legal_structure,
//            $businessPlan->business_formation_history,
//
//            'location in english :'.
//            $businessPlan->Country->name_en.'/'.
//            $businessPlan->State->name_en.'/'.
//            $businessPlan->City->name_en.'/'.
//            $businessPlan->street,
//
//            'location in arabic :'.
//            $businessPlan->Country->name_ar.'/'.
//            $businessPlan->Country->name_ar.'/'.
//            $businessPlan->City->name_ar.'/'.
//            $businessPlan->street,
//
//            $businessPlan->market_analysis,
//            $businessPlan->product_analysis,
//            $businessPlan->competitor_analysis,
//            $businessPlan->client_analysis,
//            $businessPlan->sales_and_marketing_plan,
//            $businessPlan->financial_plan,
//
//            $businessPlan->created_at,
////            Date::dateTimeToExcel($invoice->created_at),
////            $invoice->total
//        ];
//    }
    /**
    * @return \Illuminate\Support\Collection
    */
//    public function collection()
//    {
////        if(is_null($this->businessPlan->satrt_name))
////            $start_name = $this->businessPlan->Idea->name;
////        else
////            $start_name = $this->businessPlan->start_name;
////
////        $businessType = $this->businessPlan->BusinessType->name_en.'('.$this->businessPlan->BusinessType->name_ar.')';
////
////
////
////        $array = [
////            $start_name,
////            $businessType,
////            $this->businessPlan->vision,
////            $this->businessPlan->vision,
////            $this->businessPlan->mission,
////            $this->businessPlan->the_legal_structure,
////            $this->businessPlan->business_formation_history,
////            $this->businessPlan->means_of_doing_business,
////
////            'location in english :'.
////            $this->businessPlan->Country->name_en.'/'.
////            $this->businessPlan->State->name_en.'/'.
////            $this->businessPlan->City->name_en.'/'.
////            $this->businessPlan->street,
////
////            'location in english :'.
////            $this->businessPlan->Country->name_ar.'/'.
////            $this->businessPlan->State->name_ar.'/'.
////            $this->businessPlan->City->name_ar.'/'.
////            $this->businessPlan->street,
////
////            $this->businessPlan->market_analysis,
////            $this->businessPlan->product_analysis,
////            $this->businessPlan->competitor_analysis,
////            $this->businessPlan->client_analysis,
////            $this->businessPlan->sales_and_marketing_plan,
////            $this->businessPlan->financial_plan,
////
////        ];
////        $collection = new Collection($this->businessPlan);
//        return $this->businessPlan;
//    }
}
