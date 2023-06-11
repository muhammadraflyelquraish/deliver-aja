@extends('layouts.master')

@push('css')
<link href="{{ asset('build/assets') }}/css/plugins/select2/select2.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><a class="btn btn-sm btn-primary text-white btn-circle" href="{{ route('deliver.index') }}"><i class="fa fa-arrow-circle-o-left"></i></a> Buat Pengiriman</h2>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Form Pengiriman</h5>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('deliver.store') }}">
                        @csrf
                        @method('POST')

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Tipe Transaksi</label>
                                <select id="type_deliver" name="type_deliver" class="form-control" required>
                                    <option value="Offline">Offline</option>
                                    <option value="Online">Online</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Nomor Resi</label>
                                <input type="text" class="form-control" id="code_deliver" name="code_deliver" value="{{ $code_deliver }}" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Customer</label>
                                <select id="user_id" name="user_id" class="form-control select-customer" required></select>
                            </div>
                            <div class="col-md-6">
                                <label>Layanan</label>
                                <select id="service_id" name="service_id" class="form-control" required>
                                    <option value="" selected disabled>Pilih Layanan</option>
                                    @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{$service->price}}">{{ $service->name }} ({{$service->price}}/km)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat Jemput <span class="text-warning"> (isi jika layanan antar-jemput)</span></label>
                            <div class="input-group">
                                <select id="address_id" name="address_id" class="form-control" readonly>

                                </select>
                                <span class="input-group-append">
                                    <a href="#" class="btn btn-primary" id="btnAddAddress" target="_blank" disabled><i class="fa fa-plus"></i></a>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat Tujuan</label>
                            <textarea id="destination_address" name="destination_address" rows="2" class="form-control" required></textarea>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Jarak Pengiriman</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="kilometer" name="kilometer" step=".01" required>
                                    <div class="input-group-append">
                                        <span class="input-group-addon">km</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Berat Barang</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="weight" name="weight" step=".01" required>
                                    <div class="input-group-append">
                                        <span class="input-group-addon">kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Total Harga</label>
                            <input type="number" id="total_price" name="total_price" required class="form-control" readonly>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Tanggal Jemput <span class="text-warning"> (isi jika layanan antar-jemput)</span></label>
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control" id="date_pickup" name="date_pickup">
                                    <div class="input-group-append">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Tanggal Kirim</label>
                                <div class="input-group">
                                    <input type="datetime-local" class="form-control" id="date_sent" required name="date_sent">
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
                            </select>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-sm-offset-2">
                                <button class="btn btn-primary float-right" type="submit"><i class="fa fa-truck"></i> Buat Pengiriman</button>
                            </div>
                        </div>


                        <input type="hidden" class="form-control" id="service_price">
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

        $(".select-customer").select2({
            placeholder: "Cari Customer",
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('deliver.customer') }}",
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function(params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function(res) {
                    let result = []
                    res.data.forEach(function(data, index) {
                        result[index] = {
                            'id': data.id,
                            'text': `${data.name} (${data.phone_number})`,
                        }
                    })
                    return {
                        results: result
                    }
                },
            }
        });


        $(document).on('change', '#user_id', function(e) {
            e.preventDefault()
            $('#address_id').attr('readonly', false)
            $('#btnAddAddress').attr('disabled', false)

            if ($(this).val()) {
                $.ajax({
                    url: "{{ url('/deliver/address/') }}/" + $(this).val(),
                    type: "GET",
                    dataType: 'json',
                    success: function(res) {
                        $('#address_id').find('option').remove()

                        let destination_address = `<option value="" selected disabled>Pilih Alamat</option>`
                        res.data.addresses.forEach(function(data, index) {
                            destination_address += `<option value="${data.id}">${data.address}</option>`
                        })
                        $('#address_id').append(destination_address)
                        $('#btnAddAddress').attr('href', "{{ url('/users') }}/" + res.data.id + "/address")
                    },
                    error: function(res) {
                        console.log(res);
                    }
                })
            } else {
                $('#address_id').attr('readonly', true)
                $('#btnAddAddress').attr('disabled', true)
            }
        })

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