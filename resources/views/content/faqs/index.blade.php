@extends('layouts/contentNavbarLayout')

@section('title', 'Preguntas frecuentes')
@section('content')
    @include('components.toast')
    <h1>Preguntas Frecuentes</h1>

    {{-- Formulario para agregar una nueva FAQ --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('faqs.store') }}" method="POST">
                <div class="d-flex w-100 justify-content-between">

                    <h5 class="card-title">Agregar Nueva FAQ</h5>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                @csrf
                <div class="mb-3">
                    <label for="question" class="form-label">Pregunta</label>
                    <input type="text" name="question" id="question" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="answer" class="form-label">Respuesta</label>
                    <textarea name="answer" id="answer" class="form-control" rows="3" required></textarea>
                </div>

            </form>
        </div>
    </div>

    {{-- Acordeón de FAQs --}}
    <div class="accordion" id="accordionExample">
        @foreach ($faqs as $index => $faq)
            <div class="card accordion-item" data-id="{{ $faq->id }}">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button type="button" class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" data-bs-target="#accordion{{ $index }}"
                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="accordion{{ $index }}">
                        {{ $faq->question }}
                    </button>
                </h2>
                <div id="accordion{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                    aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p>
                            {{ $faq->answer }}
                        </p>
                        <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="openEditModal({{ $faq->id }}, '{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}')">Editar</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal para editar FAQ --}}
    <div class="modal fade" id="editFaqModal" tabindex="-1" aria-labelledby="editFaqModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFaqModalLabel">Editar FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFaqForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editQuestion" class="form-label">Pregunta</label>
                            <input type="text" name="question" id="editQuestion" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAnswer" class="form-label">Respuesta</label>
                            <textarea name="answer" id="editAnswer" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        var el = document.querySelector('#accordionExample');
        var sortable = Sortable.create(el, {
            animation: 150,
            onStart: function(evt) {
                evt.item.classList.add('moving');
            },
            onEnd: function(evt) {
                evt.item.classList.remove('moving');
                var order = [];
                document.querySelectorAll('.accordion-item').forEach(function(item) {
                    order.push(item.getAttribute('data-id'));
                });

                fetch("{{ route('faqs.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order: order
                        })
                    })
                    .then(response => response.json())
                    .then(data => console.log(data))
                    .catch(error => console.error('Error:', error));
            }
        });

        function openEditModal(id, question, answer) {
            document.getElementById('editQuestion').value = question;
            document.getElementById('editAnswer').value = answer;

            // Configura el envío del formulario del modal
            document.getElementById('editFaqForm').onsubmit = function(event) {
                event.preventDefault();
                editFaq(id);
            };

            var myModal = new bootstrap.Modal(document.getElementById('editFaqModal'));
            myModal.show();
        }

        function editFaq(id) {
            // Recopila los datos del formulario
            const question = document.getElementById('editQuestion').value;
            const answer = document.getElementById('editAnswer').value;

            // Envía los nuevos valores a la API con una solicitud PUT
            fetch(`/admin/faqs/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        question: question,
                        answer: answer
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al actualizar la FAQ');
                    }
                    location.reload(); // Recargar para ver los cambios
                })
                .catch(error => alert(error.message));
        }
    </script>

@endsection
