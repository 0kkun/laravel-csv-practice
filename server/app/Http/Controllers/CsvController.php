<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Csv\CsvService;
use App\Services\Csv\CsvServiceInterface;
use Carbon\Carbon;

class CsvController extends Controller
{
    private $csv_service;

    /**
     * CsvController constractor
     *
     * @param CsvServiceInterface $csv_service
     */
    public function __construct(
        CsvService $csv_service
    )
    {
        $this->csv_service = $csv_service;
    }


    public function index()
    {
        return view('csv.index');
    }


    public function export()
    {
        for ($i=0; $i<20; $i++) {
            $data[] = [
                'id' => $i,
                'name' => 'taro No.' . $i,
                'sex' => '男',
                'message' => "Hello! I'm Taro's"
            ];
        }

        $head = ['id', 'name', 'sex', 'message'];

        $today = Carbon::today()->format('Y-m-d');
        $file_name = 'CsvDownloadTest_' . $today . '.csv';

        $this->csv_service->download($file_name, $head, $data);

        return view('csv.index');
    }
}
