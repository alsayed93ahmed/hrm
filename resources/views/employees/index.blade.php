@extends('layouts.app', ['activePage' => 'icons', 'titlePage' => __('Icons')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="container-fluid">
                <div class="card card-plain">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="card-title">Employees List</h4>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-default float-right"
                                        data-toggle="modal" data-target="#addEmployeeModal">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="filter_company">Filter Companies</label>
                                            <select class="form-control" data-style="btn btn-link" id="filter_company" name="filter_company">
                                                <option value="" selected disabled>Select Company</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <table id="employees-table" class="table table-bordered yajra-datatable">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                        <th>Image</th>
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
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeLabel">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('employee.store') }}" method="post" id="addEmployeeForm" enctype='multipart/form-data'>
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" min="8">
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" min="8">
                        </div>

                        <div class="form-group">
                            <label for="company">Company</label>
                            <select class="form-control" data-style="btn btn-link" id="company" name="company">
                                <option value="" selected disabled>Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="employeeImage">Image</label>
                            </div>
                            <div class="col-12">
                                <input type="file" id="employeeImage" name="employeeImage" accept="image/*">
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

    <!-- Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeLabel">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" id="editEmployeeForm" role="form" enctype='multipart/form-data'>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="editName" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="editEmail">Email address</label>
                            <input type="email" class="form-control" id="editEmail" name="editEmail" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label for="editPassword">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="editPassword" placeholder="Password" min="8">
                        </div>
                        <div class="form-group">
                            <label for="editConfirmPassword">Password</label>
                            <input type="password" class="form-control" id="editConfirmPassword" name="editConfirmPassword" placeholder="Confirm Password" min="8">
                        </div>

                        <div class="form-group">
                            <label for="company">Company</label>
                            <select class="form-control" data-style="btn btn-link" id="editCompany" name="editCompany">
                                <option value="" selected disabled>Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="editEmployeeImage">Image</label>
                            </div>
                            <div class="col-12">
                                <input type="file" id="editEmployeeImage" name="editEmployeeImage" accept="image/*">
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
            let table = $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('employee.list') }}",
                    data: function (d) {
                        d.company = $('#filter_company').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'company', name: 'company'},
                    {data: 'image', name: 'image'},
                    {data: 'action', name: 'action'}
                ]
            });


            $('#name, #password, #confirmPassword, #email, #company').attr('required', true);
            $('#editName, #editPassword, #editConfirmPassword, #editEmail, #editCompany').attr('required', true);

            $(document).on("change", "#filter_company", function () {
                table.draw();
            })

            $('#addEmployeeForm').on('submit', function (e){
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('employee.store') }}",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    success: function( msg ) {
                        table.draw();
                        $('#addEmployeeModal').modal('toggle');
                        $('#addEmployeeModal form')[0].reset();
                    }
                });
            });

            let currentId;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#editEmployeeForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: 'employee/' + currentId,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (msg) {
                        table.draw();
                        $('#editEmployeeModal').modal('toggle');
                        $('#editEmployeeModal form')[0].reset();
                    }
                });
            });

            $(document).on("click", "#editEmployee", function () {
                let row = $(this).closest('tr');
                let data = table.row(row).data();
                currentId = data.id;
                let modal = $('#editEmployeeModal');
                $(".modal-body #editId").val(data.id);
                $(".modal-body #editName").val(data.name);
                $(".modal-body #editEmail").val(data.email);
                $(".modal-body #editCompany").val(data.company_id);
                modal.modal('toggle');
            });

            $(document).on("click", "#deleteEmployee", function () {
                let row = $(this).closest('tr');
                let data = table.row(row).data();
                let token = $("meta[name='csrf-token']").attr("content");

                $.ajax({
                    url: "employee/" + data.id,
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
