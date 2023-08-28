<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class FuasExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(Request $request)
    {
        $fuasGenerados = DB::select("SELECT NroFua AS NumeroFua,U.name FROM software_ufpa_general.dbo.FUA F 
                                    INNER JOIN [software_ufpa_general].[dbo].[users] U ON F.IdUsuario = U.id");

        return collect($fuasGenerados);
    }
}
