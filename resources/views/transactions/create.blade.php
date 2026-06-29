@extends('layouts.app')
@section('title', 'New Transaction')
@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">New Transaction</h5>
                <form action="{{ route('transactions.store') }}" method="POST" id="form-create-transaction">
                    @csrf
                    <div class="mb-3">
                        <label for="id_customer" class="form-label">Customer</label>
                        <select class="form-select @error('id_customer') is-invalid @enderror" id="id_customer" name="id_customer" required>
                            <option value="">Select Customer</option>
                            <option value="new" {{ old('id_customer') == 'new' ? 'selected' : '' }}>+ Create New Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}" {{ old('id_customer') == $customer->id ? 'selected' : '' }}>{{ $customer->customer_name }} ({{ $customer->phone }})</option>
                            @endforeach
                        </select>
                        @error('id_customer')<div class="invalid-feedback">{{ $message }}</div>@enderror

                        <!-- Existing Customer Info Card -->
                        <div id="existing_customer_info" class="mt-3 d-none">
                            <div class="p-3 border rounded bg-primary-subtle text-primary border-primary">
                                <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-user fs-4 me-1"></i> Customer Preview</h6>
                                <div class="fs-3 mb-1"><i class="ti ti-phone fs-4 me-1"></i> <span id="info_phone">-</span></div>
                                <div class="fs-3"><i class="ti ti-map-pin fs-4 me-1"></i> <span id="info_address">-</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- New Customer Fields (Hidden by default) -->
                    <div id="new_customer_fields" class="mb-3 p-3 border rounded bg-light {{ old('id_customer') == 'new' ? '' : 'd-none' }}">
                        <h6 class="fw-semibold mb-3">New Customer Details</h6>
                        <div class="mb-3">
                            <label for="new_customer_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('new_customer_name') is-invalid @enderror" id="new_customer_name" name="new_customer_name" value="{{ old('new_customer_name') }}">
                            @error('new_customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_customer_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('new_customer_phone') is-invalid @enderror" id="new_customer_phone" name="new_customer_phone" value="{{ old('new_customer_phone') }}">
                            @error('new_customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0">
                            <label for="new_customer_address" class="form-label">Address</label>
                            <textarea class="form-control @error('new_customer_address') is-invalid @enderror" id="new_customer_address" name="new_customer_address" rows="2">{{ old('new_customer_address') }}</textarea>
                            @error('new_customer_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_service" class="form-label">Service</label>
                        <select class="form-select @error('id_service') is-invalid @enderror" id="id_service" name="id_service" required>
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}" {{ old('id_service') == $service->id ? 'selected' : '' }}>{{ $service->service_name }} - Rp {{ number_format($service->price, 0, ',', '.') }}/kg</option>
                            @endforeach
                        </select>
                        @error('id_service')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">Quantity (Kg)</label>
                        <input type="number" step="0.1" class="form-control @error('qty') is-invalid @enderror" id="qty" name="qty" value="{{ old('qty') }}" required>
                        @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4 d-none" id="total_price_container">
                        <label class="form-label">Total Harga</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-light fw-bold">Rp</span>
                            <input type="text" class="form-control bg-light fw-bold text-primary fs-4" id="total_price_display" value="0" readonly>
                        </div>
                        
                        <label for="order_pay" class="form-label">Uang Bayar</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text fw-bold">Rp</span>
                            <input type="number" step="0.01" class="form-control @error('order_pay') is-invalid @enderror fw-bold fs-4" id="order_pay" name="order_pay" value="{{ old('order_pay') }}">
                        </div>
                        @error('order_pay')<div class="text-danger small mb-3">{{ $message }}</div>@enderror

                        <label class="form-label">Kembalian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">Rp</span>
                            <input type="text" class="form-control bg-light fw-bold text-success fs-4" id="order_change_display" value="0" readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Process Transaction</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </form>
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
        const customerSelect = document.getElementById('id_customer');
        const newCustomerFields = document.getElementById('new_customer_fields');
        const nameInput = document.getElementById('new_customer_name');
        const phoneInput = document.getElementById('new_customer_phone');
        const addressInput = document.getElementById('new_customer_address');
        const existingCustomerInfo = document.getElementById('existing_customer_info');
        const infoPhone = document.getElementById('info_phone');
        const infoAddress = document.getElementById('info_address');
        const serviceSelect = document.getElementById('id_service');
        const qtyInput = document.getElementById('qty');
        const totalPriceDisplay = document.getElementById('total_price_display');
        const orderPayInput = document.getElementById('order_pay');
        const orderChangeDisplay = document.getElementById('order_change_display');
        let currentTotal = 0;

        function toggleNewCustomerFields() {
            if (customerSelect.value === 'new') {
                newCustomerFields.classList.remove('d-none');
                existingCustomerInfo.classList.add('d-none');
                nameInput.setAttribute('required', 'required');
                phoneInput.setAttribute('required', 'required');
                addressInput.setAttribute('required', 'required');
            } else if (customerSelect.value !== '') {
                newCustomerFields.classList.add('d-none');
                
                const selectedOption = customerSelect.options[customerSelect.selectedIndex];
                infoPhone.textContent = selectedOption.getAttribute('data-phone') || '-';
                infoAddress.textContent = selectedOption.getAttribute('data-address') || '-';
                existingCustomerInfo.classList.remove('d-none');

                nameInput.removeAttribute('required');
                phoneInput.removeAttribute('required');
                addressInput.removeAttribute('required');
            } else {
                newCustomerFields.classList.add('d-none');
                existingCustomerInfo.classList.add('d-none');
                nameInput.removeAttribute('required');
                phoneInput.removeAttribute('required');
                addressInput.removeAttribute('required');
            }
        }

        function calculateTotal() {
            const container = document.getElementById('total_price_container');
            if (serviceSelect.selectedIndex > 0 || (serviceSelect.value && serviceSelect.value !== "")) {
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const qty = parseFloat(qtyInput.value) || 0;
                currentTotal = price * qty;
                totalPriceDisplay.value = currentTotal.toLocaleString('id-ID');
                
                if (currentTotal > 0) {
                    container.classList.remove('d-none');
                    orderPayInput.setAttribute('required', 'required');
                } else {
                    container.classList.add('d-none');
                    orderPayInput.removeAttribute('required');
                }
            } else {
                currentTotal = 0;
                totalPriceDisplay.value = '0';
                container.classList.add('d-none');
                orderPayInput.removeAttribute('required');
            }
            calculateChange();
        }

        function calculateChange() {
            const pay = parseFloat(orderPayInput.value) || 0;
            const change = pay - currentTotal;
            if (change >= 0) {
                orderChangeDisplay.value = change.toLocaleString('id-ID');
                orderChangeDisplay.classList.remove('text-danger');
                orderChangeDisplay.classList.add('text-success');
            } else {
                orderChangeDisplay.value = "Kurang " + Math.abs(change).toLocaleString('id-ID');
                orderChangeDisplay.classList.remove('text-success');
                orderChangeDisplay.classList.add('text-danger');
            }
        }

        customerSelect.addEventListener('change', toggleNewCustomerFields);
        serviceSelect.addEventListener('change', calculateTotal);
        qtyInput.addEventListener('input', calculateTotal);
        orderPayInput.addEventListener('input', calculateChange);
        
        // Run once on load to handle old input after validation error
        toggleNewCustomerFields();
        calculateTotal();

        // SweetAlert Confirmation
        const form = document.getElementById('form-create-transaction');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // If using default HTML5 validation, check validity first
                if (!this.checkValidity()) {
                    this.reportValidity();
                    return;
                }

                // Gather details
                let customerName = '';
                if (customerSelect.value === 'new') {
                    customerName = document.getElementById('new_customer_name').value + ' (Baru)';
                } else {
                    customerName = customerSelect.options[customerSelect.selectedIndex].text;
                }

                const serviceName = serviceSelect.options[serviceSelect.selectedIndex].text;
                const qty = qtyInput.value;
                const totalPrice = totalPriceDisplay.value;
                const pay = parseFloat(orderPayInput.value) || 0;

                if (pay < currentTotal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Uang Kurang',
                        text: 'Uang bayar tidak boleh kurang dari total harga!'
                    });
                    return;
                }

                const summaryHtml = `
                    <div class="text-start mt-3 p-3 border rounded bg-light">
                        <p class="mb-2"><strong>Customer:</strong> ${customerName}</p>
                        <p class="mb-2"><strong>Service:</strong> ${serviceName}</p>
                        <p class="mb-2"><strong>Qty:</strong> ${qty} Kg</p>
                        <hr class="my-2">
                        <p class="mb-1 text-primary fw-bold fs-4">Total: Rp ${totalPrice}</p>
                        <p class="mb-1 fw-bold text-dark">Bayar: Rp ${pay.toLocaleString('id-ID')}</p>
                        <p class="mb-0 text-success fw-bold fs-4">Kembali: Rp ${(pay - currentTotal).toLocaleString('id-ID')}</p>
                    </div>
                    <p class="mt-3 mb-0">Apakah data di atas sudah benar?</p>
                `;

                Swal.fire({
                    title: 'Konfirmasi Pesanan',
                    html: summaryHtml,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
