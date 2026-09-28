<?php

namespace App\Traits;

use App\Lib\FileManager;
use Illuminate\Support\Facades\Schema;

trait FileExport
{

    /**
     * Contains the file name like all_contact.csv
     */
    public $fileName;

    /**
     * Which columns to be exported
     *
     */
    public $exportColumns;


    /**
     * How many rows want to export from database
     *
     */
    public $exportItem;

    /**
     * Data ordering
     *
     */
    public $orderBy;


    public  function export()
    {
        $modelName = get_class();
        $columns   = $this->getColumNames();

        if ($this->exportColumns) {
            $data = $modelName::orderBy('id', $this->orderBy)->take($this->exportItem);
            if (count($this->exportColumns) != count($columns)) {
                foreach ($this->exportColumns as $column) {
                    $data = $data->whereNotNull($column);
                }
            }
            $data = $data->select($this->exportColumns)->get();
            $columns = $this->exportColumns;
        } else {
            $data = $modelName::select('*')->get();
        }

        if ($data->count() <= 0) {
            $notify[] = ['warning', 'No data found'];
            return back()->withNotify($notify);
        }

        if ($this->exportColumns) {
            $columns = array_intersect($columns, $this->exportColumns);
        }

        $fileManager = new FileManager();
        $fileManager->makeDirectory("assets/admin/export_files/");
        $fileName = "assets/admin/export_files/" . $this->fileName;
        $fp       = fopen($fileName, 'w');
        fputcsv($fp, $columns);
        foreach ($data as $item) {
            fputcsv($fp, $item->toArray());
        }
        fclose($fp);
        return response()->download($fileName);
    }

    public static function getColumNames()
    {
        $modelName = get_class();
        $tableName = app($modelName)->getTable();
        $columns   = Schema::getColumnListing($tableName);
        return $columns;
    }
}
