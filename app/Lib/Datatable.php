<?php

namespace App\Lib;


class Datatable
{
    public static $request;
    public static $query;
    public static $records_total;
    public static $records_filtered;

    static function make ($model, $primaryKey='id',$actions='') {
        self::$request = $_GET;
        self::$query = $model;
        self::setRecordsTotal();
        self::filter();
        self::setRecordsFiltered();
        self::orderLimit();
        self::renderJson($primaryKey, $actions);
        return true;
    }

    // set total record count
    static function setRecordsTotal () {
        self::$records_total = self::$query->count();
    }

    // filter by search query
    static function filter () {
        if (isset(self::$request['search']['value'])) {
            self::$query->where(function ($where){
                    $where->where(self::$request['columns'][0]['data'], 'like', '%' . self::$request['search']['value'] . '%');
                    foreach (self::$request['columns'] as $column) {
                        if ($column['searchable'] == 'true') {
                            if(!empty($column['data'])) {
                                $where->orWhere($column['data'], 'like', '%' . self::$request['search']['value'] . '%');
                            }
                        }
                    }
                });

        }
    }

    // set filtered record count
    static function setRecordsFiltered () {
        self::$records_filtered = self::$query->count();
    }

    // apply order by & limit
    static function orderLimit () {
        if ( isset(self::$request['order']) && count(self::$request['order']) ) {
            self::$query->orderBy(self::$request['columns'][self::$request['order'][0]['column']]['data'], self::$request['order'][0]['dir']);
            self::$query->skip(self::$request['start'])->take(self::$request['length']);
        }
    }

    // render json output
    static function renderJson ($primaryKey, $actions) {
        $array = [];
        $array['draw'] = isset ( self::$request['draw'] ) ? intval(self::$request['draw']) : 0;
        $array['recordsTotal'] = self::$records_total;
        $array['recordsFiltered'] = self::$records_filtered;
        $array['data'] = [];
        $results = self::$query->get();

        //Add action button to array
        foreach ($results as $key => &$result) {
            $result['action'] = self::actions($result->{$primaryKey}, $actions);
        }

        foreach ($results as $result) {
            $array['data'][] = $result->toArray();
        }

        $draw = json_encode($array);
        echo $draw;

    }

    static function actions($id,$action){
        $action=str_replace('{rowId}',$id,$action);
        return $action;
    }

}
