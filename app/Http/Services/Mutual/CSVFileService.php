<?php

namespace App\Http\Services\Mutual;

use App\Http\Traits\Responser;
use Illuminate\Validation\ValidationException;

class CSVFileService
{
    use Responser;
    public function store($file, $columns, $repository)
    {
        if (($handle = fopen($file, "r")) !== false) {
            $headers = fgetcsv($handle);
        }
        if ($headers === false || count(array_diff($columns, $headers)) !== 0) {
            fclose($handle);
            throw ValidationException::withMessages(["message" => "file columns should be" . implode(", ", $columns)]);
        }
        if (auth('api')->user()->products()->exists()) {
            auth('api')->user()->products()->delete();
        }
        while (($row = fgetcsv($handle)) !== false) {
            $rowData = array_combine($headers, $row);
            $rowData['user_id'] = auth('api')->id();
            $repository->create($rowData);
        }
        fclose($handle);
        return true;
    }
}
