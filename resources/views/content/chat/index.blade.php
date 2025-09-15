@extends('layouts/contentNavbarLayout')

@section('title', 'Chat con IA')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

@section('content')
    <style>
        #loading-screen {
            display: none !important;
        }

        /* Contenedor general del chat */
        #chat-container {
            margin-top: 20px;
            max-height: 500px;
            overflow-y: auto;
            border-radius: 12px;
            background: #f7f7f8;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Estilo para cada mensaje */
        .mensaje {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 1rem;
            line-height: 1.4;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .mensaje pre {
            background: #1e1e1e;
            color: #fff;
            padding: 10px;
            border-radius: 6px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
        }

        .mensaje code {
            background: #307e00;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }

        .mensaje ul,
        .mensaje ol {
            margin: 2px 0 4px 10px;
        }

        /* Mensajes usuario alineados a la derecha */
        .usuario {
            align-self: flex-end;
            background: #0b93f6;
            color: white;
            border-bottom-right-radius: 4px;
        }

        /* Mensajes bot alineados a la izquierda */
        .bot {
            align-self: flex-start;
            background: #e1e4eb;
            color: #202124;
            border-bottom-left-radius: 4px;
        }

        /* Loader / mensaje escribiendo */
        .bot-loader {
            align-self: flex-start;
            font-style: italic;
            color: #666;
            padding-left: 4px;
        }

        /* Formulario chat */
        #chat-form {
            margin-top: 16px;
            display: flex;
            gap: 8px;
        }

        #mensaje {
            flex: 1;
            padding: 12px 16px;
            border-radius: 24px;
            border: 1px solid #ccc;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s ease-in-out;
        }

        #mensaje:focus {
            border-color: #0b93f6;
            box-shadow: 0 0 5px rgba(11, 147, 246, 0.5);
        }

        button[type="submit"] {
            background-color: #0b93f6;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 0 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        button[type="submit"]:hover {
            background-color: #0873d3;
        }
    </style>

    <h1>💬 Chat con IA Local</h1>

    <form id="chat-form" autocomplete="off">
        @csrf
        <input type="text" id="mensaje" name="mensaje" placeholder="Escribí tu pregunta..." required>
        <button type="submit">Enviar</button>
    </form>
    {{-- <button onclick="limpiarHistorial()" style="margin-top: 10px;">🧹 Limpiar chat</button> --}}

    <div id="chat-container" role="log" aria-live="polite" aria-relevant="additions">
        <!-- Mensajes se irán agregando acá -->
    </div>

    <script>
        // dom 
        document.addEventListener('DOMContentLoaded', function() {


            const chatForm = document.getElementById('chat-form');
            const mensajeInput = document.getElementById('mensaje');
            const chatContainer = document.getElementById('chat-container');

            function agregarMensaje(texto, clase) {
                const mensajeDiv = document.createElement('div');
                mensajeDiv.className = 'mensaje ' + clase;
                mensajeDiv.textContent = texto;
                chatContainer.appendChild(mensajeDiv);
                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'smooth'
                });
            }

            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const mensaje = mensajeInput.value.trim();
                if (!mensaje) return;

                agregarMensaje(mensaje, 'usuario');

                mensajeInput.value = '';
                mensajeInput.focus();

                agregarMensaje('🤖 Bot está escribiendo...', 'bot-loader');

                try {
                    const token = document.querySelector('input[name="_token"]').value;

                    const response = await fetch("{{ route('chat.enviar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            mensaje
                        })
                    });

                    if (!response.ok) throw new Error('Error en la petición');

                    const data = await response.json();

                    const loader = chatContainer.querySelector('.bot-loader');
                    if (loader) chatContainer.removeChild(loader);

                    agregarMensaje(data.respuesta, 'bot');

                } catch (error) {
                    const loader = chatContainer.querySelector('.bot-loader');
                    if (loader) chatContainer.removeChild(loader);

                    agregarMensaje('❌ Error: ' + error.message, 'bot');
                }
            });

            function guardarMensajeEnHistorial(texto, clase) {
                let historial = JSON.parse(localStorage.getItem('chat_historial')) || [];
                historial.push({
                    texto,
                    clase
                });
                localStorage.setItem('chat_historial', JSON.stringify(historial));
            }
        });
    </script>

@endsection
