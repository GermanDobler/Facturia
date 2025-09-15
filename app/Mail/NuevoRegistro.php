<?php
// app/Mail/NuevoRegistro.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevoRegistro extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Nuevo registro de usuario')
                    ->view('emails.nuevo_registro')
                    ->with([
                        'usuario' => $this->usuario
                    ]);
    }
}
