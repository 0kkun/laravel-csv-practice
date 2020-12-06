<?php

namespace App\Modules;

use Illuminate\Support\Collection;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use SplFileObject;

class CsvGenerator
{
    /**
     * CSVダウンロード
     * 
     * @param string $file_name
     * @param array $data_list
     * @param array $head - 1行まで
     * @param bool $is_chunk
     * @return StreamedResponse
     */
    static function csvDownload(string $file_name, array $data_list, array $head, bool $is_chunk): StreamedResponse
    {
        Log::info('CSVダウンロード開始'. 'ファイル名：' . $file_name);

        $response = response()->stream(
            function () use ($data_list, $head, $is_chunk) {
                // ファイル生成
                $spl_object = new SplFileObject('php://output', 'w');
                // 変数の文字コードを変換する
                mb_convert_variables('SJIS', 'UTF-8', $head);
                mb_convert_variables('SJIS', 'UTF-8', $data_list);
                // ヘッダ挿入
                $spl_object->fputcsv($head);

                // chunkしているかどうかで条件分岐
                if ( $is_chunk ) {
                    foreach ($data_list as $rows) {
                        foreach ( $rows as $row ) {
                            $spl_object->fputcsv($row);
                        }
                    }
                } else {
                    foreach ($data_list as $row) {
                        $spl_object->fputcsv($row);
                    }
                }
            },
            200,
            [
                'Etag' => time(),
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $file_name . '.csv"',
            ]
        );
        Log::info('ダウンロード成功');
        return $response;
    }


    /**
     * CSVダウンロードを実行
     * publicディレクトリに保存される
     * 参考：https://snome.jp/framework/laravel-csv/
     *
     * @param string $file_name
     * @param array $head
     * @param array $data
     * @return void
     */
    static function downloadToPublic(string $file_name, array $head, array $data): void
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
