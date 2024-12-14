<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index()
    {
        // Ambil data transaksi dari database, pastikan menggunakan model yang benar
        $transactions = Transaction::simplePaginate(5);

        // Mengirimkan data transaksi ke view
        return view('frontend.user.transaction.index', compact('transactions'));
    }

    public function show($id)
    {
        // Ambil transaksi berdasarkan id
        $transaction = Transaction::findOrFail($id);
        return view('frontend.user.transaction-show', compact('transaction'));
    }

    public function destroy($id)
    {
        // Hapus transaksi berdasarkan id
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('user.transaction-history')->with('success', 'Transaction deleted successfully.');
    }
}


