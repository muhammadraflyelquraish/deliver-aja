@extends('layouts.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><a class="btn btn-sm btn-primary text-white" href="{{ route('deliver.index') }}"><i class="fa fa-arrow-left"></i></a> Detail Pengiriman</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>Pengiriman</span>
            </li>
            <li class="breadcrumb-item active">
                <strong>Detail</strong>
            </li>
        </ol>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive" style="margin-bottom: -20px;">
                        <table class="table table-bordered" width="100%">
                            <tr>
                                <th width="250px">Nomor Resi</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->code_deliver }}</td>
                                <th width="250px">Tipe Transaksi</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->type_deliver }}</td>
                            </tr>
                            <tr>
                                <th width="250px">Customer</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->user->name }}</td>
                                <th width="250px">Layanan</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->service->name }}</td>
                            </tr>
                            <tr>
                                <th width="250px">Alamat Jemput</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->address->address ?? '-' }}</td>
                                <th width="250px">Alamat Tujuan</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->destination_address }}</td>
                            </tr>
                            <tr>
                                <th width="250px">Jarak</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->kilometer }} km</td>
                                <th width="250px">Berat</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->weight }} kg</td>
                            </tr>
                            <tr>
                                <th width="250px">Tanggal Jemput</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->date_pickup ? date('Y-m-d H:i:s', strtotime($deliver->date_pickup)) : '-' }}</td>
                                <th width="250px">Tanggal Kirim</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->date_sent ? date('Y-m-d H:i:s', strtotime($deliver->date_sent)) : '-' }}</td>
                            </tr>
                            <tr>
                                <th width="250px">Tanggal Diterima</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->date_received ? date('Y-m-d H:i:s', strtotime($deliver->date_received)) : '-'}}</td>
                                <th width="250px">Status Pengiriman</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $deliver->status_deliver }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><button class="btn btn-primary btn-sm" data-toggle="modal" data-mode="add" data-target="#ModalAddEdit"><i class="fa fa-plus-square mr-1"></i> Tambah Tracking</button></h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tranckingTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Lokasi Saat Ini</th>
                                    <th>Tanggal Datang</th>
                                    <th>Status</th>
                                    <th width="1%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ModalAddEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAddEdit" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Lokasi Saat Ini</label>
                        <textarea class="form-control" id="current_location" name="current_location" required rows="2"></textarea>
                        <small class="text-danger" id="current_location_error"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Sampai</label>
                        <input type="datetime-local" class="form-control" id="date_arrived" name="date_arrived" required>
                        <small class="text-danger" id="date_arrived_error"></small>
                    </div>
                    <div class="form-group">
                        <label>Status Tracking</label>
                        <select class="form-control" id="type_tracking" name="type_tracking" required>
                            <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                            <option value="Sampai">Sampai</option>
                        </select>
                        <small class="text-danger" id="type_tracking_error"></small>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-rectangle-o mr-1"></i>Tutup [Esc]</button>
                    <button type="submit" class="btn btn-primary ladda-button ladda-button-demo" data-style="zoom-in" id="submit" tabindex="5"><i class="fa fa-check-square mr-1"></i>Simpan [Enter]</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('script')
<script>
    $(document).ready(function() {

        let serverSideTable = $('.tranckingTable').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [2, 'desc']
            ],
            ajax: {
                url: "{{ route('tracking.data', $deliver->id) }}",
            },
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false,
            }, {
                data: 'current_location',
                name: 'current_location'
            }, {
                data: 'date_arrived',
                name: 'date_arrived'
            }, {
                data: 'type_tracking',
                name: 'type_tracking'
            }, {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false
            }],
            search: {
                "regex": true
            }
        });

        //BASE 
        let ladda = $('.ladda-button-demo').ladda();

        function LaddaStart() {
            ladda.ladda('start');
        }

        function LaddaAndDrawTable() {
            ladda.ladda('stop');
            serverSideTable.draw()
        }

        function sweetalert(title, msg, type, timer = 60000, confirmButton = true) {
            swal({
                title: title,
                text: msg,
                type: type,
                timer: timer,
                showConfirmButton: confirmButton
            });
        }

        $('#ModalAddEdit').on('shown.bs.modal', function(e) {
            let button = $(e.relatedTarget)
            let modal = $(this)
            if (button.data('mode') == 'edit') {
                let id = button.data('integrity')
                let closeTr = button.closest('tr')
                $('#formAddEdit').attr('action', '{{ url("/deliver/$deliver->id/tracking") }}/' + id).attr('method', 'PATCH')

                modal.find('#modal-title').text('Edit Tracking');
                modal.find('#current_location').val(closeTr.find('td:eq(1)').text())
                modal.find('#date_arrived').val(closeTr.find('td:eq(2)').text())
                modal.find('#type_tracking').val(closeTr.find('td:eq(3)').text())
            } else {
                $('#formAddEdit').trigger('reset').attr('action', '{{ route("tracking.store", $deliver->id) }}').attr('method', 'POST')
                modal.find('#modal-title').text('Tambah Tracking');
            }
        })


        $("#formAddEdit").validate({
            messages: {
                current_location: "Lokasi saat ini tidak boleh kosong",
                date_arrived: "Tanggal sampai tidak boleh kosong",
                type_tracking: "Status tidak boleh kosong",
            },
            success: function(messages) {
                $(messages).remove();
            },
            errorPlacement: function(error, element) {
                let name = element.attr("name");
                $("#" + name + "_error").text(error.text());
            },
            submitHandler: function(form) {
                LaddaStart()
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: $(form).serialize(),
                    dataType: 'JSON',
                    success: function(res) {
                        $('#ModalAddEdit').modal('hide')
                        LaddaAndDrawTable()
                        sweetalert('Berhasil', res.msg, null, 500, false)
                    },
                    error: function(res) {
                        LaddaAndDrawTable()
                        sweetalert('Gagal', 'Terjadi kesalah', 'error')
                    }
                })
            }
        });


        $(document).on('click', '#delete', function(e) {
            let id = $(this).data('integrity')
            swal({
                title: "Hapus?",
                text: `Data akan terhapus!`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, hapus!",
                closeOnConfirm: false
            }, function() {
                swal.close()
                $.ajax({
                    url: '{{ url("/deliver/$deliver->id/tracking") }}/' + id,
                    type: "DELETE",
                    dataType: 'json',
                    success: function(response) {
                        LaddaAndDrawTable()
                        sweetalert("Terhapus!", `Data berhasil dihapus.`, null, 500, false)
                    },
                    error: function(response) {
                        LaddaAndDrawTable()
                        sweetalert("Tidak terhapus!", `Terjadi kesalahan saat menghapus data.`, 'error')
                    }
                })
            });
        });

    });
</script>
@endpush