@extends('layouts.app', ['activePage' => 'icons', 'titlePage' => __('Icons')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="container-fluid">
                <div class="card card-plain">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="card-title">Companies List</h4>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-default float-right"
                                        data-toggle="modal" data-target="#addCompanyModal">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <table id="companies-table" class="table table-bordered yajra-datatable">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Logo</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-labelledby="addCompanyLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Add Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" id="addCompanyForm" enctype='multipart/form-data'>
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="logo">Logo</label>
                            </div>
                            <div class="col-12">
                                <input type="file" id="logo" name="logo" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitForm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="editCompanyLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Edit Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="editCompanyForm" enctype='multipart/form-data'>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="editId" name="editId">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="editName" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="editAddress">Address</label>
                            <input type="text" class="form-control" id="editAddress" name="editAddress" placeholder="Address">
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="editLogo">Logo</label>
                            </div>
                            <div class="col-12">
                                <input type="file" id="editLogo" name="editLogo" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitEditForm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            let table = $('#companies-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('company.list') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'address', name: 'address'},
                    {data: 'logo', name: 'logo'},
                    {data: 'action', name: 'action'}
                ]
            });

            $('#addCompanyForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('company.store') }}",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function (msg) {
                        table.draw();
                        $('#addCompanyModal').modal('toggle');
                        $('#addCompanyModal form')[0].reset();
                    }
                });
            });

            let currentId;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#editCompanyForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: 'company/' + currentId,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (msg) {
                        table.draw();
                        $('#editCompanyModal').modal('toggle');
                        $('#editCompanyModal form')[0].reset();
                    }
                });
            });

            $(document).on("click", "#editCompany", function () {
                let row = $(this).closest('tr');
                let data = table.row(row).data();
                currentId = data.id;
                let modal = $('#editCompanyModal');
                $(".modal-body #editId").val(data.id);
                $(".modal-body #editName").val(data.name);
                $(".modal-body #editAddress").val(data.address);
                modal.modal('toggle');
            });

            $(document).on("click", "#deleteCompany", function () {
                let row = $(this).closest('tr');
                let data = table.row(row).data();
                let token = $("meta[name='csrf-token']").attr("content");

                $.ajax({
                    url: "company/" + data.id,
                    type: 'DELETE',
                    data: {
                        "id": data.id,
                        "_token": token,
                    },
                    success: function () {
                        console.log("it Works");
                        table.draw();
                    }
                });
            });
        });
    </script>
@endsection
