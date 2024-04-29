<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Task</title>
  <!-- Include Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h1 class="text-center">Edit Task</h1>
    <form id="editTaskForm" action="{{ route('Task.update', $task->id) }}" method="POST">
      @csrf 
      @method('PUT') <!-- Use the PUT method for updating -->

      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ $task->description }}</textarea>
      </div>

      <div class="mb-3">
        <label for="priority" class="form-label">Priority</label>
        <input type="number" class="form-control" id="priority" name="priority" value="{{ $task->priority }}" min="1" required>
      </div>

      <div class="mb-3">
        <label for="due_date" class="form-label">Due Date</label>
        <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $task->due_date }}" required>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="completedCheckbox" name="completed" {{ $task->completed ? 'checked' : '' }}>
        <label class="form-check-label" for="completedCheckbox">Completed</label>
      </div>

      <button type="button" id="updateTaskBtn" class="btn btn-primary">Update Task</button>
      <a href="{{ route('Task.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
  </div>

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#updateTaskBtn').click(function() {
        // Set completed value based on checkbox state
        var completedValue = $('#completedCheckbox').is(':checked');
        $('input[name="completed"]').val(completedValue); // Set the actual value

        var formData = $('#editTaskForm').serialize();

        $.ajax({
          url: "{{ route('Task.update', $task->id) }}",
          method: 'PUT',
          data: formData,
          success: function(response) {
            alert(response.message); // Display success message
            window.location.href = "{{ route('Task.index') }}"; // Redirect to task index
          },
          error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (xhr.status === 422) {
              // Display validation errors
              var errors = err.errors;
              var errorMessage = '';
              for (var key in errors) {
                errorMessage += key + ': ' + errors[key][0] + '\n'; // Get the first error message for each field
              }
              alert(errorMessage);
            } else {
              alert('An error occurred while updating the task.'); // Handle other errors
            }
          }
        });
      });
    });
  </script>
</body>
</html>
