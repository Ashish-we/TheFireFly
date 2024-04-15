@extends('user.layout')

@section('content')

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        width: 100px;
        background-color: #f2f2f2;
    }
</style>
@if (Session::has('success'))
<div style="width: 400px; margin: 20px auto; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; padding: 15px; text-align: center;" id="success-alert">

    <p style="margin: 0; color: #155724;">{{ Session::get('success') }}</p>
</div>
<script>
    // Automatically close the alert after 5 seconds (5000 milliseconds)
    setTimeout(function() {
        closeAlert();
    }, 5000);

    // Alternatively, you can close the alert when the close button is clicked
    function closeAlert() {
        document.getElementById('success-alert').style.display = 'none';
    }
</script>
@endif
@if (Session::has('error'))
<div style="width: 400px; margin: 20px auto; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; padding: 15px; text-align: center;" id="error-alert">

    <p style="margin: 0; color: #721c24;">{{ Session::get('error') }}</p>
</div>
<script>
    // Automatically close the alert after 5 seconds (5000 milliseconds)
    setTimeout(function() {
        closeAlert();
    }, 5000);

    // Alternatively, you can close the alert when the close button is clicked
    function closeAlert() {
        document.getElementById('error-alert').style.display = 'none';
    }
</script>
@endif
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Add List</h3>
            <a href="{{route('task.add_form')}}" class="btn btn-primary">Add Task</a>
        </div>
        <div class="card-body">
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Due_date</th>
                        <th>Show</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td><a href="{{ url('/task/show/'. $task->id) }}" class="btn btn-primary btn-sm">Show</a></td>
                        <td><a href="{{ url('/task/edit/'. $task->id) }}" class="btn btn-primary btn-sm">Edit</a></td>
                        <td>
                            <form action="{{ url('/task/delete/'. $task->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('Post')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Item?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tasks->links('pagination::bootstrap-5')}}
        </div>
    </div>
</div>
@endsection