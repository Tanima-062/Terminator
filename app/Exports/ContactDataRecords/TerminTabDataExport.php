<?php

namespace App\Exports\ContactDataRecords;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TerminTabDataExport implements FromView {

    public function __construct(public $data = [])
    {

    }

    public function view(): View
    {

        $languages_content = file_get_contents(base_path(sprintf('node_modules/@cospired/i18n-iso-languages/langs/%s.json', auth()->user()->language->code)));

        $languages = json_decode(($languages_content));

        return view('exports.contact-data-records.termin')->with([
            'records'   =>  $this->data,
            'languages' =>  $languages->languages
        ]);
    }
}
