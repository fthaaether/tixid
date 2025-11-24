<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Promo;
use App\Models\TicketPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // tiket aktif : sudah dibayar da berlaku di hari ini atau besok
        // tiket sesuai dengan milik akun yg login
        $ticketActive = Ticket::whereHas('ticketPayment', function($q) {
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', '>=', $date);
        })->where('user_id', Auth::user()->id)->get();
        // tiket tidak aktif : sudah dbaiayar dean berlaku do ahris kemarin (terlewat)
        // tiket sesuai dengan milik akun yg login
        $ticketNonActive = Ticket::whereHas('ticketPayment', function($q) {
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', '<', $date);
        })->where('user_id', Auth::user()->id)->get();
        return view('ticket.index', compact('ticketActive', 'ticketNonActive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'rows_of_seats' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'tax' => 'required',
            'hour' => 'required',
        ]);
        $createData = Ticket::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'rows_of_seats' => $request->rows_of_seats,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'tax' => $request->tax,
            'hour' => $request->hour,
            'date' => now(),
            'actived' => 0, // sblm bayar di nonaktif dulu
        ]);
        // karana fungsi ini dijalankan lewat JS, jadi return (dikembalikannya) bentuk format JSON
        return response()->json([
            'message' => 'Berhasil membuat data tiket',
            'data' => $createData,
        ]);
    }

    public function orderPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie'])->first();
        $promos = Promo::where('actived', 1)->get();
        return view('schedule.order', compact('ticket', 'promos'));
    }

    public function createQrcode(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required'
        ]);

        $ticket = Ticket::find($request['ticket_id']);
        $kodeQr = 'TICKET-' . $ticket['id']; // isi QR nya kode unik

        // format : svg/png/jpg/jpeg (bentuk gambar qrcode)
        // size : ukuran gambar
        // generate : isi qrcode yang akan dibuat
        $qrcode = QrCode::format('svg')->size(30)->margin(2)->generate($kodeQr);

        $filename = $kodeQr . '.svg'; // nama file qrcode yang akan disimpan
        $folder = 'qrcode/' . $filename; // lokasi gambar
        // simpan gambar ke storage dengan visibility public. put(lokasi, file)
        Storage::disk('public')->put($folder, $qrcode);

        $createData = TicketPayment::create([
            'ticket_id' => $ticket['id'],
            'qrcode' => $folder, // di db disimpan lokasi gmabar qr
            'booked_date' => now(),
            'status' => 'process'
        ]);
        // update promo_id pada tickets jika ada promo yang dipilih (bukan null)
        if ($request->promo_id != NULL) {
            $promo = Promo::find($request->promo_id);
            if ($promo['type'] == 'percent') {
                $discount = $ticket['total_price'] * $promo['discount'] / 100;
            } else {
                $discount = $promo['discount'];
            }
            $totalPriceNew = $ticket['total_price'] - $discount;
            $ticket->update([
                'total_price' => $totalPriceNew,
                'promo_id' => $request->promo_id
            ]);
        }
        return response()->json([
            'message' => 'Berhasil membuat data pembayaran dan update promo tiket!',
            'data' => $ticket
        ]);
    }

    public function paymentPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with('ticketPayment')->first();
        // dd($ticket);
        return view('schedule.payment', compact('ticket'));
    }

    public function updateStatusPayment(Request $request, $ticketId)
    {
        $updateData = TicketPayment::where('ticket_id', $ticketId)->update([
            'status' => 'paid-off',
            'paid_date' => now()
        ]);
        if ($updateData) {
            Ticket::where('id', $ticketId)->update(['actived' => 1]);
        }
        return redirect()->route('tickets.payment.proof', $ticketId);
    }

    public function proofPayment($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie', 'promo', 'ticketPayment'])->first();

        return view('schedule.proof-payment', compact('ticket'));
    }

    public function exportPdf($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie', 'promo', 'ticketPayment'])->first()->toArray();

        // untuk inisial nama daya uang dougnakan pada blade pdf
        view()->share('ticket', $ticket);

        // generate file blade yang dicetak pdf
        $pdf = Pdf::loadView('schedule.export-pdf', $ticket);

        $fileName = 'TICKET' . $ticket['id'] . '.pdf';
        return $pdf->download($fileName);
    }


    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
