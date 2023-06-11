@extends('layouts.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Data Pengriman</h2>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><a class="btn btn-primary btn-sm" href="{{ route('deliver.create') }}"><i class="fa fa-plus-square mr-1"></i> Buat Pengiriman</a></h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover deliverTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="1px">No</th>
                                    <th>Customer</th>
                                    <th>No Resi</th>
                                    <th>Layanan</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Tanggal Terima</th>
                                    <th>Total Harga</th>
                                    <th>Status Pengiriman</th>
                                    <th class="text-right" width="1px">Aksi</th>
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
@endsection

@push('script')
<script>
    $(function() {

        let serverSideTable = $('.deliverTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('deliver.data') }}",
            },
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false,
            }, {
                data: 'user.name',
                name: 'user.name'
            }, {
                data: 'code_deliver',
                name: 'code_deliver',
            }, {
                data: 'service.name',
                name: 'service.name',
            }, {
                data: 'date_sent',
                name: 'date_sent',
            }, {
                data: 'date_received',
                name: 'date_received',
            }, {
                data: 'total_price',
                name: 'total_price',
            }, {
                data: 'status_deliver',
                name: 'status_deliver',
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

        function sweetalert(title, msg, type, timer = 60000, confirmButton = true) {
            swal({
                title: title,
                text: msg,
                type: type,
                timer: timer,
                showConfirmButton: confirmButton
            });
        }

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
                    url: "{{ route('deliver.store') }}/" + id,
                    type: "DELETE",
                    dataType: 'json',
                    success: function(response) {
                        serverSideTable.draw()
                        sweetalert("Terhapus!", `Data berhasil dihapus.`, null, 500, false)
                    },
                    error: function(response) {
                        serverSideTable.draw()
                        sweetalert("Tidak terhapus!", `Terjadi kesalahan saat menghapus data.`, 'error')
                    }
                })
            });
        });

    })
</script>
@endpush