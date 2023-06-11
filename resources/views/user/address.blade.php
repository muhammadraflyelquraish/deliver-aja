@extends('layouts.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><a class="btn btn-sm btn-primary text-white" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i></a> Detail Pengguna</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <span>Pengguna</span>
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
                                <th width="250px">Nama Pengguna</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th width="250px">Email</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th width="250px">Nomor Telphone</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $user->no_telp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th width="250px">Role</th>
                                <td width="50px" class="text-center">:</td>
                                <td>{{ $user->role }}</td>
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
                    <h5><button class="btn btn-primary btn-sm" data-toggle="modal" data-mode="add" data-target="#ModalAddEdit"><i class="fa fa-plus-square mr-1"></i> Tambah Alamat [F2]</button></h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover addressTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Alamat</th>
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
                        <label>Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        <small class="text-danger" id="address_error"></small>
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

        let serverSideTable = $('.addressTable').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [1, 'asc']
            ],
            ajax: {
                url: "{{ route('address.data', $user->id) }}",
            },
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false,
            }, {
                data: 'address',
                name: 'address'
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
                $('#formAddEdit').attr('action', '{{ url("/users/$user->id/address") }}/' + id).attr('method', 'PATCH')

                modal.find('#modal-title').text('Edit Alamat');
                modal.find('#address').val(closeTr.find('td:eq(1)').text())
            } else {
                $('#formAddEdit').trigger('reset').attr('action', '{{ route("address.store", $user->id) }}').attr('method', 'POST')
                modal.find('#modal-title').text('Tambah Alamat');
            }
        })


        $("#formAddEdit").validate({
            messages: {
                address: "Alamat tidak boleh kosong",
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
                    url: '{{ url("/users/$user->id/address") }}/' + id,
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