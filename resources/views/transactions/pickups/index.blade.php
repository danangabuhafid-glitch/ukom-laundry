@extends('layouts.app')
@section('title', 'Pickups')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold mb-0">Laundry Pickups</h5>
                    <form action="{{ route('pickups.index') }}" method="GET" class="d-flex w-50" id="form-search-pickup">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Search TRX Code or Customer Name" value="{{ request('search') }}">
                    </form>
                </div>
                <div id="pickup-table-container">
                    @include('transactions.pickups.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Re-bind sweetalert to forms after ajax load
        function bindConfirmButtons() {
            const forms = document.querySelectorAll('.form-confirm-pickup');
            forms.forEach(form => {
                // Remove existing listeners to prevent duplicates if any
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);

                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = this.querySelector('button');
                    const orderCode = btn.getAttribute('data-code');

                    Swal.fire({
                        title: 'Confirm Pickup',
                        text: "Mark order " + orderCode + " as picked up? You can add optional remarks/notes below.",
                        icon: 'question',
                        input: 'textarea',
                        inputPlaceholder: 'Type your remarks/notes here (optional)...',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, confirm!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const notesInput = this.querySelector('.pickup-notes');
                            if (notesInput) notesInput.value = result.value || '';
                            this.submit();
                        }
                    });
                });
            });
        }
        
        bindConfirmButtons();

        // Auto Search on Input with AJAX
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('form-search-pickup');
        const tableContainer = document.getElementById('pickup-table-container');
        let searchTimeout;

        if (searchInput && searchForm) {
            // Focus and move cursor to end
            const val = searchInput.value;
            if (val) {
                searchInput.focus();
                searchInput.setSelectionRange(val.length, val.length);
            }

            searchForm.addEventListener('submit', e => e.preventDefault());

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = encodeURIComponent(searchInput.value);
                    const url = `${searchForm.action}?search=${query}`;
                    
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        bindConfirmButtons();
                        // Also update pagination links to use AJAX if needed, 
                        // but since the requirement is just searching, this is fine.
                    })
                    .catch(error => console.error('Error fetching data:', error));

                }, 500); // Wait 500ms after last keystroke before searching
            });
        }
    });
</script>
@endpush
