@extends('layouts.master')

@push('css')
<link href="{{ asset('build/assets') }}/css/plugins/select2/select2.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><a class="btn btn-sm btn-primary text-white btn-circle" href="{{ route('deliver.index') }}"><i class="fa fa-arrow-circle-o-left"></i></a> Ubah Pengiriman</h2>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Form Ubah Pengiriman</h5>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('deliver.update', $deliver->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Tipe Transaksi</label>
                                <select id="type_deliver" name="type_deliver" class="form-control" required>
                                    @if($deliver->type_deliver == 'Offline')
                                    <option value="Offline" selected>Offline</option>
                                    <option value="Online">Online</option>
                                    @else
                                    <option value="Offline">Offline</option>
                                    <option value="Online" selected>Online</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Nomor Resi</label>
                                <input type="text" class="form-control" id="code_deliver" name="code_deliver" value="{{ $deliver->code_deliver }}" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Customer</label>
                                <select id="user_id" name="user_id" class="form-control select-customer" required>
                                    <option value="{{ $deliver->user_id }}" selected>{{ $deliver->user->name }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Layanan</label>
                                <select id="service_id" name="service_id" class="form-control" required>
                                    @foreach($services as $service)
                                    @if($service->id == $deliver->service_id)
                                    <option value="{{ $service->id }}" data-price="{{$service->price}}" selected>{{ $service->name }} ({{$service->price}}/km)</option>
                                    @else
                                    <option value="{{ $service->id }}" data-price="{{$service->price}}">{{ $service->name }} ({{$service->price}}/km)</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat Jemput <span class="text-warning"> (isi jika layanan antar-jemput)</span></label>
                            <div class="input-group">
                                <select id="address_id" name="address_id" class="form-control">
                                    @foreach($deliver->user->addresses as $address)
                                    @if($address->id == $deliver->address_id)
                                    <option value="{{ $address->id }}" selected>{{ $address->address }}</option>
                                    @else
                                    <option value="{{ $address->id }}">{{ $address->address }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <span class="input-group-append">
                                    <a href="{{ url('/users') }}/{{ $deliver->user_id }}/address" class="btn btn-primary" id="btnAddAddress" target="_blank"><i class="fa fa-plus"></i></a>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat Tujuan</label>
                            <textarea id="destination_address" name="destination_address" rows="2" class="form-control" required>{{ $deliver->destination_address }}</textarea>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Jarak Pengiriman</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="kilometer" name="kilometer" step=".01" required value="{{ $deliver->kilometer }}">
                                    <div class="input-group-append">
                                        <span class="input-group-addon">km</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Berat Barang</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="weight" name="weight" step=".01" required value="{{ $deliver->weight }}">
                                    <div class="input-group-append">
                                        <span class="input-group-addon">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Total Harga</label>
                            <input type="number" id="total_price" name="total_price" required class="form-control" readonly value="{{ $deliver->total_price }}">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Tanggal Jemput <span class="text-warning"> (isi jika layanan antar-jemput)</span></label>
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control" id="date_pickup" name="date_pickup" value="{{ $deliver->date_pickup }}">
                                    <div class="input-group-append">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Tanggal Kirim</label>
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control" id="date_sent" required name="date_sent" value="{{ $deliver->date_sent }}">
                                    <div class="input-group-append">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status Pengiriman</label>
                            <select id="status_deliver" name="status_deliver" required class="form-control">
                                <option value="Pickup">Pickup</option>
                                <option value="Waiting">Waiting</option>
                                <option value="Sent">Sent</option>
                                <option value="Arrived">Arrived</option>
                            </select>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-sm-offset-2">
                                <button class="btn btn-primary float-right" type="submit"><i class="fa fa-truck"></i> Ubah Pengiriman</button>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" id="service_price" value="{{ $deliver->service->price }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('build/assets') }}/js/plugins/select2/select2.full.min.js"></script>
<script>
    $(function() {

        function calcTotalPrice() {
            let kilometer = $('#kilometer').val()
            let service_price = $('#service_price').val()
            let weight = $('#weight').val()

            let total_price = (service_price * kilometer) + (weight * 1000)
            $('#total_price').val(total_price)
        }

        $(document).on('change', '#service_id', function(e) {
            let price = e.target.options[e.target.selectedIndex].dataset.price;
            $('#service_price').val(price)
            calcTotalPrice()
        })

        $(document).on('keyup', '#kilometer, #weight', function(e) {
            calcTotalPrice()
        })

    })
</script>
@endpush