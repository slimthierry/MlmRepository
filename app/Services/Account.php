<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Account;
use Money\Money;
use Money\Currency;
use App\Exceptions\
            {
            InvalidAccountEntryValue,
            InvalidAccountMethod,
            DebitsAndCreditsDoNotEqual,
            TransactionCouldNotBeProcessed
            };
use Illuminate\Support\Facades\DB;

class Accounting
{
    /**
     * @var array
     */
    protected $transactions_pending = [];

    public static function newDoubleEntryTransactionGroup(): Accounting
    {
        return new self;
    }

    /**
     * @param Account $account
     * @param string $method
     * @param Money $money
     * @param Carbon|null $created_at
     * @throws InvalidJournalEntryValue
     * @throws InvalidJournalMethod
     * @internal param int $value
     */
    function addTransaction(
        Account $account,
        string $method,
        Money $money,
        Carbon $created_at = null
    ): void {

        if (!in_array($method, ['credit', 'debit'])) {
            throw new InvalidAccountMethod;
        }

        if ($money->getAmount() <= 0) {
            throw new InvalidAccountEntryValue();
        }

        $this->transactions_pending[] = [
            'account' => $account,
            'method' => $method,
            'money' => $money,
            'created_at' => $created_at
        ];
    }

    /**
     * @param Account $account
     * @param string $method
     * @param $value
     * @param Carbon|null $created_at
     * @throws InvalidJournalEntryValue
     * @throws InvalidJournalMethod
     */
    function addDollarTransaction(
        Account $account,
        string $method,
        $value,
        Carbon $created_at = null
    ): void {
        $value = (int)($value * 100);
        $money = new Money($value, new Currency('USD'));
        $this->addTransaction($account, $method, $money, $created_at);
    }

    function getTransactionsPending(): array
    {
        return $this->transactions_pending;
    }

    public function commit(): string
    {
        $this->verifyTransactionCreditsEqualDebits();
        try {
            $transactionUUID = \Ramsey\Uuid\Uuid::uuid4()->toString();

            DB::beginTransaction();

            foreach ($this->transactions_pending as $transaction_pending) {
                $transaction = $transaction_pending['account']
                ->{$transaction_pending['method']}
                    ($transaction_pending['money'],
                    $transaction_pending['created_at'], $transactionUUID);
                if ($object = $transaction_pending['referenced_object']) {
                    $transaction->referencesObject($object);
                }
            }

            DB::commit();

            return $transactionUUID;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new TransactionCouldNotBeProcessed('Rolling Back Database. Message: ' . $e->getMessage());
        }
    }

    /**
     * @throws DebitsAndCreditsDoNotEqual
     */
    private function verifyTransactionCreditsEqualDebits(): void
    {
        $credits = 0;
        $debits = 0;

        foreach ($this->transactions_pending as $transaction_pending) {
            if ($transaction_pending['method'] == 'credit') {
                $credits += $transaction_pending['money']->getAmount();
            } else {
                $debits += $transaction_pending['money']->getAmount();
            }
        }

        if ($credits !== $debits) {
            throw new DebitsAndCreditsDoNotEqual('In this transaction, credits == ' . $credits . ' and debits == ' . $debits);
        }
    }
}
