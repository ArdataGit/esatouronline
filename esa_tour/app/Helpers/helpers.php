<?php
use App\Models\Bank;

function calculate_bank_income($data, $nominal, $is_deduction = false)
{
    // dd('masok');
    if ($is_deduction) {
        // dd('masok');
        $data->update([
            'income'    => $data->income - $nominal,
            'balance'   => ($data->beginning_balance + $data->income) - $nominal - $data->expense,
        ]);
    }else{
        $data->update([
            'income'    => $data->income + $nominal,
            'balance'   => ($data->income + $nominal + $data->beginning_balance) - $data->expense,
        ]);
    }

    // dd('maosk');
    return $data;
}

function calculate_receivables($data, $nominal, $is_increase = false)
{
    if ($is_increase) {
        $data->update([
            'receivables' => $data->receivables + $nominal,
        ]);

    }else{
        $data->update([
            'receivables' => $data->receivables - $nominal,
        ]);
    }

    return $data;
}

function calculate_bank_expense($data, $nominal, $is_increase = false)
{
    if ($is_increase) {
        $data->update([
            'expense'   => $data->expense + $nominal,
            'balance'   => ($data->beginning_balance + $data->income) - ($data->expense + $nominal)
        ]);

    }else{
        $data->update([
            'expense'   => $data->expense - $nominal,
            'balance'   => ($data->beginning_balance + $data->income) - ($data->expense - $nominal)
        ]);
    }

    return $data;
}

function calculate_sell_product($data, $nominal, $is_increase = true)
{
    if ($is_increase) {
        $data->update([
            'sale'      => $data->sale + $nominal,
            'profit'    => ($data->sale + $nominal) - $data->purchase
        ]);
    }
}

function calculate_purchase_product($data, $nominal, $is_increase = true)
{
    if ($is_increase) {
        $data->update([
            'purchase'   => $data->purchase + $nominal,
            'profit'    => ($data->sale) - ($data->purchase + $nominal)
        ]);
    }
}

function calculate_profit_product($data, $is_increase = true)
{
    if ($is_increase) {
        $data->update([
            'profit'   => $data->profit + ($data->selling_price - $data->purchase_price),
        ]);
    }
}

function calculate_deposit_income($data, $nominal, $is_deduction = false)
{
    // dd('masok');
    if ($is_deduction) {
        // dd('masok');
        $data->update([
            'income'    => $data->income - $nominal,
            'balance'   => ($data->beginning_balance + $data->income) - $nominal - $data->expense,
        ]);
    }else{
        $data->update([
            'income'    => $data->income + $nominal,
            'balance'   => ($data->income + $nominal +  $data->beginning_balance) - $data->expense,
        ]);
    }

    // dd('maosk');
    return $data;
}

function calculate_deposit_expense($data, $nominal, $is_increase = false)
{
    if ($is_increase) {
        $data->update([
            'expense'   => $data->expense + $nominal,
            'balance'   => ($data->income + $data->beginning_balance) - ($data->expense + $nominal)
        ]);

    }else{
        $data->update([
            'expense'   => $data->expense - $nominal,
            'balance'   => ($data->income + $data->beginning_balance) + ($data->expense - $nominal)
        ]);
    }

    return $data;
}

function format_nominal($nominal)
{
    $nominal = str_replace('.', '', $nominal);
    $nominal = str_replace(',', '', $nominal);
    $nominal = preg_replace('/[^0-9]/', '', $nominal);
    
    return $nominal;
}