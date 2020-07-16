<?php

namespace App\Exports\Banking;

use App\Abstracts\Export;
use App\Models\Transaction as Model;

class Transactions extends Export
{
    public function collection()
    {
        $model = Model::with('account')->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $model->account_name = $model->account->name;

        if ($model->type == 'income') {
            $model->invoice_bill_number = $model->invoice ? $model->invoice->invoice_number : 0;
        } else {
            $model->invoice_bill_number = $model->bill ? $model->bill->bill_number : 0;
        }

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'type',
            'amount',
            'account_name',
            'payment_method',

        ];
    }
}
