<?php
use App\Models\Branch;
    function branchId()
    {
        $branches = Branch::where('company_id', auth()->user()->model_id)->get();
        return $branches->pluck('id');
    }
