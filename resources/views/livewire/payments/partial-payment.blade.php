<div>
    <div wire:ignore.self class="modal fade" id="modalPartialPayment" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Abono a Cuenta</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @if($sale_selected_id == null)
                    <div class="faq-form">
                        <input wire:keydown.enter.prevent="$set('search', $event.target.value)"
                            class="form-control form-control-lg" type="text" placeholder="Ingresa el nombre del cliente"
                            id="inputPartialPaySearch" style="background-color: beige">
                        <i class="search-icon" data-feather="user"></i>
                    </div>

                    {{-- @json($sales) --}}
                    <div class="order-history table-responsive  mt-2">
                        <table class="table table-bordered">
                            <thead class="">
                                <tr>
                                    <th class='p-2'> Cliente</th>
                                    <th class='p-2'>Venta</th>
                                    <th class='p-2'>Total</th>
                                    <th class='p-2'>Abonado</th>
                                    <th class='p-2'>Debe</th>
                                    <th class='p-2'></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                <tr>
                                    <td>
                                        <div class="txt-info">{{$sale->customer->name }}</div>
                                    </td>
                                    <td>
                                        <div> <b>{{$sale->id }}</b></div>
                                        <small><i class="icon-calendar"></i>
                                            {{app('fun')->dateFormat($sale->created_at)}}</small>
                                    </td>
                                    <td>${{$sale->total }}</td>
                                    <td>${{$sale->payments_sum_amount }}</td>
                                    <td>${{ round($sale->total - $sale->payments_sum_amount) }}</td>
                                    <td>


                                        @if($sale->payments_sum_amount >0)
                                        <button class="btn btn-light " wire:click="historyPayments({{ $sale->id }})">
                                            <i class="icon-receipt" style="font-size: 18px"></i>
                                        </button>
                                        @endif

                                        <button class="btn btn-light "
                                            wire:click="initPay({{ $sale->id }},'{{ $sale->customer->name }}',{{ round($sale->total - $sale->payments_sum_amount) }})">
                                            <i class="fa fa-money fa-lg"></i>
                                        </button>



                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">Sin resultado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('livewire.payments.historypays')
                    @endif



                    @if($sale_selected_id != null)
                    <section>
                        <div class="card height-equal">
                            <div class="card-header border-l-warning border-r-warning border-3 p-2">
                                <h4 class="txt-dark text-center"><i class="icofont icofont-ui-user"></i> {{
                                    $customer_name}}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="light-card balance-card align-items-center mb-1 col-sm-12 col-md-4">
                                        <h6 class="f-w-600 f-18 mb-0 txt-warning">DEBE:</h6>
                                        <div class="ms-auto text-end">
                                            <span class="f-18 f-w-700 ">
                                                ${{ $debt }}
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        class="light-card balance-card align-items-center mb-1 col-sm-12 col-md-4 m-l-10">
                                        <h6 class="f-w-600 f-18 mb-0 txt-warning">N° Venta:</h6>
                                        <div class="ms-auto text-end">
                                            <span class="f-18 f-w-700 ">
                                                {{ $sale_selected_id }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <div class="input-group mt-4">
                                        <select class="form-select" wire:model="bank">
                                            <option value="0">Seleccionar</option>
                                            @forelse($banks as $bank)
                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                            @empty
                                            <option value="-1" disabled>No hay bancos registrados</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="position-relative">
                                                <select class="form-control crypto-select info" disabled>
                                                    <option>N°. CUENTA:</option>
                                                </select>
                                                <input class="form-control" oninput="validarInputNumber(this)"
                                                    wire:model.live="acountNumber" type="text">
                                            </div>
                                            @error('nacount')
                                            <span class="txt-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="position-relative">
                                                <select class="form-control crypto-select info" disabled>
                                                    <option>N°. DEPÓSITO:</option>
                                                </select>
                                                <input class="form-control" oninput="validarInputNumber(this)"
                                                    wire:model.live="depositNumber" type="text">
                                            </div>
                                            @error('ndeposit')
                                            <span class="txt-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative">
                                            <select class="form-control crypto-select info" disabled>
                                                <option>INGRESA MONTO:</option>
                                            </select>
                                            <input class="form-control" oninput="validarInputNumber(this)"
                                                wire:model="amount" type="text" id="partialPayInput">
                                        </div>
                                        @error('amount')
                                        <span class="txt-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-4">

                                        <button class="btn btn-dark" type="button"
                                            wire:click.prevent="cancelPay">Cancelar</button>

                                        <button class="btn btn-primary" wire:click.prevent='doPayment' type="button"
                                            wire:loading.attr="disabled">

                                            <span wire:loading.remove wire:target="doPayment">
                                                Registrar Pago
                                            </span>
                                            <span wire:loading wire:target="doPayment">
                                                Registrando...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    @endif


                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary " type="button" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:init', function() {
            

        $('#modalPartialPayment').on('shown.bs.modal', function () {
                setTimeout(() => {
                    setFocus()
                }, 700)
        })


        Livewire.on('clear-search', event => {
            setFocus()
        })

        Livewire.on('show-payhistory', event => {
            $('#modalPayHistory').modal('show')
        })
        
        Livewire.on('focus-partialPayInput', event => {
           setTimeout(() => {
            document.getElementById('partialPayInput').value = ''
            document.getElementById('partialPayInput').focus()
           }, 600);
        })

        })

        function setFocus() {
            document.getElementById('inputPartialPaySearch').value = ''
            document.getElementById('inputPartialPaySearch').focus()
        }
    </script>
</div>