<?php
use App\Models\Branch;
    function branchId()
    {
        $branches = Branch::where('company_id', auth()->user()->model_id)->get();
        return $branches->pluck('id');
    }

    function getBranch()
    {
        $branch = Branch::find( auth()->user()->model_id);
        return $branch;
    }
