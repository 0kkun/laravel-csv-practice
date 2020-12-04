<?php

namespace App\Services\Csv;

use Illuminate\Support\Collection;

interface CsvServiceInterface
{
    /**
     * CSVダウンロードを実行
     *
     * @param string $file_name
     * @param array $head
     * @param array $data
     * @return void
     */
    public function download(string $file_name, array $head, array $data): void;
}