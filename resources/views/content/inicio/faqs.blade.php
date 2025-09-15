@extends('layouts/front')

@section('title', 'Preguntas Frecuentes')

@section('layoutContent')
    @include('components.toast')

    <section class="section hero__v7 position-relative py-0" id="home">
        <div class="container" style="max-width: 800px">
            <h1 class="mt-4 text-center">
                Preguntas Frecuentes
            </h1>
            <p class="text-center contenido-p mb-5">Encuentra respuestas a las dudas más comunes</p>
            <div class="accordion" id="faqsAccordion">
                @foreach ($faqs as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                            <button class="accordion-button @if (!$loop->first) collapsed @endif"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $faq->id }}">
                                <h2>
                                    {{ $faq->question }}
                                </h2>
                            </button>
                        </h2>
                        <div id="collapse{{ $faq->id }}"
                            class="accordion-collapse collapse @if ($loop->first) show @endif"
                            aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faqsAccordion">
                            <div class="accordion-body contenido">
                                <p class="mb-0">
                                    {{ $faq->answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
<style>
    .contenido p {
        font-size: 1rem;
        line-height: 1.2rem;
        color: #221E20 !important;
        font-weight: 350;
    }

    h2 {
        font-size: 1rem !important;
        color: #221E20 !important;
        font-weight: 700 !important;
    }

    h1 {
        font-size: 1.7rem !important;
        color: #221E20 !important;
        font-weight: 700 !important;
    }
</style>
