@extends('layouts.app2')

@section('page-title')
    <li><a href="{{route('admin.index')}}">{{ __('message.title.dashboard') }}</a></li>
    <li class="active">{{ __('message.feedback') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Quản Lý Phản Hồi
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="feedback">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Người dùng</th>
                            <th scope="col">Sản phẩm </th>
                            <th scope="col">Nội dung</th>
                            <th scope="col">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')

<script type="text/javascript">

$(document).ready(function() {
    var notificationsWrapper = $('.for-feedback');
    var notificationsToggle = notificationsWrapper.find('button[data-toggle]');
    var notificationsCountElem = notificationsToggle.find('span[data-count]');
    var notificationsCount = parseInt(notificationsCountElem.data('count'));
    var notifications = notificationsWrapper.find('.feedback-dropdown');

    var table = $('#feedback').DataTable({
        ajax: {
            processing: true,
            serverSide: true,
            order: [0, "desc"],
            url: '{!! route('admin.feedback.json') !!}',
            type: 'get',
        },
        columns: [
            { data: 'id' },
            { data: 'user.name' },
            { data: 'product.name' },
            { data: 'content' },
            {
                data: 'status',
                render: function(data, type, row) {
                    var checked = (data == 1) ? 'checked' : '';
                    return `<a href="#" class="active">
                        <label class="switch switch-3d switch-primary mr-3">
                            <input type="checkbox" class="switch-input" ${checked}>
                            <span class="switch-label"></span><span class="switch-handle"></span>
                        </label>
                    </a>`;
                },
            },
        ],
    });

    $('#feedback tbody').on('click', '.active', function(event) {
        event.preventDefault();
        var id = $(this).closest('tr').find('td:eq(0)').text();

        $.ajax({
            url: route('admin.feedback.active', id),
            type: 'GET',
        })
        .done(function(data) {
            if (data == 1) {
                notificationsCount -= 1;
                notificationsCountElem.attr('data-count', notificationsCount);
                notificationsWrapper.find('#count_feedback').text(notificationsCount);
                $('#feedback' + id).remove();
            } else {
                var avatar = (data.avatar ? 'avatars/' + data.avatar : 'default.jpeg');
                notificationsCount += 1;
                notificationsCountElem.attr('data-count', notificationsCount);
                notificationsWrapper.find('#count_feedback').text(notificationsCount);
                $('.feedback-dropdown').show( `
                    <a class="dropdown-item media bg-flat-color-1" id="feedback${data.id}" href="#">
                        <span class="photo media-left">
                            <img alt="avatar" class="user-avatar rounded-circle" src="http://127.0.0.1:8000/images/${avatar}">
                        </span>
                        <span class="message media-body">
                            <span class="name float-left">${data.user.name}</span>
                            <p>${data.content}</p>
                        </span>
                    </a>
                `);
            }

            table.ajax.reload();
            console.log("success");
        })
        .fail(function() {
            swal('Something wrong !', {icon: 'error'});
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    });
});
</script>

@endsection
