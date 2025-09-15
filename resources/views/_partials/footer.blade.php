<style>
    ul li {
        margin-bottom: 0px;
    }
</style>
<footer class="footer pt-5 pb-5 mt-auto">
    <div class="container">

        <div class="row">
            {{-- Col 1: Logo + descripción + (opcionales) redes --}}
            <div class="col-lg-3 mb-4">
                <p class="mb-3">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="img-fluid" style="max-height:400px">
                </p>
                <p class="mb-2">IV Circunscripción Judicial Rio Negro</p>

                {{-- Redes (solo si tenés links cargados en $footer) --}}

            </div>

            {{-- Col 2: Institucional (PDFs desde "Leyes y otros") --}}
            <div class="col-lg-3 mb-4">
                <h3 class="footer-heading"><span>Institucional</span></h3>
                <ul class="list-unstyled">
                    <li class="mb-0">
                        <a class="dropdown-item" href="{{ route('autoridades.inicio') }}">
                            Autoridades
                        </a>
                    </li>
                    @forelse ($leyesNavFiles as $archivo)
                        <li class="mb-0">
                            <a class="dropdown-item d-flex align-items-center justify-content-between"
                                href="{{ asset(env('ARCHIVOS_GENERALES_LEYES_PATH') . basename($archivo->ruta)) }}"
                                target="_blank" rel="noopener">
                                <span class="text-truncate"
                                    style="max-width:260px">{{ \Illuminate\Support\Str::beforeLast($archivo->nombre_original, '.') }}</span>

                            </a>
                        </li>
                    @empty
                        <li class="mb-0"><span class="dropdown-item-text text-muted">No hay archivos</span></li>
                    @endforelse
                </ul>
            </div>

            {{-- Col 3: Colegiados (links internos) --}}
            <div class="col-lg-3 mb-4">
                <h3 class="footer-heading"><span>Colegiados</span></h3>
                <ul class="list-unstyled">
                    <li class="mb-0"><a href="https://rionegro.gov.ar/?contID=38298" target="_blank">Requisitos de
                            Colegiación</a>
                    </li>
                    <li class="mb-0"><a href="{{ route('faqs_inicio') }}">Preguntas Frecuentes</a></li>
                    <li class="mb-0"><a href="{{ route('padron.publico') }}">Padrón de colegiados</a></li>
                    <li class="mb-0"><a href="{{ route('inmobiliarias.lista') }}">Inmobiliarias registradas</a></li>
                    <li class="mb-0"><a href="{{ route('noticias_inicio') }}">Noticias</a></li>
                </ul>
            </div>

            {{-- Col 4: Contacto --}}
            <div class="col-lg-3 mb-4 quick-contact">
                <h3 class="footer-heading"><span>Contacto</span></h3>
                <ul class="list-unstyled">
                    @if (!empty($footer->direccion))
                        <li class="mb-0">
                            @php
                                $maps = !empty($footer->maps_url) ? $footer->maps_url : null;
                            @endphp
                            @if ($maps)
                                <a href="{{ $maps }}" target="_blank"
                                    rel="noopener">{{ $footer->direccion }}</a>
                            @else
                                <span>{{ $footer->direccion }}</span>
                            @endif
                        </li>
                    @endif

                    {{-- Emails múltiples si existen, si no, el principal --}}
                    @if (!empty($footer->email))
                        <li class="mb-0"><a href="mailto:{{ $footer->email }}">{{ $footer->email }}</a></li>
                    @endif
                    @if (!empty($footer->email_secretaria))
                        <li class="mb-0"><a
                                href="mailto:{{ $footer->email_secretaria }}">{{ $footer->email_secretaria }}</a></li>
                    @endif
                    @if (!empty($footer->email_fiscalizacion))
                        <li class="mb-0"><a
                                href="mailto:{{ $footer->email_fiscalizacion }}">{{ $footer->email_fiscalizacion }}</a>
                        </li>
                    @endif
                    @if (!empty($footer->email_tesoreria))
                        <li class="mb-0"><a
                                href="mailto:{{ $footer->email_tesoreria }}">{{ $footer->email_tesoreria }}</a></li>
                    @endif
                    @if (!empty($footer->email_denuncias))
                        <li class="mb-0"><a
                                href="mailto:{{ $footer->email_denuncias }}">{{ $footer->email_denuncias }}</a></li>
                    @endif

                    @if (!empty($footer->telefono))
                        <li class="mb-0"><a
                                href="tel:{{ preg_replace('/\s+/', '', $footer->telefono) }}">{{ $footer->telefono }}</a>
                        </li>
                    @endif
                    @if (!empty($footer->whatsapp))
                        <li class="mb-0"><a target="_blank" rel="noopener"
                                href="https://wa.me/{{ preg_replace('/\D+/', '', $footer->whatsapp) }}">WhatsApp</a>
                        </li>
                    @endif
                    <div class="d-flex gap-2 flex-row social pt-3">
                        @if (!empty($footer->facebook))
                            <a class="d-flex justify-content-center align-items-center" href="{{ $footer->facebook }}"
                                target="_blank" rel="noopener">
                                <i class="bi bi-facebook"></i>
                            </a>
                        @endif
                        @if (!empty($footer->instagram))
                            <a class="d-flex justify-content-center align-items-center" href="{{ $footer->instagram }}"
                                target="_blank" rel="noopener">
                                <i class="bi bi-instagram"></i>
                            </a>
                        @endif
                    </div>
                </ul>
            </div>
        </div>

        {{-- Créditos / links legales --}}
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-xl-8 text-center text-xl-start mb-3 mb-xl-0">
                &copy;
                <script>
                    document.write(new Date().getFullYear());
                </script>
                Todos los derechos reservados por IV Circunscripción Judicial Rio Negro.
            </div>
            <div
                class="col-xl-4 justify-content-start justify-content-xl-end quick-links d-flex flex-column flex-xl-row text-center text-xl-start gap-3">
                <a href="{{ url('/terminos_y_condiciones') }}">Términos y Condiciones</a>
                <a href="{{ url('/politica_privacidad') }}">Políticas de Privacidad</a>
            </div>
        </div>

    </div>
</footer>
