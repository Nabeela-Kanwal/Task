<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Task Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h1 class="text-center">Task Management</h1>

    <div class="d-flex justify-content-end mb-3">
      <a href="{{ route('Task.create') }}" class="btn btn-success">Add Task</a>
    </div>

    <table id="taskTable" class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Priority</th>
          <th>Due Date</th>
          <th>Completed</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tasks as $task)
        <tr>
          <td>{{ $task->id }}</td>
          <td>{{ $task->title }}</td>
          <td>{{ $task->description }}</td>
          <td>{{ $task->priority }}</td>
          <td>{{ $task->due_date }}</td>
          <td>{{ $task->completed ? 'Yes' : 'No' }}</td>
          <td>
            <button type="button" class="btn btn-primary edit-task" data-task-id="{{ $task->id }}">Edit</button>
            <form id="deleteForm{{ $task->id }}" action="{{ route('destroy', $task->id) }}" method="POST" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="button" class="btn btn-danger delete-task" data-task-id="{{ $task->id }}">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editTaskForm" action="#" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" id="editTaskId" name="id">
              
              <div class="mb-3">
                <label for="editTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="editTitle" name="title">
              </div>
              
              <div class="mb-3">
                <label for="editDescription" class="form-label">Description</label>
                <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
              </div>
              
              <div class="mb-3">
                <label for="editPriority" class="form-label">Priority</label>
                <input type="number" class="form-control" id="editPriority" name="priority" min="1">
              </div>

              <div class="mb-3">
                <label for="editDueDate" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="editDueDate" name="due_date">
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="editCompleted" name="completed">
                <label class="form-check-label" for="editCompleted">Completed</label>
              </div>

              <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.edit-task').click(function() {
        var taskId = $(this).data('task-id');
        $('#editTaskId').val(taskId);

        // Retrieve task data and populate the form fields
        var taskTitle = $('#taskTable').find('tr[data-task-id="' + taskId + '"] td:eq(1)').text();
        var taskDescription = $('#taskTable').find('tr[data-task-id="' + taskId + '"] td:eq(2)').text();
        var taskPriority = $('#taskTable').find('tr[data-task-id="' + taskId + '"] td:eq(3)').text();
        var taskDueDate = $('#taskTable').find('tr[data-task-id="' + taskId + '"] td:eq(4)').text();
        var taskCompleted = $('#taskTable').find('tr[data-task-id="' + taskId + '"] td:eq(5)').text().trim() === 'Yes' ? true : false;

        $('#editTitle').val(taskTitle);
        $('#editDescription').val(taskDescription);
        $('#editPriority').val(taskPriority);
        $('#editDueDate').val(taskDueDate);
        $('#editCompleted').prop('checked', taskCompleted);

        $('#editTaskModal').modal('show');
      });

      // Edit task form submission
      $('#editTaskForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var taskId = $('#editTaskId').val();
        var editUrl = "{{ route('Task.update', ':id') }}".replace(':id', taskId);
        var formData = form.serialize();

        $.ajax({
          url: editUrl,
          method: 'PUT',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            alert(response.message);
            $('#editTaskModal').modal('hide');
            location.reload(); // Reload the page to reflect changes
          },
          error: function(xhr, status, error) {
            alert('An error occurred while updating the task. Please check your form data and try again.');
          }
        });
      });

      // Delete task button click event
      $('.delete-task').click(function(e) {
        e.preventDefault();
        var taskId = $(this).data('task-id');
        var deleteUrl = "{{ route('destroy', ':id') }}".replace(':id', taskId);

        if (confirm("Are you sure you want to delete this task?")) {
          $.ajax({
            url: deleteUrl,
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
              alert(response.message);
              $('#deleteForm' + taskId).closest('tr').remove();
            },
            error: function(xhr, status, error) {
              alert('An error occurred while deleting the task.');
            }
          });
        }
      });
    });
  </script>
</body>
</html>
