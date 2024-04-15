<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .task {
            margin-bottom: 20px;
        }

        .task h2 {
            color: #666;
        }

        .task p {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Task Details</h1>

        <div class="task">
            <h2>{{ $task->title }}</h2>
            <p>{{ $task->description }}</p>
            <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
            <p><strong>Created At:</strong> {{ $task->created_at }}</p>
            <p><strong>Last Updated:</strong> {{ $task->updated_at }}</p>
        </div>

        <a href="{{ route('dashboard') }}">Back to All Tasks</a>
    </div>

</body>

</html>