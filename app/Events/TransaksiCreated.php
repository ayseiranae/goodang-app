<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransaksiCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaksi;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
     * Tentukan channel broadcast
     */
    public function broadcastOn()
    {
        // channel publik bernama "transaksi"
        return new Channel('transaksi');
    }

    /**
     * Nama event di frontend
     */
    public function broadcastAs()
    {
        return 'TransaksiCreated';
    }
}
