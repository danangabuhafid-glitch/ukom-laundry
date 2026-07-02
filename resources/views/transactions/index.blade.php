@extends('layouts.app')
@section('title', 'Transactions')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Transactions</h5>
                    @can('create-transactions')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
                        <i class="ti ti-plus"></i> New Transaction
                    </button>
                    @endcan
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <!-- Filter Tabs -->
                    <div class="btn-group" role="group" aria-label="Transaction Filters">
                        <button type="button" class="btn btn-outline-primary active filter-btn" data-status="all">Semua</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-status="0">Baru</button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-status="1">Sudah Diambil</button>
                    </div>
                    
                    <form class="d-flex w-25" id="form-search-transaction">
                        <input type="text" name="search" id="search-input" class="form-control" placeholder="Search TRX Code or Name" value="{{ request('search') }}">
                    </form>
                </div>

                <div id="transaction-table-container">
                    @include('transactions.table', ['orders' => $orders])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div class="modal fade" id="createTransactionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <form id="form-create-transaction" action="{{ route('transactions.store') }}">
                    @csrf
                    <div class="row">
                        <!-- Customer Column -->
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3">Customer Info</h6>
                                    
                                    <div class="mb-3">
                                        <select class="form-select" id="id_customer" name="id_customer" required>
                                            <option value="">Select Customer</option>
                                            <option value="new">+ Create New Customer</option>
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}">{{ $customer->customer_name }} ({{ $customer->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Existing Customer Info Card -->
                                    <div id="existing_customer_info" class="mt-3 d-none">
                                        <div class="p-3 border rounded bg-primary-subtle text-primary border-primary">
                                            <div class="fs-3 mb-1"><i class="ti ti-phone fs-4 me-1"></i> <span id="info_phone">-</span></div>
                                            <div class="fs-3"><i class="ti ti-map-pin fs-4 me-1"></i> <span id="info_address">-</span></div>
                                        </div>
                                    </div>

                                    <!-- New Customer Fields -->
                                    <div id="new_customer_fields" class="mt-3 d-none">
                                        <div class="mb-2">
                                            <input type="text" class="form-control form-control-sm" id="new_customer_name" name="new_customer_name" placeholder="Name">
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control form-control-sm" id="new_customer_phone" name="new_customer_phone" placeholder="Phone">
                                        </div>
                                        <div class="mb-0">
                                            <textarea class="form-control form-control-sm" id="new_customer_address" name="new_customer_address" rows="2" placeholder="Address"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services / Cart Column -->
                        <div class="col-md-8 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3">Services Cart</h6>
                                    
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-7">
                                            <select class="form-select" id="id_service">
                                                <option value="">Select Service to Add</option>
                                                @foreach($services as $service)
                                                @php
                                                    $activePromo = $promos->first(function($p) use ($service) {
                                                        return $p->services->isEmpty() || $p->services->contains('id', $service->id);
                                                    });
                                                    $promoText = '';
                                                    if ($activePromo) {
                                                        $promoVal = $activePromo->discount_type == 'percent' ? floatval($activePromo->discount_value) . '%' : 'Rp ' . number_format($activePromo->discount_value, 0, ',', '.');
                                                        $promoText = " (Promo: -$promoVal min {$activePromo->min_qty}Kg)";
                                                    }
                                                @endphp
                                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-name="{{ $service->service_name }}">{{ $service->service_name }} - Rp {{ number_format($service->price, 0, ',', '.') }}/kg {{ $promoText }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="number" step="0.1" class="form-control" id="qty" placeholder="Qty (Kg)">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="btn-add-service" class="btn btn-secondary w-100"><i class="ti ti-plus"></i> Add</button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Service</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Subtotal</th>
                                                    <th width="50">Act</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cart-table-body">
                                                <tr><td colspan="5" class="text-center text-muted">Cart is empty</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm border-0 bg-success-subtle">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="form-label">Payment Method</label>
                                            <select class="form-select" id="payment_method" name="payment_method" required>
                                                @foreach($paymentMethods as $pm)
                                                <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="form-label">Subtotal</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                                <input type="text" class="form-control nominal-input bg-white" id="subtotal_display" value="0" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="form-label">Tax ({{ $taxRate }}%)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                                <input type="text" class="form-control nominal-input bg-white" id="tax_display" value="0" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="form-label text-primary fw-bold">Grand Total</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light fw-bold text-primary">Rp</span>
                                                <input type="text" class="form-control nominal-input fw-bold bg-white text-primary" id="grand_total_display" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mt-3">
                                        <div class="col-md-6 mb-2 mb-md-0">
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="form-label text-success fw-bold">Uang Bayar</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                                                <input type="text" class="form-control nominal-input fw-bold" id="order_pay" name="order_pay" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Kembalian</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                                <input type="text" class="form-control nominal-input fw-bold bg-white" id="order_change_display" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-submit-transaction" class="btn btn-primary"><i class="ti ti-check"></i> Process Transaction</button>
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

        const filterBtns = document.querySelectorAll('.filter-btn');
        const tableContainer = document.getElementById('transaction-table-container');
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('form-search-transaction');
        let searchTimeout;

        function fetchTransactions() {
            const activeBtn = document.querySelector('.filter-btn.active');
            const status = activeBtn ? activeBtn.getAttribute('data-status') : 'all';
            const query = searchInput ? searchInput.value : '';
            
            const url = new URL(window.location.href);
            if (status !== 'all') {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }

            if (query) {
                url.searchParams.set('search', query);
            } else {
                url.searchParams.delete('search');
            }
            
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.display = 'block'; // Show loader
                preloader.style.opacity = '0.7'; // Make it slightly transparent so they see it's an overlay
                preloader.style.zIndex = '999999';
            }
            
            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                tableContainer.innerHTML = html;
                bindConfirmButtons();
            })
            .finally(() => {
                if (preloader) {
                    preloader.style.display = 'none';
                    preloader.style.opacity = '1';
                }
            });
        }

        // Filters logic
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                fetchTransactions();
            });
        });

        // Search logic
        if (searchInput && searchForm) {
            searchForm.addEventListener('submit', e => e.preventDefault());
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(fetchTransactions, 500);
            });
        }

        // Cart Logic
        let cart = [];
        let subtotalBeforeTax = 0;
        let grandTotal = 0;
        const availablePromos = @json($promos);
        const taxRate = {{ $taxRate ?? 0 }};

        const customerSelect = document.getElementById('id_customer');
        const newCustomerFields = document.getElementById('new_customer_fields');
        const nameInput = document.getElementById('new_customer_name');
        const phoneInput = document.getElementById('new_customer_phone');
        
        const existingCustomerInfo = document.getElementById('existing_customer_info');
        const infoPhone = document.getElementById('info_phone');
        const infoAddress = document.getElementById('info_address');

        customerSelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newCustomerFields.classList.remove('d-none');
                existingCustomerInfo.classList.add('d-none');
                nameInput.setAttribute('required', 'required');
                phoneInput.setAttribute('required', 'required');
            } else if (this.value !== '') {
                newCustomerFields.classList.add('d-none');
                const selectedOption = this.options[this.selectedIndex];
                infoPhone.textContent = selectedOption.getAttribute('data-phone') || '-';
                infoAddress.textContent = selectedOption.getAttribute('data-address') || '-';
                existingCustomerInfo.classList.remove('d-none');
                nameInput.removeAttribute('required');
                phoneInput.removeAttribute('required');
            } else {
                newCustomerFields.classList.add('d-none');
                existingCustomerInfo.classList.add('d-none');
                nameInput.removeAttribute('required');
                phoneInput.removeAttribute('required');
            }
        });

        // Add to Cart
        const serviceSelect = document.getElementById('id_service');
        const qtyInput = document.getElementById('qty');
        const btnAddService = document.getElementById('btn-add-service');
        const cartTableBody = document.getElementById('cart-table-body');
        const subtotalDisplay = document.getElementById('subtotal_display');
        const taxDisplay = document.getElementById('tax_display');
        const grandTotalDisplay = document.getElementById('grand_total_display');
        const orderPayInput = document.getElementById('order_pay');
        const orderChangeDisplay = document.getElementById('order_change_display');

        btnAddService.addEventListener('click', function() {
            const serviceId = serviceSelect.value;
            const qty = parseFloat(qtyInput.value);
            
            if (!serviceId || isNaN(qty) || qty <= 0) {
                alert('Please select a service and enter a valid quantity.');
                return;
            }

            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const serviceName = selectedOption.getAttribute('data-name');
            const price = parseFloat(selectedOption.getAttribute('data-price'));

            // Check if already exists in cart, update qty instead
            const existingIndex = cart.findIndex(item => item.id_service === serviceId);
            if (existingIndex > -1) {
                cart[existingIndex].qty += qty;
            } else {
                cart.push({
                    id_service: serviceId,
                    name: serviceName,
                    price: price,
                    qty: qty
                });
            }

            // reset inputs
            serviceSelect.value = '';
            qtyInput.value = '';

            renderCart();
        });

        function calculateItemSubtotal(item, hasAppliedPromo) {
            let subtotal = item.price * item.qty;
            let discountAmount = 0;
            let appliedPromoName = null;

            if (!hasAppliedPromo) {
                // Find applicable promo (Check if services array is empty OR includes this service)
                let promo = availablePromos.find(p => {
                    return p.services.length === 0 || p.services.some(s => s.id == item.id_service);
                });

                if (promo && item.qty >= parseFloat(promo.min_qty)) {
                    if (promo.discount_type === 'percent') {
                        discountAmount = subtotal * (parseFloat(promo.discount_value) / 100);
                    } else {
                        discountAmount = parseFloat(promo.discount_value);
                    }
                    if (discountAmount > 0) {
                        appliedPromoName = promo.name;
                    }
                }
            }
            
            // Ensure discount doesn't exceed subtotal
            if (discountAmount > subtotal) discountAmount = subtotal;

            return {
                originalSubtotal: subtotal,
                discountAmount: discountAmount,
                finalSubtotal: subtotal - discountAmount,
                promoName: appliedPromoName
            };
        }

        function renderCart() {
            if (cart.length === 0) {
                cartTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Cart is empty</td></tr>';
                subtotalBeforeTax = 0;
            } else {
                cartTableBody.innerHTML = '';
                subtotalBeforeTax = 0;
                let hasAppliedPromo = false;

                cart.forEach((item, index) => {
                    const calc = calculateItemSubtotal(item, hasAppliedPromo);
                    subtotalBeforeTax += calc.finalSubtotal;
                    
                    if (calc.discountAmount > 0) {
                        hasAppliedPromo = true; // Block other promos
                    }
                    
                    let discountHtml = '';
                    if (calc.discountAmount > 0) {
                        discountHtml = `<br><small class="text-danger">-${calc.promoName} (Rp ${calc.discountAmount.toLocaleString('id-ID')})</small>`;
                    }

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${item.name}</td>
                        <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                        <td>${item.qty} Kg</td>
                        <td>Rp ${calc.finalSubtotal.toLocaleString('id-ID')} ${discountHtml}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger btn-remove-cart" data-index="${index}">
                                <i class="ti ti-x"></i>
                            </button>
                        </td>
                    `;
                    cartTableBody.appendChild(tr);
                });
            }

            let taxAmount = subtotalBeforeTax * (taxRate / 100);
            grandTotal = subtotalBeforeTax + taxAmount;

            subtotalDisplay.value = subtotalBeforeTax.toLocaleString('id-ID');
            taxDisplay.value = taxAmount.toLocaleString('id-ID');
            grandTotalDisplay.value = grandTotal.toLocaleString('id-ID');
            calculateChange();
        }

        // Event delegation for remove button
        cartTableBody.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-cart')) {
                const index = e.target.closest('.btn-remove-cart').getAttribute('data-index');
                cart.splice(index, 1);
                renderCart();
            }
        });

        orderPayInput.addEventListener('input', calculateChange);

        function calculateChange() {
            if (cart.length === 0) {
                orderChangeDisplay.value = '0';
                orderChangeDisplay.classList.remove('text-success', 'text-danger');
                return;
            }
            const pay = window.parseNominal(orderPayInput.value) || 0;
            const change = pay - grandTotal;
            if (change >= 0) {
                orderChangeDisplay.value = change.toLocaleString('id-ID');
                orderChangeDisplay.classList.remove('text-danger');
                orderChangeDisplay.classList.add('text-success');
            } else {
                orderChangeDisplay.value = "-" + Math.abs(change).toLocaleString('id-ID');
                orderChangeDisplay.classList.remove('text-success');
                orderChangeDisplay.classList.add('text-danger');
            }
        }

        // Submit Transaction
        const btnSubmit = document.getElementById('btn-submit-transaction');
        const form = document.getElementById('form-create-transaction');
        
        btnSubmit.addEventListener('click', function() {
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (cart.length === 0) {
                Swal.fire({icon: 'error', title: 'Cart Empty', text: 'Please add at least one service!'});
                return;
            }

            const pay = window.parseNominal(orderPayInput.value) || 0;
            if (pay < grandTotal) {
                Swal.fire({icon: 'error', title: 'Uang Kurang', text: 'Uang bayar tidak boleh kurang dari total tagihan!'});
                return;
            }

            const formData = new FormData(form);

            // Reconstruct JSON payload for arrays
            const payload = {
                id_customer: formData.get('id_customer'),
                new_customer_name: formData.get('new_customer_name'),
                new_customer_phone: formData.get('new_customer_phone'),
                new_customer_address: formData.get('new_customer_address'),
                payment_method: formData.get('payment_method'),
                order_pay: pay,
                services: cart.map(item => ({ id_service: item.id_service, qty: item.qty }))
            };

            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json().then(data => ({status: res.status, body: data})))
            .then(response => {
                if (response.status === 200 || response.status === 201) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Transaksi berhasil disimpan.',
                        showCancelButton: true,
                        confirmButtonText: '<i class="ti ti-printer"></i> Print Struk',
                        cancelButtonText: 'Tutup',
                        confirmButtonColor: '#13deb9',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(`/transactions/${response.body.order_id}/print`, '_blank');
                        }
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', response.body.message || 'Validation failed', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'An unexpected error occurred.', 'error');
                console.error(err);
            });
        });

    });
</script>
@endpush
