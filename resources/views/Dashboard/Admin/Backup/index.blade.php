@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">نسخ احتياطى </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل
                    النسخ</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')
    @include('Dashboard.Admin.Backup.create')
    <!-- row opened -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">

                        <button class="btn btn-primary" data-toggle="modal" data-target="#create">اضافة نسخة
                            احتياطية</button>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>


                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table table-bordered mg-b-0 text-md-nowrap">
                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>Path</th>
                                    <th>Size</th>
                                    <th>type</th>
                                    <th>Date</th>
                                    <th> Action </th>

                                </tr>
                            </thead>
                            <tbody id="backupTableBody">
                                @foreach ($backups as $backup)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ basename($backup->path) }}</td>
                                        <td>{{ number_format($backup->size / (1024 * 1024), 2) }} MB</td>
                                        <td>{{ $backup->type == 1 ? 'manual' : 'schedule' }}</td>
                                        <td>{{ $backup->created_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.download-backup', $backup->id) }}"
                                                class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#Deleted{{ $backup->id }}" data-id="{{ $backup->id }}"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    @include('Dashboard.Admin.Backup.Deleted')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script>
        document.getElementById('backupButton').addEventListener('click', function() {
            fetch('{{ route('admin.manual-backup') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notif({
                            msg: "تم إنشاء النسخة الاحتياطية بنجاح",
                            type: "success"
                        });

                        // تحديث الجدول بالسجل الجديد
                        const tableBody = document.getElementById('backupTableBody');
                        const newRow = document.createElement('tr');

                        newRow.innerHTML = `
                <td>${tableBody.rows.length + 1}</td>
                <td>${data.path}</td>
                <td>${(data.size / (1024 * 1024)).toFixed(2)} MB</td>
                <td>${data.type}</td>
                <td>${data.date}</td>
                <td>
                    <a href="/admin/download-backup/${data.id}" class="btn btn-sm btn-primary"><i class="fas fa-download"></i></a>
                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#Deleted${data.id}" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                </td>
            `;

                        tableBody.appendChild(newRow);
                    } else {
                        // notif({
                        //     msg: "فشل في إنشاء النسخة الاحتياطية",
                        //     type: "error"
                        // });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // notif({
                    //     msg: "حدث خطأ أثناء النسخ الاحتياطي",
                    //     type: "error"
                    // });
                });
        });

        $('#Deleted').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var backupId = button.data('id');
            var modal = $(this);
            modal.find('.modal-body input[name="id"]').val(backupId);
        });
    </script>

    <!--Internal  Notify js -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
