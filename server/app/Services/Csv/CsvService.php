<?php

namespace App\Services\Csv;

use Illuminate\Support\Collection;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Response;
use App\Services\Csv\CsvServiceInterface;

class CsvService implements CsvServiceInterface
{

    /**
     * constructor.
     * @param
     */
    public function __construct(
    )
    {
    }

    /**
     * CSVダウンロードを実行
     * publicディレクトリに保存される
     *
     * @param string $file_name
     * @param array $head
     * @param array $data
     * @return void
     */
    public function download(string $file_name, array $head, array $data): void
    {
        // 書き込み用ファイルを開く
        $f = fopen($file_name, 'w');
        if ($f) {
            // カラムの書き込み
            mb_convert_variables('SJIS', 'UTF-8', $head);
            fputcsv($f, $head);
            // データの書き込み
            foreach ($data as $line) {
                mb_convert_variables('SJIS', 'UTF-8', $line);
                fputcsv($f, $line);
            }
        }
        // ファイルを閉じる
        fclose($f);

        // HTTPヘッダ
        header("Content-Type: application/octet-stream");
        header('Content-Length: '.filesize($file_name));
        header('Content-Disposition: attachment; filename=' . $file_name);
        readfile($file_name);
    }
}