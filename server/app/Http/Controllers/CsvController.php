<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Csv\CsvService;
use App\Services\Csv\CsvServiceInterface;
use App\Modules\CsvGenerator;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class CsvController extends Controller
{
    private $csv_service;

    const CHUNK_SIZE = 100;

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


    public function download()
    {
        $is_chunk = true;

        $head = ['id', 'name', 'sex', 'message'];

        for ($i=0; $i<20; $i++) {
            $data_list[] = [
                'id' => $i,
                'name' => 'taro No.' . $i,
                'sex' => '男',
                'message' => "Hello! I'm Taro's"
            ];
        }
        $data_list = array_chunk($data_list, self::CHUNK_SIZE);

        $today = Carbon::today()->format('Y-m-d');
        $file_name = 'CsvDownloadTest_' . $today . '.csv';

        try {
            return CsvGenerator::csvDownload($file_name, $data_list, $head, $is_chunk, $is_chunk);
        } catch (Exception $e) {
            Log::error([$e->getMessage(), $e->getTraceAsString()]);
            return redirect()->back()->withErrors('CSVデータダウンロード中にエラーが発生しました。');
        }
    }
}
