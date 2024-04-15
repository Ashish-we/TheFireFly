<!-- resources/views/admin/customer/add.blade.php -->

@extends('user.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- <div style="width: 80%; margin-left:10%;" class="alert">
    @if (session('status'))
    <div class="alert alert-warning" role="alert">
        {{ session('status') }}
    </div>
    @endif
</div> -->
<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>ADD Task</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/task/update/'. $task->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Title</label>
                        <input type="text" class="form-control" value="{{$task->title}}" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required>{{$task->description}}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Due Date</label>
                        <input type="date" class="form-control" value="{{$task->due_date}}" id="due_date" name="due_date" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update task</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Get today's date
    var today = new Date();

    // Calculate tomorrow's date
    var tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    // Format the date in YYYY-MM-DD format (required by input type="date")
    var tomorrowFormatted = tomorrow.toISOString().split('T')[0];

    // Set the minimum attribute of the input field to tomorrow's date
    document.getElementById("due_date").min = tomorrowFormatted;
</script>
@endsection